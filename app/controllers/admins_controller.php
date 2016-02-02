<?php
/*
 * Created on May 1, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class AdminsController extends AppController {
 	
	var $uses = array('Admin', 'Advertiser', 'AdvPlan', 'AdvContent', 'Category', 'Plan', 'Message', 'Alias', 'Contact',
						'Domain', 'History', 'Upgrade', 'User', 'Feedback', 'UserTmp', 'Maillog', 'MerchantOrder', 
						'IncompleteRegistration', 'AliasReminder', 'BulkUser', 'BulkAccount', 'BulkFeedback',
						'BulkAccountRecharge', 'BulkSmsLog', 'BulkSenderid', 'BulkGroup', 'BulkSmsTag', 'BulkUserpersonalinfo',
						'AliasInvoice', 'AliasBuy', 'BulkAccountRegister', 'BulkSmsLogDetail', 'BlacklistDomain', 'SmsVendor',
						'SenderIdRepository', 'UserSuspended', 'UserSuspendedReason', 'SpamContainer'); 
	
	function beforeFilter() {
		
		/*	Admin have to login every 10 mins	*/
		if($_REQUEST['url'] <> 'admins/' && $_REQUEST['url'] <> 'admins/index' && !IS_TEST_SERVER && !CRON_DISPATCHER) {
			if($this->Session->read('milegekitnadetihai') <> 'admin') $this->signout();
			//if($this->Session->read('alloutjaloamacharbhagao') + ADMIN_TIMEOUT < time()) $this->signout();
		}
	}
	
	function Orders() {
		
		$orders = $this->MerchantOrder->findAll();
		$orders = array_reverse($orders);
		$this->set('orders', $orders);
		$this->set('tab', array('7'));
	}
	
	function feedbacklog() {
		
		$feedback = $this->Feedback->findAll();
		$this->set('feedback',$feedback);
		$this->set('domain_list', $this->Domain->find('list'));
		$this->set('tab', array('3'));
		
	}
	
	function contactlog() {
		
		$contact = $this->Contact->findAll();
		$this->set('contact',$contact);
		$this->set('tab', array('5'));
		
	}
	
	function bulkuser() {
		
		$this->set('sms_vendor', $this->SmsVendor->find('all'));
		$this->set('tab', array('6'));
		$bulk_user = $this->BulkUser->findAll();
		$this->set('bulk_user', $bulk_user);
		
	}
	
	function buybulksms() {
		
		$this->data['activation_key'] = md5(md5(date('YmdHis').SALT).date('YmdHis').SALT);
		$this->data['validtill'] = date('Y-m-d', strtotime($this->data['validtill']));
		$this->BulkAccountRegister->save($this->data);
		
		$url = SERVER.'bulksms/register/'.$this->data['activation_key'];
		
		$this->_sendBulkRegistrationEmail($url, $this->data['email']);
	
 		$this->redirect($this->referer());
		exit;
		
	}
	
	function view() {
		
		// after hitting signout, it won't comeback when back button is pressed
		//$this->disableCache();

		$this->getSmsVendorDetails();
		foreach($this->sms_vendor_details as $value) {
			$url = $value['sms_balance_check_url'] .'?'. $value['sms_url_username'] .'='. $value['sms_username'] .'&'. $value['sms_url_password'] .'='. $value['sms_password'] .'&'. $value['sms_route'];
			$data = file_get_contents($url);
			$data = ereg_replace("[^0-9]", "", $data);
			$quantity_left[$value['name']] = $data; 
		}
		
		$data = $this->Domain->findAll();
		foreach($data as $k => $v) {
			$total_message[$v['Domain']['id']] = $this->_getTotalMessage($v['Domain']['id'], $v['Domain']['server']);
		}
		
		// get all suspend reasons
		$reason = $this->UserSuspendedReason->find('all', array('conditions'=>array('status'=>'0')));
		$user_suspend_list = $this->UserSuspended->find('list', array('conditions'=>array('status'=>'0'), 'fields'=>array('user_id', 'reason_id')));
		
		$user_tmp = $this->UserTmp->findAll(array('UserTmp.status'=>'0'));
		$inc_reg = $this->IncompleteRegistration->find('list', array('fields'=>array('id', 'user_tmp_id')));
		
		$this->layout = 'admin';
		$this->set('total_message', $total_message);
		$this->set('inc_reg', $inc_reg);
		$this->set('user_tmp', $user_tmp);
		$this->set('quantity_left', $quantity_left);
		$this->set('data', $data);
		$this->set('reason', $reason);
		$this->set('user_suspend_list', $user_suspend_list);
		$this->set('tab', array('1'));
		
	}
	
	function _getTotalMessage($domain_id, $server_id) {
		
		$condition['conditions']['domain_id'] = $domain_id;
		$this->Message->setSource($server_id.'_log');
		return $this->Message->find('count', $condition);
	
	}
	
	function changecategory($id) {
		
		if(isset($this->data)) {
			
			$this->Domain->save($this->data);
			$this->redirect('/admins/view');
			
		} else {
		
			$this->Domain->id = $id;
			$domain = $this->Domain->field('name');
			//$category = $this->Category->findAll(array('Category.status'=>'0', 'Domain.plan_id'=>'1'));
			$category = $this->Category->findAll(array('Category.status'=>'0'));
			
			$this->set('domain', $domain);
			$this->set('category', $category);
			$this->set('domain_id', $id);
			$this->set('tab', array('1'));
			
		}
		
	}
	
	function changeip($id) {
		
		$this->Domain->id = $id;
		$domain = $this->Domain->field('name');
		$ip = gethostbyname($domain);
		
		$this->Domain->id = $id;
		$this->Domain->saveField('ip', $ip);
		
		$this->redirect('/admins/view');
		
	}
	
 	function changepublish($type, $id) {
		
		if($type == 'feedback') {
			
			$this->Feedback->id = $id;
			$publish = $this->Feedback->field('publish');
			
			$publish = ($publish == '0') ? '1' : '0';
			
			$this->Feedback->id = $id;
			$this->Feedback->saveField('publish', $publish);
			
			$this->redirect('/admins/feedbacklog');
			exit;
		}
		$this->redirect('/admins/view');
		
	}
	
	function removednd($id) {
		
		$this->Alias->query('update alias set dnd="off" where id='.$id);
		
		$this->Alias->id = $id;
		$this->Domain->id = $this->Alias->field('domain_id');
		$this->User->id = $this->Domain->field('user_id');
		$email = $this->User->field('email');
		
		$this->Alias->id = $id;
		$sender_id = $this->Alias->field('name');
		
		$subject = 'DND Filter Removal Request';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for your continued support. We are pleased to inform you that your '.
					'DND removal request for Sender ID \''.$sender_id.'\' has been processed and activated. Enjoy using freesmsapi.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
		
		$this->redirect($this->referer());
		exit;
		
	}
	
 	function bulkremovednd($id) {
		
		$this->BulkSenderid->query('update bulk_senderid set dnd="off" where id='.$id);
		
		$this->BulkSenderid->id = $id;
		$this->BulkUser->id = $this->BulkSenderid->field('bulk_user_id');
		$this->BulkUserpersonalinfo->id = $this->BulkUser->field('bulk_userpersonalinfo_id');
		$email = $this->BulkUserpersonalinfo->field('email');
		
		$this->BulkSenderid->id = $id;
		$sender_id = $this->BulkSenderid->field('name');
		
		$subject = 'DND Filter Removal Request';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for your continued support. We are pleased to inform you that your '.
					'DND removal request for Sender ID \''.$sender_id.'\' has been processed and activated. Enjoy using freesmsapi.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
		
		$this->redirect($this->referer());
		exit;
		
	}
	
	function changestatus($type, $id) {
		
		if($type == 'domain') {
			
			$this->changeDomainStatus($id);
			$this->changeUerStatus($id);
			$this->redirect('/admins/view');
			exit;
			
		} else if($type == 'feedback') {
			
			$this->Feedback->id = $id;
			$status = $this->Feedback->field('status');
			
			$status = ($status == '0') ? '1' : '0';
			
			$this->Feedback->id = $id;
			$this->Feedback->saveField('status', $status);
			
			$this->redirect('/admins/feedbacklog');
			exit;
		}
		$this->redirect('/admins/view');
		
	}
	
	// add sender id and price
	function buysenderid() {
		
		$this->Alias->unBindAll();
		$this->Alias->updateAll(
			array('Alias.status' => '1'),
			array('Alias.name' => $this->data['name'], 'Alias.domain_id' => $this->data['domain_id'])
		);
		
		$v['name'] =  $this->data['name'];
		$v['domain_id'] = $this->data['domain_id'];
		$v['publish'] = '1';
		$this->Alias->save($v);
		$senderid = $this->Alias->getLastInsertId();
		
		if($this->data['amount'] == '3000') $validtill = '1 year';
		else if($this->data['amount'] == '1800') $validtill = '6 months';
		else if($this->data['amount'] == '1000') $validtill = '3 months';
		else $validtill = '1 month';
		
		$from_date = date('jS M Y');
		$to_date = date('jS M Y', strtotime($validtill));
		
		unset($v);
		$v['alias_id'] = $senderid;
		$v['amount'] = $this->data['amount'];
		$v['validtill'] = date('Y-m-d H:i:s', strtotime('+'.$validtill));
		$v['friends'] = isset($this->data['friends']) ? 1 : 0;
		$this->AliasInvoice->save($v);
		
		unset($v);
		$v['alias_id'] = $senderid;
		$v['alias_invoice_id'] = $this->AliasInvoice->getLastInsertId();
		$this->AliasBuy->save($v);
		
		// Send mail
		$this->Domain->id = $this->data['domain_id'];
		$this->User->id = $this->Domain->field('user_id');
		$email = $this->User->field('email');
		
		$subject = 'Sender ID Purchase and Activation Details';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for giving us the opportunity to serve you better. We are pleased to inform you that your '.
					'Sender ID \''.$this->data['name'].'\' has been activated.' .
					SEPERATOR . SEPERATOR .
					'Please find below your purchase details.'.
					SEPERATOR .
					'1. Total Amount Paid: ' . $this->format_money($this->data['amount']) . ' INR.' . SEPERATOR .
					'2. Sender ID Expires on: '. $to_date . '.' .
					SEPERATOR . SEPERATOR .
					'For any further clarifications please feel free to write us back.' .
					SEPERATOR . SEPERATOR .
					'Enjoy using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
		$this->redirect($this->referer());
		
		$this->autoRender = false;
	}
	
	function checkalias() {

		$c['conditions']['AliasReminder.status'] = '0';
		$c['fields'] = array('domain_id', 'type');
		$alias_reminder = $this->AliasReminder->find('list', $c);
		
		$this->Domain->unBindAll();
		$domain = $this->Domain->find('list', array('conditions'=>array('Domain.status'=>'0')));
		$d = '';
		
		$alias_reminder_list = $this->AliasReminder->find('list', array('conditions'=>array('AliasReminder.status'=>'0')));
		
		foreach($domain as $k => $v) {
			//if(in_array($k, $alias_reminder_list))
				$d .= '<option value="'.$k.'">'.$v.'</option>';
		}
		$this->set('domain', $d);
		
		$dnd = '';
		foreach($domain as $k => $v) {
			$dnd .= '<option value="'.$k.'">'.$v.'</option>';
		}
		$this->set('dnd_drop', $dnd);
		
		//$cond['conditions']['Alias.status'] = '0';
		//$data = $this->Alias->find('all', $cond);
		$data = $this->Alias->find('all', array('order' => array('Alias.created','Alias.domain_id')));
		
		foreach($data as $v) {
			$this->Alias->create();
			$cr_ar[$v['Alias']['domain_id']] = $this->Alias->field('created', array('Alias.domain_id' => $v['Domain']['id'], 'Alias.publish'=>'1'));
		}
		
		// get all sender IDs which were send for activation
		unset($cond);
		$cond['conditions']['SenderIdRepository.sms_vendor_id'] = SENDER_ID_DEFAULT_SMS_VENDOR_ID;
		$cond['conditions']['SenderIdRepository.status'] = 0;
		$cond['conditions']['SenderIdRepository.activate'] = 0;
		$cond['fields'] = array('name');
		$senderIdRepository = $this->SenderIdRepository->find('list', $cond);
		
		$this->layout = 'admin';
		$this->set('cr_ar', $cr_ar);
		$this->set('data', $data);
		$this->set('alias_reminder', $alias_reminder);
		$this->set('sender_id_repository', $senderIdRepository);
		$this->set('tab', array('4'));
		
	}
	
 	function bulkacceptalias($id) {
		
		$this->BulkSenderid->updateAll(array('BulkSenderid.publish' => '1'), array('BulkSenderid.id' => $id));
		
		$this->BulkSenderid->id = $id;
		$this->BulkUser->id = $this->BulkSenderid->field('bulk_user_id');
		$this->BulkUserpersonalinfo->id = $this->BulkUser->field('bulk_userpersonalinfo_id');
		$email = $this->BulkUserpersonalinfo->field('email');
		
		$this->BulkSenderid->id = $id;
		$sender_id = $this->BulkSenderid->field('name');
		
		$subject = 'Sender ID Request Status';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for your continued support. We are pleased to inform you that your '.
					'requested Sender ID \''.$sender_id.'\' has been activated. Enjoy using freesmsapi.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
		$this->redirect($this->referer());
		
		$this->autoRender = false;
		
	}
	
	function acceptalias($id, $name) {
		
		$this->Alias->recursive = -1;
		$aliasData = $this->Alias->find('all', array('conditions'=>array('Alias.name'=>$name, 'Alias.id'=>$id, 'Alias.status'=>'0')));

        $this->Domain->recursive = -1;
        $this->Domain->id = $aliasData['0']['Alias']['domain_id'];

        $this->User->id = $this->Domain->field('user_id');
        $email = $this->User->field('email');

		$this->Alias->unBindAll();
        $this->Alias->updateAll(array('Alias.publish' => '1'), array('Alias.id' => $id));

        $subject = 'Sender ID Request Status';
        $message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
		            'Thank you for your continued support. We are pleased to inform you that your '.
		            'requested Sender ID \''.$name.'\' has been activated. Enjoy using freesmsapi.'.
		            SEPERATOR . SEPERATOR .
		            'Kindly note that this free Sender ID is offered for a limited period of '.ALIAS_TRIAL_PERIOD.' days from the day of your first activation.'.
		            SEPERATOR . SEPERATOR .
		            MAIL_FOOTER;

        $this->sendMail($email, $subject, $message);

		$this->redirect('/admins/checkalias');

        $this->autoRender = false;
	}

	/*function acceptalias($id, $name) {
		
 		$data['name'] = $name;
 		$data['sms_vendor_id'] = SENDER_ID_DEFAULT_SMS_VENDOR_ID;
		$this->SenderIdRepository->save($data);
		
		// send activation request
		$this->setSmsVendor(SENDER_ID_DEFAULT_SMS_VENDOR_ID);
		$this->callForSenderIdActivation($name);
		
		$this->redirect('/admins/checkalias');
		
		$this->autoRender = false;
		
	}*/
	
	/*function callForSenderIdActivation($name) {
		$sms_vendor = $this->sms_vendor_details['sms_vendor_'.$this->sms_vendor_id];
		$url = SENDER_ID_DEFAULT_SEND_URL ."?". $sms_vendor['sms_url_username'].'='.$sms_vendor['sms_username'].'&'.
				 							$sms_vendor['sms_url_password'].'='.$sms_vendor['sms_password'].'&'.
				 							 'sender='.$name;
		file_get_contents($url); 
	}*/
	
	function sendAliasForActivation() {
		
		$ids = explode('::', $this->data['id']);
		$names = explode('::', $this->data['name']);
		
		$ids = array_filter($ids);
		$names = array_filter($names);
		
		foreach($names as $key => $name) {
			$filename[] = $name; 
			$data[$key]['name'] = $name;
			$data[$key]['alias_id'] = $ids[$key];
	 		$data[$key]['sms_vendor_id'] = SENDER_ID_DEFAULT_SMS_VENDOR_ID;
		}
		
		$ext = '.jpg';
		$filename = implode(', ', $filename);
		$fullpath = SENDER_ID_PATH . SENDER_ID_FOLDER . $filename . $ext;
		
		if(!empty($data)) {
			$this->SenderIdRepository->saveAll($data);

			/* Initialize image */
			$image = new Imagick();
			$draw = new ImagickDraw(); 
			$image->readImage(SENDER_ID_PATH . SENDER_ID_FILE);
			
			/* Black text */
			$draw->setFillColor('black');
			
			/* Font properties */
			$draw->setFont(SENDER_ID_PATH . 'arial.ttf');
			$draw->setFontSize(40);
			
			/* Create text */
			$image->annotateImage($draw, 1620, 780, 0, date('d/m/y'));
			$image->annotateImage($draw, 1290, 1230, 0, $filename);
			
			/* Give image a format and minify */
			$image->setImageFormat('jpg');
			$image->minifyImage();
			
			/* Save file */
			file_put_contents($fullpath, $image);
			@chmod($fullpath, 0777);
			
			/* send activation request */
			$this->sendSenderIDForActivation($filename . $ext);
			
			/* send activation request
			$this->setSmsVendor(SENDER_ID_DEFAULT_SMS_VENDOR_ID);
			$this->callForSenderIdActivation($name);*/
		}
		
		$this->redirect('/admins/checkalias');
		
	}
	
 	function declinealias() {

 		$id = trim($_POST['id']);
 		$reason = trim($_POST['reason']);
 		
		$this->Alias->updateAll(array('Alias.status' => '1'), array('Alias.id' => $id));
		
		$this->Alias->id = $id;
		$this->Domain->id = $this->Alias->field('domain_id');
		$this->User->id = $this->Domain->field('user_id');
		$email = $this->User->field('email');
		
		$subject = 'Sender ID Request Status';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for your continued support. We regret to inform you that the requested Sender ID '.
					'cannot be activated as the requested due the following reason(s):'.
					SEPERATOR . SEPERATOR .
					$reason.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
		$this->redirect('/admins/checkalias');
		
	}
	
	function checkupgrade() {
		
		$data = $this->Upgrade->getUpgradeData();
		$plan = $this->Plan->find('all', array('conditions'=>array('status'=>'0'), 'fields'=>array('id', 'name', 'sms')));
		
		$o = '';
		foreach($plan as $value) {
			$o .= '<option value="'.$value['Plan']['id'].'">'.$value['Plan']['name'] .' - '.$value['Plan']['sms'].'</option>';
		}
		
		$this->set('data', $data);
		$this->set('plan', $o);
		$this->set('tab', array('2'));
		
	}
	
	function removeupgrade($id) {
		
		$this->Upgrade->id = $id;
		$this->Upgrade->saveField('status', '1');
		$this->redirect('/admins/checkupgrade');
		
	}
	
	function declineupgrade($domain_id, $id) {
		
		$this->Upgrade->id = $id;
		$this->Upgrade->saveField('approve', '0');
		
		$this->Domain->id = $domain_id;
		$user_id = $this->Domain->field('user_id');
		
		$this->User->id = $user_id;
		$email = $this->User->field('email');
		
		$subject = 'SMS UPGRADE REQUEST';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We regret to inform you that your upgrade request for additional '.
					'messages has been declined. Due to unprecedented traffic and overwhelming '.
					'response, we will be unable to authorize '.
					'additional free messages at this time. Please upgrade to our bulk SMS paid plans at <a href="' . SERVER . 'users/pricing">'.SERVER.'users/pricing</a>' . SEPERATOR . SEPERATOR .
					'The princing package includes the following features: '. SEPERATOR .
					'1. Address Book & Group SMS'. SEPERATOR .
					'2. Long Messages (upto 800 Char)'. SEPERATOR .
					'3. Free Multiple Alpha Numeric Sender ID\'s (8 Char)'. SEPERATOR .
					'4. Scheduling'. SEPERATOR .
					'6. Developer API (You can integrate SMS solution with your software and can be automated)'. SEPERATOR .
					'7. NDNC Nos. can be filtered without loss of any credits. Non DND routes as per requirement'. SEPERATOR .
					'8. Real time delivery report and unlimited storage of all reports.'. SEPERATOR . SEPERATOR .
					'Please do not hesitate to contact us if you have any further questions or concerns.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER; 
 		
 		$this->sendMail($email, $subject, $message);
		$this->redirect('/admins/checkupgrade');
		
		$this->autoRender = false;
	}
	
	/*function testmail() {
		$this->sendMail('dilzfiesta@gmail.com', 'testmail', 'Helloworld');
		$this->autoRender = false;
	}*/

	function changeupgrage($domain_id, $id) {
		
		$this->Upgrade->id = $id;
		$this->Upgrade->saveField('approve', '1');
		
		$plan = $this->data['select'];
		$this->Domain->id = $domain_id;
		$this->Domain->saveField('plan_id', $plan);
		
		$this->Domain->id = $domain_id;
		$user_id = $this->Domain->field('user_id');
		
		$this->User->id = $user_id;
		$email = $this->User->field('email');
		
		$value['domain_id'] = $domain_id;
		$value['plan_id'] = $plan;
		$this->History->save($value);
		
		$subject = 'SMS UPGRADE REQUEST';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We are pleased to inform you that your upgrade request for additional '.
					'messages has been processed. This email confirms the upgradation of your '.
					'account. Please do not hesitate to contact us if you have any further '.
					'questions or concerns. '.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER; 
		
		$this->sendMail($email, $subject, $message);
		$this->redirect('/admins/checkupgrade');
		
		$this->autoRender = false;
	}
	
	function incompleteregistration() {

 		$inc_reg = $this->IncompleteRegistration->find('list', array('fields'=>array('id', 'user_tmp_id')));
		
 		$cond['conditions']['UserTmp.status'] = '0';
 		$cond['conditions']['NOT'] = array('UserTmp.id'=>$inc_reg);
 		$cond['fields'] = array('id', 'name', 'password', 'email');
		$data = $this->UserTmp->find('all', $cond);
		
		if(!empty($data)) {
			$count = 0;
			foreach($data as $key => $values) {
				$this->_inc_reg($values['UserTmp']);
				$output[$count++]['user_tmp_id'] = $values['UserTmp']['id'];
			}
			$this->IncompleteRegistration->saveAll($output);
		}
		
		$this->autoRender = false;
	}
	
	function _inc_reg($values) {
		
		$subject = 'Freesmsapi Incomplete registration';
		$message = 'Hello,'.
					SEPERATOR . SEPERATOR .
					'Our records indicate that your account activations is still pending.'.
					SEPERATOR . SEPERATOR .
					'You can login to our website at '.SERVER.'users/login and use the '.
					'below username and password to activate your account.'.
					SEPERATOR . SEPERATOR .
					'Username: '.$values['name'] . SEPERATOR .
					'Password: '.$values['password'].
					SEPERATOR . SEPERATOR .
					'Thank you for using Free SMS API.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER; 
 		
 		$this->sendMail($values['email'], $subject, $message);
		
	}
	
	function index() {
		
		if(isset($this->data)) {
			
			if($this->data['username'] == ADMIN_USERNAME && $this->data['password1'] == ADMIN_PASSWORD1 && $this->data['password2'] == ADMIN_PASSWORD2) {

				$this->Session->start();
				$this->Session->write('milegekitnadetihai', 'admin');
				$this->Session->write('alloutjaloamacharbhagao', time());
				$this->redirect('/admins/view');
				exit;

			} else {
				
				//$error = 'Invalid username and password';
				//$this->set('error', $error);
				
			}
			
		} else {
			
			$this->Session->destroy();
			
		}
		
		$this->getFeedback();
		
	}
	
	function viewsms($domain_id) {

		$this->Domain->id = $domain_id;
		$server = strtolower($this->Domain->field('server'));
		$this->Message->create();
		$this->Message->setSource($server.'_log');
		$data = $this->Message->findAll(array('domain_id' => $domain_id));
		$this->set('data', $data);
		$this->set('tab', array('1'));
		
	}
	
	function bulksmsdetails($user_id) {
		
		$bulk_account = $this->BulkAccount->findAll(array('BulkAccount.bulk_user_id' => $user_id));
		$this->set('bulk_account', $bulk_account['0']['BulkAccount']);
		
		$bulk_account_recharge = $this->BulkAccountRecharge->findAll(array('BulkAccountRecharge.bulk_account_id' => $bulk_account['0']['BulkAccount']['id']));
		$this->set('bulk_account_recharge', $bulk_account_recharge);
		
		$bulk_feedback = $this->BulkFeedback->findAll(array('BulkFeedback.user_id' => $user_id));
		$this->set('bulk_feedback', $bulk_feedback);
		
		$bulk_group = $this->BulkGroup->findAll(array('BulkGroup.bulk_user_id' => $user_id));
		$this->set('bulk_group', $bulk_group);
		
		$bulk_senderid = $this->BulkSenderid->findAll(array('BulkSenderid.bulk_user_id' => $user_id));
		$this->set('bulk_senderid', $bulk_senderid);
		
		$bulk_sms_tag = $this->BulkSmsTag->findAll(array('BulkSmsTag.bulk_user_id' => $user_id));
		$this->set('bulk_sms_tag', $bulk_sms_tag);
		
		$cond['conditions']['BulkGroup.bulk_user_id'] = $user_id;
 		$groups = $this->BulkGroup->find('list', $cond);
 		$this->set('groups', $groups);
 		
 		$bulk_sms_log = $this->BulkSmsLog->findAll(array('BulkSmsLog.bulk_user_id' => $user_id));
		$this->set('bulk_sms_log', $bulk_sms_log);
		
 		unset($cond);
 		$cond['conditions']['BulkSenderid.bulk_user_id'] = $user_id;
 		$senderid = $this->BulkSenderid->find('list', $cond);
 		$this->set('senderid', $senderid);
 		
 		unset($cond);
 		$cond['conditions']['BulkSmsTag.bulk_user_id'] = $user_id;
 		$tag = $this->BulkSmsTag->find('list', $cond);
 		$this->set('tag', $tag);
 		
 		$this->set('user_id', $user_id);
		$this->set('tab', array('1'));
	}
	
	function bulksmsrecharge() {
		
		$this->BulkAccountRecharge->save($this->data);
		
		$this->BulkAccount->unBindAll();
		$data = $this->BulkAccount->find(array('bulk_user_id'=>$this->data['user_id']));
		
		$amount = $this->data['amount'] + $data['BulkAccount']['amount'];
		$quantity = $this->data['quantity'] + $data['BulkAccount']['quantity'];
		
		$this->BulkAccount->unBindAll();
		$this->BulkAccount->updateAll(
			array('amount'=>$amount, 'quantity'=>$quantity),
			array('bulk_user_id'=>$this->data['user_id'])
		);
		
		//TODO: send mail
		
		$this->redirect($this->referer());
		
	}
	
	function viewpdf($name) { 
		
		if(isset($name) && !empty($name)) {
			Configure::write('debug',0); // Otherwise we cannot use this method while developing
			$this->set('name',$name);
			$this->set('admin',$this);
			$this->layout = 'pdf'; 
			$this->render(); 
		}        
    }
	
    function sendSenderIDForActivation($filename) {
    	
	    if($filename != null) {
	    	
	    	list($names, $ext) = explode('.', $filename);
	    	$names = explode(', ', $names);
	    	
	 		$message = 'Please create the following Sender ID(s) into the account of gyrodevit.'.
						SEPERATOR.SEPERATOR;
						
						for($i=0; $i<count($names); $i++) {
							$message .= $i+1 . '. ' . $names[$i] . SEPERATOR;
						}
						
			$message .= SEPERATOR.
						'Do reply once activated.'.
						SEPERATOR.SEPERATOR.
						'--'.SEPERATOR.
						'Thank you,'.SEPERATOR.
						'Warm Regards,'.SEPERATOR.
						SMS_FOOTER;

			$recipient = 'support@pinnacleteleservices.com';
			$cc = 'rituraj.kumar@pinnacleteleservices.com';
			$subject = 'New Sender ID Acitvation';
			$attachments[] = SENDER_ID_PATH . SENDER_ID_FOLDER . $filename;
			$this->sendMail($recipient, $subject, $message, $cc, $attachments);
				
		}
    }
    
	function signout() {
		
		$this->Session->destroy();
		$this->redirect('/admins/');
		exit;
		
	}
	
	function closuresms($domain) {

		$domain = trim($domain);
		$domain = strtolower($domain);

		//Configure::write('debug',2);
		$cond['conditions']['Domain.status'] = 0;
		$cond['conditions']['Domain.name'] = $domain;
		$cond['fields'] = array('id', 'user_id');
		$user_list = $this->Domain->find('list', $cond);

		unset($cond);
		$cond['conditions']['User.status'] = 0;
		$cond['conditions']['User.id'] = $user_list;
		$cond['fields'] = array('User.name', 'User.email');
        $data = $this->User->find('list', $cond);

		//echo '<pre>';print_r($user_list);print_r($data);

        if(!empty($data)) {
        	foreach($data as $key => $values) {
            	$this->_closuresms($key);
			}
		}

		/*$reason = md5(SALT . date('YmdHis'));

		$this->User->updateAll(
			array('User.password'=>'"'.$reason.'"', 'User.status'=>1),
			array('User.id'=>$user_list)
		);

		$this->Domain->updateAll(
			array('Domain.secret_key'=>'"'.$reason.'"', 'Domain.status'=>1),
			array('Domain.user_id'=>$user_list)
		);*/

		$this->addBlacklistDomain($domain);

		$this->autoRender = false;
	}

	function _closuresms($mobile) {
	
		$message = 'It came to our attention that you have used a Free Email ID to register at Freesmsapi. As per our company policy we do not allow Free Email ID\'s and hence your account is being terminated effective immediately. Please use a legitimate Domain Email to register again, Thank you - Freesmsapi Team';

		$this->sendSMS($mobile, $message, INTERNAL_SMS_SENDER_ID, true);
		                
		$this->autoRender = false;

	}

	// suspend user
 	function usersuspend() {

 		$user_id = $this->data['user_id'];
 		$reason_id = $this->data['reason_id'];
 		
		$cond['conditions']['id'] = $user_id;	
		$cond['conditions']['status'] = '0';
 		$cond['fields'] = array('id', 'email');
		//$cond['limit'] = '800,100';
		$data = $this->User->find('list', $cond);

		//pr($data);exit;

		if(!empty($data)) {
			foreach($data as $key => $values) {
				
				// for termination
				if(in_array($reason_id, array(1,5))) {
					$this->UserSuspended->create();
					$this->UserSuspended->save(
						array(
							'user_id' => $key,
							'reason_id' => $reason_id
						)					
					);

				// ask to use widget (suspend API)
				} else if(in_array($reason_id, array(2,3))) {
					$this->Domain->recursive = -1;
					$this->Domain->updateAll(
						array('widget' => '1'),
						array('user_id' => $key)
					);
				}
				
				$this->_usersuspend_mail($values, $reason_id);
			}
		}
		
		$this->redirect($this->referer());
		$this->autoRender = false;
	}
	
 	function _usersuspend_mail($email, $reason_id) {

 		if(in_array($reason_id, array(1))) {
 			$subject = 'Your Account has been Terminated';
			$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'It came to our attention that you have used a Free Email ID to register at Freesmsapi. As per our company policy we do not allow Free Email ID\'s and hence your account is being terminated effective immediately. This measure is taken to discourage users from sending spam and abusive messages.'.
					SEPERATOR . SEPERATOR .
					'Please use valid domain email address, either of your company\'s or of your own to register again.'.
	                SEPERATOR . SEPERATOR .
					'Thank you for your co-operation,'.
					SEPERATOR . SEPERATOR .
					'Freesmsapi Team';
					
			list($name, $domain) = explode('@', $email);		
			$this->closuresms($domain);
			
 		} else if(in_array($reason_id, array(2, 3))) {
			$subject = 'Your API has been Suspended';
			$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We have received a complaint from CYBER CRIME DIVISION (MUMBAI) that your website is responsible for sending abusive and spam sms\'s. '.
					'As this activity is strictly prohibited by law, we are suspending your use of SMS API. '.
					'Instead you can install our new SMS WIDGET which tracks Sender\'s IP and reports the same to CYBER CRIME DIVISION incase of any misuse. '.
					'For more information on features and installation visit '.SERVER.'widgets'.
					SEPERATOR . SEPERATOR .
					'We regret the inconvenience caused. Please feel free to contact us if you have any questions or concerns.'.
	                SEPERATOR . SEPERATOR .
					'Regards,'.
					SEPERATOR . SEPERATOR .
					'Freesmsapi Team';
 		
 		} /*else if(in_array($reason_id, array(3))) {
			$subject = 'Your Account has been Suspended';
            $message = 'Dear User,'.
            		SEPERATOR . SEPERATOR .
					'We have received complaints from CYBER CRIME DIVISION (MUMBAI) that your website is responsible for sending abusive and spam messages. '.
            		'As this activity is strictly prohibited by law, we are temporarily suspending our service offerings to you until further notice.'.
                    SEPERATOR . SEPERATOR .
                    'We regret the inconvenience caused. Please feel free to contact us if you have any questions or concerns.'.
                    SEPERATOR . SEPERATOR .
                    'Regards,'.
                    SEPERATOR . SEPERATOR .
                    'Freesmsapi Team';

		}*/ else if(in_array($reason_id, array(4))) {
 			$subject = 'Abusive and spam sms\'s are being sent from your account';
			$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'It came to our notice that several abusive and spam sms\'s are continuously being sent from your account. '.
					'Our company policy discourages sending of spam and abusive messages and your account will be terminated permanently if you continue sending these sms\'s.'.
					SEPERATOR . SEPERATOR .
	                'To view the list of sms\'s sent from your account go to "Delivery Reports" under "SMS" section after login.'.
	                SEPERATOR . SEPERATOR .
					'Thank you,'.
					SEPERATOR . SEPERATOR .
					'Freesmsapi Team';
 		
 		} else if(in_array($reason_id, array(5))) {
			$subject = 'Your Account has been Terminated';
            $message = 'Dear User,'.
            		SEPERATOR . SEPERATOR .
					'We would like to inform you that your account has been terminated permanently as several spam and abusive sms\'s were continously being sent from your account registered at Freesmsapi.'.
                    SEPERATOR . SEPERATOR .
                    'Please feel free to contact us if you have any questions or concerns.'.
                    SEPERATOR . SEPERATOR .
                    'Regards,'.
                    SEPERATOR . SEPERATOR .
                    'Freesmsapi Team';
		}
 		
		/*$message = 'Dear Valued Customer,'.
				SEPERATOR . SEPERATOR .
				'As many of you may be aware, the Telecom Regulatory Authority of India (TRAI) recently issued new regulations on the SMS industry in India. These regulations are designed to significantly curb the sending of unsolicited commercial SMSes to consumers. Starting from Mar 1st 2011 every SMS service provider has to adhere to the new TRAI NCPR (National Consumer Preference Register (NCPR)) guidelines. Check www.nccptrai.gov.in for more details on new TRAI guidelines.'.
				SEPERATOR . SEPERATOR .
				'In order to adhere to TRAI guidelines Freesmsapi is also changing its messaging policies from 1st of Mar, 2011 onwards.'.
				SEPERATOR . SEPERATOR .
				'New Policies'.
				SEPERATOR . SEPERATOR .
				'* According to the New TRAI guidelines, No SMS provider is allowed to deliver SMSes to DND Registered mobile numbers. So, Starting from Mar 1st 2011 Freesmsapi does not deliver SMSes to DND Registered mobile numbers. However, DND Registered mobile numbers will be displayed in your Delivery Reports.'.
				SEPERATOR . SEPERATOR .
				'* All messages sent via Freesmsapi will be sent as "TD- XXXXXX" as the sender (XXXXXX is the Unique number allotted by Telecom operator to Freesmsapi). No Longer you can use your own sender ID.'.
				SEPERATOR . SEPERATOR .
				'We seek your understanding as we continue to employ our best efforts to comply with the TRAI Guidelines in a timely manner.'.
				SEPERATOR . SEPERATOR .
				'We regret any inconvenience caused to you and hope the advance notice will enable you to plan your future use of our services accordingly.'.
				SEPERATOR . SEPERATOR .
				'Thanks & Regards,'.
                                SEPERATOR . SEPERATOR .
                                'Freesmsapi Team';*/
 		//echo $email.' :: '.$subject.' :: '.$message;exit;
 		$this->sendMail($email, $subject, $message);
		
	}
	
	function spamcontainer($mode=null, $domain_id=null) {
		
		if(!empty($mode) && !empty($domain_id)) {
			$this->SpamContainer->recursive = -1;
			$cond['conditions']['SpamContainer.status'] = '0';
			$cond['conditions']['SpamContainer.domain_id'] = $domain_id;
			$data = $this->SpamContainer->find('all', $cond);
			$this->set('count_list', 0);
		} else {
			$cond['conditions']['SpamContainer.status'] = '0';
			$cond['fields'] = array('count(SpamContainer.id) as count', 'Domain.id', 'Domain.name');
			$cond['group'] = array('SpamContainer.domain_id');
			$data = $this->SpamContainer->find('all', $cond);
			$this->set('count_list', 1);
			
			// get last 10 spam messages
			unset($cond);
			$cond['conditions']['SpamContainer.status'] = '0';
			$cond['order'] = 'SpamContainer.id DESC';
			$cond['limit'] = '10';
			$spam_list = $this->SpamContainer->find('all', $cond);
			$this->set('spam_list', $spam_list);
		}
		
		$this->layout = 'admin';
		$this->set('data', $data);
		$this->set('tab', array('8'));
		
	}
	
	function unsuspend() {
		
		$data = $this->Domain->query("select distinct d.name,d.id, u.id, u.email, us.id, us.reason_id,u.status,d.status from domain d inner join user u on u.id=d.user_id inner join user_suspended us on us.user_id=u.id and us.reason_id in (2,3) and us.status=0 and d.id not in(1504, 583, 409, 1490, 443)");
		foreach($data as $value) {
			$all_user_id[] = $value['u']['id'];
			$password = $this->generateRandomNumber(8);
			$secret_key = md5( md5( $value['d']['name'] . SALT ) . SALT . $password );
			
			$this->User->unBindAll();
			$this->User->create();
			$this->User->updateAll(
				array('password' => $password, 'status' => 0),
				array('id' => $value['u']['id'])
			);
			
			$this->Domain->unBindAll();
			$this->Domain->create();
			$this->Domain->updateAll(
				array('secret_key' => '"'.$secret_key.'"', 'widget' => 1, 'status' => 0),
				array('id' => $value['d']['id'])
			);
			
			$this->UserSuspended->unBindAll();
			$this->UserSuspended->create();
			$this->UserSuspended->updateAll(
				array('status' => 1),
				array('id' => $value['us']['id'])
			);
			
			$this->_unsuspend_mail($value['u']['email'], $password);
		}
			
		exit;
	}
	
	function _unsuspend_mail($email, $password) {
		
		$subject = 'Your Account has been Un-suspended';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We are delighted to inform you that we have unsuspended your account. Please find below your new password to login at Freesmsapi.'.
					SEPERATOR . SEPERATOR .
					'Password: '. $password . 
					SEPERATOR . SEPERATOR .
					'Also note that your use of API is suspended but you can still send SMS using our new WIDGET.' .
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;

		echo $email . '<br/>';
 		$this->sendMail($email, $subject, $message);
		
	}
	
	function newfeature() {
		
		//$cond['conditions']['status'] = '0';
 		$cond['fields'] = array('id', 'email');
		$cond['order'] = 'id DESC';
		//$cond['limit'] = '800,100';
		$data = $this->User->find('list', $cond);
		
		if(!empty($data)) {
			foreach($data as $id => $email) {
				$this->_newfeature_mail($email);
			}
		}
		//print_r($data);
		exit;
		
	}
	
	
	function _newfeature_mail($email) {
		
		/*$subject = 'New Feature - IP Address Lock';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We are delighted to inform you that we have undertaken certain precautionary measures as mandated by the '. 
					'regulatory authorities to ensure quality service. Kindly make a note of the following feature implemented '. 
					'for enhanced user experience.'.
					SEPERATOR . SEPERATOR .
					'IP ADDRESS LOCK'. 
					SEPERATOR . SEPERATOR .
					'We have introduced locking of your Domain IP to verify User’s Genuine Identity. All the messages sent through '.
					'your account using our API will be pushed to your customers only if your Sending Domain IP match with the IP of your '.
					'registered Domain at Freesmsapi.'.
					SEPERATOR . SEPERATOR .
					'Eg: If your domain \'www.example.com\' has the IP address \'12.34.56.78\', then messages will only be pushed if it is '.
					'coming from the that IP address, And rest of the messages will be rejected.'.
					SEPERATOR . SEPERATOR .
					'We will implement this IP Lock from 1st August 2011, and request you to make the necessary changes to your code base '.
					'before the stipulated deadline to avoid any inconviences later.' .
					SEPERATOR . SEPERATOR .
					'Please note that this feature is NOT applicable for those users who are sending messages through our Web Console.' .
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					NEW_FEATURE_FOOTER.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER; */
 		
		/*$subject = 'New Feature - SMS WIDGET';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We are delighted to inform you that we have undertaken certain precautionary measures as mandated by the '. 
					'regulatory authorities to ensure quality service. Kindly make a note of the following feature implemented '. 
					'for enhanced user experience.'.
					SEPERATOR . SEPERATOR .
					'SMS WIDGET'. 
					SEPERATOR . SEPERATOR .
					'We are pleased to introduce a new SMS WIDGET that can be easily integrated with your existing websites.'.
					SEPERATOR . SEPERATOR .
					'Some of the silent features are:'.
					SEPERATOR .
					'1. The SMS Widget helps visitors of your website to send text messages to mobile phones for free.' .
					SEPERATOR .
					'2. No registrations is needed for your customers.' .
					SEPERATOR .
					'3. Easy to integrate with any PHP based website.' .
					SEPERATOR . SEPERATOR .
					'For more information visit http://www.freesmsapi.com/widgets' .
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;*/
		
		/*$subject = 'SMS WIDGET and IP LOCK updates';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We are delighted to inform you that we have undertaken certain precautionary measures as mandated by the '. 
					'regulatory authorities to ensure quality service. Kindly make a note of the following feature implemented '. 
					'for enhanced user experience.'.
					SEPERATOR . SEPERATOR .
					'NEW SMS WIDGET'. 
					SEPERATOR . SEPERATOR .
					'Our new SMS Widget helps check Abusive and Spam messages level and reports the same to Cyber Crime Cell. '.
					'We do this by making sender\'s mobile number compulsory and verify the same before relaying the actual sms. ' .
					'This unique feature helps us verify user\'s true identity and to stop Abusive and Spam messages.' .
					SEPERATOR .
					'For more information visit http://www.freesmsapi.com/widgets' .
					SEPERATOR . SEPERATOR . SEPERATOR .
					'UPDATED IP LOCK'.
					SEPERATOR . SEPERATOR .
					'Earlier we were checking your Primary Domain IP which help us verify your Genuine Identity. ' .
					'From now on, we will be accepting your Secondary Domain IP as well along with your Primary Domain IP. ' .
					'Your can add your Secondary Domain IP in "Change IP Address" under "My Account" section after logging in. ' .
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;*/

		$subject = 'Important Notice';
		$message = 'Dear Member,'.
					SEPERATOR . SEPERATOR .
					'Due to the new TRAI regulations W.E.F 27th Sept 2011, Freesmsapi will be barring the services of delivering the SMS to any mobile number registered under NDNC. The said modification has been enforced by the newly amended statute passed by TRAI. Although, if you happen to send any SMS to a mobile number which falls under NDNC registration your delivery report will show the status of \'DND\', allowing you to remove this mobile number from your subscription list.'.
					SEPERATOR . SEPERATOR .
					'Another regulation that has been mandated by TRAI is to terminate all the personalized Sender ID\'s, for that matter all the future SMS send via our service will be sent as "TD-XXXXXX" sender ID (where XXXXXX is the unique number that will be allotted by Telecom operator for Freesmsapi) effectively from 27th Sept 2011.'. 
					SEPERATOR . SEPERATOR .
					'Thank you for using Freesmsapi.com'.
					SEPERATOR . SEPERATOR .
					'Regards,'.
					SEPERATOR .
					'Freesmsapi Team';
					
		echo $email . PHP_EOL;
 		$this->sendMail($email, $subject, $message);
		
	}
	
	function addBlacklistDomain($domain) {

		if(!empty($domain)) {
	
			$data['name'] = $domain;
			$this->BlacklistDomain->save($data);

		}

	}

 	function testmail($filename=null) {

 		$message = 'Hello'.SEPERATOR.'World';
 		$toUser = 'dilzfiesta@gmail.com';
 		$subject = 'message of hope';
 		echo $this->sendMail($toUser, $subject, $message);

 		$message = 'Hello'.SEPERATOR.'World';
 		$toUser = 'mohtashim.shaikh@cognizant.com';
 		$subject = 'message of hope';
 		echo $this->sendMail($toUser, $subject, $message);

 		exit;
 		
     	//$this->Email->template = 'email/default';
 
 		if($filename != null) {
	 		$message = 'Please create the following Sender ID(s) into the account of gyrodev and gyrodevit.'.
						SEPERATOR.SEPERATOR.
						'1. '. $filename.
						SEPERATOR.SEPERATOR.
						'Do reply once activated.'.
						SEPERATOR.SEPERATOR.
						'--'.SEPERATOR.
						'Thank you,'.SEPERATOR.
						'Warm Regards,'.SEPERATOR.
						SMS_FOOTER;
 		} else $message = 'Test message';
 					
		$this->set('content_for_layout', $message);
		$toUser = 'mohtashim@freesmsapi.com';
		$this->Email->to = $toUser;
		$this->Email->subject = 'FUBAR';
		$this->Email->attach(SENDER_ID_PATH . DS . $filename . '.pdf');

		echo $result = $this->Email->send();
		exit;
	}
	
 }
?>
