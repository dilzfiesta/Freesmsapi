<?php
/*
 * Created on Feb 23, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class MessagesController extends AppController {
 	
 	var $uses = array('Message', 'Domain', 'Monitor', 'Plan', 'ValidNumber', 'AdvContent', 'Smslog', 
 						'Alias', 'User', 'SmsVendor', 'UserSuspended', 'SpamList', 'SpamContainer', 'DomainIp');

 	function sendnow() {
 		
 		$this->checkAccess('developer');
 		
 		/*	Check if Sender ID is purchased or not, 
 		 * if purchased then dont show verify mobile number box
 		
 		$sid = $this->check_alias_purchase();
 		
	 	//	Verify mobile number	
	 	if(NOW > VERIFY_START_DATE && empty($sid['0']['AliasInvoice'])) {
	 		
		 	if(!$this->Session->read('verified')) {
		 		
		 		$verified_mobile = 0;
		 		$this->set('verified_mobile', 0);
		 		
				$c['conditions']['User.id'] = $this->Session->read('user_id');
				$c['conditions']['User.status'] = 0;
				$c['fields'] = array('User.verify', 'User.verifycode');
				$verifydata = $this->User->find('all', $c);
				$this->set('verifydata', $verifydata['0']['User']);
				
				if($this->Session->check('error')) {
		
					$this->set('error', $this->Session->read('error'));
					$this->set('user_name', $this->Session->read('verify_user_name'));
					$this->Session->delete('error');
					$this->Session->delete('verify_user_name');
				
				} else {
		
					$this->set('user_name', $this->Session->read('user_name'));
				}
				
		 	} else {
		 		
		 		$verified_mobile = 1;
		 		$verifymobile = '+91'.$this->Session->read('verifymobile');
		 		
		 		$this->set('verified_mobile', 1);
		 		$this->set('verifymobile', '+91'.$this->Session->read('verifymobile'));
		 	}
	 	}
	 	
 		//	Check if 30 days trial	
 		if($this->check_alias_date()) {
 			
  			
 		} else {
 			
 			unset($cond);
	 		$cond['Alias.publish'] = '1';
	 		$cond['Alias.status'] = '0';
	 		$cond['Alias.domain_id'] = $this->Session->read('domain_id');
	 		$this->Alias->unBindAll();
	 		$sid = $this->Alias->findAll($cond);
	 		
 		}
 		
 		$senderid_data = '';
 		if(!empty($sid))
 			foreach($sid as $key => $value) $senderid_data .= '<option value="'.$value['Alias']['id'].'">'.$value['Alias']['name'].'</option>';
	 	
 		$this->set('senderid_data', $senderid_data);*/
	 	
 		if($this->Session->check('success')) {
			$this->set('success', $this->Session->read('success'));
			$this->Session->delete('success');
		}
		
		
	 	/*	Send SMS	*/
 		if(isset($this->data)) {
 			
 			if(FREE_SMS_SERVER_DOWN) {
 				$error = UNAVAILABLE_MESSAGE;
 			}
 			
 			if(NINE_TO_NINE_ACTIVATED) {
 				$error = NINE_TO_NINE_ACTIVATED_MESSAGE;
 			} 			
 			
 			$this->Domain->id = $this->Session->read('domain_id');
			$skey = $this->Domain->field('secret_key');
 			$mess = urldecode(trim($this->data['message']));
 			
 			$og_recipient = $recipient = filter_var(urldecode(trim($this->data['recipient'])), FILTER_SANITIZE_STRING);
 			
 			/*	Check if Sender ID belongs to current user	*/
 			if(SHOW_SENDER_ID) {
	 			if(!isset($this->data['senderid'])) $senderid = SMS_SENDER_ID;
	 			else {
	 				$c['Alias.domain_id'] = $this->Session->read('domain_id');
	 				$c['Alias.id'] = $this->data['senderid'];
	 				$c['Alias.publish'] = '1';
	 				$c['Alias.status'] = '0';
	 				
	 				if($this->Alias->find('count', array('conditions'=>$c)) > 0) {
	 					
	 					$this->Alias->id = $this->data['senderid'];
	 					$senderid = $this->Alias->field('name');
	 					
	 				} else $senderid = SMS_SENDER_ID;
	 			}
 			} else { 			
	 			$senderid = SMS_SENDER_ID;
 			}
 			
 			/*	Append Mobile Number to Message  */
 			if(NOW > VERIFY_START_DATE && empty($senderid_data) && isset($verifymobile)) {
 				
 				$mess = substr($mess, 0, MESSAGE_MOBILE_CHAR_LIMIT);
 				$mess .= SEPERATOR . $verifymobile;
 				
 			}
 			
			/*	If file uploaded	*/
 			if(isset($this->data['file']) && $this->data['file']['error'] <> '4') {

 				$allowed_type = array('csv', 'xls');
 				$file_ext = strtolower(end(explode('.', $this->data['file']['name'])));
 				
 				if($this->data['file']['error'] == '0') {
	 				
		 			if(in_array($file_ext, $allowed_type)) {
		 				
		 				if($file_ext == 'csv') {
			 				$data = file_get_contents($this->data['file']['tmp_name']);
			 				$data = $this->csv_to_array($data);
			 				foreach($data as $v) {
			 					foreach($v as $b) {
			 						$numbers[] = $b;
			 					}
			 				}
			 				$numbers = array_unique($numbers);
		 				}
		 				
		 				if($file_ext == 'xls') {
		 					App::import('vendor', 'Spreadsheet_Excel_Reader', array('file' => 'Excel/reader.php'));
		 					$data = new Spreadsheet_Excel_Reader();
							$data->setOutputEncoding('CP1251');
		 					$data->read($this->data['file']['tmp_name']);
							
		 					foreach($data->sheets as $k => $v) {
								if($v['numRows'] <> '0' && $v['numCols'] == '1') $content[$k] = $v['numRows']; 
							}
							
							foreach($data->sheets[$this->min_key($content)]['cells'] as $k => $v) {
								if(is_array($v)) {
									foreach($v as $b) {
										$numbers[] = $b;
									}
								}
							}
							$numbers = array_unique($numbers);
		 				}
		 				
		 				if(!empty($recipient)) $recipient = implode(',', $numbers) .','. $recipient;
		 				else $recipient = implode(',', $numbers);
		 				
		 			} else $error = 'Invalid file extension';
	 			
 				} else $error = 'There was an error while uploading the file, please try again';
 			
 			}
 			
 			if(!isset($error)) {
 				//$error = $this->send(true, $skey, $mess, $recipient, $senderid);
 				$return_data = $this->send(true, $skey, $mess, $recipient, $senderid);
 				if(strpos($return_data, '::') !== false)
 					list($success, $error) = explode("::", $return_data);
 				else {
 					$success = '';
 					$error = $return_data;
 				}
 				
 				$error = str_replace('</number><number>', ', ', $error);
 				$error = str_replace('</error><success>', '<br/> ', $error);
				$error = str_replace('</error><error>', '<br/> ', $error);
 			}

 			$this->set('success', $success);
 			$this->set('error', $error);
 			$this->set('message', $mess);
 			$this->set('recipient', $og_recipient);
 		}

			
	 	$this->layout = 'after_login';
	 	$this->set('tab', array('3'));
		//$this->getFeedback();
		
 	}
 	
 	function send($sendnow=false, $skey=null, $mess=null, $recipient=null, $senderid=null, $widget=false) {
 		
 		// tweak to make call from widget work
 		$sendnow = (boolean)$sendnow;
 		$widget = (boolean)$widget;
 		
 		//echo $sendnow.'-'.$skey.'-'.$mess.'-'.$recipient.'-'.$senderid;exit;
 		//pr($_REQUEST); exit;
		

		$this->write(date('Y-m-d H:i:s') .' :: '. $_SERVER['REMOTE_ADDR']);

 		if(!$sendnow) {

 			$skey = urldecode(trim($_REQUEST['skey']));
	 		$mess = urldecode(trim($_REQUEST['message']));
	 		$recipient = filter_var(urldecode(trim($_REQUEST['recipient'])), FILTER_SANITIZE_STRING);
	 		
	 		// Sender ID is optional
	 		if(isset($_REQUEST['senderid'])) $senderid = filter_var(urldecode(trim($_REQUEST['senderid'])),FILTER_SANITIZE_STRING);
	 		else $senderid = '';
	 		
	 		// Response is optional
	 		if(isset($_REQUEST['response'])) $response_format = trim($_REQUEST['response']);
	 		else $response_format = 'xml';
	 		
 		}

 		/* url decode the message if coming from widget	*/
 		if($widget) {
 			$mess = urldecode($mess);
 		}
 		
 		if(FREE_SMS_SERVER_DOWN) {

 			if(!$sendnow) $this->_throwError($this->_errorMsgEnvelope($this->_errorMsg($sendnow, UNAVAILABLE_MESSAGE)), $response_format);
 			else return UNAVAILABLE_MESSAGE;
 			
 		}
 		
 		if(NINE_TO_NINE_ACTIVATED) {

 			if(!$sendnow) $this->_throwError($this->_errorMsgEnvelope($this->_errorMsg($sendnow, NINE_TO_NINE_ACTIVATED_MESSAGE)), $response_format);
 			else return NINE_TO_NINE_ACTIVATED_MESSAGE;
 			
 		}
 		
 		/*	Get Remote Address	*/
		$remote_addr = $this->getRemoteAddr();
		
		/*	Get Client IP is available (from widget)*/
		$client_ip = 0;
		if(isset($_REQUEST['client_ip']) && !empty($_REQUEST['client_ip']))
			$client_ip = $_REQUEST['client_ip'];
		
 		/*	Correct Encoding	*/
 		$mess = iconv('UTF-8', 'ASCII//TRANSLIT', $mess);
 		
 		$original_message = $mess;
 		
 		/*	Convert newlines to spaces	*/
 		$mess = str_replace("\r\n", "\n", $mess);
 		
 		$output = '';
 		
 		if(!$skey || !$mess || !$recipient) {
 			
 			$return_message = 'One or more parameters are missing';
 			$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
 			
 			if(!$sendnow) $this->_throwError($output, $response_format); 
 			else return $return_message;
 			
 		}
 		
 		$domain = $this->checkSecretKey($skey);
 		//pr($domain);exit;
 		if($domain) {
 			
 			$domain_id = $domain['0'];
 			$from = $domain_name = $domain['1'];
 			$plan_id = $domain['2'];
 			$server = $domain['3'];
 			$ip = $domain['4'];
 			$category_id = $domain['5'];
 			$verify = $domain['6'];
 			$per_day_limit = $domain['7'];
 			$user_id = $domain['8'];
 			$widget_restriction_on_user = $domain['9'];
 			
 			/*	SMS's are accepted only from widgets */
 			if(!$sendnow && $widget_restriction_on_user) {
 				
 				$return_message = 'Use of API is suspended from your account. Instead use SMS WIDGET';
	 			$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
	 			
				if(!$sendnow) $this->_throwError($output, $response_format); 
	 			else return $return_message;
	 			
 			}
 		
			/*	Check for Spam	*/
			if($this->checkIfSpam($mess)) {
				
				/*	Add to spam table	*/
				$this->markAsSpam($recipient, $mess, $domain_id, $remote_addr, $client_ip);
				
				$return_message = 'Spam and Abusive messages are strictly prohibited';
	 			$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
	 			
				if(!$sendnow) $this->_throwError($output, $response_format); 
	 			else return $return_message;
			}

			/*	Add freesmsapi footer */
			$mess = $this->charLimit($mess);
			$mess = $mess . FREE_SMS_FOOTER;
			
 			/* check if suspended	*/
 			$terminate = $this->checkUserSuspended($user_id);
			if(!empty($terminate)) {
				$return_message = $terminate;
				$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
	 			
				if(!$sendnow) $this->_throwError($output, $response_format); 
	 			else return $return_message;
			}
 			
 			/*	Get sms vendor details	*/
 			$this->getSmsVendor($domain_id);
 			//pr($this->sms_vendor_details);
 			
 			if(!$sendnow && NOW > VERIFY_START_DATE && !$verify) {

 				/*	Check if Sender ID is purchased or not, 
		 		 * if purchased then dont show verify mobile number box
		 		*/
 				if(!$this->get_alias($domain_id)) {

	 				$return_message = 'Please verify your Mobile Number to enjoy Uninterrupted Service';
	 				$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
	 				
	 				if(!$sendnow) $this->_throwError($output, $response_format); 
	 				else return $return_message;
			 	
			 	} /*else if(!$this->check_alias_purchase($domain_id)) {

	 				$output .= '<error><message>Please verify your Mobile Number to enjoy Uninterrupted Service.</message></error>';
	 				$this->_throwError($output, $response_format);
			 	
			 	}*/
 				
 			}
 			
 			// Check sub - domain
 			if(!IS_TEST_SERVER && SUBDOMAIN_LOCK) {
 				
 				if(!$sendnow && $_SERVER['SERVER_NAME'] != $server.'.'.SERVERNAME) {
	 				$return_message = 'Invalid Sub-domain';
	 				$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
	 				
	 				if(!$sendnow) $this->_throwError($output, $response_format); 
	 				else return $return_message;
 				}
 			}
 			
 			
 			// Check IP
 			if(!IS_TEST_SERVER && IP_LOCK) {

 				 // for new widget || for old too
 				if(($sendnow && $widget) || (!$sendnow)) {
 
 					if(!ip2long($ip)) $return_message = 'Invalid IP Address';
	 				else if($remote_addr != $ip) {
	 					unset($cond);
	 					$cond['conditions']['DomainIp.domain_id'] = $domain_id;
	 					$cond['conditions']['DomainIp.ip'] = ip2long($remote_addr);
	 					$cond['conditions']['DomainIp.status'] = 0;
	 					if($this->DomainIp->find('count', $cond) == 0) {
	 						//if(!$this->traceroute($remote_addr, $ip, $domain_id)) {
	 							//$return_message = 'Invalid IP Address, SMS\'s are accepted only from the IP Address '.$ip;
	 							$return_message = 'Invalid Domain, Requests are accepted only from the domain "'.$domain_name.'" with an IP Address '.$ip;
	 							//$return_message = '';
	 						//}	
	 					}
	 				}
	 				
					if(isset($return_message)) {
	
	 					$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
	 				
		 				if(!$sendnow) $this->_throwError($output, $response_format); 
		 				else return $return_message;
				
					}
					
 				}
 				
 			}
 			
 			
 			// check total number of messages send today and exit if limit exceeded
 			$this->Message->setSource($server.'_log');
 			$message_count = $this->Message->find('count', array('conditions'=>array('created'=>date('Y-m-d'), 'domain_id'=>$domain_id)));
 			
 			/*$condition['conditions']['id'] = $plan_id;
 			$condition['fields'] = array('sms', 'interval');
 			$allowed = $this->Plan->find('all', $condition);
 			$per_day_limit = $allowed['0']['Plan']['sms'];*/
 			
 			if($message_count >= $per_day_limit) {
 				
 				$return_message = 'Your daily sms limit has reached ('.$per_day_limit.' SMS)';
 				$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
 				
 				if(!$sendnow) $this->_throwError($output, $response_format); 
 				else return $return_message;
 				
 			}
 			
 			$mobile_number = explode(',', $recipient);
 			$invalid_mobile = '';
 			$success = '';
 			$failed = '';
 			
 			
 			//should not be more than 50 in single request
 			$allowed_recipient = MAX_RECIPIENT;
 			if(count($mobile_number) > $allowed_recipient) {
 				
 				$return_message = 'You are tyring to send SMS to '.count($mobile_number).' recipients, While max recipient limit is '.MAX_RECIPIENT.'. Please rectify the above error and then try again';
 				$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));
 				
 				if(!$sendnow) $this->_throwError($output, $response_format);
 				else return $return_message;
 				
 			}
 			
 			
 			//should not be more than 100 in single request
 			/*$allowed_recipient = MAX_RECIPIENT;
 			if(count($mobile_number) > $allowed_recipient) {
 				$tmp = array_slice($mobile_number, 0, $allowed_recipient);
 				
 				for($i=count($tmp); $i<count($mobile_number); $i++)
 					$maxlimitreached[] = $mobile_number[$i];
 				
 				$mobile_number = $tmp;
 			}*/
 			
 			
 			/*	Should be reduced to $per_day_limit	*/
 			$big_count = $message_count + count($mobile_number);
 			if($big_count > $per_day_limit) {
 				
 				$reduce_to = $big_count - $per_day_limit;
 				$reduce_to = count($mobile_number) - $reduce_to;
 				$mobile_number = array_slice($mobile_number, 0, $reduce_to);
 				
 			}
 			
 			/*if(!ONLY_DEVELOPER_SECTION) {
	 			//get advertisement related to category
	 			//$adv_cond['conditions'] = array('adv_send != quantity');
	 			$adv_cond['conditions']['status'] = '0';
	 			$adv_cond['conditions']['category_id'] = $category_id;
	 			$adv_cond['conditions']['launch_date'] = date('Y-m-d');
	 			$adv_cond['conditions']['0'] = 'adv_send <> quantity';
	 			$adv_cond['fields'] = array('id', 'content', 'adv_send', 'quantity');
	 			$this->AdvContent->unbindAll();
	 			$content = $this->AdvContent->find('all', $adv_cond);
	
	  			//add advertisement to message
	 			if(!empty($content)) {
	 				
	 				$p = 0;
	 				$content_present = true;
	 				foreach($content as $c) {
	 					$content_data[$p]['id'] = $c['AdvContent']['id'];
	 					$content_data[$p]['content'] = $c['AdvContent']['content'];
	 					$content_data[$p]['actual'] = $c['AdvContent']['quantity'];
	 					$content_data[$p]['remaining'] = $c['AdvContent']['quantity'] - $c['AdvContent']['adv_send'];
	 					$p++;
	 				}
	 				
	 			} else $content_present = false;
 			}*/
 			
 			$p = 0;
 						
			/*	Get senderid  */
 			if(SHOW_SENDER_ID) {
	 			if(!$senderid) $senderid = SMS_SENDER_ID;
				else {
					$val['Alias.domain_id'] = $domain_id;
					$val['Alias.name'] = $senderid;
					$val['Alias.publish'] = '1';
					$val['Alias.status'] = '0';
					$this->Alias->unBindAll();
					if($this->Alias->find('count', array('conditions'=>$val)) == 0) $senderid = SMS_SENDER_ID;
				}
			} else {
 				$senderid = SMS_SENDER_ID;
 			}
 			
 			$mobile_number = array_filter($mobile_number);
 			
 			foreach($mobile_number as $v) {
 				$to = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
 				$to = trim($to);
 				
 				//check if mobile number is valid
 				if($this->checkNumber($to)) {
 					
 					/*if(!ONLY_DEVELOPER_SECTION) {
	 					//merge the message with the advertisement if available
	 					if($content_present) {
	 						if(isset($content_data[$p])) {
			 					if($content_data[$p]['remaining'] > 0) {
			 						
			 						$mess = $original_message . LINE_BREAK . $content_data[$p]['content'];
			 						$content_data[$p]['remaining']--;
			 						
			 					} else {
			 						
			 						$p++;
			 						if(isset($content_data[$p])) {
				 						$mess = $original_message . LINE_BREAK . $content_data[$p]['content'];
				 						$content_data[$p]['remaining']--;
			 						}
			 						
			 					}
	 						}	 					
	 					}
 					}*/
 					
 					
 					//send sms
		 			$response_key = $this->_sendMessage($to, $mess, $senderid);
 					
		 			if($response_key) {

		 				$value['name'] = $to;
			 			$value['sender'] = $senderid;
			 			$value['message'] = $original_message;
			 			$value['domain_id'] = $domain_id;
			 			$value['adv_content_id'] = (isset($content_data[$p]['id']) ? $content_data[$p]['id'] : '0');
			 			$value['response_key'] = $response_key;
			 			$value['response_status'] = UNDELIVERED;
			 			$value['ip'] = ip2long($remote_addr);
		 				$value['client_ip'] = ip2long($client_ip);
			 			$value['sms_vendor_id'] = $this->sms_vendor_id;
			 			$value['updated'] = date('Y-m-d H:i:s');
			 			
			 			//insert sms entry into log
			 			$this->Message->create();
			 			$this->Message->setSource($server.'_log');
			 			$this->Message->save($value);
			 			
			 			// store for DND numbers in a seperate array
			 			if(substr($response_key, 0, 4) == '007-') {
			 				$dnd[] = $to;
			 			} else {
				 			$success[] = $to;
			 			}
			 			
			 			unset($value);
			 			
		 			} else {
		
		 				$failed[] = $to;
 					
		 			}
		 				
 				} else {
 					
 					$invalid_mobile[] = $to;
 						
 				}
 				
 			}
 			
 			//update DB with total sms send
 			/*if(isset($content_present)) {
	 			foreach($content_data as $c) {
	
	 				$adv_send = $c['actual'] - $c['remaining'];
	 				$this->AdvContent->create();
	 				$this->AdvContent->id = $c['id'];
	 				$this->AdvContent->saveField('adv_send', $adv_send);
	 				
	 			}
 			}*/
 			
 			/*echo 'in: ';pr($invalid_mobile);
 			echo 'suc: ';pr($success);
 			echo 'fai: ';pr($failed);
 			echo 'dn: ';pr($dnd);
 			exit;*/

 			
 			
 			// get error messages
 			$returnerrors = '';
 			if(isset($invalid_mobile) && !empty($invalid_mobile)) {
 				$allerrors[] = $this->_errorMsg($sendnow, 'Invalid mobile number(s)', $invalid_mobile);
 			}
 			
 			if(isset($failed) && !empty($failed)) {
 				$allerrors[] = $this->_errorMsg($sendnow, 'Unable to send to following number(s)', $failed);
 			}
 			
 			if(isset($dnd) && !empty($dnd)) {
 				$allerrors[] = $this->_errorMsg($sendnow, 'DND service is enabled on following number(s)', $dnd);
 			}
 			
 			if(!empty($allerrors)) {
 				if(!$sendnow)
 					$returnerrors = '<errors>'.implode('', $allerrors).'</errors>';
 				else
 					$returnerrors = implode('<br/>', $allerrors);
 			}
 			
 			// get success message
 			$returnsuccess = '';
 			if(isset($success) && !empty($success)) {
 				$returnsuccess = $this->_successMsg($sendnow, 'Message successfully sent to following number(s)', $success);	
 			}
 			
 			// return everything
			if(!$sendnow) {
				
				$this->_throwError($returnsuccess.$returnerrors, $response_format);
			
			} else {
				
				if(!$widget) {
					
					return $returnsuccess.'::'.$returnerrors;
				
				} else {
					
					// one mobile number is served at a time
					if(empty($returnsuccess)) return $returnerrors;
					else if(empty($returnerrors)) return $returnsuccess;
				
				}
			}
			
 			// if plan id is not 1 and image is not set on the user domain then return with error
 			/*$allow = $this->Monitor->field('image_found', array('domain_id' => $domain_id));
 			if($plan_id != '1' && $allow == 'false') {
 				
 				echo 'We are unable to find our advertisement on your website';
 				exit;

 			}*/ 			

 			
 			// Check time interval between messages
 			/*if($allowed['0']['Plan']['interval'] != '0') {
	 			unset($condition);
	 			$condition['conditions']['domain_id'] = $domain_id;
	 			$condition['fields'] = array('MAX(id) as maxid', 'MAX(created) as maxcreated');
	 			$this->Message->setSource($server.'_log');
	 			$message = $this->Message->find('all', $condition);
	 			//echo '<pre>';print_r($message);
	 			
	 			$result = $this->Message->query('SELECT UNIX_TIMESTAMP()-UNIX_TIMESTAMP("'.$message['0']['0']['maxcreated'].'") as difference');
	 			if($result['0']['0']['difference'] < $allowed['0']['Plan']['interval']) {
	 				
	 				echo 'This message will be dropped as your time interval betweeen two messages should be greater than '.$allowed['0']['Plan']['interval'].' secs';
	 				exit;
	 				
	 			}
 			}*/
 			
 			
 		} else { 
 				
 			$return_message = 'Invalid Secret Key';
 			$output = $this->_errorMsgEnvelope($this->_errorMsg($sendnow, $return_message));

 			if(!$sendnow) $this->_throwError($output, $response_format);
 			else return $return_message;
			
 		}
 		
 		exit;
 		
 	}
 	
 	function _sendMessage($to, $mess, $senderid) {
 		
		return $this->sendSMS($to, $mess, $senderid); 		
  		
 	}
 	
 	function _getAdvertisment($mess) {
 		
 		$this->autoRender = false;
 		
 	}
 	
 	function _errorMsgEnvelope($message) {
 		
 		return '<errors>'.$message.'</errors>';
 		
 	}
 	
 	function _errorMsg($sendnow, $message, $numbers=null) {

 		if(!empty($numbers)) {
	 		if(!$sendnow) return '<error><message>'.$message.' </message><number>'.implode('</number><number>', $numbers).'</number></error>';
	 		else return $message.' '.implode(', ', $numbers);
 		
 		} else {
 			if(!$sendnow) return '<error><message>'.$message.' </message></error>';
	 		else return $message;
 		}
 		
 	}
 	
 	function _successMsg($sendnow, $message, $numbers) {

 		if(!$sendnow) return '<success><message>'.$message.' </message><number>'.implode('</number><number>', $numbers).'</number></success>';
 		else return $message.' '.implode(', ', $numbers);
 		
 	}
 	
 	function checkIfSpam($message) {

 		$params['conditions']['SpamList.status'] = 0;
		$spam_list = $this->SpamList->find('list', $params);
		
		$message = strtolower($message);
		$message = str_replace('-', ' ', $message);
		$message = str_replace('_', ' ', $message);
		$message = explode(' ', $message);
		$message = array_filter($message);
		
		$diff = array_diff($message, $spam_list);
		
		if(count($message) != count($diff)) return true;
		else return false;
		
	}
	
	function markAsSpam($name, $message, $domain_id, $ip, $client_ip) {
		
		$data['name'] = $name;
		$data['message'] = $message;
		$data['domain_id'] = $domain_id;
		$data['ip'] = ip2long($ip);
		$data['client_ip'] = ip2long($client_ip);
		$this->SpamContainer->save($data);
	
	}
 	
 }
?>
