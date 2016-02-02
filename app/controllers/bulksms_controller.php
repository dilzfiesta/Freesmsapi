<?php
 class BulksmsController extends AppController {
 	
 	var $uses = array('Feedback', 'BulkUserpersonalinfo', 'BulkUser', 'BulkGroup', 'BulkSmsSchedule',
 						'BulkSenderid', 'ValidNumber', 'BulkAddressbook', 'BulkSmsTag',
 						'BulkSmsLogDetail', 'BulkSmsLog', 'BulkFeedback', 'BulkAccountRegister',
 						'BulkAccount', 'BulkAccountRecharge', 'BulkSetting', 'Maillog', 'SmsVendor',
 						'BulkSmsCli', 'BulkSmsResponsePinnacle');

 	var $paginate = array(
 		'BulkAddressbook' => array(
			'limit' => 50,
 			'order' => 'BulkAddressbook.firstname ASC',
			'page' => 1
 		)
	);
 	
	function beforeFilter() {
		
		if($this->Session->check('validity') && !$this->checkBulkValidity($this->Session->read('validity'))) {
			$this->signout();
		}
		
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
 			Configure::write('debug', 0);
 		}
		
	}
	
	function index() {
		
		$this->redirect('/bulksms/login');
		
	}
	
 	function register($actky=null) {
 		
 		if(isset($this->data)) {
 			
 			$firstname = filter_var(trim($this->data['BulkUserpersonalinfo']['firstname']), FILTER_SANITIZE_STRING);
 			$lastname = filter_var(trim($this->data['BulkUserpersonalinfo']['lastname']), FILTER_SANITIZE_STRING);
 			$email = filter_var(trim($this->data['BulkUserpersonalinfo']['email']), FILTER_SANITIZE_EMAIL);
 			$mobile = filter_var(trim($this->data['BulkUserpersonalinfo']['mobile']), FILTER_SANITIZE_STRING);
 			$username = filter_var(trim($this->data['BulkUser']['username']), FILTER_SANITIZE_STRING);
 			$password = filter_var(trim($this->data['BulkUser']['password']), FILTER_SANITIZE_STRING);
 			
 			if(empty($firstname)) $error[] = 'Firstname is required';
 			
 			if(empty($lastname)) $error[] = 'Lastname is required';
 			
 			if(!$email) $error[] = 'Email Address is required';
	 		else if(!$this->checkIfEmail($email)) $error[] = 'Email Address is invalid';
 			
 			if(!is_numeric($mobile)) $error[] = 'Mobile number is invalid';
	 		else if(strlen($mobile) <> 10) $error[] = 'Mobile number should be of 10 digits';
	 		else if(!$this->checkNumber($mobile)) $error[] = 'Only Indian mobiles are allowed';
	
	 		if(empty($username)) $error[] = 'Username is required';
	 		else if(strlen($username) < 6) $error[] = 'Username should be greater than 6 chars';
	 		
	 		if(empty($password)) $error[] = 'Password is required';
	 		else if(strlen($password) < 8) $error[] = 'Password should be greater than 8 chars';
	 		
	 		if($this->BulkUser->find('count', array('conditions'=>array('username'=>$username))))
				$error[] = 'Username is already in use';

	 		$cond['BulkAccountRegister.activation_key'] = $actky;
 			$cond['BulkAccountRegister.status'] = '0';
 			$bulk_acc_reg = $this->BulkAccountRegister->findAll($cond);
 			if(count($bulk_acc_reg) == '0') $error[] = 'Invalid URL';
 			
 			if(!isset($error)) {

 				$value['firstname'] = ucfirst($firstname);
 				$value['lastname'] = ucfirst($lastname);
	 			$value['email'] = strtolower($email);
 				$value['mobile'] = $mobile;
 				
 				if($this->BulkUserpersonalinfo->save($value)) {
 					
 					unset($value);
 					$value['username'] = $username;
	 				$value['password'] = $password;
	 				$value['bulk_userpersonalinfo_id'] = $this->BulkUserpersonalinfo->getLastInsertId();
	 				$this->BulkUser->save($value);
	 				
	 				//create default TAG
	 				$v['name'] = 'General';
	 				$v['bulk_user_id'] = $this->BulkUser->getLastInsertId();
	 				$this->BulkSmsTag->save($v);
	 				
	 				//create default GROUP
	 				unset($v);
	 				$v['name'] = 'General';
	 				$v['narration'] = 'General Category'; 
	 				$v['bulk_user_id'] = $this->BulkUser->getLastInsertId();
	 				$this->BulkGroup->save($v);
	 				
	 				//insert first user entry
	 				unset($v);
	 				$v['firstname'] = ucfirst($firstname);
	 				$v['lastname'] = ucfirst($lastname);
	 				$v['mobile'] = $mobile;
	 				$v['bulk_group_id'] = $this->BulkGroup->getLastInsertId();
	 				$this->BulkAddressbook->save($v);
	 				
	 				//account info
	 				unset($value);
	 				$value['secret_key'] = md5(md5(md5(SALT.date('YmdHis')).SALT.date('YmdHis')).SALT);
	 				$value['quantity'] = $bulk_acc_reg['0']['BulkAccountRegister']['quantity'];
	 				$value['amount'] = $bulk_acc_reg['0']['BulkAccountRegister']['amount'];
	 				$value['bulk_user_id'] = $this->BulkUser->getLastInsertId();
	 				$value['server'] = $this->getMinBulkServer();
	 				$value['sms_vendor_id'] = $bulk_acc_reg['0']['BulkAccountRegister']['sms_vendor_id'];
	 				$this->BulkAccount->save($value);
	 				
	 				unset($value);
	 				$value['amount'] = $bulk_acc_reg['0']['BulkAccountRegister']['amount'];
	 				$value['costpersms'] = $bulk_acc_reg['0']['BulkAccountRegister']['costpersms'];
	 				$value['validtill'] = $bulk_acc_reg['0']['BulkAccountRegister']['validtill'];
	 				$value['quantity'] = $bulk_acc_reg['0']['BulkAccountRegister']['quantity'];
	 				$value['bulk_account_id'] = $this->BulkAccount->getLastInsertId();
	 				$this->BulkAccountRecharge->save($value);
	 				
	 				$this->BulkAccountRegister->id = $bulk_acc_reg['0']['BulkAccountRegister']['id'];
	 				$this->BulkAccountRegister->saveField('status', '1');
	 				
	 				//start session
	 				$this->Session->destroy();
		 			$this->Session->write('user_id', $this->BulkUser->getLastInsertId());
		 			$this->Session->write('user_name', ucfirst($firstname) .' '. ucfirst($lastname));
		 			$this->Session->write('user_type', 'bulksms');
		 			$this->Session->write('user_email', strtolower($email));
		 			$this->Session->write('validity', date('Y-m-d', strtotime($bulk_acc_reg['0']['BulkAccountRegister']['validtill'])));
					
	 				$this->redirect('/bulksms/view');
	 				exit;
	 				
 				}
		
	 		} else {
	 			
	 			$this->set('error', $error);
	 			$this->set('firstname', $firstname);
	 			$this->set('lastname', $lastname);
	 			$this->set('email', $email);
	 			$this->set('mobile', $mobile);
	 			$this->set('username', $username);
	 			
	 		}
 			
 		} else if(!empty($actky)) {
 			
 			$cond['BulkAccountRegister.activation_key'] = trim($actky);
 			$cond['BulkAccountRegister.status'] = '0';
 			if($this->BulkAccountRegister->find('count', array('conditions'=>$cond)) == '0') {
 				$this->redirect('/bulksms/login');
 				exit;
 			}
 			
 		} else {
 			
 			$this->redirect('/bulksms/login');
 			exit;
 			
 		}
 		
 		$this->layout = 'before_login';
 		$this->set('tab', array('4'));
		$this->getFeedback();
 		
 	}
 	
 	function view() {
 		
 		$this->checkAccess('bulksms');
 		
 		$cond['conditions']['BulkGroup.bulk_user_id'] = $this->Session->read('user_id');
 		$groups = $this->BulkGroup->find('list', $cond);
 		
 		/*unset($cond);
 		$cond['fields'] = array('BulkSmsLog.id', 'BulkSmsLog.sms_count');
 		$cond['conditions']['BulkSmsLog.bulk_group_id'] = array_keys($groups);
 		$sms_log = $this->BulkSmsLog->find('list', $cond);
 		
 		foreach($sms_log as $k => $v) {
 			$new_sms_log[$v][] = $k;
 		}
 		
 		$sms_log_detail_count = 0;
 		if(!empty($new_sms_log)) {
	 		foreach($new_sms_log as $k => $v) {
		 		unset($cond);
		 		$cond['conditions']['BulkSmsLogDetail.bulk_sms_log_id'] = $v;
		 		$cond['conditions']['BulkSmsLogDetail.response_status'] = 'DELIVERED';
		 		$this->BulkSmsLogDetail->create();
		 		$sms_log_detail[$k] = $this->BulkSmsLogDetail->find('count', $cond) * $k;
		 		$sms_log_detail_count = $sms_log_detail[$k] + $sms_log_detail_count;
	 		}
 		}
	 	$this->set('sms_log_detail_count', $sms_log_detail_count);*/
 		
 		//get account details
 		unset($cond);
 		$cond['BulkAccount.bulk_user_id'] = $this->Session->read('user_id');
 		$cond['BulkAccount.status'] = '0';
 		$bulk_account = $this->BulkAccount->findAll($cond);
 		
 		/*unset($cond);
 		$cond['BulkAccountRecharge.bulk_account_id'] = $bulk_account['0']['BulkAccount']['id'];
 		$cond['BulkAccountRecharge.status'] = '0';
 		$bulk_account_recharge = $this->BulkAccountRecharge->findAll($cond);*/
 		
 		//get user info
 		unset($cond);
 		$cond['BulkUser.id'] = $this->Session->read('user_id');
 		$cond['BulkUser.status'] = '0';
 		$bulk_user = $this->BulkUser->find('all', array('conditions'=>$cond));
		
 		$this->set('validity', $this->Session->read('validity'));
 		
 		//setting the variables
 		$this->set('bulk_user', $bulk_user['0']);
 		$this->set('bulk_account', $bulk_account['0']['BulkAccount']);
 		$this->set('bulk_account_recharge', $bulk_account['0']['BulkAccountRecharge']);
 		
 		$this->layout = 'bulksms';
 		$this->set('tab', array('1'));
		//$this->getFeedback();
		
		$this->set('t', $this);
 	}
 	
 	function groups($id=null) {
 		
 		$this->checkAccess('bulksms');
 		
 		$name = $narration = '';
 		
 		if(isset($this->data)) {
 			
 			$name = filter_var(trim($this->data['BulkGroup']['name']), FILTER_SANITIZE_STRING);
 			$narration = filter_var(trim($this->data['BulkGroup']['narration']), FILTER_SANITIZE_STRING);
 			$id = filter_var(trim($this->data['BulkGroup']['id']), FILTER_SANITIZE_STRING);
 			
 			if(empty($name)) $error = 'Group name is required';
 			
 			if(!isset($error)) {
 				
 				$value['id'] = (!empty($id)) ? $id : '';
 				$value['name'] = $name;
 				$value['narration'] = $narration;
 				$value['bulk_user_id'] = $this->Session->read('user_id');
 				$this->BulkGroup->save($value);
 				
 				$this->Session->write('success', 'Group name saved successfully');
 				$this->redirect('/bulksms/groups');
 				exit;
 				
 			} else {
 				
 				$this->set('error', $error);
 				$this->set('narration', $narration);
 				$this->set('name', $name);
 				
 			}
 			
 		}
 		
 		if($this->Session->check('success')) {
 			
 		 	$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 			
 		}
 		
 		//upon edit
 		if(!empty($id)) {
 			
 			$c['conditions']['BulkGroup.id'] = $id;
 			$c['conditions']['BulkGroup.status'] = '0';
 			$c['conditions']['BulkGroup.bulk_user_id'] = $this->Session->read('user_id');
 			$group = $this->BulkGroup->find('all', $c);
 			$name = $group['0']['BulkGroup']['name'];
 			$narration = $group['0']['BulkGroup']['narration'];
 			$this->set('group_id', $group['0']['BulkGroup']['id']);
 			
 		}
 		
 		$this->set('name', $name);
 		$this->set('narration', $narration);
 		
 		/*	Get all groups	*/
 		$cond['conditions']['BulkGroup.status'] = '0';
 		$cond['conditions']['BulkGroup.bulk_user_id'] = $this->Session->read('user_id');
 		$cond['order'] = 'BulkGroup.name ASC';
 		$data = $this->BulkGroup->find('all', $cond);
 		$this->set('data', $data);
 		
 		/*	Get Contact Count for each Group	*/
 		foreach($data as $v) {
 			$contacts[$v['BulkGroup']['id']] = $this->BulkAddressbook->find('count', array('conditions'=>array('BulkAddressbook.bulk_group_id'=>$v['BulkGroup']['id'], 'BulkAddressbook.status'=>'0')));
 			$grouplist[] = $v['BulkGroup']['id'];
 		}
 		$this->set('contacts', $contacts);
 		
 		/*	Get Scheduled SMS against each group if any	*/
 		unset($cond);
 		$cond['conditions']['BulkSmsSchedule.status'] = '0';
 		$cond['conditions']['BulkSmsSchedule.send'] = '0';
 		$cond['conditions']['BulkSmsSchedule.bulk_group_id'] = $grouplist;
 		$cond['fields'] = array('BulkSmsSchedule.bulk_group_id');
 		$schedulelist = $this->BulkSmsSchedule->find('list', $cond);
 		$this->set('schedulelist', $schedulelist);
 		
 		$this->layout = 'bulksms';
 		$this->set('tab', array('4'));
		//$this->getFeedback();
 		
 	}
 	
 	function myaccount() {
 		
 		$this->checkAccess('bulksms');
 		
 		// Sender ID
 		if(isset($this->data['BulkSenderid'])) {
 			
 			$name = filter_var(trim($this->data['BulkSenderid']['name']), FILTER_SANITIZE_STRING);
 			if(empty($name)) $error = 'Sender ID is required';
 			
 			if(empty($name)) $error = 'Sender ID is required';
 			else if(strlen($name) > 8) $error = 'Sender ID should be less than or equal to 8 chars';
 			
 			if(!isset($error)) {
 				
 				$value['name'] = $name;
 				$value['bulk_user_id'] = $this->Session->read('user_id');
 				
 				/*	Notify Admin	*/
				$to_admins['type'] = 'Bulk Alias';
				$to_admins['data'] = $value;
				$this->notifyAdmin($to_admins);
				
				/*	Save Data	*/
 				$this->BulkSenderid->save($value);
 				
 				$this->Session->write('success', 'Sender ID is send for activation. Please note it will take an hour to get activated');
 				$this->redirect('/bulksms/myaccount');
 				exit;
 				
 			} else {
 				
 				$this->set('error', $error);
 				
 			}
 			
 		} else if(isset($this->data['BulkSmsTag'])) {
 			
 			$name = filter_var(trim($this->data['BulkSmsTag']['name']), FILTER_SANITIZE_STRING);
 			if(empty($name)) $error = 'Tag name is required';
 			
 			if(!isset($error)) {
 				
 				$value['name'] = $name;
 				$value['bulk_user_id'] = $this->Session->read('user_id');
 				$this->BulkSmsTag->save($value);
 				
 				$this->Session->write('tag_success', 'Tag saved successfully');
 				$this->redirect('/bulksms/myaccount');
 				exit;
 				
 			} else {
 				
 				$this->set('tag_error', $error);
 				
 			}
 			
 		} else if(isset($this->data['BulkUser'])) {
				
			$condition['conditions']['password'] = $this->data['BulkUser']['password'];
			$condition['conditions']['id'] = $this->Session->read('user_id');
			$this->BulkUser->unBindAll();
			 
			if(!$this->BulkUser->find('count', $condition))
				$error[] = 'Old password does not match';

			if(strlen($this->data['BulkUser']['new_password']) < 8)
				$error[] = 'New password should be greater or equal to 8 characters';
			
			if($this->data['BulkUser']['new_password'] != $this->data['BulkUser']['retype_password'])
				$error[] = 'New password and retyped password did not match';

			if(!isset($error)) {
					
				$this->BulkUser->id = $this->Session->read('user_id');
				$this->BulkUser->saveField('password', $this->data['BulkUser']['new_password']);
				$this->Session->write('success', 'Password changed successfully');
				$this->redirect('/bulksms/myaccount');
				exit;

			} else {
					
				$this->set('error', $error);
					
			}
 		}
 		
 		if($this->Session->check('success')) {
 			
 		 	$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 			
 		} else if($this->Session->check('tag_success')) {
 			
 		 	$this->set('tag_success', $this->Session->read('tag_success'));
 			$this->Session->delete('tag_success');
 			
 		}
 		
 		//get all sender ID
 		$cond['conditions']['status'] = '0';
 		$cond['conditions']['bulk_user_id'] = $this->Session->read('user_id');
 		$data = $this->BulkSenderid->find('all', $cond);
 		$this->set('data', $data);
 		
 		//get all Tag
 		$cond['conditions']['status'] = '0';
 		$cond['conditions']['bulk_user_id'] = $this->Session->read('user_id');
 		$data = $this->BulkSmsTag->find('all', $cond);
 		$this->set('tagdata', $data);
 		
 		$this->layout = 'bulksms';
 		$this->set('tab', array('5'));
		//$this->getFeedback();
 		
 	}
 	
 	function profile() {
 		
 		$this->checkAccess('bulksms');
 		
 		$this->BulkUser->id = $this->Session->read('user_id');
 		$bulk_userpersonalinfo_id = $this->BulkUser->field('bulk_userpersonalinfo_id');
 				
 		$cond['conditions']['BulkUserpersonalinfo.id'] = $bulk_userpersonalinfo_id;
 		$data = $this->BulkUserpersonalinfo->find('all', $cond);
 		$data = $data['0']['BulkUserpersonalinfo'];
 		
 		if(isset($this->data)) {
 			
 			$firstname = filter_var(trim($this->data['BulkUserpersonalinfo']['firstname']), FILTER_SANITIZE_STRING);
 			$lastname = filter_var(trim($this->data['BulkUserpersonalinfo']['lastname']), FILTER_SANITIZE_STRING);
 			$email = filter_var(trim($this->data['BulkUserpersonalinfo']['email']), FILTER_SANITIZE_EMAIL);
 			$mobile = filter_var(trim($this->data['BulkUserpersonalinfo']['mobile']), FILTER_SANITIZE_NUMBER_INT);

 			if(empty($firstname)) $error[] = 'Firstname is required';
 			if(empty($lastname)) $error[] = 'Lastname is required';
 			if(empty($email)) $error[] = 'Email is required';
 			else if(!$this->checkIfEmail($email)) $error[] = 'Invalid Email Address';
 			if(empty($mobile)) $error[] = 'Mobile Number is required';
 			else if(strlen($mobile) <> 10) $error[] = 'Invalid Mobile Number';
 			else if(!$this->checkNumber($mobile)) $error[] = 'Invalid Mobile Number';
 			
 			if(!isset($error)) {
 				
 				$s['firstname'] = $firstname;
 				$s['lastname'] = $lastname;
 				$s['email'] = $email;
 				$s['mobile'] = $mobile;
 				$this->BulkUserpersonalinfo->id = $bulk_userpersonalinfo_id;
 				$this->BulkUserpersonalinfo->save($s);
 				
 				$this->Session->write('user_name', ucfirst($firstname).' '.ucfirst($lastname));
 				$this->Session->write('user_email', $email);
 				
 				$this->Session->write('success', 'Profile details changed successfuly');
				$this->redirect('/bulksms/profile');
				exit;
				
 			} else {
 				
 				$this->set('error', $error);
 				
		 		$data['firstname'] = $firstname;
		 		$data['lastname'] = $lastname;
		 		$data['email'] = $email;
		 		$data['mobile'] = $mobile;
 		
 			}
 		}
 		
 		if($this->Session->check('success')) {
 			
 		 	$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 			
 		}
 		
 		$this->set('data', $data);
 		$this->layout = 'bulksms';
 		$this->set('tab', array('5'));
 	
 	}
 	
 	function setting() {
 		
 		$this->checkAccess('bulksms');
 		
 		if(isset($this->data)) {
 			
 			if(isset($this->data['BulkSetting']['daily_report'])) $daily_report = 1;
 			else $daily_report = 0;
			 			
 			$this->BulkSetting->updateAll(
 				array('BulkSetting.status' => '1'),
 				array('BulkSetting.bulk_user_id' => $this->Session->read('user_id'))
 			);
 			
 			$d['daily_report'] = $daily_report;
 			$d['bulk_user_id'] = $this->Session->read('user_id');
 			$this->BulkSetting->save($d);
 			
 			$this->Session->write('success', 'Settings changed successfully');
 			$this->redirect('/bulksms/setting');
 			exit;
 			
 		}
 		
 		$cond['conditions']['BulkSetting.status'] = '0';
 		$cond['conditions']['BulkSetting.bulk_user_id'] = $this->Session->read('user_id');
 		$data = $this->BulkSetting->find('all', $cond);
 		
 		if($this->Session->check('success')) {
 			
 		 	$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 			
 		}
 		
 		$this->set('data', $data);
 		$this->layout = 'bulksms';
 		$this->set('tab', array('5'));
 		
 	}
 	
 	function addressbook($mode=null, $group_id=null, $id=null) {
 		
 		$this->checkAccess('bulksms');
 		
 		$this->layout = 'bulksms';
	 	$this->set('tab', array('4'));
		//$this->getFeedback();
		
		// Check if group ID belongs to current user
		if(!empty($group_id) && !$this->checkBulkGroupId($group_id)) $group_id=null;
		
		// Get all groups
		$cond['conditions']['BulkGroup.status'] = '0';
	 	$cond['conditions']['BulkGroup.bulk_user_id'] = $this->Session->read('user_id');
	 	$cond['order'] = 'BulkGroup.name ASC';
	 	$group = $this->BulkGroup->find('list', $cond);
	 	$groupdata = $bulk_group_id = $groupname = '';
	 	
	 	// Create group dropdown
	 	if($mode != 'add') $groupdata = '<option value="0">All Groups</option>';
	 	foreach($group as $key => $value) {
	 		//$firstgroup = ($firstgroup=='')?$key:$firstgroup;
	 		$groupname = ($group_id==$key)?$value:$groupname;
	 		$selected = ($group_id==$key)?'selected':'';
	 		$groupdata .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
	 	}
	 	$this->set('groupdata', $groupdata);
	 	$this->set('groupname', $groupname);
		
 		if($this->Session->check('success')) {
 			
 		 	$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 			
 		}
 		
 		switch($mode) {
 			
 			case 'add' :	$firstname = $lastname = $mobile = '';
 							
				 			// Check if group ID belongs to current user
				 			if(!empty($this->data['BulkAddressbook']['bulk_group_id']) && !$this->checkBulkGroupId($this->data['BulkAddressbook']['bulk_group_id']))
								$error[] = 'Invalid Group Selected';

							// Add contacts using XSL or CSV
 							if(!empty($this->data['file']['name'])) {
 								
 								$allowed_type = array('csv', 'xls');
					 			$file_ext = strtolower(end(explode('.', $this->data['file']['name'])));
					 				
					 			if($this->data['file']['error'] <> '4') {
					 				
					 				if(!isset($error) && $this->data['file']['error'] == '0') {
						 				
							 			if(in_array($file_ext, $allowed_type)) {
							 				
							 				if($file_ext == 'csv') {
								 				$data = file_get_contents($this->data['file']['tmp_name']);
								 				$data = $this->csv_to_array($data);
								 				
								 				$a = 0;
								 				foreach($data as $v) {
								 					if(is_array($v)) {
								 						
								 						// If only numbers are in csv
								 						if(is_numeric($v['0'])) {
								 							if($this->checkNumber($v['0'])) {
																$numbers[$a]['mobile'] = $v['0'];
																$numbers[$a]['bulk_group_id'] = $this->data['BulkAddressbook']['bulk_group_id'];
																$a++;
															} else {
																$invalid[] = $v;
															}
								 						} else {
								 							if($this->checkNumber($v['2'])) {
																$numbers[$a]['firstname'] = $v['0'];
																$numbers[$a]['lastname'] = $v['1'];
																$numbers[$a]['mobile'] = $v['2'];
																$numbers[$a]['bulk_group_id'] = $this->data['BulkAddressbook']['bulk_group_id'];
																$a++;
															} else {
																$invalid[] = $v;
															}
								 						}
													}
								 				}
							 				}
							 				
							 				if($file_ext == 'xls') {
							 					App::import('vendor', 'Spreadsheet_Excel_Reader', array('file' => 'Excel/reader.php'));
							 					$data = new Spreadsheet_Excel_Reader();
							 					$data->setOutputEncoding('CP1251');
												$data->read($this->data['file']['tmp_name']);
												
												$a = 0;

												foreach($data->sheets as $k => $v) {
													if($v['numRows'] <> '0' && $v['numCols'] == '3' && count($data->sheets[$k]['cells']) > 0) $content[$k] = $v['numRows'];
													else if($v['numRows'] <> '0' && $v['numCols'] == '1' && count($data->sheets[$k]['cells']) > 0) $content[$k] = $v['numRows']; 
												}
												//pr($content);
												//pr($data->sheets);
												//pr($data->sheets[$this->min_key($content)]['cells']);exit;
												foreach($data->sheets[$this->min_key($content)]['cells'] as $k => $v) {
													if(is_array($v)) {
														
														// Mobile "" ""
														if(isset($v['1']) && !isset($v['2']) && !isset($v['3']) && is_numeric($v['1'])) {
														if($this->checkNumber($v['1'])) {
																$numbers[$a]['mobile'] = $v['1'];
																$numbers[$a]['bulk_group_id'] = $this->data['BulkAddressbook']['bulk_group_id'];
																$a++;
															} else {
																$invalid[] = $v;
															}
															
														// Firstname Lastname Mobile
														} else if(isset($v['1']) && isset($v['2']) && isset($v['3']) && is_numeric($v['3'])) {
															if($this->checkNumber($v['3'])) {
																$numbers[$a]['firstname'] = $v['1'];
																$numbers[$a]['lastname'] = $v['2'];
																$numbers[$a]['mobile'] = $v['3'];
																$numbers[$a]['bulk_group_id'] = $this->data['BulkAddressbook']['bulk_group_id'];
																$a++;
															} else {
																$invalid[] = $v;
															}
															
														// Firstname "" Mobile	
														} else if(isset($v['1']) && !isset($v['2']) && isset($v['3']) && is_numeric($v['3'])) {
															if($this->checkNumber($v['3'])) {
																$numbers[$a]['firstname'] = $v['1'];
																$numbers[$a]['lastname'] = '';
																$numbers[$a]['mobile'] = $v['3'];
																$numbers[$a]['bulk_group_id'] = $this->data['BulkAddressbook']['bulk_group_id'];
																$a++;
															} else {
																$invalid[] = $v;
															}
														} else {
															$invalid_contact[] = $v;
														}
													}
												}
												//pr($numbers);pr($invalid);pr($invalid_contact);exit;
							 				}
							 				
							 			} else $error = 'Invalid file extension';
						 			
					 				} else $error = 'There was an error while uploading the file, please try again';
					 				
					 				if(!isset($error)) {
						 			
					 					if(isset($numbers) && !empty($numbers)) {
					 						$this->BulkAddressbook->saveAll($numbers);
					 						$this->Session->write('success', count($numbers) . ' Contact(s) Add Successfully');
					 					}
					 					
					 					if(isset($invalid_contact)) {
						 					$error[] = 'Invalid Contact(s), Please refer to example file for more details (<a href="'.SERVER.'example/bulk_excel_example.xls" target="_blank">Excel</a>, <a href="http://'.SERVER.'example/bulk_csv_example.xls" target="_blank">CSV</a>)';
						 					foreach($invalid_contact as $v) $error[] = implode(' ', $v);
					 					}
					 					
					 					if(isset($invalid)) {
						 					$error[] = 'Invalid Mobile Number(s)';
						 					foreach($invalid as $v) $error[] = implode(' ', $v);
					 					}
					 					
					 					if(isset($error)) $this->Session->write('error', $error);
					 					
					 					$this->redirect('/bulksms/addressbook/add');
					 					exit;

					 				} else $this->set('error', $error);
					 				
					 			}
						 			
 							} else if(isset($this->data['BulkAddressbook'])) {
 								
 								$firstname = filter_var(trim($this->data['BulkAddressbook']['firstname']), FILTER_SANITIZE_STRING);
 								$lastname = filter_var(trim($this->data['BulkAddressbook']['lastname']), FILTER_SANITIZE_STRING);
 								$mobile = filter_var(trim($this->data['BulkAddressbook']['mobile']), FILTER_SANITIZE_STRING);
 								
 								//if(empty($firstname)) $error[] = 'Firstname is required';
 								//if(empty($lastname)) $error[] = 'Lastname is required';
 								if(empty($mobile)) $error[] = 'Mobile is required';
 								else if(!is_numeric($mobile)) $error[] = 'Mobile number is invalid';
						 		else if(strlen($mobile) <> 10) $error[] = 'Mobile number should be of 10 digits';
						 		else if(!$this->checkNumber($mobile)) $error[] = 'Only Indian mobiles are allowed';
						 		
						 		if(!isset($error)) {
						 			
						 			$this->BulkAddressbook->save($this->data['BulkAddressbook']);
						 			$this->Session->write('success', 'Contact added successfully');
 									$this->redirect('/bulksms/addressbook/add');
 									exit;
						 			
						 		} else $this->set('error', $error);

 							} else {
 								
 								if($this->Session->check('success')) {
 									$this->set('success', $this->Session->read('success'));
 									$this->Session->delete('success');
 								}
 								
 								if($this->Session->check('error')) {
 									$this->set('error', $this->Session->read('error'));
 									$this->Session->delete('error');
 								}
 								
 							}
 			
						 	$this->set('firstname', $firstname);
						 	$this->set('lastname', $lastname);
						 	$this->set('mobile', $mobile);
						 	
						 	$this->render('/bulksms/addressbook_add');
							break;

 			case null :
 			case 'view'  : 	/*if(isset($this->data)) {
 								unset($cond);
 								$cond['conditions']['bulk_group_id'] = $this->data['BulkAddressbook']['bulk_group_id'];
 								$cond['conditions']['status'] = '0';
 								$data = $this->paginate('BulkAddressbook', $cond['conditions']);
 								$this->set('data', $data);

 							} else {*/
 				
 								$s_group = '<option value="0">Move Contact To</option>';
 								foreach($group as $key => $value) {
							 		$s_group .= '<option value="'.$key.'">'.$value.'</option>';
							 	}
							 	$this->set('s_group', $s_group);
							 	
							 	$c_group = '<option value="0">Copy Contact To</option>';
 								foreach($group as $key => $value) {
							 		$c_group .= '<option value="'.$key.'">'.$value.'</option>';
							 	}
							 	$this->set('c_group', $c_group);
	 	
							 	unset($cond);
							 	if(isset($this->data)) {
							 		if(!empty($this->data['firstname']) && $this->data['firstname'] != 'firstname') {
							 			$cond['conditions']['BulkAddressbook.firstname LIKE'] = '%' . $this->data['firstname'] . '%';
							 			$this->set('firstname', $this->data['firstname']);
							 		}
							 		if(!empty($this->data['lastname']) && $this->data['lastname'] != 'lastname') {
							 			$cond['conditions']['BulkAddressbook.lastname LIKE'] = '%' . $this->data['lastname'] . '%';
							 			$this->set('lastname', $this->data['lastname']);
							 		}
							 		if(!empty($this->data['mobile']) && $this->data['mobile'] != 'mobile') {
							 			if(strpos($this->data['mobile'], ',') !== false) {
							 				$mobile_tmp = explode(',', $this->data['mobile']);
							 				$mobile_tmp = array_filter($mobile_tmp);
							 				if(count($mobile_tmp) > 1) $cond['conditions']['BulkAddressbook.mobile'] = $mobile_tmp;
							 				else $cond['conditions']['BulkAddressbook.mobile LIKE'] = '%' . $mobile_tmp['0'] . '%'; 
							 			} else $cond['conditions']['BulkAddressbook.mobile LIKE'] = '%' . $this->data['mobile'] . '%';
							 			$this->set('mobile', $this->data['mobile']); 
							 		}
							 	}
 								
 								$group_id = ($group_id) ? $group_id : array_keys($group);
 								$cond['conditions']['BulkAddressbook.bulk_group_id'] = $group_id;
 								$cond['conditions']['BulkAddressbook.status'] = '0';
 								$data = $this->paginate('BulkAddressbook', $cond['conditions']);
 								$this->set('data', $data);
 								//$this->set('group', $group);

 							//}
 							break;
 							
 			case 'edit'	:	if(isset($_POST)) {
 								$id = filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING);
 								$firstname = filter_var(trim($_POST['firstname']), FILTER_SANITIZE_STRING);
 								$lastname = filter_var(trim($_POST['lastname']), FILTER_SANITIZE_STRING);
 								$mobile = filter_var(trim($_POST['mobile']), FILTER_SANITIZE_STRING);
 								
 								// Check if this id belongs to this user id
 								if(!$this->BulkAddressbook->find('count', array('conditions'=>array('BulkAddressbook.id'=>$id, 'BulkAddressbook.bulk_group_id'=>array_keys($group)))))
 									$error[] = 'Invalid Contact';
 								
 								if(empty($id)) $error[] = 'Invalid Contact ID';
 								//if(empty($firstname)) $error[] = 'Firstname is required';
 								//if(empty($lastname)) $error[] = 'Lastname is required';
 								if(empty($mobile)) $error[] = 'Mobile is required';
 								else if(!is_numeric($mobile)) $error[] = 'Mobile number is invalid';
						 		else if(strlen($mobile) <> 10) $error[] = 'Mobile number should be of 10 digits';
						 		else if(!$this->checkNumber($mobile)) $error[] = 'Only Indian mobiles are allowed';
 								
 								$val['id'] = $id;
 								$val['firstname'] = $firstname;
 								$val['lastname'] = $lastname;
 								$val['mobile'] = $mobile;
 								
 								if(!isset($error)) {
 									
 									// get the contact
 									$this->BulkAddressbook->unBindAll();
 									$tmp = $this->BulkAddressbook->findAll(array('BulkAddressbook.id'=>$id));
 									
 									// update this contact in every group
 									$this->BulkAddressbook->unBindAll();
 									$this->BulkAddressbook->updateAll(
 										array('BulkAddressbook.firstname'=>"'".$firstname."'", 'BulkAddressbook.lastname'=>"'".$lastname."'", 'BulkAddressbook.mobile'=>"'".$mobile."'"),
 										array('BulkAddressbook.firstname'=>$tmp['0']['BulkAddressbook']['firstname'], 'BulkAddressbook.lastname'=>$tmp['0']['BulkAddressbook']['lastname'], 'BulkAddressbook.mobile'=>$tmp['0']['BulkAddressbook']['mobile'], 'BulkAddressbook.bulk_group_id'=>array_keys($group))
 									);
 									
 									// save the contact
 									$this->BulkAddressbook->save($val);
 									
 								} echo json_encode(implode('\r\n', $error));
 								
 								$this->autoRender = false;
 							}
 							break;
 							
 			case 'changegroup'	:	if(isset($_POST)) {
 										$id = json_decode(stripslashes($_POST['id']), true);
 										$group_id = filter_var(trim($_POST['group_id']), FILTER_SANITIZE_STRING);
 										
 										// Check if group ID belongs to current user
										if(!empty($group_id) && !$this->checkBulkGroupId($group_id)) return false;
 										
										$this->BulkAddressbook->updateAll(
											array('BulkAddressbook.bulk_group_id' => $group_id),
											array('BulkAddressbook.id' => $id)
										);
										
 										$s = count($id) . ' Contact(s) Moved Successfully';
 										echo $s;
 										$this->autoRender = false;
 									}
 									break;
 									
			case 'copygroup'	:	if(isset($_POST)) {
 										$id = json_decode(stripslashes($_POST['id']), true);
 										$group_id = filter_var(trim($_POST['group_id']), FILTER_SANITIZE_STRING);
 										
 										// Check if group ID belongs to current user
										if(!empty($group_id) && !$this->checkBulkGroupId($group_id)) return false;
 										
										$c['conditions']['BulkAddressbook.status'] = '0';
										$c['conditions']['BulkAddressbook.id'] = $id;
										$data = $this->BulkAddressbook->find('all', $c);
										
										for($i=0; $i<count($data); $i++) {
											$data[$i]['BulkAddressbook']['id'] = NULL;
											$data[$i]['BulkAddressbook']['bulk_group_id'] = $group_id;
										}
										
										$this->BulkAddressbook->saveAll($data);
										
 										$s = count($id) . ' Contact(s) Copied Successfully';
 										echo $s;
 										$this->autoRender = false;
 									}
 									break;

			case 'deletecontact'	:	if(isset($_POST)) {
	 										$id = json_decode(stripslashes($_POST['id']), true);
	 										$group_id = filter_var(trim($_POST['group_id']), FILTER_SANITIZE_STRING);
	 										
	 										// Check if contacts belongs to current user
	 										unset($c);
											$c['conditions']['BulkAddressbook.bulk_group_id'] = array_keys($group);
	 										$c['conditions']['BulkAddressbook.id'] = $id;
	 										$c['conditions']['BulkAddressbook.status'] = '0';
	 										$id = $this->BulkAddressbook->find('list', $c);
											
	 										$this->BulkAddressbook->updateAll(
	 											array('BulkAddressbook.status'=>'1'),
	 											array('BulkAddressbook.bulk_group_id'=>array_keys($group), 'BulkAddressbook.id'=>$id)
	 										);
											
	 										$s = count($id) . ' Contact(s) Deleted Successfully';
	 										echo $s;
	 										$this->autoRender = false;
	 									}
	 									break;

 			default :	return false;
 			
 		}
 		
 		
 		
 	}
 	
 	/*	Get contacts from group to show in sendnow page	*/
 	function getcontacts() {
 		
 		$this->checkAccess('bulksms');
 		
 		$group_id = filter_var(trim($_POST['group_id']), FILTER_SANITIZE_STRING);
 		$c['conditions']['BulkAddressbook.status'] = '0';
		$c['conditions']['BulkAddressbook.bulk_group_id'] = $group_id;
		$c['fields'] = array('BulkAddressbook.id', 'BulkAddressbook.mobile', 'CONCAT(BulkAddressbook.firstname, \' \', BulkAddressbook.lastname) as full_name');
		$data = $this->BulkAddressbook->find('all', $c);
 		foreach($data as $v) {
 			$return_data[$v['BulkAddressbook']['id']]['name'] = (empty($v['0']['full_name'])) ? 'no name' : $v['0']['full_name'];
 			$return_data[$v['BulkAddressbook']['id']]['mobile'] = $v['BulkAddressbook']['mobile'];
 		}
 		echo json_encode($return_data);
 		
 		$this->autoRender = false;
 	}
 	
 	function delete($what, $id) {
 		
 		$this->checkAccess('bulksms');
 		
 		if(empty($what) || empty($id)) return false;
 		
 		switch($what) {
 			
	 		case 'groups' :		$this->_deleteGroup($id);
	 							break; 

 			case 'senderid' :	$this->_deleteSenderid($id);
 								break;  

 			case 'addressbook' :	$this->_deleteAddressbook($id);
 									break;
 							
 			case 'tag'	:		$this->_deleteTag($id);
 								break;

 			case 'schedule'	:	$this->_deleteSchedule($id);
 								break;
 							
 			default  :	return false;
 			
 		}
 		
 	}
 	
 	function _deleteGroup($id) {
 		
 		$cond['bulk_user_id'] = $this->Session->read('user_id');
 		$cond['id'] = $id;
 		$this->BulkGroup->updateAll( array('BulkGroup.status' => '1'), $cond );
 		
 		/*	Delete all schedules related to this group	*/
 		$this->BulkSmsSchedule->unBindAll();
 		$this->BulkSmsSchedule->updateAll( array('BulkSmsSchedule.status' => '1'), array('BulkSmsSchedule.bulk_group_id' => $id) );
 		
 		$this->Session->write('success', 'Group deleted successfully');
 		$this->redirect('/bulksms/groups');
 		exit;
 		
 	}
 	
 	function _deleteSenderid($id) {
 	
 		$cond['bulk_user_id'] = $this->Session->read('user_id');
 		$cond['id'] = $id;
 		$this->BulkSenderid->updateAll( array('BulkSenderid.status' => '1'), $cond );
 		$this->Session->write('success', 'Sender ID deleted successfully');
 		$this->redirect('/bulksms/myaccount');
 		exit;
 	
 	}
 	
 	function _deleteAddressbook($id) {
 	
 		$this->BulkAddressbook->id = $id;
 		$group_id = $this->BulkAddressbook->field('bulk_group_id');
 		$cond['bulk_user_id'] = $this->Session->read('user_id');
 		$cond['id'] = $group_id;
 		if($this->BulkGroup->find('count', array('conditions' => $cond))) {
 			$this->BulkAddressbook->updateAll( array('BulkAddressbook.status' => '1'), array('BulkAddressbook.id' => $id) );
 			$this->Session->write('success', 'Contact deleted successfully');
 		}
 		$this->redirect($this->referer());
 		exit;
 		
 	}
 	
 	function _deleteTag($id) {
 		
 		$cond['bulk_user_id'] = $this->Session->read('user_id');
 		$cond['id'] = $id;
 		$this->BulkSmsTag->updateAll( array('BulkSmsTag.status' => '1'), $cond );
 		$this->Session->write('tag_success', 'Tag deleted successfully');
 		$this->redirect('/bulksms/myaccount');
 		exit;
 		
 	}
 	
 	function _deleteSchedule($id) {
 		
 		$this->BulkSmsSchedule->id = $id;
 		$group_id = $this->BulkSmsSchedule->field('bulk_group_id');
 		$cond['bulk_user_id'] = $this->Session->read('user_id');
 		$cond['id'] = $group_id;
 		if(empty($group_id) || $this->BulkGroup->find('count', array('conditions' => $cond))) {
 			$this->BulkSmsSchedule->unBindAll();
 			$this->BulkSmsSchedule->updateAll( array('BulkSmsSchedule.status' => '1'), array('BulkSmsSchedule.id' => $id) );
 			$this->Session->write('success', 'Scheduled SMS deleted successfully');
 		}
 		$this->redirect('/bulksms/schedulereport');
 		exit;
 		
 	}
 	
 	function login() {
 		
 		$this->redirect('/users/login');
 		
 		/*if(isset($this->data)) {
 			
 			if(empty($this->data['BulkUser']['username']) || empty($this->data['BulkUser']['password'])) {
 				$error = 'Invalid Username and Password';
 			} else {
 				
				$data = $this->BulkUser->find('all', array('conditions' => $this->data['BulkUser']));
				if(count($data) > 0) {
					
					$this->Session->destroy();
		 			$this->Session->write('user_id', $data['0']['BulkUser']['id']);
		 			$this->Session->write('user_name', ucfirst($data['0']['BulkUserpersonalinfo']['firstname']) .' '. ucfirst($data['0']['BulkUserpersonalinfo']['lastname']));
		 			$this->Session->write('user_type', 'bulksms');
		 			$this->Session->write('user_email', strtolower($data['0']['BulkUserpersonalinfo']['email']));
		 			$this->redirect('/bulksms/view');
		 			exit;
		 			
				} else $error = 'Invalid Username and Password'; 				
 			}
 			
 			$this->set('error', $error);
 			
 		}
 		
 		$this->layout = 'before_login';
 		$this->set('tab', array('4'));
		$this->getFeedback();*/
 		
 	}
 	
 	function api_response() {
		
 		$this->checkAccess('bulksms');
 		
		$this->layout = 'bulksms';
		$this->set('tab', array('6'));
		
	}
	
	function api_example() {
		
		$this->checkAccess('bulksms');
		
		$c['BulkAccount.bulk_user_id'] = $this->Session->read('user_id');
 		$c['BulkAccount.status'] = '0';
 		$this->set('secret_key', $this->BulkAccount->field('secret_key', $c));
 		$this->set('server', 'http://s1.freesmsapi.com');
 		
		$this->layout = 'bulksms';
		$this->set('tab', array('6'));
		
	}
	
	function api_balance_check() {
		
		$this->checkAccess('bulksms');
		
		$c['BulkAccount.bulk_user_id'] = $this->Session->read('user_id');
 		$c['BulkAccount.status'] = '0';
 		$this->set('secret_key', $this->BulkAccount->field('secret_key', $c));
 		$this->set('server', 'http://s1.freesmsapi.com');
 		
		$this->layout = 'bulksms';
		$this->set('tab', array('6'));
		
	}
	
 	function api_schedule_sms() {
		
 		$this->checkAccess('bulksms');
 		
 		$c['BulkAccount.bulk_user_id'] = $this->Session->read('user_id');
 		$c['BulkAccount.status'] = '0';
 		$this->set('secret_key', $this->BulkAccount->field('secret_key', $c));
 		$this->set('server', 'http://s1.freesmsapi.com');
 		
		$this->layout = 'bulksms';
		$this->set('tab', array('6'));
		
	}
	
	function api_schedule_sms_response() {
		
		$this->checkAccess('bulksms');
 		
		$this->layout = 'bulksms';
		$this->set('tab', array('6'));
		
	}
	
 	function api_check_delivery() {
 		
 		$this->checkAccess('bulksms');
		
 		$skey = $this->_getBulkSecretKey();
 		
		$this->set('url', 'http://s1.freesmsapi.com/bulksms/response?skey='.$skey.'&key=RESPONSE_KEY');
		
		$this->set('skey', $skey);
		$this->layout = 'bulksms';
		$this->set('tab', array('6'));
 		
 	}
 	
 	function help() {
 		
 		$this->checkAccess('bulksms');
 		
 		$c['BulkAccount.bulk_user_id'] = $this->Session->read('user_id');
 		$c['BulkAccount.status'] = '0';
 		$this->set('secret_key', $this->BulkAccount->field('secret_key', $c));
 		$this->set('server', 'http://s1.freesmsapi.com');
 		
 		$this->layout = 'bulksms';
 		$this->set('tab', array('6'));
		//$this->getFeedback();
 		
 	}
 	
 	function balance() {
 		
 		if(isset($_REQUEST['skey']) && !empty($_REQUEST['skey'])) {
 			
	 		$secret_key = filter_var(trim($_REQUEST['skey']), FILTER_SANITIZE_STRING);
	 		
	 		if(isset($_REQUEST['response'])) $response_format = trim($_REQUEST['response']);
		 	else $response_format = 'xml';
		 	
	 		$rs = $this->checkBulkSecretKey($secret_key);
	 		$user_id = $rs['user_id'];
	 		
	 		if(!$user_id) {
	 			$this->_throwError('<error><message>Invalid Secret Key</message></error>', $response_format);
	 		} else {
 				
		 		$c['conditions']['BulkAccount.bulk_user_id'] = $user_id;
		 		$c['conditions']['BulkAccount.status'] = '0';
		 		$c['fields'] = array('BulkAccount.id', 'BulkAccount.amount', 'BulkAccount.quantity');
		 		$data = $this->BulkAccount->find('all', $c);
		 		
		 		/*unset($c);
		 		$c['conditions']['BulkAccountRecharge.bulk_account_id'] = $data['0']['BulkAccount']['id'];
		 		$c['conditions']['BulkAccountRecharge.status'] = '0';
		 		$c['order'] = 'BulkAccountRecharge.id DESC';
		 		$c['limit'] = '1';
		 		$c['fields'] = array('BulkAccountRecharge.validtill');
	 			$validity = $this->BulkAccountRecharge->find('all', $c);
		 		$validity = $validity['0']['BulkAccountRecharge']['validtill'];*/
		 		
		 		$validity = $data['0']['BulkAccountRecharge'][count($data['0']['BulkAccountRecharge'])-1]['validtill'];
		 		
		 		$output = '<success><amount>'.$this->format_money($data['0']['BulkAccount']['amount'], true).'</amount><quantity>'.$this->format_money($data['0']['BulkAccount']['quantity']).'</quantity><validtill>'.$validity.'</validtill></success>';
		 		$this->_throwError($output, $response_format);
	 		}
	 		
 		} else {
 			
 			$output = '<error><message>Invalid Secret Key</message></error>';
 			$this->_throwError($output, $response_format);
 			
 		}
 		
 		exit;
 	}
 	
 	/*	Schedule SMS from URL Request	*/
 	function schedulesms() {
 		
 		Configure::write('debug', 0);
 		
 		$this->send(true, $_REQUEST['message'], $_REQUEST['mobile'], $_REQUEST['skey'], $_REQUEST['senderid'], 
 							$_REQUEST['group'], $_REQUEST['tag'], $_REQUEST['date'], $_REQUEST['response']);
 		
 	}
 	
 	/*	Send SMS from URL Request	*/
 	function send($schedulesms=false, $message=false, $number=false, $secret_key=false, $senderid=false, $group=false, $tag=false, $date=false, $response_format=false) {
 		
 		set_time_limit(0);
 		ini_set('memory_limit', '256M');
 		
 		$message = !$message ? isset($_REQUEST['message']) ? $_REQUEST['message'] : '' : $message;
 		$message = urldecode(trim($message));
 		
 		/*	Correct Encoding	*/
 		$message = iconv('UTF-8', 'ASCII//TRANSLIT', $message);
 		
 		$number = !$number ? isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '' : $number;
 		$number = filter_var(urldecode(trim($number)), FILTER_SANITIZE_STRING);
 		
 		$secret_key = !$secret_key ? isset($_REQUEST['skey']) ? $_REQUEST['skey'] : '' : $secret_key;
 		$secret_key = filter_var(urldecode(trim($secret_key)), FILTER_SANITIZE_STRING);
		 		
 		$senderid = !$senderid ? isset($_REQUEST['senderid']) ? $_REQUEST['senderid'] : '' : $senderid;
 		$senderid = filter_var(urldecode(trim($senderid)), FILTER_SANITIZE_STRING);
 		
 		$group = !$group ? isset($_REQUEST['group']) ? $_REQUEST['group'] : '' : $group;
 		$group = filter_var(urldecode(trim($group)), FILTER_SANITIZE_STRING);
 		
 		$tag = !$tag ? isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '' : $tag;
 		$tag = filter_var(urldecode(trim($tag)), FILTER_SANITIZE_STRING);
 		
 		$date = !$date ? isset($_REQUEST['date']) ? $_REQUEST['date'] : '' : $date;
 		$date = filter_var(urldecode(trim($date)), FILTER_SANITIZE_STRING);
 		
 		$response_format = !$response_format ? isset($_REQUEST['response']) ? $_REQUEST['response'] : '' : $response_format;
 		$response_format = filter_var(urldecode(trim($response_format)), FILTER_SANITIZE_STRING);
 		
 		if(BULK_SMS_SERVER_DOWN) {
			$this->_throwError('<error><message>'.UNAVAILABLE_MESSAGE.'</message></error>', $response_format);
 		}

		if(!$schedulesms && NINE_TO_NINE_ACTIVATED) {
 			$this->_throwError('<error><message>'.NINE_TO_NINE_ACTIVATED_MESSAGE.'</message></error>', $response_format);
 		}
		
 		$error = false;
 		$output = '';
 		
 		// User ID
 		$rs = $this->checkBulkSecretKey($secret_key);
	 	$user_id = $rs['user_id'];
	 	$validity = $rs['validity'];
	 	
 		if(!$user_id) {
 			$this->_throwError('<error><message>Invalid Secret Key</message></error>', $response_format);
 		}
 		
 		unset($cond);
	 	$cond['conditions']['BulkAccount.status'] = '0';
	 	$cond['conditions']['BulkAccount.bulk_user_id'] = $user_id;
	 	$quantity = $this->BulkAccount->field('BulkAccount.quantity', $cond['conditions']);
	 	
 		if(!$this->checkBulkValidity($validity) || $quantity == '0') {
 			$this->_throwError('<error><message>'.BULK_ACCOUNT_EXPIRED.'</message></error>', $response_format);
 		}
		 		
 		$n = $bulk_group_id = 0;
 		if(empty($number) && empty($group)) {
 			$output .= '<error><message>Please provide mobile numbers or a group name</message></error>';
 			$error = true;
		
 		} else {
 			
 			if(!empty($number)) {
 				$n = explode(',', $number);
 				array_walk($n, 'AppController::sanitize');
 				$n = array_filter($n);
 				
 				foreach($n as $k => $v) {
 					if(!$this->checkNumber($v)) $inv_num[] = $v;
 					$new_n[] = $v;
 				}
				
 				$n = $new_n;
 				if(isset($inv_num)) {
 					$output .= '<error><message>Invalid Mobile Number(s)</message><number>'.implode('</number><number>', $inv_num).'</number></error>';
 					$error = true;
 				}
 				
 				/*	Limit API call	*/
		 		if(count($n) > NUMBER_LIMIT_IN_API) $n = array_slice($n, 0, NUMBER_LIMIT_IN_API);
 				
 				if(count($n) > 0 && $group == 'GROUP_NAME') {
 					$group = '';
 				}
 				
 			} 
 			
 			// Group
 			if($user_id && !empty($group)) {
 				$bulk_group_id = $this->checkBulkGroupName($group, $user_id);
 				if(!$bulk_group_id) {
 					$output .= '<error><message>Please provide a valid group</message></error>';
 					$error = true;
 				} else {
 					// get mobile numbers
 					$mc['conditions']['BulkAddressbook.bulk_group_id'] = $bulk_group_id;
 					$mc['conditions']['BulkAddressbook.status'] = '0';
 					$mc['fields'] = array('BulkAddressbook.mobile');
 					$mobile_list = $this->BulkAddressbook->find('list', $mc);
 					
 					unset($new_n);
 					foreach($mobile_list as $k => $v) {
 						$new_n[] = $v; 
 					}
 					
 					if(!$schedulesms) $n = !empty($n) ? array_merge($n, $new_n) : $new_n;
 				}
 			}
 		}
 		
 		// Tag
	 	if(!empty($tag) && $tag != 'TAG_NAME') {
 			$bulk_tag_id = $this->checkBulkTagName($tag, $user_id);
 			if($user_id && !$bulk_tag_id) {
 				$output .= '<error><message>Please provide a valid tag</message></error>';
 				$error = true;
 			}

	 	} else {
	 		$tag_general = $this->BulkSmsTag->find('all', array('conditions'=>array('bulk_user_id'=>$user_id, 'status'=>'0'), 'limit' =>'1'));
	 		$bulk_tag_id = $tag_general['0']['BulkSmsTag']['id'];
	 		//$output .= '<error><message>Please provide a valid tag</message></error>';
 			//$error = true;
	 	}

 		// Sender ID (optional)
 		if(!empty($senderid) && $senderid != 'YOUR_SENDERID') {
 			$bulk_senderid_id = $this->checkBulkSenderidName($senderid, $user_id);
 			if($user_id && !$bulk_senderid_id) {
 				$output .= '<error><message>Please provide a valid Sender ID</message></error>';
 				$error = true;
 			}

 		} else {
 			$senderid = BULK_SMS_SENDER_ID;
 			$bulk_senderid_id = 0;
 		}

 		//message
 		if(strlen($message) > 0) { 
 			if(strlen($message) > MAX_CHARS) 
 				$message = substr($message, 0, MAX_CHARS);
 				
 		} else {
 			$output .= '<error><message>Please provide a message</message></error>';
 			$error = true;
 		}
 		
 		
 		//schedule date
 		if($schedulesms) {
	 		if(!empty($date) && $date != 'FUTURE_DATE') {
	 			$date = explode('_', $date);
	 			list($year, $month, $day) = explode('-', $date['0']);
	 			list($hours, $minutes) = explode(':', $date['1']);
	 			
	 			if(!in_array($minutes, array('00', '15', '30', '45'))) {
	 				$output .= '<error><message>Please provide minutes as multiple of 15. Eg: 00, 15, 30, 45, and Date should be of the format (Year-Month-Day_Hour:Minute)</message></error>';
	 				$error = true;
	 			}
	 			
	 			$date = date('Y-m-d H:i:s', mktime($hours, $minutes, '0', $month, $day, $year));
	 			if($date < date('Y-m-d H:i:s')) {
	 				$output .= '<error><message>Please provide a future date</message></error>';
	 				$error = true;
	 			}

				if(!($hours > '08' && $hours < '21')) {
		 			$this->_throwError('<error><message>'.NINE_TO_NINE_ACTIVATED_MESSAGE.'</message></error>', $response_format);
		 		}

	 		} else {
	 			$output .= '<error><message>Please provide date</message></error>';
	 			$error = true;
	 		}
 		}
 		//echo date('Y-m-d H:i:s', mktime($hours, $minutes, '0', $month, $day, $year)); exit;
 		//pr($error);exit;
 		
 		// Save
 		if(!$error) {
 			
 			//get sms vendor details
 			$this->getBulkSmsVendor($user_id);
 			//pr($this->sms_vendor_details);
		 			
 			unset($v);
	 		$v['message'] = $message;
		 	$v['bulk_user_id'] = $user_id;
		 	$v['sms_count'] = $this->sms_count($message);
		 	$v['numbers'] = !empty($n) ? implode(', ', $n) : '0';
		 	$v['bulk_senderid_id'] = $bulk_senderid_id;
		 	$v['bulk_group_id'] = $bulk_group_id;
		 	$v['bulk_tag_id'] = $bulk_tag_id;
		 	$v['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			 	
 			//schedule sms
 			if($schedulesms) {
 				$udate = date('jS M, Y g:i a', strtotime($date));
 				
 				$v['not_included'] = '0';
 				$v['scheduledate'] = $date;
 				$this->BulkSmsSchedule->save($v);
 				
 				$this->_throwError('<success><message>Message Successfully Scheduled on '.$udate.'</message></success>', $response_format);
 				
 			} else {
		 		
 				$v['sms_vendor_id'] = $this->sms_vendor_id;
			 	$this->BulkSmsLog->save($v);
			 	
			 	//SEND SMS
			 	$response_data = $this->_sendBulkMessage($bulk_group_id, $bulk_senderid_id, $message, $this->BulkSmsLog->getLastInsertId(), $user_id, $n, true, $this->sms_vendor_id);
		 		$response_output = array();
			 	foreach($response_data as $value) $response_output = array_merge($response_output, $value); 
			 	
			 	if(!empty($group)) {
		 			$group = '<group>'. $group . ' Group</group>';
		 		}
		 		
		 		if(!empty($n)) {
		 			$number = '<contacts>'.implode('</contacts><contacts>', $response_output).'</contacts>';
		 		}
		 		
		 		$this->_throwError('<success><message>Message Sent Successfully</message>'.$group.$number.'</success>', $response_format);
 			}
 			
 		} else {

 			$this->_throwError($output, $response_format);
 			
 		}
 		
 		exit;
 		
 	}
 	
 	
 	/*	Get status from response key	*/
 	function response() {
 		
 		$secret_key = filter_var(urldecode(trim($_REQUEST['skey'])), FILTER_SANITIZE_STRING);
 		
 		if(isset($_REQUEST['response'])) $response_format = trim($_REQUEST['response']);
		else $response_format = 'xml';
		
		// User ID
 		$rs = $this->checkBulkSecretKey($secret_key);
	 	$user_id = $rs['user_id'];
	 	$server = $rs['server'];
	 	
 		if(!$user_id) {
 			$this->_throwError('<error><message>Invalid Secret Key</message></error>', $response_format);
 		}
 		
 		$output = '';
		if(isset($_REQUEST['key']) && $_REQUEST['key'] != 'RESPONSE_KEY') {
	 		$key = filter_var(trim($_REQUEST['key']), FILTER_SANITIZE_STRING);
	 		$key = explode(',', $key);
			
	 		if(!empty($key)) {
	 			
		 		/*	Sanitize the array elements	*/
		 		array_walk($key, 'AppController::sanitize');
		 		
		 		/*	Limit API call	*/
		 		if(count($key) > RID_LIMIT_IN_API) $key = array_slice($key, 0, RID_LIMIT_IN_API);
	 			
		 		$c['conditions']['BulkSmsLogDetail.status'] = '0';
		 		$c['conditions']['BulkSmsLogDetail.response_key'] = $key;
		 		$c['fields'] = array('BulkSmsLogDetail.response_status', 'BulkSmsLogDetail.response_key');
		 		$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $server);
		 		$data = $this->BulkSmsLogDetail->find('all', $c);
		 		
		 		if(!empty($data)) {
			 		foreach($data as $value) {
			 			$output[] = '<contacts><rid>'.$value['BulkSmsLogDetail']['response_key'].'</rid><status>'.$value['BulkSmsLogDetail']['response_status'].'</status></contacts>'; 
			 		}
			 		$output = implode('', $output);
			 		$this->_throwError($output, $response_format);
		 		
		 		} else {
		 			
		 			$output = '<error><message>Invalid Response ID</message></error>';
 					$this->_throwError($output, $response_format);
		 		
		 		}
	 		}
	 		
 		} else {
 			$output = '<error><message>Invalid Response ID</message></error>';
 			$this->_throwError($output, $response_format);
 		}
 		exit;
 	}
 	
 	/*	Send SMS from Console	*/
 	function sendnow() {
 		
 		set_time_limit(0);
 		ini_set('memory_limit', '256M');
 		
 		$this->checkAccess('bulksms');
 		
 		if(isset($this->data)) {
 			
 			if(BULK_SMS_SERVER_DOWN) {
 				$error[] = UNAVAILABLE_MESSAGE;
 			}

			if($this->data['type'] && NINE_TO_NINE_ACTIVATED) {
	 			$error[] = NINE_TO_NINE_ACTIVATED_MESSAGE;
	 		}			
 			
 			// Sender ID is optional
 			if(isset($this->data['bulk_senderid_id'])) $bulk_senderid_id = filter_var(trim($this->data['bulk_senderid_id']), FILTER_VALIDATE_INT);
 			else $bulk_senderid_id = 0;
 			
 			$bulk_group_id = filter_var(trim($this->data['bulk_group_id']), FILTER_VALIDATE_INT);
 			$bulk_tag_id = filter_var(trim($this->data['bulk_tag_id']), FILTER_VALIDATE_INT);
 			$message = urldecode(trim($this->data['message']));
 			$number = filter_var(trim($this->data['number']), FILTER_SANITIZE_STRING);
 			$day = filter_var(trim($this->data['day']), FILTER_VALIDATE_INT);
 			$month = filter_var(trim($this->data['month']), FILTER_VALIDATE_INT);
 			$year = filter_var(trim($this->data['year']), FILTER_VALIDATE_INT);
 			$hours = filter_var(trim($this->data['hours']), FILTER_VALIDATE_INT);
 			$minutes = filter_var(trim($this->data['minutes']), FILTER_VALIDATE_INT);
 			
 			/*	Correct Encoding	*/
 			$message = iconv('UTF-8', 'ASCII//TRANSLIT', $message);
 			
 			if(empty($message)) $error[] = 'Message Cannot Be Empty';
 			//if(empty($bulk_group_id)) $error[] = 'Invalid Group';
 			//if(empty($bulk_tag_id)) $error[] = 'Invalid Tag';
 			
 			$n = 0;
 			if(empty($number) && empty($bulk_group_id)) $error[] = 'Enter Mobile Numbers or select a Group';
 			else {
 				if(!empty($number)) {
 					$n = explode(',', $number);
 					$n = array_filter($n);
 					foreach($n as $k => $v) {
 						$v = trim($v);
 						if(!$this->checkNumber($v)) $inv_num[] = $v;
 						$new_n[] = $v; 
 					}
 					$n = $new_n;
 					if(isset($inv_num)) $error[] = 'Invalid Mobile Number(s) '.implode(',', $inv_num);
 					
 				}
 				
 				if(!empty($bulk_group_id) && !$this->checkBulkGroupId($bulk_group_id)) $error[] = 'Invalid Group ID';

 			}
 			
 			if(!empty($bulk_senderid_id) && !$this->checkBulkSenderId($bulk_senderid_id)) $error[] = 'Invalid Sender ID';
 			//if(!$this->checkBulkGroupId($bulk_group_id)) $error[] = 'Invalid Group ID';
 			if(!$this->checkBulkTagId($bulk_tag_id)) $error[] = 'Invalid Tag ID';
 			
 			//check if the group empty or not
 			if(!empty($bulk_group_id)) {
	 			$c['BulkAddressbook.bulk_group_id'] = $bulk_group_id;
	 			$c['BulkAddressbook.status'] = '0';
	 			if($this->BulkAddressbook->find('count', array('conditions'=>$c)) == '0') {
	 				$error[] = 'Group is empty';
	 			} else {
 					
	 				// Get mobile numbers
	 				if(isset($this->data['mobile_list'])) $mobile_list = $this->data['mobile_list'];
	 				else {
	 					$mc['conditions']['BulkAddressbook.bulk_group_id'] = $bulk_group_id;
	 					$mc['conditions']['BulkAddressbook.status'] = '0';
	 					$mc['fields'] = array('BulkAddressbook.id');
	 					$mobile_list = $this->BulkAddressbook->find('list', $mc);
	 				}
	 				
	 				//SEND NOW
 					if($this->data['type']) {
	 					$mc['conditions']['BulkAddressbook.id'] = $mobile_list;
	 					$mc['conditions']['BulkAddressbook.bulk_group_id'] = $bulk_group_id;
	 					$mc['conditions']['BulkAddressbook.status'] = '0';
	 					$mc['fields'] = array('BulkAddressbook.mobile');
	 					$mobile_list = $this->BulkAddressbook->find('list', $mc);
	 					
	 					unset($new_n);
	 					foreach($mobile_list as $k => $v) {
	 						$new_n[] = $v; 
	 					}
	 					$n = !empty($n) ? array_merge($n, $new_n) : $new_n;
 					
 					} else {
 						
 					}
	 			}
 			}
 			
 			//SEND NOW
 			if($this->data['type']) {
 				
 				if(!isset($error)) {
 					
 					if(strlen($message) > MAX_CHARS)
 						$message = substr($message, 0, MAX_CHARS);
 					
 					//get sms vendor details
		 			$this->getBulkSmsVendor($this->Session->read('user_id'));
		 			//pr($this->sms_vendor_details);
 					
 					unset($v);
 					$v['message'] = $message;
 					$v['bulk_user_id'] = $this->Session->read('user_id');
 					$v['sms_count'] = $this->sms_count($message);
 					$v['numbers'] = !empty($n) ? implode(', ', $n) : '0';
 					$v['bulk_senderid_id'] = $bulk_senderid_id;
 					$v['bulk_group_id'] = empty($bulk_group_id) ? '0' : $bulk_group_id;
 					$v['bulk_tag_id'] = $bulk_tag_id;
 					$v['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
 					$v['sms_vendor_id'] = $this->sms_vendor_id;
 					$this->BulkSmsLog->save($v);
 					$bulk_sms_log_lid = $this->BulkSmsLog->getLastInsertId();
 					
 					if(count($n) < BULK_SMS_CLI_DECIDER) {
 						
						//SEND SMS
 						$this->_sendBulkMessage($bulk_group_id, $bulk_senderid_id, $message, $bulk_sms_log_lid, $this->Session->read('user_id'), $n, false, $this->sms_vendor_id);
 					
 					} else {

 						//SAVE SMS IN TEMP TABLE
	 					unset($v);
	 					$v['message'] = $message;
	 					$v['numbers'] = !empty($n) ? implode(', ', $n) : '0';
	 					$v['bulk_user_id'] = $this->Session->read('user_id');
						$v['bulk_senderid_id'] = $bulk_senderid_id;
 						$v['bulk_group_id'] = empty($bulk_group_id) ? '0' : $bulk_group_id;
 						$v['bulk_sms_log_id'] = $bulk_sms_log_lid;
 						$v['sms_vendor_id'] = $this->sms_vendor_id;
 						$this->BulkSmsCli->save($v);
	 					$lastinsertid = $this->BulkSmsCli->getLastInsertId();
 					
 					
 						//CALL CLI TO SEND SMS
 						$path = "php " . WWW_ROOT . "cron_dispatcher.php /bulksms/send_using_cli/".$lastinsertid;
 						$outputfile = "/tmp/output.cli.".$lastinsertid;
 						$pidfile = "/tmp/pid.cli.".$lastinsertid;
 						exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $path, $outputfile, $pidfile));
 					
 					}
 					
 					//GET GROUP NAME
 					$groupname = $number = '';
 					if(!empty($bulk_group_id)) {
	 					$this->BulkGroup->id = $bulk_group_id;
	 					$groupname = '<br/>Contact(s) from '.$this->BulkGroup->field('name') . ' Group';
 					}
 					
 					if(!empty($n)) {
 						$number = '<br/>Mobile Numbers(s) '.implode(', ', $n);
 					}
 					
 					$return_data = 'Message Successfully Send To:'.$groupname.$number;
			 		if(strlen($return_data) > 250) $return_data = substr($return_data, 0, 200).'..';
			 		
			 		$return_data .= '<br/><a href="/bulksms/showdetailedreport/'.$bulk_sms_log_lid.'">View Detailed Delivery Report</a>';
			 		
 					$this->Session->write('success', $return_data);
 					$this->redirect('/bulksms/sendnow');
 					exit;
 					
 				} else {
 					$this->set('error', $error);
 					$this->set('message', $message);
 					$this->set('number', $number);
 					$this->set('day', $day);
 					$this->set('month', $month);
 					$this->set('year', $year);
 					$this->set('hour', $hours);
 					$this->set('minutes', $minutes);
 				}
 				
 			//SCHEDULE	
 			} else {
 				
 				/*if($day == '' || $month == '' || $year == '' || $hours == '' || $minutes == '') { 
 					$error[] = 'Invalid Date Entered';
 				} else {*/
 					$date = date('Y-m-d H:i:s', mktime($hours, $minutes, '0', $month, $day, $year));
 					if($date < date('Y-m-d H:i:s')) $error[] = 'Please select a future date';
 				//}
 				
				if(!($hours > '08' && $hours < '21')) {
 					$error[] = NINE_TO_NINE_ACTIVATED_MESSAGE;
 				}

 				if(isset($mobile_list) && !empty($mobile_list)) {
	 				unset($c);
	 				if(count($mobile_list) == 1) $mobile_list = $mobile_list['0'];
	 				$c['conditions']['BulkAddressbook.bulk_group_id'] = $bulk_group_id;
	 				$c['conditions']['BulkAddressbook.id NOT'] = $mobile_list;
	 				$c['conditions']['BulkAddressbook.status'] = '0';
	 				$c['fields'] = array('BulkAddressbook.id');
	 				$not_included = $this->BulkAddressbook->find('list', $c);
 				}
 				
 				if(!isset($error)) {
 					
 					$date = date('Y-m-d H:i:s', mktime($hours, $minutes, '0', $month, $day, $year));
 					$udate = date('jS M, Y g:i a', strtotime($date));
 					
 					unset($v);
 					$v['message'] = $message;
 					$v['bulk_user_id'] = $this->Session->read('user_id');
 					$v['numbers'] = !empty($n) ? implode(',', $n) : '0';
 					$v['not_included'] = !empty($not_included) ? implode(',', $not_included) : '0';
 					$v['scheduledate'] = $date;
 					$v['bulk_senderid_id'] = $bulk_senderid_id;
 					$v['bulk_group_id'] = empty($bulk_group_id) ? '0' : $bulk_group_id;
 					$v['bulk_tag_id'] = $bulk_tag_id;
 					$v['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
 					$this->BulkSmsSchedule->save($v);
 					
 					$this->Session->write('success', 'Message Successfully Scheduled on '.$udate);
 					$this->redirect('/bulksms/sendnow');
 					exit;
 					
 				} else {
 					$this->set('error', $error);
 					$this->set('message', $message);
 					$this->set('number', $number);
 					$this->set('day', $day);
 					$this->set('month', $month);
 					$this->set('year', $year);
 					$this->set('hour', $hours);
 					$this->set('minutes', $minutes);
 				}
 				
 			}
 			
 		}
 		
 		unset($cond);
 		$cond['conditions']['status'] = '0';
	 	$cond['conditions']['bulk_user_id'] = $this->Session->read('user_id');
	 	$group = $this->BulkGroup->find('list', $cond);
	 	$groupname = '<option value="0">Select Group</option>';
	 	
	 	foreach($group as $key => $value) {
	 		$groupname .= '<option value="'.$key.'">'.$value.'</option>';
	 	}
	 	$this->set('groupname', $groupname);
	 	
	 	unset($cond);
	 	$cond['conditions']['status'] = '0';
	 	$cond['conditions']['publish'] = '1';
	 	$cond['conditions']['bulk_user_id'] = $this->Session->read('user_id');
	 	$sender = $this->BulkSenderid->find('list', $cond);
	 	$senderid = '';
	 	
	 	foreach($sender as $key => $value) {
	 		$senderid .= '<option value="'.$key.'">'.$value.'</option>';
	 	}
	 	$this->set('senderid', $senderid);
	 	
	 	unset($cond);
	 	$cond['conditions']['status'] = '0';
	 	$cond['conditions']['bulk_user_id'] = $this->Session->read('user_id');
	 	$data = $this->BulkSmsTag->find('list', $cond);
	 	$tag = '';
	 	
	 	foreach($data as $key => $value) {
	 		$tag .= '<option value="'.$key.'">'.$value.'</option>';
	 	}
	 	$this->set('tag', $tag);
	 	
	 	unset($cond);
	 	$cond['conditions']['BulkAccount.status'] = '0';
	 	$cond['conditions']['BulkAccount.bulk_user_id'] = $this->Session->read('user_id');
	 	$quantity = $this->BulkAccount->field('BulkAccount.quantity', $cond['conditions']);
	 	$this->set('quantity', $quantity);
	 	
 		$this->layout = 'bulksms';
 		$this->set('tab', array('2'));
		//$this->getFeedback();
		
 		if($this->Session->check('success')) {
 			$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 		}
 
 	}

 	function send_using_cli($bulk_sms_cli_id) {
 		
 		if(CRON_DISPATCHER && !empty($bulk_sms_cli_id)) {
 			
 			$data = $this->BulkSmsCli->findById($bulk_sms_cli_id);
 			$this->_sendBulkMessage($data['BulkSmsCli']['bulk_group_id'], $data['BulkSmsCli']['bulk_senderid_id'], 
 									$data['BulkSmsCli']['message'], $data['BulkSmsCli']['bulk_sms_log_id'], $data['BulkSmsCli']['bulk_user_id'], 
 									explode(',', $data['BulkSmsCli']['numbers']), false, $data['BulkSmsCli']['sms_vendor_id']);
 			
 			$this->BulkSmsCli->id = $bulk_sms_cli_id;
 			$this->BulkSmsCli->saveField('status', 1);
 			
 		}
 		
 		exit;
 		
 	}
 	
 	function schedulereport() {
 		
 		$this->checkAccess('bulksms');
 		
 		//schedule sms
		$x['BulkSmsSchedule.status'] = '0';
		$x['BulkSmsSchedule.send'] = '0';
		$x['BulkSmsSchedule.bulk_user_id'] = $this->Session->read('user_id');
		$sc_data = $this->BulkSmsSchedule->findAll($x, '', 'BulkSmsSchedule.scheduledate ASC');
		$this->set('sc_data', $sc_data);
		
 		if($this->Session->check('success')) {
 			$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 		}
 		
		$this->layout = 'bulksms';
 		$this->set('tab', array('2'));
 		
 	}
 	
 	function showreport() {
 		
 		$this->checkAccess('bulksms');
 		
 		$date1 = date('Y-m-d') . ' 00:00:00';
 		$date2 = date('Y-m-d') . ' 23:59:59';
 		$status = '';
 		$name = '';
 		
 		//get all group
 		$c['conditions']['status'] = '0';
 		$c['conditions']['bulk_user_id'] = $this->Session->read('user_id');
 		$groups = $this->BulkGroup->find('list', $c);
 		$groupname = '<option value="0">All Groups</option>';
	 	
	 	foreach($groups as $key => $value) {
	 		$sel = (!empty($this->data['group_id']) && $this->data['group_id'] == $key) ? 'selected="selected"' : '';
	 		$groupname .= '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
	 	}
	 	$this->set('groupname', $groupname);
	 	
 		//get all sender ID
 		unset($c);
 		$c['conditions']['bulk_user_id'] = $this->Session->read('user_id');
 		$c['conditions']['publish'] = '1';
 		$senderid = $this->BulkSenderid->find('list', $c);
 		
 		//get all tags
 		unset($c);
 		$c['conditions']['status'] = '0';
 		$c['conditions']['bulk_user_id'] = $this->Session->read('user_id');
 		$tags = $this->BulkSmsTag->find('list', $c);
 		$this->set('tag_list', $tags);
 		$tagname = '<option value="0">All Tags</option>';
	 	
	 	foreach($tags as $key => $value) {
	 		$sel = (!empty($this->data['tag_id']) && $this->data['tag_id'] == $key) ? 'selected="selected"' : '';
	 		$tagname .= '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
	 	}
	 	$this->set('tagname', $tagname);
	 	
 		//$condition['conditions']['BulkSmsLog.bulk_group_id'] = array_keys($groups);
 		
 		if(isset($this->data)) {
 			
 			if(!empty($this->data['date1']) && !empty($this->data['date2'])) {
 			
	 			$date1 = date('Y-m-d', strtotime($this->data['date1'])) . ' 00:00:00';
	 			$date2 = date('Y-m-d', strtotime($this->data['date2'])) . ' 23:59:59';
	 			
	 			// chk if it belons to current user
	 			if(!empty($this->data['group_id'])) {
	 			
	 				if(!$this->checkBulkGroupId($this->data['group_id'])) $error[] = 'Invalid Group';
	 				else $condition['conditions']['BulkSmsLog.bulk_group_id'] = $this->data['group_id'];
	 				
	 			}
	 			
	 			// chk if it belongs to current user
	 			if(!empty($this->data['tag_id'])) {
	 				
	 				if(!$this->checkBulkTagId($this->data['tag_id'])) $error[] = 'Invalid Message Tag';
	 				else $condition['conditions']['BulkSmsLog.bulk_tag_id'] = $this->data['tag_id'];
	 				
	 			} 
	 			
 			} else {

 				$error[] = 'Please select a date';
 				
 			}
 			
 		}
 		
 		$condition['conditions']['0'] = array('BulkSmsLog.created BETWEEN ? AND ?' => array($date1, $date2));
 		$condition['conditions']['BulkSmsLog.bulk_user_id'] = $this->Session->read('user_id');
 		$condition['conditions']['BulkSmsLog.status'] = 0;
 		$condition['fields'] = array('id', 'message', 'numbers', 'bulk_group_id', 'bulk_senderid_id', 'bulk_tag_id', 'sms_count', 'created');
 		$condition['order']['BulkSmsLog.id'] = 'desc';
 		$data = $this->BulkSmsLog->find('all', $condition);
 		
 		$date1 = date('d-m-Y', strtotime($date1));
 		$date2 = date('d-m-Y', strtotime($date2));
 		
 		$this->set('data', $data);
 		$this->set('date1', $date1);
 		$this->set('date2', $date2);
		$this->set('groups', $groups);
 		$this->set('senderid', $senderid);
 		$this->set('tags', $tags);
		
 		$this->layout = 'bulksms';
 		$this->set('tab', array('2'));
		//$this->getFeedback();
		
 		if($this->Session->check('sc_success')) {
 			$this->set('sc_success', $this->Session->read('sc_success'));
 			$this->Session->delete('sc_success');
 		}
 		
 		if($this->Session->check('sc_error')) {
 			$this->set('sc_error', $this->Session->read('sc_error'));
 			$this->Session->delete('sc_error');
 		}
 		
 	}
 	
 	function showdetailedreport($bulk_sms_log_id) {
 		
 		$this->checkAccess('bulksms');
 		
 		// Check if this sms_log_id belongs to current user
 		$this->BulkSmsLog->unBindAll();
 		if(!$this->BulkSmsLog->find('count', array('conditions'=>array('BulkSmsLog.id'=>$bulk_sms_log_id, 'BulkSmsLog.bulk_user_id'=>$this->Session->read('user_id')))))
 			return false;
 		
 		$status = '';
 		$name = '';
 		
 		if(isset($this->data)) {
 			
 			if(!empty($this->data['status'])) {
 				
 				$status = $this->data['status'];
 				switch($status) {
 					case 1 : $condition['conditions']['response_status'] = 'DELIVERED';
 								break;
 					case 2 : $condition['conditions']['response_status'] = 'UNDELIVERED';
 								break;
 					case 3 : $condition['conditions']['response_status'] = 'PENDING';
 								break;
 					case 4 : $condition['conditions']['response_status'] = 'EXPIRED';
 								break;
 					case 5 : $condition['conditions']['response_status'] = 'DND';
 								break;
 					default : '';
 				}
 				
 			}
 			
 			if(!empty($this->data['name'])) {
 				
 				$name = $this->data['name'];
 				$condition['conditions']['mobile LIKE'] = $name . '%';
 				 
 			}
	 			
 		}
 		
 		$server = $this->_getBulkServer();
 		 
 		$condition['conditions']['bulk_sms_log_id'] = $bulk_sms_log_id;
 		$condition['fields'] = array('mobile', 'response_status', 'created');
 		$condition['order']['id'] = 'desc';
 		$this->BulkSmsLogDetail->setSource('bulk_sms_log_detail_' . $server);
 		$data = $this->BulkSmsLogDetail->find('all', $condition);
 		
 		/*	Count all response status	*/
 		$data_count = count($data);
 		for($i=0; $i<$data_count; $i++) {
 			if(isset($response_status[$data[$i]['BulkSmsLogDetail']['response_status']]))
 				$response_status[$data[$i]['BulkSmsLogDetail']['response_status']] = ++$response_status[$data[$i]['BulkSmsLogDetail']['response_status']];
 			else $response_status[$data[$i]['BulkSmsLogDetail']['response_status']] = 1; 
 		}
 		
 		asort($response_status);
 		$this->set('response_status', $response_status);
 		$this->set('data', $data);
 		$this->set('status', $status);
 		$this->set('name', $name);
 		
 		$this->layout = 'bulksms';
 		$this->set('tab', array('2'));
		//$this->getFeedback();
 		
 	}
 	
 	function feedback() {
 		
 		$this->checkAccess('bulksms');
 		
 		if(isset($this->data)) {
 		
	 		$feedback = filter_var(trim($this->data['BulkFeedback']['feedback']), FILTER_SANITIZE_STRING);
	 		if(empty($feedback)) $error[] = 'Feedback is required';
			
			if(empty($error)) {
				
				$value['feedback'] = $feedback;
				$value['user_id'] = $this->Session->read('user_id');
				
				/*	Notify Admin	*/
				$to_admins['type'] = 'Bulk Feedback';
				$to_admins['data'] = $value;
				$this->notifyAdmin($to_admins);
				
				/*	Save Data	*/
				$this->BulkFeedback->save($value);
				$success = 'Thank you for your time and patience. Your feedback will definitely help us serve you better.';
				$this->Session->write('success', $success);
				$this->redirect('/bulksms/feedback');
				exit;
				
			} else {

				$this->set('feedbackText', $feedback);
				$this->set('error', $error);
				
			}
 		
 		} else {
 			
 			if($this->Session->check('success')) {
 				
 				$this->set('success', $this->Session->read('success'));
 				$this->Session->delete('success');
 				
 			}
 			
 		}
 		
 		$this->set('tab', array('5'));
 		$this->layout = 'bulksms';
 		//$this->getFeedback();
 	}
 	
 	function signout() {
		
		$this->Session->destroy();
		$this->redirect('/bulksms/login');
		exit;
		
	}
	
 }
 
?>
