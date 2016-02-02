<?php
/*
 * Created on Mar 5, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class CronsController extends AppController {
 	
 	var $uses = array('Cron', 'Domain', 'Message', 'User', 'Alias', 'AliasReminder', 'BulkSmsLogDetail',
 						'BulkSmsSchedule', 'BulkAddressbook', 'BulkSenderid', 'BulkSmsLog', 'SenderIdRepository',
 						'BulkGroup', 'BulkAccount', 'BulkAccountRecharge', 'AliasInvoice', 'Maillog', 'BulkUser',
 						'BulkAccountBackup', 'BulkSetting', 'BulkUserpersonalinfo', 'SmsVendor', 'BulkSmsExpiryReminder',
 						'BulkSmsResponsePinnacle');
	
 	function beforeFilter() {
 		
 		if(!CRON_DISPATCHER) return false;
 		
 	}
 	
 	function checkIP () {
		
 		$this->Domain->unBindModel(array(
 			'belongsTo' => array('User', 'Plan')
 		));
 		
 		//$condition['conditions'][0] = 'Domain.ip IS NULL';
 		$condition['conditions']['Domain.status'] = '0';
 		$condition['fields'] = array('Domain.id', 'Domain.name', 'Domain.ip', 'Domain.plan_id');
 		$data = $this->Domain->find('all', $condition);
 		
 		foreach($data as $value) {
 			$ip = $this->getDomainIP($value['Domain']['name']);
 			//echo $value['Domain']['name'] .' => '. $ip . PHP_EOL;
 			if($ip != $value['Domain']['ip']) {
 				$this->Domain->id = $value['Domain']['id'];
 				$this->Domain->saveField('ip', $ip);
 			}
 		}
 		
 		$crondata['name'] = 'checkIP';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
 	
 	function checkImage() {

 		$this->Domain->unBindModel(array(
 			'belongsTo' => array('User')
 		));
 		
 		$condition['conditions']['Domain.status'] = '0';
 		$condition['conditions']['Plan.status'] = '0';
 		$condition['conditions']['Plan.name <>'] = 'free';
 		$condition['fields'] = array('Domain.id', 'Domain.name', 'Domain.ip', 'Plan.id', 'Plan.name');
 		$data = $this->Domain->find('all', $condition);
 		
 		if(count($data) > 0) {
 			foreach($data as $value) {
 				
 				// If Image not found then revert it back to free plan
				if(!$this->findImageOnWebsite($value['Plan']['id'], $value['Domain']['name'])) {
					$this->Domain->id = $value['Domain']['id'];
					$this->Domain->saveField('plan_id', '1'); // plan id 1 is for free plan
				}
 			}	
 		}
 		
 		$crondata['name'] = 'checkImage';
 		$this->Cron->save($crondata);
 		
 		exit;
 	}
 	
	/*	FREE USER	*/
 	function checkDelivery() {

		set_time_limit(0);
 		ini_set('memory_limit', '256M');

 		//get all sms vendor details
 		$this->getSmsVendorDetails();
 		//print_r($this->sms_vendor_details);exit;
 		
 		foreach($this->serverList as $value) {

 			foreach($this->sms_vendor_details as $sms_vendor) {
 				
	 			$c['conditions']['0'] = array('Message.created BETWEEN ? AND ?'=>array(date('Y-m-d', strtotime('-1 day')), date('Y-m-d')));
	 			$c['conditions']['1'] = array('Message.response_key != ""');
	 			$c['conditions']['2'] = array('Message.name != ""');
	 			$c['conditions']['Message.response_status'] = array(UNDELIVERED, 'INVALID RESPONSE ID.');
	 			$c['conditions']['Message.sms_vendor_id'] = $sms_vendor['id'];
	 			//$c['limit'] = BULK_SMS_QUERY_LIMIT;
	 			$this->Message->setSource(strtolower($value).'_log');
	 			$data = $this->Message->find('all', $c);
	 			unset($c);
	 			//print_r($data);
	 		 	//continue;
	 			
	 			if(!empty($data)) {
	 				$data_to_check = '';
	 				$count = $array_count = 0;
	 				$limit = $sms_vendor['sms_delivery_limit'];
	 				foreach($data as $message) {
	 					
	 					/*if(substr($message['Message']['response_key'], 0, 4) == '007-') {
							$this->Message->setSource(strtolower($value).'_log');
		 					$this->Message->id = $message['Message']['id'];
		 					$this->Message->saveField('response_status', DND);
		 						 						
	 					} else*/ if(!empty($message['Message']['response_key']) && is_numeric(str_replace(array('-','_'), '', $message['Message']['response_key']))) {
	 						
	 						if($count == $limit) { $count = 0; $array_count++; }
	 						$data_to_check[$array_count][$message['Message']['id']] = trim($message['Message']['response_key']);
		 					$count++;
							
	 					}
	 				}
	 				//print_r($data_to_check);

					if(!empty($data_to_check)) {
	 					$return_data = $this->_getFreeResponse($data_to_check, $sms_vendor['id']);
	 					if(!empty($return_data)) {
			 				$this->Message->setSource(strtolower($value).'_log');
			 				$this->Message->saveAll($return_data);
	 					}
	 				}
	 				
	 			}
 				
 			}
 			
 			
 		}
 		
 		$crondata['name'] = 'checkDelivery';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
 	
 function _getFreeResponse($data, $sms_vendor_id) {
 		
 		$returndata = array();
 		$sms_vendor = $this->sms_vendor_details['sms_vendor_'.$sms_vendor_id];
 		
	 	foreach($data as $val) {
	 			
 			$scheduleid = implode(',', $val);
 			$scheduleid_keys = array_keys($val);
 			
 			//$url = $sms_vendor['sms_delivery_report_url'].'?'.$sms_vendor['sms_delivery_report_id'].'='.$scheduleid;
 			$url = $sms_vendor['sms_delivery_report_url'].'?'.
 					$sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
					$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'].'&'.
					$sms_vendor['sms_delivery_report_id'].'='.$scheduleid;

 			$response_key = file_get_contents($url);
 			
 			$regex = $sms_vendor['sms_delivery_report_seperator'];
 			$response_key = $this->br2sep($response_key, '##', $regex);
 			$response_key = array_filter(explode('##', $response_key));
 			
 			//echo count($response_key).'-'.count($scheduleid_keys).'<br>';
 			
 			for($i=0; $i<count($response_key); $i++) {
 				
 				$values[$i]['id'] = $scheduleid_keys[$i];
 				$response_key[$i] = strip_tags($response_key[$i]);
 				
	 			/*if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_delivered']))
	 				$response_key[$i] = 'DELIVERED';
	 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_undelivered']))
	 				$response_key[$i] = 'UNDELIVERED';
	 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_pending']))
	 				$response_key[$i] = 'PENDING';
	 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_expired']))
	 				$response_key[$i] = 'EXPIRED';
	 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_ndnc']))
	 				$response_key[$i] = 'DND';*/


				$response_key[$i] = strtolower($response_key[$i]);
 				if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_delivered'])) !== false)
	 				$response_key[$i] = 'DELIVERED';
	 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_undelivered'])) !== false)
	 				$response_key[$i] = 'UNDELIVERED';
	 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_pending'])) !== false)
	 				$response_key[$i] = 'PENDING';
	 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_expired'])) !== false)
	 				$response_key[$i] = 'EXPIRED';
	 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_ndnc'])) !== false)
	 				$response_key[$i] = 'DND';
				else $response_key[$i] = 'PENDING';
				
	 			$values[$i]['response_status'] = strtoupper($response_key[$i]);
 			}
 			
 			$returndata = array_merge($returndata, $values);
 			unset($values);
 		}
	 	
 		//pr($returndata);exit;
 		return $returndata;
 	}
 	
 	/*	BULK USER	*/
 	function checkBulkDelivery() {
 		
		set_time_limit(0);
 		ini_set('memory_limit', '256M');

 		//get all sms vendor details
 		$this->getSmsVendorDetails();
 		//print_r($this->sms_vendor_details);exit;
 		
 		foreach($this->bulkServerList as $value) {
 			
 			foreach($this->sms_vendor_details as $sms_vendor) {
 				
				$c['conditions']['0'] = array('BulkSmsLogDetail.created BETWEEN ? AND ?'=>array(date('Y-m-d', strtotime('-1 day')), date('Y-m-d')));
				//$c['conditions']['1'] = array('BulkSmsLogDetail.mobile != ""');
				$c['conditions']['BulkSmsLogDetail.response_status'] = UNDELIVERED;
				$c['conditions']['bulkSmsLog.sms_vendor_id'] = $sms_vendor['id'];
				$c['limit'] = BULK_SMS_QUERY_LIMIT;
				$c['fields'] = array('BulkSmsLogDetail.id', 'BulkSmsLogDetail.response_key');
				
				$this->BulkSmsLogDetail->bindModel(
					array('belongsTo' => array(
 						'bulkSmsLog' => array(
 							'foreignKey' => 'bulk_sms_log_id'
 							)
 						)
 					)
 				);
 	
				$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $value);
				$data = $this->BulkSmsLogDetail->find('all', $c);
				//$data = $this->BulkSmsLogDetail->find('all', array('conditions'=>array(array('BulkSmsLogDetail.created BETWEEN ? AND ?'=>array(date('Y-m-d', strtotime('-1 day')), date('Y-m-d'))), 'BulkSmsLogDetail.response_status'=>array(UNDELIVERED, '', 'UNDELIVERED'))));
				//print_r($data);
				//continue;
				
				if(!empty($data)) {
					$data_to_check = '';
		 			$count = $array_count = 0;
		 			$limit = $sms_vendor['sms_delivery_limit'];
		 			
				 	foreach($data as $message) {
				 		
				 		/*if(substr($message['BulkSmsLogDetail']['response_key'], 0, 4) == '007-') {
				 			$this->BulkSmsLogDetail->id = $message['BulkSmsLogDetail']['id'];
				 			$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $value);
				 			$this->BulkSmsLogDetail->saveField('response_status', DND);
				 				 						
				 		} else*/ if(!empty($message['BulkSmsLogDetail']['response_key'])  && is_numeric(str_replace(array('-','_'), '', $message['BulkSmsLogDetail']['response_key']))) {
				 			
				 			if($count == $limit) { $count = 0; $array_count++; }
		 					$data_to_check[$array_count][$message['BulkSmsLogDetail']['id']] = trim($message['BulkSmsLogDetail']['response_key']);
		 					$count++;
	
				 		}
				 	}
				 	
					if(!empty($data_to_check)) {
						$return_data = $this->_getBulkResponse($data_to_check, $sms_vendor['id']);
				 		if(!empty($return_data)) {
					 		$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $value);
						 	$this->BulkSmsLogDetail->saveAll($return_data);
				 		}
				 	}
				 	
				 	unset($data, $data_to_check);
		 		}
 			}
 		}
 		
 		$crondata['name'] = 'checkBulkDelivery';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
	
 	function _getBulkResponse($data, $sms_vendor_id) {
 		
 		$returndata = array();
 		$sms_vendor = $this->sms_vendor_details['sms_vendor_'.$sms_vendor_id];
 		
	 	// Only for pinnacle - 2012-02-26
	 	if($sms_vendor_id == 1) {
	 		
	 		foreach($data as $val) {
	 			$scheduleid = $val;
	 			$scheduleid_keys = array_keys($val);
	 			//pr($scheduleid);
	 			
	 			$i = 0;
	 			foreach($scheduleid as $k => $v) {
	 				$values[$i]['id'] = $k;
	 				$tmp = $this->BulkSmsResponsePinnacle->find(array('value' => $v));
	 				
	 				//pr($tmp);
	 				
	 				$key = $tmp['BulkSmsResponsePinnacle']['key'];
	 				$mobile = $tmp['BulkSmsResponsePinnacle']['mobile'];
	 				
	 				$url = $sms_vendor['sms_delivery_report_url'].'?'.
	 					$sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
						$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'].'&'.
						$sms_vendor['sms_delivery_report_id'].'='.$key;
						
					$response = file_get_contents($url);
					
					$pattern = "/$mobile \w+?\<br\>/";
					preg_match($pattern, $response, $matches);
					
					if(!empty($matches[0])) {
						$response_status = strtolower($matches[0]);
		 				if(strpos($response_status, strtolower($sms_vendor['sms_delivery_delivered'])) !== false)
			 				$response_status = 'DELIVERED';
			 			else if(strpos($response_status, strtolower($sms_vendor['sms_delivery_undelivered'])) !== false)
			 				$response_status = 'UNDELIVERED';
			 			else if(strpos($response_status, strtolower($sms_vendor['sms_delivery_pending'])) !== false)
			 				$response_status = 'PENDING';
			 			else if(strpos($response_status, strtolower($sms_vendor['sms_delivery_expired'])) !== false)
			 				$response_status = 'EXPIRED';
			 			else if(strpos($response_status, strtolower($sms_vendor['sms_delivery_ndnc'])) !== false)
			 				$response_status = 'DND';
						else $response_status = 'PENDING';
						
			 			$values[$i]['response_status'] = strtoupper($response_status);
					} else {
						$values[$i]['response_status'] = 'PENDING';
					}
					$i++;
					
					$returndata = array_merge($returndata, $values);
	 				unset($values);
	 			}
	 		}
	 		
	 	} else {
	 		foreach($data as $val) {
	 			
	 			$scheduleid = implode(',', $val);
	 			$scheduleid_keys = array_keys($val);
	 			
	 			//$url = $sms_vendor['sms_delivery_report_url'].'?'.$sms_vendor['sms_delivery_report_id'].'='.$scheduleid;
	 			$url = $sms_vendor['sms_delivery_report_url'].'?'.
	 					$sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
						$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'].'&'.
						$sms_vendor['sms_delivery_report_id'].'='.$scheduleid;
	
	 			$response_key = file_get_contents($url);
	 			
	 			$regex = $sms_vendor['sms_delivery_report_seperator'];
	 			$response_key = $this->br2sep($response_key, '##', $regex);
	 			$response_key = array_filter(explode('##', $response_key));
	 			
	 			//echo count($response_key).'-'.count($scheduleid_keys).'<br>';
	 			
	 			for($i=0; $i<count($response_key); $i++) {
	 				
	 				$values[$i]['id'] = $scheduleid_keys[$i];
	 				$response_key[$i] = strip_tags($response_key[$i]);
	 				
		 			/*if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_delivered']))
		 				$response_key[$i] = 'DELIVERED';
		 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_undelivered']))
		 				$response_key[$i] = 'UNDELIVERED';
		 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_pending']))
		 				$response_key[$i] = 'PENDING';
		 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_expired']))
		 				$response_key[$i] = 'EXPIRED';
		 			else if(strtolower($response_key[$i]) == strtolower($sms_vendor['sms_delivery_ndnc']))
		 				$response_key[$i] = 'DND';*/
	
	
					$response_key[$i] = strtolower($response_key[$i]);
	 				if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_delivered'])) !== false)
		 				$response_key[$i] = 'DELIVERED';
		 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_undelivered'])) !== false)
		 				$response_key[$i] = 'UNDELIVERED';
		 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_pending'])) !== false)
		 				$response_key[$i] = 'PENDING';
		 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_expired'])) !== false)
		 				$response_key[$i] = 'EXPIRED';
		 			else if(strpos($response_key[$i], strtolower($sms_vendor['sms_delivery_ndnc'])) !== false)
		 				$response_key[$i] = 'DND';
					else $response_key[$i] = 'PENDING';
					
		 			$values[$i]['response_status'] = strtoupper($response_key[$i]);
	 			}
	 			
	 			$returndata = array_merge($returndata, $values);
	 			unset($values);
	 		}
	 	}
 		//pr($returndata);exit;
 		return $returndata;
 	}
 	
 	function checkSchedule() {
 		
 		set_time_limit(0);
 		ini_set('memory_limit', '256M');
 		
 		//get all sms vendor details
 		$this->getSmsVendorDetails();
 		//print_r($this->sms_vendor_details);
 		
 		$cond['BulkSmsSchedule.status'] = '0';
 		$cond['BulkSmsSchedule.send'] = '0';
 		$cond['BulkSmsSchedule.scheduledate'] = date('Y-m-d H:i') . ':00';
 		$data = $this->BulkSmsSchedule->findAll($cond);
		//print_r($data);
		
 		foreach($data as $v) {
 			
 			unset($cond);
		 	$cond['conditions']['BulkAccount.bulk_user_id'] = $v['BulkSmsSchedule']['bulk_user_id'];
		 	$cond['conditions']['BulkAccount.status'] = '0';
		 	
		 	// Following is done to fetch recharge details BCOZ "hasMany" relation is lost in the loop of multiple data
		 	$this->BulkAccount->unBindAll();
		 	$bulk_account = $this->BulkAccount->find('all', $cond);
		 	
		 	$this->sms_vendor_id = $bulk_account['0']['BulkAccount']['sms_vendor_id'];
		 	
		 	unset($cond);
		 	$cond['conditions']['BulkAccountRecharge.bulk_account_id'] = $bulk_account['0']['BulkAccount']['id'];
		 	$cond['conditions']['BulkAccountRecharge.status'] = 0;
 			$bulk_account_recharge = $this->BulkAccountRecharge->find('all', $cond);
 			
		 	$validity = $bulk_account_recharge[count($bulk_account_recharge)-1]['BulkAccountRecharge']['validtill'];
			$quantity = $bulk_account['0']['BulkAccount']['quantity'];
			
	 		if(!$this->checkBulkValidity($validity) || $quantity == '0') {
	 			
	 			$this->sendScheduleStatus(1, $v);
	 			
 			} else {
 				
 				$numbers = explode(',', $v['BulkSmsSchedule']['numbers']);
 				
 				if(!empty($v['BulkSmsSchedule']['bulk_group_id'])) {
	 				unset($c);
	 				$not_included = explode(',', $v['BulkSmsSchedule']['not_included']);
 					if(!empty($v['BulkSmsSchedule']['not_included'])) {
	 					if(count($not_included) == 1) $c['conditions']['BulkAddressbook.id NOT'] = $not_included['0'];
	 					else $c['conditions']['BulkAddressbook.id NOT'] = $not_included;
	 				}
	 				$c['conditions']['BulkAddressbook.bulk_group_id'] = $v['BulkSmsSchedule']['bulk_group_id'];
	 				$c['conditions']['BulkAddressbook.status'] = '0';
	 				$c['fields'] = array('BulkAddressbook.mobile');
	 				$addressbook_contacts = $this->BulkAddressbook->find('list', $c);
	 				
	 				if(!empty($numbers)) $numbers = array_merge($numbers, $addressbook_contacts);
	 				else $numbers = $addressbook_contacts;
 				}
 				
 				$numbers = array_filter($numbers);
 				$numbers = array_values($numbers);
 				
 				// Save Data
	 			$savedata['message'] = $v['BulkSmsSchedule']['message'];
	 			$savedata['numbers'] = $v['BulkSmsSchedule']['numbers'];
		 		$savedata['sms_count'] = $this->sms_count($v['BulkSmsSchedule']['message']);
		 		$savedata['bulk_senderid_id'] = $v['BulkSmsSchedule']['bulk_senderid_id'];
		 		$savedata['bulk_group_id'] = $v['BulkSmsSchedule']['bulk_group_id'];
		 		$savedata['bulk_tag_id'] = $v['BulkSmsSchedule']['bulk_tag_id'];
		 		$savedata['bulk_user_id'] = $v['BulkSmsSchedule']['bulk_user_id'];
		 		$savedata['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
		 		$savedata['sms_vendor_id'] = $this->sms_vendor_id;
		 		$this->BulkSmsLog->create();
		 		$this->BulkSmsLog->save($savedata);
		 		
		 		// Change Schedules status to SEND
	 			$this->BulkSmsSchedule->id = $v['BulkSmsSchedule']['id'];
	 			$this->BulkSmsSchedule->saveField('send', '1');
 			
	 			// Send SMS
	 			$this->_sendBulkMessage($v['BulkSmsSchedule']['bulk_group_id'], $v['BulkSmsSchedule']['bulk_senderid_id'], $v['BulkSmsSchedule']['message'], $this->BulkSmsLog->getLastInsertId(), $v['BulkSmsSchedule']['bulk_user_id'], $numbers, false, $this->sms_vendor_id);
	 			
	 			// Send Success SMS to User
	 			$this->sendScheduleStatus(2, $v);
 			}
 		}
 		
 		$crondata['name'] = 'checkSchedule';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
 	
 	function sendScheduleStatus($type, $data) {
 		
 		$user_data = $this->BulkUser->findAll(array('BulkUser.id'=>$data['BulkSmsSchedule']['bulk_user_id']), array('BulkUserpersonalinfo.mobile'));
 		$mobile = $user_data['0']['BulkUserpersonalinfo']['mobile'];
 		
 		/*	Account expire or no money left	*/
 		if($type == 1) {
 			
 			$message = 'We regret non execution of your schedule request as your recharge pack or validity has expired. Contact the sales team to refill your account - ' . SMS_FOOTER;

 		/*	Send Success SMS to User	*/	
 		} else if($type == 2) {
 			
 			$message = 'Your Schedule request on '.date('jS M, Y g:i a', strtotime($data['BulkSmsSchedule']['scheduledate'])).' has been successufly executed - ' . SMS_FOOTER;
 			
 		}
 		
 		//get sms vendor details
		$this->sms_vendor_id = 1;
 		$this->getSmsVendorDetails(1);
 		
 		$this->sendSMS($mobile, $message, BULK_SMS_SENDER_ID, true);
 		
 	}
 	
 	function checkInactivity($days=30) {
 		
 		$data = $this->Domain->find('all', array('condtitions'=>array('Domain.status'=>'0')));//print_r($data);
 		foreach($data as $v) {
 			$date = date('Y-m-d', strtotime($v['User']['updated']));
 			$last = date('Y-m-d', strtotime("-$days days"));
 			
 			if($date < $last) {
 				$this->Message->setSource($v['Domain']['server'].'_log');
 				$message_count = $this->Message->find('count', array('conditions'=>array('created >'=>$last, 'domain_id'=>$v['Domain']['id'])));
 				
 				//disable the account and notify the user abt it;
 				if(empty($message_count)) {
 					$this->changeDomainStatus($v['Domain']['id']);
					$this->changeUerStatus($v['Domain']['id']);
 					$this->sendInactivityMail();
 				}
 			}
 		}
 		
 		$crondata['name'] = 'checkInactivity';
 		$this->Cron->save($crondata);
 		
 		exit;
 	}
 	
 	/**
 	 *  To be set daily at midnight
 	 */ 	
 	function checkBulkSmsExpiry() {
 		
 		$bulkUser = $this->BulkUser->find('all', array('conditions'=>array('BulkUser.status'=>'0')));
 		foreach($bulkUser as $key => $value) {
	
 			$maxReminder = max($this->bulkSmsExpiryReminderList);
			$now = date('Y-m-d');
			 
 			unset($cond);
 			$cond['conditions']['BulkAccountRecharge.bulk_Account_id'] = $value['BulkAccount']['id'];
 			$cond['conditions']['BulkAccountRecharge.validtill BETWEEN ? AND ?'] = array($now, date('Y-m-d', strtotime("+$maxReminder days")));
 			$cond['conditions']['BulkAccountRecharge.status'] = 0;
 			$cond['fields'] = array('validtill', 'created');
 			$cond['order']['BulkAccountRecharge.id'] = 'desc';
 			$cond['limit'] = 1;
 			$bulkAccountRecharge = $this->BulkAccountRecharge->find('all', $cond);
 			
 			if(count($bulkAccountRecharge) < 1) continue;
 			
			$validtill = date('Y-m-d', strtotime($bulkAccountRecharge['0']['BulkAccountRecharge']['validtill']));
			$created = date('Y-m-d', strtotime($bulkAccountRecharge['0']['BulkAccountRecharge']['created']));
			//echo $value['BulkAccount']['id'].' - '.$validtill.'<br/>';

			// get previous send reminders
 			unset($cond);
 			$cond['conditions']['BulkSmsExpiryReminder.bulk_account_id'] = $value['BulkAccount']['id'];
 			$cond['conditions']['BulkSmsExpiryReminder.status'] = 0;
 			$cond['fields'] = array('days');
 			$bulkSmsExpiryReminder = $this->BulkSmsExpiryReminder->find('list', $cond);
 			
 			foreach($this->bulkSmsExpiryReminderList as $days) {
 				$check = date('Y-m-d', strtotime("+$days days"));
 				
 				if($validtill == $check && !in_array($days ,$bulkSmsExpiryReminder)) {
 					$this->sendBulkSmsExpiryReminderMail($days, $created, $value['BulkAccount']['quantity'], $value['BulkUserpersonalinfo']['email']);
 					$this->updateBulkSmsExpiryReminder($days, $value['BulkAccount']['id']);
 				}
 				
 			}
 		}
 		
 		$crondata['name'] = 'checkBulkSmsExpiry';
 		$this->Cron->save($crondata);
 		
 		exit;
 	}
 	
 	function updateBulkSmsExpiryReminder($days, $bulkAccountId) {
 		$data['days'] = $days;
 		$data['bulk_account_id'] = $bulkAccountId;
 		$this->BulkSmsExpiryReminder->create();
 		$this->BulkSmsExpiryReminder->save($data);
 	}
 	
 	function sendBulkSmsExpiryReminderMail($days, $created, $quantity, $email) {
 		//echo $days.'-'.$created.'-'.$quantity.'-'.$email.'<br/>';return;
 		
 		// send expiry email
 		if($this->bulkSmsExpired == $days) {
			$subject = 'Bulk SMS Package Expired';
 			$mess = 'We at FREESMSAPI value your continued support and loyalty. '. 
 					'Our records indicate that, your Bulk SMS package bought on '.date('jS M Y', strtotime($created)) .' '.
 					'has expired on  '.date('jS M Y', strtotime("+$days days")) .'. '. 
 					'Kindly review our revised bulk rates specially extended to our patron members '.
 					'and renew your SMS credits to enjoy our uninterrupted quality service. '.
 					'The payment remittance details is mentioned on our website at '.PRICING_LINK.'.';

 		// send no. of days left email
 		} else {
 			$subject = 'Bulk SMS Package Expiry in '.$days.' Days';
 			$mess = 'We at FREESMSAPI value your continued support and loyalty. '. 
 					'Our records indicate that, your Bulk SMS package bought on '.date('jS M Y', strtotime($created)) .' '.
 					'will expire after '.$days.' days on '.date('jS M Y', strtotime("+$days days")) .'. '. 
 					'Kindly review our revised bulk rates specially extended to our patron members '.
 					'and renew your SMS credits to enjoy our uninterrupted quality service. '.
 					'The payment remittance details is mentioned on our website at '.PRICING_LINK.'.';
 		}
 		
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					$mess.
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER; 
 		//echo $message . '<br/>';
 		
 		$this->sendMail($email, $subject, $message);
 	}
 	
 	/**
 	 * 	check id sender ID is activated though API
 	 *  To be set every minute
 	 *  	
 	 * */
 	function checkSmsVendorForSenderId() {
 		
 		//$this->requestAction(array('controller' => 'admins', 'action' => 'activatealias'), array('pass' => array(5)));
 		
 		$cond['conditions']['SenderIdRepository.status'] = 0;
 		$cond['conditions']['SenderIdRepository.activate'] = 0;
 		$cond['conditions']['SenderIdRepository.sms_vendor_id'] = SENDER_ID_DEFAULT_SMS_VENDOR_ID;
 		$cond['fields'] = array('name');
 		$senderIdRepository = $this->SenderIdRepository->find('list', $cond);
 		
 		if(!empty($senderIdRepository)) {
 		
	 		// set vendor details
	 		$this->setSmsVendor(SENDER_ID_DEFAULT_SMS_VENDOR_ID);
	 		
	 		$sms_vendor = $this->sms_vendor_details['sms_vendor_'.$this->sms_vendor_id];
			$url = SENDER_ID_DEFAULT_GET_URL ."?". $sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
					 							$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'];
	 		$response = file_get_contents($url);
	 		
	 		if(!empty($response)) {
	 			$activated = array();
	 			$data = explode(',', $response);
	 			for($i=0; $i<count($data); $i++) {
	 				$data[$i] = trim($data[$i]);
	 				if(in_array($data[$i], $senderIdRepository)) {
	 					$activated[] = $data[$i];
	 				}
	 			}
	 			
	 			if(!empty($activated)) {
	 				foreach($activated as $value) {
		 				$this->SenderIdRepository->updateAll(array('SenderIdRepository.activate' => '1'), array('SenderIdRepository.name' => $value));
		 				$this->activatealias($value);
	 				}
	 			}
	 		}
 		}
 		
 		$crondata['name'] = 'checkSmsVendorForSenderId';
 		$this->Cron->save($crondata);
 		
 		exit;
 	}
 	
 	function activatealias($name) {
		
 		$sender_id = $aliasData['0']['Alias']['name'];
 		
 		$this->Alias->recursive = -1;
		$this->Alias->updateAll(array('Alias.publish' => '1'), array('Alias.id' => $id));
		
		$this->User->id = $aliasData['0']['Domain']['user_id'];
		$email = $this->User->field('email');
		
		$subject = 'Sender ID Request Status';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for your continued support. We are pleased to inform you that your '.
					'requested Sender ID \''.$sender_id.'\' has been activated. Enjoy using freesmsapi.'.
					SEPERATOR . SEPERATOR .
					'Kindly note that this free Sender ID is offered for a limited period of '.ALIAS_TRIAL_PERIOD.' days from the day of your first activation.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
		
		$this->autoRender = false;
		
	}
 	
 	function checkAlias() {
 		
 		// Get all valid domains
 		$find['conditions']['Domain.status'] = '0';
 		$this->Domain->unBindAll();
 		$data = $this->Domain->find('list', $find);

 		
 		if($data) {
 			
 			// Check if Expiry mail has already been send or not
 			$this->AliasReminder->unBindAll();
 			$reminder_mail = $this->AliasReminder->find('list', array('fields' => array('AliasReminder.id', 'AliasReminder.domain_id', 'AliasReminder.type')));
 				
 			foreach($data as $k => $v) {
 				
 				// Get Domain Alias List
 				$this->Alias->create();
 				$this->Alias->unBindAll();
 				$alias_list = $this->Alias->find('list', array('fields' => array('Alias.name', 'Alias.id'), 'conditions' => array('Alias.domain_id' => $k)));
 				
 				// Check if its a Paid Sender ID
 				$count = '';
 				if(!empty($alias_list)) {
	 				$this->AliasInvoice->unBindAll();
	 				$count = $this->AliasInvoice->find('count', array('conditions' => array('AliasInvoice.alias_id' => $alias_list)));
 				}
 				
 				if($count == '0') {
 					
 					// Get the first created date of Sender ID
	 				$this->Alias->create();
					$starting_date = $this->Alias->field('created', array('Alias.domain_id' => $k, 'Alias.publish' => '1', 'Alias.status' => '0'));
			 		$starting_date = date('Y-m-d', strtotime($starting_date));
					$end_date = date('Y-m-d');
					$diff = $this->date_diff('d', $starting_date, $end_date, false);
					
					// If nothing is published but cancelled by the user
					if($starting_date == '1970-01-01') continue;
					
					// Send mail upon expiration
					if($diff && $diff > ALIAS_TRIAL_PERIOD && !in_array($k, $reminder_mail['expiration day'])) {
						
						unset($val);
						$this->AliasReminder->create();
						$val['domain_id'] = $k;
						$val['type'] = 'expiration day';
						$this->AliasReminder->save($val);
						
						$this->Alias->unBindAll();
						$this->Alias->updateAll(array('Alias.publish' => '0'), array('Alias.domain_id' => $k));
						
						$this->sendAliasMail($k, true);
	
					// Send mail before 5 days of expiration
					} else if($diff && $diff > ALIAS_TRIAL_PERIOD-5 && !in_array($k, $reminder_mail['5 days before'])) {
						
						if(!$this->AliasReminder->find('count', array('conditions' => array('domain_id' => $k)))) {
							
							unset($val);
							$this->AliasReminder->create();
							$val['domain_id'] = $k;
							$val['type'] = '5 days before';
							$this->AliasReminder->save($val);
							
							$this->sendAliasMail($k, false);
						}
					}
 				}
 			}
 		}
 		
 		$crondata['name'] = 'checkAlias';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
 	
 	function sendAliasMail($domain_id, $state) {
 		
 		$this->Domain->id = $domain_id;
 		$this->User->id = $this->Domain->field('user_id');
 		$email = $this->User->field('email');
 		
 		if($state) {
 			$subject = 'Sender ID Promotional Offer Expired';
 			$mess = 'The Free Sender ID was offered on promotional basis for 30 days. '.
					'As per our records your evaluation period of 30 days has expired. However, if you wish to continue using '.
					'personalized Sender ID service, please make the purchase at the rates specified below.';
 		
 		} else {
 			$subject = 'Sender ID Promotional Offer Expiry in 5 Days';
 			$mess = 'The Free Sender ID is currently offered on promotional basis for 30 days. '.
 					'Your evaluation period will expire after 5 days on '.date('jS M Y', strtotime('+5 days')) .'. '.
 					'However, if you wish to continue using '.
					'personalized Sender ID service, please make the purchase at the rates specified below.';
 		}
 		
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					$mess.
					SEPERATOR . SEPERATOR .
					'1. 1 Month Validity - 400 INR'. SEPERATOR .
					'2. 3 Months Validity - 1,000 INR'. SEPERATOR .
					'3. 6 Months Validity - 1,800 INR'. SEPERATOR .
					'4. 1 Year Validity - 3,000 INR.'. SEPERATOR .
					'Cost mentioned above is for 1 Sender ID and is inclusive of service tax'.
					SEPERATOR . SEPERATOR .
					'Please contact us at sales@freesmsapi.com to purchase Sender IDs.'.
					SEPERATOR . SEPERATOR .
					'Please note that you can still send SMS using the default Sender ID "'. SMS_SENDER_ID .'" if you do not wish to subscribe to our plan.'.
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER; 
 		//echo $message . '<br/>';
 		
 		
 		$this->sendMail($email, $subject, $message);
 		
 	}
 	
 	function sendDailyReport() {
 		
 		/*	Only once in a day	*/
 		if($this->Cron->find('count', array('conditions'=>array('created LIKE'=>date('Y-m-d').'%', 'name'=>'sendDailyReport'))) > 0)
 			die('Only once in a day');
 			
 		/*	Get all users	*/
 		$c['conditions']['BulkSetting.status'] = '0';
 		$c['conditions']['BulkSetting.daily_report'] = '1';
 		$c['fields'] = array('BulkSetting.bulk_user_id');
 		$bulk_user_list = $this->BulkSetting->find('list', $c);
 		
 		if(empty($bulk_user_list)) exit;
 		
 		unset($c);
 		$c['conditions']['BulkUser.id'] = $bulk_user_list;
 		$this->BulkUser->unBindAll();
 		$bulkuser = $this->BulkUser->find('all', $c);
 		foreach($bulkuser as $v) {
 			unset($c);
 			$c['conditions']['BulkSmsLog.bulk_user_id'] = $v['BulkUser']['id'];
 			$c['conditions']['BulkSmsLog.status'] = '0';
 			$c['conditions']['BulkSmsLog.created LIKE'] = date('Y-m-d') . '%';
 			$c['fields'] = array('BulkSmsLog.id', 'BulkSmsLog.sms_count');
 			$this->BulkSmsLog->unBindAll();
 			$bulksmslog = $this->BulkSmsLog->find('list', $c);
 			
 			if(count($bulksmslog) > 0) {
 				$server = $this->_getBulkServer($v['BulkUser']['id']);
 				
	 			unset($c);
	 			$c['conditions']['BulkSmsLogDetail.bulk_sms_log_id'] = array_keys($bulksmslog);
	 			$c['conditions']['BulkSmsLogDetail.status'] = '0';
	 			$c['fields'] = array('BulkSmsLogDetail.id', 'BulkSmsLogDetail.response_status');
	 			$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $server);
	 			$detail = $this->BulkSmsLogDetail->find('list', $c);
	 			
 				/*	Count all response status	*/
		 		foreach($detail as $status) {
		 			if(isset($response_status[$status]))
		 				$response_status[$status] = ++$response_status[$status];
		 			else $response_status[$status] = 1; 
		 		}
		 		
		 		asort($response_status);
		 		$output = '';
		 		foreach($response_status as $key => $value) {
		 			$output .= ucfirst(strtolower($key)) .' - '. $value . SEPERATOR;
		 		}
		 		
		 		if(!empty($output)) {
			 		$this->BulkUserpersonalinfo->id = $v['BulkUser']['bulk_userpersonalinfo_id'];
		 			$mobile = $this->BulkUserpersonalinfo->field('mobile');
		 			$message = 'Daily Summary Report' . SEPERATOR . SEPERATOR;
		 			$message .= $output . SEPERATOR;
		 			$message .= SMS_FOOTER;
		 			
		 			//get sms vendor details
					$this->sms_vendor_id = 1;
			 		$this->getSmsVendorDetails(1);
 		
		 			$this->sendSMS($mobile, $message, BULK_SMS_SENDER_ID, true);
		 		}
 			}
 			
 		}
 		
 		$crondata['name'] = 'sendDailyReport';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
 	
 	/*	Update amount if sms is undelivered or expired for yesterday	*/
 	function updateBulkAmount() {
 		
 		return false;
 		
 		/*	Only once in a day	*/
 		if($this->Cron->find('count', array('conditions'=>array('created LIKE'=>date('Y-m-d').'%', 'name'=>'updateBulkAmount'))) > 0)
 			die('Only once in a day');
 		
 		/*	Get all users	*/
 		$bulkuser = $this->BulkUser->find('all', array('conditions'=>array('BulkUser.status'=>0)));//print_r(($bulkuser);
 		foreach($bulkuser as $v) {
 			unset($c);
 			$c['conditions']['BulkSmsLog.bulk_user_id'] = $v['BulkUser']['id'];
 			$c['conditions']['BulkSmsLog.status'] = '0';
 			$c['conditions']['BulkSmsLog.created LIKE'] = date('Y-m-d', strtotime('-1 day')) . '%';
 			//$c['conditions']['BulkSmsLog.created LIKE'] = date('Y-m-d') . '%';
 			$c['fields'] = array('BulkSmsLog.id', 'BulkSmsLog.sms_count');
 			$this->BulkSmsLog->unBindAll();
 			$bulksmslog = $this->BulkSmsLog->find('list', $c);
 			
 			if(count($bulksmslog) > 0) {
 				$server = $this->_getBulkServer($v['BulkUser']['id']);
	 			unset($c);
	 			$c['conditions']['BulkSmsLogDetail.bulk_sms_log_id'] = array_keys($bulksmslog);
	 			$c['conditions']['BulkSmsLogDetail.status'] = '0';
	 			$c['fields'] = array('BulkSmsLogDetail.id', 'BulkSmsLogDetail.response_status', 'BulkSmsLogDetail.bulk_sms_log_id');
	 			$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $server);
	 			$detail = $this->BulkSmsLogDetail->find('list', $c);
	 			
 				/*	Count all response status	*/
	 			$count = 0;
	 			//$res = array('UNDELIVERED', 'EXPIRED');
		 		foreach($detail as $key => $value) {
		 			foreach($value as $status) {
		 				if(in_array($status, $this->updateBulkAmountArray)) {
		 					$count += $bulksmslog[$key];
		 				}
		 			}
		 		}
		 		
		 		if(!empty($v['BulkAccount']['amount'])) {
			 		$costpersms = $v['BulkAccount']['amount'] / $v['BulkAccount']['quantity'];
			 		$newamount = $v['BulkAccount']['amount'] + ($costpersms * $count);
			 		$newquantity = $v['BulkAccount']['quantity'] + $count;
			 		
			 		/*	Add previous entry	*/
			 		$this->BulkAccountBackup->create();
			 		$this->BulkAccountBackup->save(array(
			 			'amount' => $v['BulkAccount']['amount'],
			 			'quantity' => $v['BulkAccount']['quantity'],
			 			'bulk_user_id' => $v['BulkUser']['id']
	 				));
			 		
			 		/*	Add new entry	*/
	 				$this->BulkAccountBackup->create();
			 		$this->BulkAccountBackup->save(array(
			 			'amount' => $newamount,
			 			'quantity' => $newquantity,
			 			'bulk_user_id' => $v['BulkUser']['id']
	 				));
	 				
	 				/*	Update Original table	*/
			 		$this->BulkAccount->updateAll(
				 		array(
				 			'amount' => $newamount,
				 			'quantity' => $newquantity,
		 				),
		 				array(
		 					'id' => $v['BulkUser']['id']
		 				)
		 			);
		 		}
 			}
 			
 		}
 		
 		$crondata['name'] = 'updateBulkAmount';
 		$this->Cron->save($crondata);
 		
 		exit;
 		
 	}
 	
 	function sendInactivityMail() {
 		
 	}
 	
 }
?>
