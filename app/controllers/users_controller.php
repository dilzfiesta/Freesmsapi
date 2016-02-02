<?php
/*
 * Created on Feb 21, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class UsersController extends AppController {

	//var $components = array('Email');
	var $uses = array('User', 'UserTmp', 'Plan', 'Domain', 'History', 'Monitor', 'Message', 'Alias',
						'ValidNumber', 'BlacklistDomain', 'Upgrade', 'Maillog', 'Advertiser', 'AdvPlan',
 						'BulkUser', 'BulkUserpersonalinfo', 'AliasBuy', 'AliasInvoice', 'ReferralProgram',
						'Userpersonalinfo', 'Pricing', 'MerchantOrder', 'BulkAccountRegister', 'SmsVendor',
						'UserSuspended', 'DomainIp');


	function index() {

		/*if($this->Session->read('user_type') == 'developer' && $this->Session->check('user_id')) {
			$this->redirect('/users/view');
			exit;
		} else if($this->Session->read('user_type') == 'bulksms' && $this->Session->check('user_id')) {
			$this->redirect('/bulksms/view');
			exit;
		} else {
			$this->redirect('/users/login');
			exit;
		}*/
		
		//$this->set('tab', array('1'));
		//$this->layout = 'index';
	}
	
	/* function sendNewUserMail() {
	 $this->Email->to = 'dilzfiesta@gmail.com';
	 $this->Email->subject = 'Welcome to our really cool thing';
	 $this->Email->replyTo = 'support@example.com';
	 $this->Email->from = 'Cool Web App <app@example.com>';
	 $this->Email->send();
	 exit;
	 }
	 function download () {
	 $this->checkAccess('developer');
	 $this->view = 'Media';
	 $params = array(
	 'id' => 'example.zip',
	 'name' => 'example',
	 'download' => true,
	 'extension' => 'zip',
	 'path' => APP . 'files' . DS
	 );
	 $this->set($params);
	 }*/

	function register() {

		if(ONLY_DEVELOPER_SECTION) { $this->redirect('/users/registration'); exit; }

		$this->layout = 'before_login';
		$this->set('tab', array('1'));
		$this->getFeedback();

	}

	function onregistration() {
			
		App::import('Vendor', 'recaptchalib');
		$publickey = CAPTCHA_PUBLIC_KEY;
		$this->set('recaptcha', recaptcha_get_html($publickey));
		$this->set('tab', array('1'));
		$this->getFeedback();
			
	}

	function onlogin() {
			
		//$this->Session->destroy();
		$this->set('tab', array('1'));
		$this->getFeedback();
			
	}

	function onupgrade() {
			
		$this->checkAccess('developer');
			
		//$this->getFeedback();
		$this->set('tab', array('7'));
			
	}

	function upgrade() {
		exit;
		$this->onupgrade();
		$this->layout = 'after_login';
			
		if(isset($this->data)) {

			if(!empty($this->data['Upgrade']['requestbody'])) {

				$value['requestbody'] = filter_var(trim($this->data['Upgrade']['requestbody']), FILTER_SANITIZE_STRING);
				$value['domain_id'] = $this->Session->read('domain_id');
				
				/*	Notify Admin	*/
				$to_admins['type'] = 'Upgrade';
				$to_admins['data'] = $value;
				$this->notifyAdmin($to_admins);
				
				/*	Save Data	*/
				$this->Upgrade->save($value);

				//$success = 'We will review your request and get back to you shortly';
				$success = 'Your application for an upgradation is currently pending approval. Once it has been approved, you will receive another e-mail containing information about additional free SMS and other details';
				$this->Session->write('success', $success);
					
				$this->_sendUpgrade($this->Session->read('domain_name'), $this->Session->read('user_email'));
					
				$this->redirect('/users/upgrade');
				exit;
					
			} else {
					
				$error = 'Please enter something and then hit submit button';
				$this->set('error', $error);
					
			}
				
		} else {

			if($this->Session->check('success')) {
					
				$this->set('success', $this->Session->read('success'));
				$this->Session->delete('success');
					
			}
				
		}
			
		$cond['Upgrade.status'] = '0';
		$cond['Upgrade.domain_id'] = $this->Session->read('domain_id');
		$upgrade_data = $this->Upgrade->findAll($cond, array('id', 'requestbody', 'created', 'approve'), 'Upgrade.id DESC');
		$this->set('upgrade_data', $upgrade_data);
			
	}

	function help() {

		$this->checkAccess('developer');
		
		$this->layout = 'after_login';
		$domain_name = $this->Session->read('domain_id');
			
		$condition['conditions']['Domain.id'] = $domain_name;
		$condition['fields'] = array('Plan.id', 'Domain.id', 'Domain.secret_key', 'Domain.server');
		$this->Domain->unBindmodel(array('belongsTo' => array('User')));
		$data = $this->Domain->find('all', $condition);
			
		$server = 'http://'.$data['0']['Domain']['server'].'.'.SERVERNAME;

		$this->set('server', $server);
		$this->set('secret_key', $data['0']['Domain']['secret_key']);
		$this->set('tab', array('9'));
		//$this->getFeedback();

	}

	function api_response() {
		
		$this->checkAccess('developer');
		$this->layout = 'after_login';
		$this->set('tab', array('9'));
		
	}
	
	function signOut() {
			
		$this->checkAccess();
			
		$this->Session->destroy();
		$this->redirect('/users/login');
			
	}

	function login() {
			
		$this->onlogin();
		$this->layout = 'before_login';
		$this->set('usertype', 'developer');
			
		if(isset($this->data)) {

			/*	After two attempts show captcha to avoid brute force login	*/
			if($this->Session->check('login_captcha')) {
				
				App::import('Vendor', 'recaptchalib');
				$privatekey = CAPTCHA_PRIVATE_KEY;
				$resp = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
	
				if (!$resp->is_valid) {
					$error[] = 'CAPTCHA wasn\'t entered correctly';
				}
				
			}
				
			if(isset($error)) {
				
				$this->set('error', $error);
				
			} else {
			
				$username = filter_var(trim($this->data['User']['name']), FILTER_SANITIZE_STRING);
				//$password = filter_var(trim($this->data['User']['password']), FILTER_SANITIZE_STRING);
				$password = trim($this->data['User']['password']); // for special characters
					
				if($this->data['User']['type'] == 'advertiser') { //Advertiser login
					
					if(ONLY_DEVELOPER_SECTION) return false;
					
					$condition['conditions']['Advertiser.email'] = $username;
					$condition['conditions']['Advertiser.password'] = $password;
					$condition['conditions']['Advertiser.status'] = '0';
					$condition['fields'] = array('Advertiser.id', 'Advertiser.fname', 'Advertiser.lname', 'Advertiser.mobile',
													'Advertiser.email', 'Advertiser.type', 'AdvPlan.id', 'AdvPlan.adv_limit', 'AdvPlan.payment_id');
					$advertiser = $this->Advertiser->find('all', $condition);
					 
					if(count($advertiser) > 0) {
						 
						$this->Session->destroy();
						$this->Session->write('user_id', $advertiser['0']['Advertiser']['id']);
						$this->Session->write('user_name', $advertiser['0']['Advertiser']['fname'].' '.$advertiser['0']['Advertiser']['lname']);
						$this->Session->write('user_type', 'advertiser');
						$this->Session->write('user_email', $advertiser['0']['Advertiser']['email']);
						$this->Session->write('user_mobile', $advertiser['0']['Advertiser']['mobile']);
	
						$this->Session->write('adv_plan_id', $advertiser['0']['AdvPlan']['id']);
						$this->Session->write('adv_plan_type', $advertiser['0']['Advertiser']['type']);
						$this->Session->write('adv_plan_limit', $advertiser['0']['AdvPlan']['adv_limit']);
						$this->Session->write('adv_plan_payment_id', $advertiser['0']['AdvPlan']['payment_id']);
	
						//update the date inorder to record last login
						$this->Advertiser->id = $advertiser['0']['Advertiser']['id'];
						$this->Advertiser->saveField('updated', NOW);
	
						$this->redirect('/advertisers/view');
						exit;
						 
					} else {
	
						$this->set('tab', array('3'));
						$this->getFeedback();
						$this->set('error', 'Invalid username and password');
						//$this->render('/users/login');
	
					}
					 
						
				} else if($this->data['User']['type'] == 'developer') {  //Developer login
					
					$condition['conditions']['User.name'] = $username;
					$condition['conditions']['User.password'] = $password;
					$condition['conditions']['User.status'] = '0';
					$condition['fields'] = array('User.id', 'User.name', 'User.email', 'User.verify', 'User.verifymobile', 'Domain.id', 'Domain.name');
					$user = $this->User->find('all', $condition);
					
					// check if suspended
					if(!empty($user['0']['User']['id'])) {
						$terminate = $this->checkUserSuspended($user['0']['User']['id']);
						if(!empty($terminate)) {
							$error[] = $terminate;
							$this->set('error', $error);
						}
					}
					
					//$this->User->unBindAll();
					if(count($user) > 0 && !isset($error)) {
	
						$this->Session->destroy();
						$this->Session->write('user_id', $user['0']['User']['id']);
						$this->Session->write('user_name', $user['0']['User']['name']);
						$this->Session->write('user_type', 'developer');
						$this->Session->write('user_email', $user['0']['User']['email']);
						$this->Session->write('domain_id', $user['0']['Domain']['id']);
						$this->Session->write('domain_name', $user['0']['Domain']['name']);
						$this->Session->write('verified', $user['0']['User']['verify']);
						$this->Session->write('verifymobile', $user['0']['User']['verifymobile']);
						$this->Session->write('notice_once', true);
						
						//update the date inorder to record last login
						$this->Domain->id = $user['0']['Domain']['id'];
						$this->Domain->saveField('updated', NOW);
	
						$this->User->id = $user['0']['User']['id'];
						$this->User->saveField('updated', NOW);
												
						$this->redirect('/users/view');
						exit;
						 
					}
					 
					unset($condition);
					$condition['conditions']['UserTmp.name'] = $username;
					$condition['conditions']['UserTmp.password'] = $password;
					$condition['conditions']['UserTmp.status'] = 0;
					$condition['fields'] = array('UserTmp.id', 'UserTmp.email', 'UserTmp.domain', 'UserTmp.referral');
					$data = $this->UserTmp->find('all', $condition);
					if(count($data) > 0 && !isset($error)) {
	
						$referral_domain_id = $this->getDomainID($data['0']['UserTmp']['referral']);
						
						// check if deleted domain is registered again with the same referral
						$c['conditions']['User.email like'] = "%@".$data['0']['UserTmp']['domain'];
						$c['conditions']['User.referral'] = $referral_domain_id;
						$this->User->recursive = -1;
						if($this->User->find('count', $c) > 0) $hasRefProg = true;
						else $hasRefProg = false;
						
						// save in user table
						$value['User']['name'] = $username;
						$value['User']['password'] = $password;
						$value['User']['email'] = $data['0']['UserTmp']['email'];
						$value['User']['referral'] = $referral_domain_id;
						$value['Domain']['name'] = $data['0']['UserTmp']['domain'];
						$value['Domain']['ip'] = $this->getDomainIP($data['0']['UserTmp']['domain']);
						$value['Domain']['sms_vendor_id'] = DEFAULT_SMS_VENDOR_ID;
						$value['Domain']['plan_id'] = DEFAULT_PLAN_ID;
						$value['Domain']['server'] = $this->serverList[rand(0,1)];
						$value['Domain']['secret_key'] = $this->getSecretKey($data['0']['UserTmp']['domain']);
						$this->User->saveAll($value);
						$user_id = $this->User->getLastInsertId();
						
						// delete from tmp table
						$this->UserTmp->id = $data['0']['UserTmp']['id'];
						$this->UserTmp->saveField('status', '1');
						
						// save in history for archeiving
						unset($value);
						$value['domain_id'] = $this->getDomainID($data['0']['UserTmp']['domain']);
						$value['plan_id'] = 1;
						$this->History->save($value);
						
						// referral program
						if(!$hasRefProg)
							$this->_getReferralProgram($referral_domain_id);
						
						// start a new session
						$this->Session->destroy();
						$this->Session->write('user_id', $user_id);
						$this->Session->write('user_name', $username);
						$this->Session->write('user_email', $data['0']['UserTmp']['email']);
						$this->Session->write('user_type', 'developer');
						$this->Session->write('domain_id', $value['domain_id']);
						$this->Session->write('domain_name', $data['0']['UserTmp']['domain']);
						$this->Session->write('verified', 0);
						$this->Session->write('notice_once', true);

						$this->redirect('/users/view');
						exit;
	
					} else if(!isset($error)) {
	
						//$this->set('tab', array('3'));
						//$this->set('usertype', 'developer');
						$error[] = 'Invalid username and password';
						$this->set('error', $error);
						//$this->render('/users/login');
	
					}
	
				} else if($this->data['User']['type'] == 'bulksms') {  //Bulk SMS login
					 
					if(isset($this->data)) {
	
						unset($condition);
						$condition['conditions']['BulkUser.username'] = $username;
						$condition['conditions']['BulkUser.password'] = $password;
						$condition['conditions']['BulkUser.status'] = '0';
						
						$this->BulkUser->recursive = 2;
						$data = $this->BulkUser->find('all', $condition);
						
						if(count($data) > 0) {
							
							$this->BulkUser->id = $data['0']['BulkUser']['id'];
							$this->BulkUser->saveField('updated', NOW);
							$validity = $data['0']['BulkAccount']['BulkAccountRecharge'][count($data['0']['BulkAccount']['BulkAccountRecharge'])-1]['validtill'];
							
					 		if(!$this->checkBulkValidity($validity)) $error = BULK_ACCOUNT_EXPIRED;
	 						else {
								$this->Session->destroy();
								$this->Session->write('user_id', $data['0']['BulkUser']['id']);
								$this->Session->write('user_name', ucfirst($data['0']['BulkUserpersonalinfo']['firstname']) .' '. ucfirst($data['0']['BulkUserpersonalinfo']['lastname']));
								$this->Session->write('user_type', 'bulksms');
								$this->Session->write('user_email', strtolower($data['0']['BulkUserpersonalinfo']['email']));
								$this->Session->write('validity', $validity);
								$this->Session->write('notice_once', true);
								
								$this->redirect('/bulksms/view');
								exit;
	 						}
							 
						} else $error = 'Invalid Username and Password';
	
						//$this->set('usertype', 'bulksms');
						$this->set('error', $error);
	
					}
					 
				} else {
						
					//$this->set('tab', array('3'));
					//$this->getFeedback();
					$this->set('error', 'Please select a user type');
					//$this->render('/users/login');
					//exit;
						
				}
				
			}
			
		}
		
		/*	After two attempts show captcha to avoid brute force login	*/
		if($this->Session->check('login_attempt')) {
			
			$login_attempt = $this->Session->read('login_attempt');
			if($login_attempt > LOGIN_ATTEMPT) {
					
				App::import('Vendor', 'recaptchalib');
				$publickey = CAPTCHA_PUBLIC_KEY;
				$this->set('recaptcha', recaptcha_get_html($publickey));
				$this->Session->write('login_captcha', 1);
				
			} else $this->Session->write('login_attempt', ++$login_attempt);
			
		} else $this->Session->write('login_attempt', 1);
			
	}

	function view() {
		
		$this->checkAccess('developer');

		$this->layout = 'after_login';
		$domain_name = $this->Session->read('domain_name');

		$condition['conditions']['User.id'] = $this->Session->read('user_id');
		$condition['conditions']['Domain.name'] = $domain_name;
		$condition['fields'] = array('Plan.id', 'Plan.sms', 'Domain.id', 'Domain.name', 'Domain.secret_key', 'Domain.server', 'Domain.ip');
		$data = $this->Domain->find('all', $condition);
		$plan_sms = $data['0']['Plan']['sms'];
		$this->Session->write('domain_id', $data['0']['Domain']['id']);
		
		// check if the domain is hosted or not
		if($data['0']['Domain']['name'] == $data['0']['Domain']['ip']) $this->set('no_ip', true);
		else $this->set('no_ip', false);
		
		// total sms send till date
		unset($condition);
		$condition['conditions']['domain_id'] = $data['0']['Domain']['id'];
		$this->Message->setSource($data['0']['Domain']['server'].'_log');
		$total = $this->Message->find('count', $condition);
			
		$condition['conditions']['created'] = date('Y-m-d');
		$this->Message->setSource($data['0']['Domain']['server'].'_log');
		$total_today = $this->Message->find('count', $condition);

		unset($c);
 		$c['conditions']['User.status'] = 0;
 		$c['conditions']['User.referral'] = $this->Session->read('domain_id');
 		$currentstatus = $this->User->find('count', $c);
		$this->set('currentstatus', $currentstatus);
 			
		$server = 'http://'.$data['0']['Domain']['server'].'.'.SERVERNAME;

		// verify mobile number
		if(!$this->Session->read('verified')) {
			
			$this->set('verified_mobile', 0);
			unset($c);
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
		
		} else $this->set('verified_mobile', 1);
		
		if($this->Session->check('success')) {
			$this->set('success', $this->Session->read('success'));
			$this->Session->delete('success');
		}
		
		if($this->Session->read('notice_once')) $show_notice = true;
		else $show_notice = false;
		
		$this->Session->write('notice_once', false);
		$this->set('show_notice', $show_notice);
		
		//$this->set('url', $url);
		$this->set('domain_ip', $data['0']['Domain']['ip']);
		$this->set('plan_id', $data['0']['Plan']['id']);
		$this->set('ip', $data['0']['Domain']['ip']);
		$this->set('total_sms', $total);
		$this->set('total_sms_today', $total_today);
		$this->set('plan_sms', $plan_sms);
		$this->set('server', $server);
		$this->set('secret_key', $data['0']['Domain']['secret_key']);
		$this->set('user_id', $this->Session->read('user_id'));
		$this->set('domain_name', $this->Session->read('domain_name'));
		$this->set('max_recipient', MAX_RECIPIENT);
		$this->set('tab', array('1'));

	}

	function onmyaccount() {
			
		$this->checkAccess();
			
		$this->layout = ($this->Session->read('user_type') == 'advertiser') ? 'advertiser' : 'after_login';
		$this->set('tab', array('2'));
		//$this->getFeedback();
			
	}

	function myaccount() {
			
		$this->onmyaccount();
		
		/* Change Password */
		if(isset($this->data['User'])) {
				
			$type = ($this->Session->read('user_type') == 'advertiser') ? $this->Advertiser : $this->User;

			$condition['conditions']['password'] = $this->data['User']['password'];
			$condition['conditions']['id'] = $this->Session->read('user_id');
			$type->unBindAll();
			 
			if(!$type->find('count', $condition))
			$error[] = 'Invalid old password';

			if(strlen($this->data['User']['new_password']) < 8)
			$error[] = 'New password should be greater or equal to 8 characters';
			if($this->data['User']['new_password'] != $this->data['User']['retype_password'])
			$error[] = 'New password and retyped password did not match';

			if(!isset($error)) {
					
				$type->id = $this->Session->read('user_id');
				$type->saveField('password', $this->data['User']['new_password']);
				$this->Session->write('success', 'Password changed successfully');
				$this->redirect('/users/myaccount');
				exit;

			} else {
					
				$this->set('error', $error);
					
			}

	 	/* Change Alias */
		} else if(isset($this->data['Alias']) && $this->Session->read('user_type') != 'advertiser') {

			$regex = '/[^A-Za-z0-9\-._]/';
			$this->data['Alias']['name'] = filter_var($this->data['Alias']['name'], FILTER_SANITIZE_STRING);
			$this->data['Alias']['name'] = trim($this->data['Alias']['name']);

			if(empty($this->data['Alias']['name']))
				$error[] = 'Sender ID is required';
			else if(strlen($this->data['Alias']['name']) > 8)
				$error[] = 'Sender ID should be less than or equal to 8 chars';
			else if(preg_match($regex, $this->data['Alias']['name']))
				$error[] = 'Sender ID should not contain any spaces or special characters other than dot(.), hyphen(-) and underscore(_)';
			else if(!$this->check_alias_date() && $this->Alias->find('count', array('conditions'=>array('Alias.domain_id'=>$this->Session->read('domain_id'), 'Alias.status'=>0))) >= MAX_FREE_ALIAS)
				$error[] = 'Maximum '.MAX_FREE_ALIAS.' Sender ID is allowed, If you want more please <a href="/users/pricingsenderid">purchase it</a>';
			
			if(!isset($error)) {
					
				if(!$this->check_alias_date()) {
				
					$value['name'] = $this->data['Alias']['name'];
					$value['domain_id'] = $this->Session->read('domain_id');
					
					/*	Notify Admin	*/
					$to_admins['type'] = 'Alias';
					$to_admins['data'] = $value;
					$this->notifyAdmin($to_admins);
					
					/*	Add New	Alias	*/
					$this->Alias->save($value);
					$this->Session->write('alias_success', 'Sender ID is send for approval, you will be notified once approved');
				
				} else {
					
					//if(!SHOW_ONLY_TO_ME) {
						$this->redirect('/users/pricingsenderid');
						exit;
					//} else {
					//	$this->Session->write('buysenderid', $this->data['Alias']['name']);
					//	$this->redirect('/users/buysenderid');
					//	exit;
					//}
					
				}
				
				//send sender ID mail
				$this->Domain->id = $this->Session->read('domain_id');
				$this->User->id = $this->Domain->field('user_id');
				$email = $this->User->field('email');
				$this->_senderIdMail($email);

				$this->redirect('/users/myaccount');
				exit;

			} else {
					
				$this->set('alias_error', $error);
					
			}

		} else {

			if($this->Session->check('success')) {
					
				$this->set('success', $this->Session->read('success'));
				$this->Session->delete('success');
					
			} else if($this->Session->check('alias_success')) {
					
				$this->set('alias_success', $this->Session->read('alias_success'));
				$this->Session->delete('alias_success');
					
			} else if($this->Session->check('secretkey_success')) {
					
				$this->set('secretkey_success', $this->Session->read('secretkey_success'));
				$this->Session->delete('secretkey_success');
					
			}

		}
			
		
		unset($cond);
		$cond['Alias.domain_id'] = $this->Session->read('domain_id');
		$cond['Alias.status'] = '0';
			
		// if 30 days trial is over
		if($this->check_alias_date()) {
			
			$senderid = $this->Alias->findAll($cond);
			$this->set('senderid', $senderid);
			$this->set('trial_expired', true);
				
		} else {

			$this->Alias->unbindAll();
			$senderid = $this->Alias->findAll($cond);
			$this->set('senderid', $senderid);
			$this->set('trial_expired', false);

		}

		//get secret key
		//$this->Domain->id = $this->Session->read('domain_id');
		//$secret_key = $this->Domain->field('secret_key');
		
		unset($cond);
		$this->Domain->recursive = -1;
		$cond['conditions']['Domain.id'] = $this->Session->read('domain_id');
		$cond['conditions']['Domain.status'] = 0;
		$cond['fields'] = array('Domain.name', 'Domain.secret_key', 'Domain.ip');
		$data = $this->Domain->find('all', $cond);
		 
		$this->set('secret_key', $data['0']['Domain']['secret_key']);
		
		// check if the domain is hosted or not
		if($data['0']['Domain']['name'] == $data['0']['Domain']['ip']) $this->set('no_ip', true);
		else $this->set('no_ip', false);
		
		$this->set('domain_ip', $data['0']['Domain']['ip']);
			
	}
	
	function checkipaddress() {
		
		$this->checkAccess();
		
		$this->Domain->id = $this->Session->read('domain_id');
		$name = $this->Domain->field('name');
		
		$ip = $this->getDomainIP($name);

		$this->Domain->id = $this->Session->read('domain_id');
		$this->Domain->saveField('ip', $ip);
		
		$this->Session->write('success', 'IP Address checked successfully');
		$this->redirect('/users/myaccount');
		
	}
	
	function profile() {
 		
 		$this->checkAccess();
 		
 		$cond['conditions']['Userpersonalinfo.user_id'] = $this->Session->read('user_id');
 		$data = $this->Userpersonalinfo->find('all', $cond);
 		
 		if(!empty($data)) {
 			$userpersonalinfo_id = $data['0']['Userpersonalinfo']['id'];
 			$data = $data['0']['Userpersonalinfo'];
 		} else {
 			$userpersonalinfo_id = null;
 			$data = null;
 		}
 		
 		if(isset($this->data)) {
 			
 			$firstname = filter_var(trim($this->data['Userpersonalinfo']['firstname']), FILTER_SANITIZE_STRING);
 			$lastname = filter_var(trim($this->data['Userpersonalinfo']['lastname']), FILTER_SANITIZE_STRING);
 			$email = filter_var(trim($this->data['Userpersonalinfo']['alternate_email']), FILTER_SANITIZE_EMAIL);
 			$mobile = filter_var(trim($this->data['Userpersonalinfo']['alternate_mobile']), FILTER_SANITIZE_NUMBER_INT);
 			$address = filter_var(trim($this->data['Userpersonalinfo']['address']), FILTER_SANITIZE_STRING);

 			if(!$this->checkIfEmail($email)) $error[] = 'Invalid Email Address';
 			if(strlen($mobile) <> 10) $error[] = 'Invalid Mobile Number';
 			else if(!$this->checkNumber($mobile)) $error[] = 'Invalid Mobile Number';
 			
 			if(!isset($error)) {
 				
 				$s['firstname'] = $firstname;
 				$s['lastname'] = $lastname;
 				$s['alternate_email'] = $email;
 				$s['alternate_mobile'] = $mobile;
 				$s['address'] = $address;
 				$s['user_id'] = $this->Session->read('user_id');
 				$this->Userpersonalinfo->id = $userpersonalinfo_id;
 				$this->Userpersonalinfo->save($s);
 				
 				$this->Session->write('success', 'Profile details changed successfuly');
				$this->redirect('/users/profile');
				exit;
				
 			} else {
 				
 				$this->set('error', $error);
 				
		 		$data['firstname'] = $firstname;
		 		$data['lastname'] = $lastname;
		 		$data['alternate_email'] = $email;
		 		$data['alternate_mobile'] = $mobile;
		 		$data['address'] = $address;
 		
 			}
 		}
 		
 		if($this->Session->check('success')) {
 			
 		 	$this->set('success', $this->Session->read('success'));
 			$this->Session->delete('success');
 			
 		}
 		
 		$this->set('data', $data);
 		$this->layout = 'after_login';
		$this->set('tab', array('2'));
 	
 	}

	function buysid($id) {
		$this->Session->write('buysenderid_id', $id);
		$this->redirect('/users/buysenderid');
	}
	
	function buysenderid() {
	
		$this->checkAccess();

		if(!$this->Session->check('buysenderid') && !$this->Session->check('buysenderid_id')) {
			$this->redirect($this->referer());
			exit;
		}
			
		
		if(isset($this->data)) {
			
			if($this->Session->check('buysenderid')) {
				
				$v['name'] =  $this->Session->read('buysenderid');
				$v['domain_id'] = $this->Session->read('domain_id');
				$this->Alias->save($v);
				$senderid = $this->Alias->getLastInsertId();
				
			} else $senderid = $this->Session->read('buysenderid_id');
			
			unset($v);
			$v['alias_id'] = $senderid;
			$v['amount'] = '3000';
			$v['validtill'] = date('Y-m-d H:i:s', strtotime('+1 year'));
			$this->AliasInvoice->save($v);
			
			unset($v);
			$v['alias_id'] = $senderid;
			$v['alias_invoice_id'] = $this->AliasInvoice->getLastInsertId();
			$this->AliasBuy->save($v);
			
			$this->Session->delete('buysenderid');
			$this->Session->delete('buysenderid_id');
			
			$this->Session->write('success', 'Transaction was successfull, Please note it will take some time for the new Sender ID to get activated. Once activated you will be notified via email');
			$this->redirect('/users/myaccount');
			
		} else {

			$this->set('senderid', $this->Session->read('buysenderid'));
			$this->layout = 'after_login';
			$this->set('tab', array('2'));
			//$this->getFeedback();
		}
	}
	
	function deletealias($id) {
			
		$this->Alias->updateAll(
			array('Alias.status' => '1'),
			array('Alias.id' => $id, 'Alias.domain_id' => $this->Session->read('domain_id'))
		);
			
		$this->Session->write('success', 'Secder ID deleted successfully');
		$this->redirect($this->referer());
			
	}

	function changesecretkey() {
			
		$secret_key = $this->getSecretKey($this->Session->read('domain_name') . date('Y-m-d H:i:s'));
		$this->Domain->id = $this->Session->read('domain_id');
		$this->Domain->saveField('secret_key', $secret_key);
			
		$this->Session->write('secretkey_success', 'Secret Key changed successfully');
		$this->redirect($this->referer());
			
	}

	function onforgotpassword() {
			
		$this->set('tab', array('1'));
		$this->getFeedback();
			
	}

	function forgotpassword() {
			
		$this->onforgotpassword();
		$this->layout = 'before_login';
		
		if(isset($this->data)) {
			
			if(!empty($this->data['User']['name']) && !empty($this->data['User']['email'])) {
					
				if($this->data['User']['type'] == 'developer') {

					$condition['conditions']['User.name'] = $this->data['User']['name'];
					$condition['conditions']['User.email'] = $this->data['User']['email'];
					$condition['conditions']['User.status'] = 0;
					$result = $this->User->find('all', $condition);

					if(count($result) > 0) {
						$user = $result['0']['Domain']['name'];
						$pass = $result['0']['User']['password'];
						$email = $result['0']['User']['email'];
					} else {
						unset($condition);
						$condition['conditions']['UserTmp.name'] = $this->data['User']['name'];
						$condition['conditions']['UserTmp.email'] = $this->data['User']['email'];
						$condition['conditions']['UserTmp.status'] = 0;
						$result = $this->UserTmp->find('all', $condition);

						if(count($result) > 0) {
							$user = $result['0']['UserTmp']['name'];
        	                                        $pass = $result['0']['UserTmp']['password'];
	                                                $email = $result['0']['UserTmp']['email'];
						} else {
							$error[] = 'Invalid Mobile number and Email Address provided';
							$this->set('error', $error);
						}
						$this->set('error', $error);
					}
					
					// check if suspended
					if(!empty($result['0']['User']['id'])) {
						$terminate = $this->checkUserSuspended($result['0']['User']['id']);
						if(!empty($terminate)) {
							$error[] = $terminate;
							$this->set('error', $error);
						}
					}
					
						
				} else if($this->data['User']['type'] == 'advertiser') {
						
					if(ONLY_DEVELOPER_SECTION) return false;

					$condition['conditions']['Advertiser.mobile'] = $this->data['User']['name'];
					$condition['conditions']['Advertiser.email'] = $this->data['User']['email'];
					$result = $this->Advertiser->find('all', $condition);
						
					if(count($result) > 0) {
						$user = $adv_result['0']['Advertiser']['fname'] .' '. $adv_result['0']['Advertiser']['lname'];
						$pass = $adv_result['0']['Advertiser']['password'];
						$email = $adv_result['0']['Advertiser']['email'];
					} else {
						$error[] = 'Invalid Mobile number and Email Address provided';
						$this->set('error', $error);
					}

				} else if($this->data['User']['type'] == 'bulksms') {
						
					$condition['conditions']['BulkUserpersonalinfo.mobile'] = $this->data['User']['name'];
					$condition['conditions']['BulkUserpersonalinfo.email'] = $this->data['User']['email'];
					$condition['conditions']['BulkUserpersonalinfo.status'] = 0;
					$result = $this->BulkUser->find('all', $condition);

					if(count($result) > 0) {
						$user = $result['0']['BulkUserpersonalinfo']['firstname'].' '.$result['0']['BulkUserpersonalinfo']['lastname'];
						$pass = $result['0']['BulkUser']['password'];
						$email = $result['0']['BulkUserpersonalinfo']['email'];
					} else {
						$error[] = 'Invalid Mobile number and Email Address provided';
						$this->set('error', $error);
					}

				} else {

					$error[] = 'Invalid Mobile number and Email Address provided';
					$this->set('error', $error);

				}

				if(!isset($error)) {

					$this->_sendForgotPassword($user, $pass, $email);
						
					$success = 'Your password is mailed to <strong>'.$email.'</strong>';
					$this->Session->write('success', $success);
					$this->redirect('/users/forgotpassword');
					exit;

				}
					
				/*if(!empty($user_result)) {

				//send forgot password for user
				$this->_sendForgotPassword($user_result['0']['Domain']['name'], $user_result['0']['User']['password'], $user_result['0']['User']['email']);

				$success = 'Your password is mailed to <strong>'.$user_result['0']['User']['email'].'</strong>';
				$this->Session->write('success', $success);
				$this->redirect('/users/forgotpassword');
				exit;
					
				} else if($adv_result) {

				//send forgot password for advertiser
				$fullname = $adv_result['0']['Advertiser']['fname'] .' '. $adv_result['0']['Advertiser']['lname'];
				$this->_sendForgotPassword($fullname, $adv_result['0']['Advertiser']['password'], $adv_result['0']['Advertiser']['email']);

				$success = 'Your password is mailed to <strong>'.$adv_result['0']['Advertiser']['email'].'</strong>';
				$this->Session->write('success', $success);
				$this->redirect('/users/forgotpassword');
				exit;

				} else {

				$error[] = 'Invalid Mobile number and Email Address provided';
				$this->set('error', $error);

				}*/
					
			} else {
					
				$error[] = 'Invalid Mobile number and Email Address provided';
				$this->set('error', $error);
					
			}

		} else {

			if($this->Session->check('success')) {
					
				$this->set('success', $this->Session->read('success'));
				$this->Session->delete('success');
					
			}

		}
			
	}

	function savePlan() {	exit;

	$this->checkAccess('developer');

	//print_r($this->data);
	if($this->findImageOnWebsite($this->data['User']['plan_id'], $this->Session->read('domain_name'))) {

		$this->Domain->id = $this->Session->read('domain_id');
		$this->Domain->saveField('plan_id', $this->data['User']['plan_id']);

		// insert when plan is change
		$value['domain_id'] = $this->Session->read('domain_id');
		$value['plan_id'] = $this->data['User']['plan_id'];
		$this->History->save($value);

		// insert when plan is change
		if($this->data['User']['plan_id'] != '1') {
			$value['domain_id'] = $this->Session->read('domain_id');
			$value['image_found'] = true;
			$this->Monitor->save($value);
		}

		$success = 'Plan saved successfully';
		$this->set('success', $success);
		$this->view();
		$this->render('/users/view');
			
	} else {
			
		$error = 'ERROR : Unable to find the respective CODE on your website';
		$this->set('error', $error);
		$this->view();
		$this->render('/users/view');
			
	}
	$this->autoRender = false;
		
	}

	//if no sms is recieved start the registration process again
	function newreg() {
			
		if($this->Session->read('registrationcomplete')) {

			$this->UserTmp->delete($this->Session->read('user_tmp_id'));
			$this->Session->destroy();

		}
			
		$this->redirect('/users/registration');
			
	}

	function registration() {
			
		$this->onregistration();
		$this->layout = 'before_login';
			
		if(isset($this->data)) {

			// check whether below 3 are valid, if not return back with an error
			$mobile = filter_var(trim($this->data['UserTmp']['name']), FILTER_SANITIZE_STRING);
			$email = filter_var(trim($this->data['UserTmp']['email']), FILTER_SANITIZE_EMAIL);
			$domainOG = filter_var(trim($this->data['UserTmp']['domain']), FILTER_SANITIZE_URL);
			$referral = filter_var(trim($this->data['UserTmp']['referral']), FILTER_SANITIZE_URL);

			//$domainArr = explode('/',$domainOG);
			//$domain = $domainArr['0'];
			$domain = $domainOG;
			if(strpos($domain, 'http://') === 0) $domain = str_replace('http://', '', $domain);
			if(strpos($domain, 'www.') === 0) $domain = str_replace('www.', '', $domain);
			if(strpos($domain, '/') === strlen($domain)-1) $domain = str_replace('/', '', $domain); // at the end of domain
			
			if(!empty($referral)) {
				if(strpos($referral, 'http://') === 0) $referral = str_replace('http://', '', $referral);
				if(strpos($referral, 'www.') === 0) $referral = str_replace('www.', '', $referral);
				if(strpos($referral, '/') === strlen($referral)-1) $referral = str_replace('/', '', $referral); // at the end of domain
			}
			
			if(!is_numeric($mobile)) $error[] = 'Mobile number is invalid';
			else if(strlen($mobile) <> 10) $error[] = 'Mobile number should be of 10 digits';
			else if(!$this->checkNumber($mobile)) $error[] = 'Only Indian mobiles are allowed';

			if(!$email) $error[] = 'Email Address is required';
			else if(!$this->checkIfEmail($email)) $error[] = 'Email Address is invalid';

			if(!$this->checkIfLink($domain)) $error[] = 'Domain name is invalid';
			else if(!$this->checkDomain($domain)) $error[] = 'Free Domains are not allowed';
			else if(!$this->checkSubdomain($domain)) $error[] = 'Sub Domains are not allowed';
			//else if(!$this->checkDomainIP($domain)) $error[] = 'No IP address found against this domain';


			$domain_from_email = substr($email, strpos($email, '@')+1);
			if(strcmp($domain_from_email, $domain) <> 0) {
				$error[] = 'Email Address does not belong to the specified domain';
			}

			if(!isset($this->data['UserTmp']['agree']) || $this->data['UserTmp']['agree'] != 'on') {
				$error[] = 'You have to agree to the terms and condition as specified';
			}


			// check if email, domain or mobile number is used or not
			$this->User->recursive = -1;
			if($this->UserTmp->find('count', array('conditions'=>array('name'=>$mobile,'status'=>0))) ||
				$this->User->find('count', array('conditions'=>array('name'=>$mobile,'status'=>0))))
			$error[] = 'Mobile number is already in use';
			
			$this->User->recursive = -1;
			if($this->UserTmp->find('count', array('conditions'=>array('email'=>$email,'status'=>0))) ||
				$this->User->find('count', array('conditions'=>array('email'=>$email,'status'=>0))))
			$error[] = 'Email Address is already in use';
			
			$this->Domain->recursive = -1;
			if($this->UserTmp->find('count', array('conditions'=>array('domain'=>$domain,'status'=>0))) ||
				$this->Domain->find('count', array('conditions'=>array('Domain.name'=>$domain,'Domain.status'=>0))))
			$error[] = 'Domain is already in use';

			// check recaptcha
			App::import('Vendor', 'recaptchalib');
			$privatekey = CAPTCHA_PRIVATE_KEY;
			$resp = recaptcha_check_answer ($privatekey,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);

			if (!$resp->is_valid) {
				$error[] = 'CAPTCHA wasn\'t entered correctly';
			}

			// if no error then save it
			if(!isset($error)) {
					
				$password = $this->generateRandomNumber(8);

				$value['name'] = $mobile;
				$value['domain'] = strtolower($domain);
				$value['email'] = strtolower($email);
				$value['password'] = $password;
				$value['referral'] = $referral;
				
				/*	Notify Admin	*/
				$to_admins['type'] = 'Registration';
				$to_admins['data'] = $value;
				$this->notifyAdmin($to_admins);
				
				/*	Save Data	*/
				if($this->UserTmp->save($value)) {


					// send registration details on email
					$this->_sendRegistration($domain, $mobile, $email, $password);
					
					$this->Session->start();
					$this->Session->write('registrationcomplete', true);
					$this->Session->write('mobile', $mobile);
					$this->Session->write('email', $email);
					$this->Session->write('user_tmp_id', $this->UserTmp->getLastInsertId());

					$this->redirect('/users/registrationcomplete');
					exit;

				} else 	$error[] = 'Problem saving data';

			}

			if(isset($error)) {
					
				//echo $absolute_url  = Router::url('/', true);echo $this->referer();
				$checkbox = (isset($this->data['UserTmp']['agree']) && $this->data['UserTmp']['agree'] == 'on') ? 'checked="checked"' : '';
				$this->set('error', $error);
				$this->set('mobile', $mobile);
				$this->set('email', $email);
				$this->set('domain', $domainOG);
				$this->set('referral', $referral);
				$this->set('checkbox', $checkbox);
					
			}

		}
		
		if(isset($_GET['ref']) && !empty($_GET['ref'])) $this->set('ref', $_GET['ref']);

	}

	function registrationcomplete() {
			
		if($this->Session->read('registrationcomplete')) {

			$this->layout = 'before_login';
			$this->set('mobile', $this->Session->read('mobile'));
			$this->set('email', $this->Session->read('email'));
			$this->set('tab', array('1'));
			$this->getFeedback();

		} else {

			$this->redirect('/users/registration');

		}
	}

	function tnc() {
			
		$this->layout = 'before_login';
		$this->set('tab', array('1'));
		$this->getFeedback();

	}

	function showreport() {

		$this->checkAccess('developer');
			
		$this->layout = 'after_login';
		$this->Domain->id = $this->Session->read('domain_id');
		$server = $this->Domain->field('server');
			
		$date1 = date('Y-m-d');
		$date2 = date('Y-m-d');
		$status = '';
		$name = '';

		if(isset($this->data)) {

			if(!empty($this->data['date1']) && !empty($this->data['date2'])) {

				$date1 = date('Y-m-d', strtotime($this->data['date1']));
				$date2 = date('Y-m-d', strtotime($this->data['date2']));
					
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
					$condition['conditions']['name LIKE'] = $name . '%';
						
				}
					
			} else {

				$error[] = 'Please select a date';
					
			}

		}
			
		$condition['conditions']['domain_id'] = $this->Session->read('domain_id');
		$condition['conditions']['0'] = array('created BETWEEN ? AND ?' => array($date1, $date2));
		$condition['fields'] = array('name', 'message', 'response_status', 'created');
		$condition['order']['id'] = 'desc';
		$this->Message->setSource($server.'_log');
		$data = $this->Message->find('all', $condition);
			
		/*$day = $month = $year = '';
		 for($i=1; $i<32; $i++) {
		 if($d['d'] == $i) $day .= '<option value='.$i.' selected>'.$i.'</option>';
		 else $day .= '<option value='.$i.'>'.$i.'</option>';
		 }
		 	
		 $montharr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		 for($i=0; $i<count($montharr); $i++) {
		 if($d['m'] == $i+1) $month .= '<option value='.($i+1).' selected>'.$montharr[$i].'</option>';
		 else $month .= '<option value='.($i+1).'>'.$montharr[$i].'</option>';
		 }
		 	
		 	
		 for($i=2010; $i<2012; $i++) {
		 if($d['y'] == $i) $year .= '<option value='.$i.' selected>'.$i.'</option>';
		 else $year .= '<option value='.$i.'>'.$i.'</option>';
		 }
		 	
		 $this->set('day', $day);
		 $this->set('month', $month);
		 $this->set('year', $year);*/
		//$this->set('report_for', $report_for);
			
		$date1 = date('d-m-Y', strtotime($date1));
		$date2 = date('d-m-Y', strtotime($date2));
			
		$this->set('data', $data);
		$this->set('date1', $date1);
		$this->set('date2', $date2);
		$this->set('status', $status);
		$this->set('name', $name);
		$this->set('tab', array('3'));
		//$this->getFeedback();
			
	}

	function uc() {
			
		// under construction
			
	}

	/*	verify mobile number after login	*/
	function verify_mobile($step) {
		
		$this->checkAccess('developer');
		
		if(FREE_SMS_SERVER_DOWN) {
			$error = UNAVAILABLE_MESSAGE;
			$this->Session->write('error', $error);
			$this->redirect($this->referer());
			exit;
		}
		
		if(NINE_TO_NINE_ACTIVATED) {
			$error = NINE_TO_NINE_ACTIVATED_MESSAGE;
			$this->Session->write('error', $error);
			$this->redirect($this->referer());
			exit;
		}
		
		if($step == 1) {
			
			$mobile = $this->data['mobile'];
			if(!is_numeric($mobile)) $error[] = 'Mobile number is invalid';
			else if(strlen($mobile) <> 10) $error[] = 'Mobile number should be of 10 digits';
			else if(!$this->checkNumber($mobile)) $error[] = 'Only Indian mobiles are allowed';
					
			if($this->UserTmp->find('count', array('conditions'=>array('UserTmp.name'=>$mobile, 'UserTmp.domain NOT'=>$this->Session->read('domain_name')))))
				$error[] = 'Mobile number is already in use';
			
			if(isset($error)) $this->Session->write('error', $error);
			else {
				
				// send a unique code to mobile
				$code = $this->generateRandomNumber(4);
				$message = 'Freesmsapi.com mobile number verification code - '.$code;
				$this->_sendInternalSMS($mobile, $message, INTERNAL_SMS_SENDER_ID);
				
				// insert into db
				$this->User->id = $this->Session->read('user_id');
				$this->User->set(array(
					'verifycode' => $code,
					'verifymobile' => $mobile
				));
				$this->User->save();
				
				$this->Session->write('success', 'Verification code is send on mobile number '.$mobile.'.');
			}
			
			$this->Session->write('verify_user_name', $mobile);
		
		} else if($step == 2) {

			$this->User->id = $this->Session->read('user_id');
			$verifycode = $this->User->field('verifycode');
			
			if($this->data['verifycode'] != $verifycode) {
				
				$this->Session->write('error', 'Invalid verification code entered');
				
			} else {
				
				$this->Session->write('verified', 1);
				$this->Session->write('verifymobile', $this->Session->read('verify_user_name'));
				$this->User->id = $this->Session->read('user_id');
				$this->User->saveField('verify', 1);

				$this->Session->write('success', 'Congragulations, Your mobile number was verified successfully.');
				
			}
			
			
		} else if($step == 3) {
			
			// resend the code
			$c['conditions']['User.id'] = $this->Session->read('user_id');
			$c['conditions']['User.status'] = 0;
			$c['fields'] = array('User.verifycode', 'User.verifymobile');
			$verifydata = $this->User->find('all', $c);
			
			$message = 'Freesmsapi.com mobile number verification code - '.$verifydata['0']['User']['verifycode'];
			$this->_sendInternalSMS($verifydata['0']['User']['verifymobile'], $message, INTERNAL_SMS_SENDER_ID);
			
			$this->Session->write('success', 'Verification code is send on mobile number '.$verifydata['0']['User']['verifymobile'].'.');
			
		} else if($step == 4) {
			
			// begin again
			$this->User->updateAll(
				array('User.verifycode' => null, 'User.verifymobile' => null),
				array('User.id' => $this->Session->read('user_id'))
			);
			
		}
		
		$this->redirect($this->referer());
		$this->autoRender = false;
		
	}
	
	function _sendInternalSMS($recipient, $message, $sender_id) {
			
		//get sms vendor details
 		$this->getSmsVendor($this->Session->read('domain_id'));
 		//pr($this->sms_vendor_details);
 			
		$this->sendSMS($recipient, $message, $sender_id, true);
		$this->autoRender = false;
			
	}

	function _sendRegistration($domain, $mobile, $email, $data) {
			
		$subject = 'Login Information';
		$message = 'Dear user,'.
					SEPERATOR . SEPERATOR .
 					'Thank you for registering at '.SERVERNAME.'. You may now log in using the following username and password.'.
					SEPERATOR . SEPERATOR .
 					'username : '.$mobile.
					SEPERATOR .
 					'password : '.$data.
					SEPERATOR . SEPERATOR .
 					'You may also log in by clicking on this link or copying and pasting it in your browser.'.
					SEPERATOR . SEPERATOR .
 					'<a href="'.SERVER.'users/login" target="_blank">'.SERVER.'users/login</a>'.
					SEPERATOR . SEPERATOR .
 					'After logging in, please change your password by selecting "My Account" tab from top navigation bar.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
			
		$this->sendMail($email, $subject, $message);
		//$this->write($subject);$this->write($message);
		$this->autoRender = false;
			
	}

	function _sendForgotPassword($domain, $password, $email) {

		$subject = 'Login Information';
		$message = 'Dear user,'.
					SEPERATOR . SEPERATOR .
					'A request to recover the password for your account has been made by '.SERVERNAME.'.'.
					SEPERATOR . SEPERATOR .
 					'password : '.$password.
					SEPERATOR . SEPERATOR .
 					'You may log in by clicking on this link or copying and pasting it in your browser.'.
					SEPERATOR . SEPERATOR .
 					'<a href="'.SERVER.'users/login" target="_blank">'.SERVER.'users/login</a>'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
			
		$this->sendMail($email, $subject, $message);
			
		$this->autoRender = false;

	}

	function _sendUpgrade($domain, $email) {

		$subject = 'SMS UPGRADE REQUEST';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for contacting Freesmsapi. This email confirms that we have received your request to upgrade your SMS plan. Someone from our team will be in touch with you as quickly as possible.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
			
		$this->sendMail($email, $subject, $message);
			
		$this->autoRender = false;

	}

	function _senderIdMail($email) {

		$subject = 'Sender ID Request Status';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'Thank you for your continued support. This email confirms that your Sender ID request has '.
					'been received. Your Sender ID will be activated within 24 hrs. Please do contact us at '.
					INTERNAL_SENDER .' if your Sender ID is not activated within 24 hrs.'.
					SEPERATOR . SEPERATOR .
					'Kindly note that this free Sender ID is offered for a limited period of '.ALIAS_TRIAL_PERIOD.' days from the day of your first activation.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
			
		$this->sendMail($email, $subject, $message);
			
		$this->autoRender = false;
	}

	function pricingsenderid() {
		
		/*if(SHOW_ONLY_TO_ME) {
			$this->redirect('/users/buysenderid');
			exit;
		}*/
		
		$this->checkAccess('developer');
		$this->layout = 'after_login';
		
		$this->set('tab', array('7'));
		//$this->getFeedback();
		
	}
	
	function pricing() {

		//if($this->isLogin('developer'))  $this->layout = 'after_login';
		/*else*/ $this->layout = 'before_login';
		
		//session_regenerate_id();
		
		$cond['conditions']['Pricing.status'] = 0;
		$cond['conditions']['Pricing.validity'] = 6;
		$package1 = $this->Pricing->find('all', $cond);
		
		$cond['conditions']['Pricing.validity'] = 12;
		$package2 = $this->Pricing->find('all', $cond);
		
		$this->set('app', $this);
		$this->set('package1', $package1);
		$this->set('package2', $package2);
		$this->set('tab', array('7'));
		$this->getFeedback();

	}
	
	function confirmPayment() {

		//if(!SHOW_PAYMENT_MODULE) return false;

		$this->disableCache();
		
		//if($this->isLogin('developer'))  $this->layout = 'after_login';
		/*else*/ $this->layout = 'before_login';
		
		$session_id = session_id();
		
		if(isset($this->data['payment1'])) $id = $this->data['payment1'];
		else if(isset($this->data['payment2'])) $id = $this->data['payment2'];
		else {
			$this->redirect($this->referer());
			exit;
		}
		
		$cond['conditions']['status'] = 0;
		$cond['conditions']['id'] = $id;
		$data = $this->Pricing->find('all', $cond);
		
		$servicetax = ceil(($data['0']['Pricing']['totalcost'] * SERVICE_TAX) / 100);
		//$amount = ceil($servicetax + $data['0']['Pricing']['totalcost']);
		$amount = $data['0']['Pricing']['totalcost']; // NO SERVICE TAX
		
		/*unset($cond);
		$cond['conditions']['MerchantOrder.status'] = 0;
		$cond['conditions']['MerchantOrder.session_id'] = $session_id;
		$cond['conditions']['MerchantOrder.ip'] = ip2long($_SERVER['REMOTE_ADDR']);
		$merchant_order_data = $this->MerchantOrder->find('all', $cond);
		
		if(!empty($merchant_order_data)) {
			//update
			$merchant_order_id = $merchant_order_data['0']['MerchantOrder']['id'];
			$this->MerchantOrder->updateAll(
				array('pricing_id'=> $id, 'amount'=>$amount),
				array('id' => $merchant_order_id)
			);
			
		} else {*/
		
			//insert new
			$save['session_id'] = $session_id;
			$save['pricing_id'] = $id;
			$save['amount'] = $amount;
			$save['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$this->MerchantOrder->save($save);
			$merchant_order_id = $this->MerchantOrder->getLastInsertId();

		//}
		
		$this->Session->write('pricing_id', $id);
		$this->Session->write('merchant_order_id', $merchant_order_id);
		$this->Session->write('amount', $amount);
		
		
		$this->set('reference_id', $merchant_order_id);
		$this->set('amount', $amount);
		$this->set('servicetax', $servicetax);
		$this->set('app', $this);
		$this->set('data', $data['0']['Pricing']);
		$this->set('tab', array('7'));
		$this->getFeedback();
		
	}
	
	function getPaymentDetails() {
		
		if(!SHOW_PAYMENT_MODULE) return false;
		
		//check fingerprint
		
		$pricing_id = $this->Session->read('pricing_id');
		$merchant_order_id = $this->Session->read('merchant_order_id');
		$paymentemail = trim($_REQUEST['paymentemail']);
		$session_id = session_id();
		
		if(empty($pricing_id)) {
			echo 0;
			exit;
		}
		
		if(!$this->checkIfEmail($paymentemail)) {
			echo 1;
			exit;
			
		} else {
			//insert email
			$this->MerchantOrder->updateAll(
				array('email'=> '"'. $paymentemail .'"'),
				array('id'=> $merchant_order_id)
			);
		}
		
		$cond['conditions']['status'] = 0;
		$cond['conditions']['id'] = $pricing_id;
		$pricing_data = $this->Pricing->find('all', $cond);
		
		$amount = $this->Session->read('amount');
		
		if(!empty($pricing_data)) {
			
			$data["MID"] = "200904281000001";
			$data["Operating Mode"] = "DOM";
			$data["Country"] = "IND";
			$data["Currency"] = "INR";
			$data["Amount"] = $amount;
			$data["Merchant Order No"] = $merchant_order_id;
			$data["Other Details"] = $pricing_id;
			$data["Success URL"] = DIRECPAY_SUCCESS_URL;
			$data["Failure URL"] = DIRECPAY_FAILURE_URL;
			$data["Collaborator"] = DIRECPAY_COLLABORATOR;
			
			$data = implode('|', $data);
			$data = $this->direcpayEncode($data);
			
			echo $data;
			
		}
	
		$this->autoRender = false;
		
	}
	
	function paymentresponse($state) {
		
		if(!SHOW_PAYMENT_MODULE) return false;
		
		/*if($this->isLogin('developer'))  $this->layout = 'after_login';
		else*/ $this->layout = 'before_login';
		pr($_REQUEST);
		
		if(!empty($_REQUEST['responseparams'])) {
		
			$response = $_REQUEST['responseparams'];
			$responseparams = explode('|', $response);
			$responseparams = array_filter($responseparams);
			//$flag = $_REQUEST['flag'];
			
			$direcpay_reference_id = $responseparams[1];
			$resp_flag = $responseparams[1];
			$pricing_id = $responseparams[4];
			$merchant_order_id = $responseparams[5];
			$amount = $responseparams[6];
			//$amount = 500;
			
			// update with response from direcpay
			$this->MerchantOrder->updateAll(
				array('direcpay_response' => '"'.$response.'"', 'resp_amount' => $amount,
						'flag' => '"'.$resp_flag.'"', 'dp_ref_id' => $direcpay_reference_id),
				array('id' => $merchant_order_id)
			);
			
			pr($responseparams);

		} else {
			
			/*	Notify Admin	*/
			$to_admins['type'] = 'Bulk Registration';
			$to_admins['data'] = array('Got an empty response');
			$this->notifyAdmin($to_admins);
			
		}
		
		
		if($state === 0) {
			
			// show transaction failed page

			/*	Notify Admin	*/
			$to_admins['type'] = 'Bulk Registration';
			$to_admins['data'] = array('Transaction failed for state '.$state.' and params '.$_REQUEST['responseparams']);
			$this->notifyAdmin($to_admins);
			
		} else if($state === 1) {
			
			if($resp_flag == 'SUCCESS') {
				
				unset($cond);
				$cond['conditions']['MerchantOrder.status'] = 0;
				$cond['conditions']['MerchantOrder.id'] = $merchant_order_id;
				$merchant_data = $this->MerchantOrder->find('all', $cond);
				
				pr($merchant_data); 
				
				// check if given amount is same as the response amount
				if(round($amount) != round($merchant_data[0]['MerchantOrder']['amount'])) {

					// show transaction failed with message of suspicious activity and ask to contact support team	

					/*	Notify Admin	*/
					$to_admins['type'] = 'Bulk Registration';
					$to_admins['data'] = array('Suspicious activity detected for state '.$state.' and params '.$_REQUEST['responseparams']);
					$this->notifyAdmin($to_admins);
				
				} else {
				
					// send mail to the user with login instructions
					unset($cond);
					$cond['conditions']['Pricing.status'] = 0;
					$cond['conditions']['Pricing.id'] = $pricing_id;
					$pricing_data = $this->Pricing->find('all', $cond);
				
					unset($data);
					$data['merchant_order_id'] = $merchant_order_id;
					$data['amount'] = $amount;
					$data['costpersms'] = $pricing_data[0]['Pricing']['cost'];
					$data['quantity'] = $pricing_data[0]['Pricing']['credit'];
					$data['activation_key'] = md5(md5(date('YmdHis').SALT).date('YmdHis').SALT);
					$data['validtill'] = date('Y-m-d', strtotime("+ ".$pricing_data[0]['Pricing']['validity']." months"));
					$this->BulkAccountRegister->save($data);
					
					$url = SERVER.'bulksms/register/'.$data['activation_key'];
					
					$this->_sendBulkRegistrationEmail($url, $merchant_data[0]['MerchantOrder']['email']);

					// show success page
					
					/*	Notify Admin	*/
					$to_admins['type'] = 'Bulk Registration';
					$to_admins['data'] = array('Transaction successfull for state '.$state.' and params '.$_REQUEST['responseparams']);
					$this->notifyAdmin($to_admins);
					
				}
				
			} else {
				
				// show transaction failed page 
				
				/*	Notify Admin	*/
				$to_admins['type'] = 'Bulk Registration';
				$to_admins['data'] = array('Transaction failed for state '.$state.' and params '.$_REQUEST['responseparams']);
				$this->notifyAdmin($to_admins);
				
			}
		
		}
		
		exit;
		$this->set('tab', array('7'));
		$this->getFeedback();
		
	}
	
	function manual_recon() {
		
		pr($_REQUEST);
		exit;
		
	}
	
	function delete() {
		
		$this->checkAccess('developer');
		
		if(isset($this->data)) {
			
			$reason = filter_var(trim($this->data['reason']), FILTER_SANITIZE_STRING);
			$password = filter_var(trim($this->data['password']), FILTER_SANITIZE_STRING);
			
			if(empty($reason)) $error[] = 'Reason is compulsory'; 
			
			$c['conditions']['User.id'] = $this->Session->read('user_id');
			$c['conditions']['User.password'] = $password;
			$c['conditions']['User.status'] = 0;
			if($this->User->find('count', $c)) {

				$this->User->updateAll(
					array('password' => '"'.md5(time()).'"', 'status' => 1, 'reasontodelete' => '"'.$reason.'"'),
					array('id' => $this->Session->read('user_id'))
				);
				
				$this->Domain->unBindAll();
				$this->Domain->updateAll(
					array('secret_key' => '"'.md5(time()).'"', 'status' => 1),
					array('id' => $this->Session->read('domain_id'))
				);
				
				$this->Session->destroy();
				$this->set('success', 'Your account has been successfully deleted.<br/>You will be redirected in 5 seconds.');
				$this->set('redirect', '<script>setTimeout(\'window.location.replace("'.SERVER.'");\', 5000);</script>');
			
			} else $error[] = 'Incorrect password entered';
		}
		
		if(isset($error)) {
			$this->set('error', $error);
			$this->set('reason', $reason);
		}
		
		$this->layout = 'after_login';
		$this->set('tab', array('2'));
		//$this->getFeedback();

	}
	
	function _getReferralProgram($referral_domain_id) {
 		
 		$c['conditions']['User.referral'] = $referral_domain_id;
 		$c['conditions']['User.status'] = 0;
 		$this->User->recursive = -1;
 		$count = $this->User->find('count', $c);
 		
 		unset($c);
 		$c['conditions']['ReferralProgram.status'] = 0;
 		$c['conditions']['ReferralProgram.points'] = $count;
 		$c['fields'] = array('ReferralProgram.points', 'ReferralProgram.plan_id');
 		$data = $this->ReferralProgram->find('list', $c);
 		
 		if(!empty($data)) {
 			
 			// upgrade plan
 			$this->Domain->id = $referral_domain_id;
 			$this->Domain->saveField('plan_id', $data[$count]);
 			
 			// update history
 			$value['domain_id'] = $referral_domain_id;
			$value['plan_id'] = $data[$count];
			$this->History->save($value);
			
			/*	Notify Admin	*/
			$to_admins['type'] = 'Referral Program Upgrade';
			$to_admins['data'] = $value;
			$this->notifyAdmin($to_admins);
 			
 			// send mail
 			$this->_sendReferralProgramMail($referral_domain_id, $data[$count]);
 			
 		}
 		
 		$this->autoRender = false;
 		
 	}
 	
	function _sendReferralProgramMail($domain_id, $plan_id) {
 		
 		$this->Domain->id = $domain_id;
 		$this->User->id = $this->Domain->field('user_id');
 		$email = $this->User->field('email');

 		$this->Plan->id = $plan_id;
 		$sms = $this->Plan->field('sms');
 		
 		$subject = 'Referral Program SMS Upgrade';
		$message = 'Dear User,'.
					SEPERATOR . SEPERATOR .
					'We are pleased to inform you that your SMS Quota has been increased to <strong>'.$sms.' SMS</strong> per day.'.
					'Refer more users and achieve the next level of SMS upgrade.'.
					SEPERATOR . SEPERATOR .
					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
 		
 		$this->autoRender = false;
 		
 	}
 	
 	function refprogram() {
 		
 		$this->checkAccess('developer');
		$this->layout = 'after_login';
				
 		$c['conditions']['ReferralProgram.status'] = 0;
 		$c['fields'] = array('ReferralProgram.points', 'ReferralProgram.plan_id');
 		$data = $this->ReferralProgram->find('list', $c);
 		
 		unset($c);
 		$c['conditions']['Plan.status'] = 0;
 		$c['fields'] = array('Plan.id', 'Plan.sms');
 		$this->Plan->recursive = -1;
 		$plan = $this->Plan->find('list', $c);
 		
 		unset($c);
 		$c['conditions']['User.status'] = 0;
 		$c['conditions']['User.referral'] = $this->Session->read('domain_id');
 		$currentstatus = $this->User->find('count', $c);
 		
 		$this->set('ref_link', SERVER . "users/registration?ref=" . $this->Session->read('domain_name'));
 		
 		$this->set('data', $data);
 		$this->set('plan', $plan);
 		$this->set('currentstatus', $currentstatus);
 		$this->set('tab', array('7'));
 		
 	}
 	
 	function referralprogram() {
 		
 		$this->layout = 'before_login';
				
 		$c['conditions']['ReferralProgram.status'] = 0;
 		$c['fields'] = array('ReferralProgram.points', 'ReferralProgram.plan_id');
 		$data = $this->ReferralProgram->find('list', $c);
 		
 		unset($c);
 		$c['conditions']['Plan.status'] = 0;
 		$c['fields'] = array('Plan.id', 'Plan.sms');
 		$this->Plan->recursive = -1;
 		$plan = $this->Plan->find('list', $c);
 		
 		$this->set('ref_link', SERVER . "users/registration?ref=YourDomain.com");
 		
 		$this->set('data', $data);
 		$this->set('plan', $plan);
 		$this->set('tab', array('1'));
 		$this->getFeedback();
 		
 	}
 	
 	function changeip($method=null, $domainip_id=null) {
 		
 		$this->checkAccess('developer');
 		$this->layout = 'after_login';
 		
 		if(isset($this->data)) {
 			
 			// check ip address limit
 			$cond['conditions']['DomainIp.domain_id'] = $this->Session->read('domain_id');
 			$cond['conditions']['DomainIp.status'] = 0;
 			if($this->DomainIp->find('count', $cond) >= IP_ADDRESS_LIMIT) {
 				$this->Session->write('error', 'Maximum of '.IP_ADDRESS_LIMIT.' valid secondary IP Address(s) is allowed');
 				$this->redirect('/users/changeip');
 				exit;
 			}
  			
 			
 			$ipaddress = ip2long($this->data['DomainIp']['ip']);
 			
 			if($ipaddress <= 0) {
 				$this->Session->write('error', 'Invalid IP Address');
 			} else {
 				
 				$this->Domain->id = $this->Session->read('domain_id');
 				$domain_ip = $this->Domain->field('ip');
 		
 				unset($cond);
 				$cond['conditions']['DomainIp.ip'] = $ipaddress;
 				$cond['conditions']['DomainIp.domain_id'] = $this->Session->read('domain_id');
 				$cond['conditions']['DomainIp.status'] = 0;
 				
 				if(ip2long($domain_ip) == $ipaddress || $this->DomainIp->find('count', $cond) > 0) {
 					$this->Session->write('error', 'Duplicate entry for IP Address '.long2ip($ipaddress));
 					
 				} else {
 					$data['ip'] = $ipaddress;
 					$data['domain_id'] = $this->Session->read('domain_id');
 					$this->DomainIp->save($data);
 					$this->Session->write('success', 'IP Address saved successfully');
 				}
 			}
 			$this->redirect('/users/changeip');
 			exit;
 		
 		} else if(!empty($method) && $method == 'delete' && !empty($domainip_id)) {
 			
 			$this->DomainIp->updateAll(
 				array('status' => 1),
 				array('domain_id' => $this->Session->read('domain_id'), 'id' => $domainip_id)
 			);
 			$this->Session->write('success', 'IP Address deleted successfully');
 			
 			$this->redirect('/users/changeip');
 			exit;
 			
 		}
 		
 		$this->Domain->id = $this->Session->read('domain_id');
 		$ips[0]['ip'] = $this->Domain->field('ip');
 		$ips[0]['id'] = '';
 		$ips[0]['created'] = '-';
 		$ips[0]['primary'] = 'YES';
 		
 		unset($cond);
 		$cond['conditions']['DomainIp.domain_id'] = $this->Session->read('domain_id');
 		$cond['conditions']['DomainIp.status'] = 0;
 		$secondaryips = $this->DomainIp->find('all', $cond);
 		
 		for($i=0; $i<count($secondaryips); $i++) {
 			$ips[$i+1]['id'] = $secondaryips[$i]['DomainIp']['id'];
 			$ips[$i+1]['ip'] = long2ip($secondaryips[$i]['DomainIp']['ip']);
 			$ips[$i+1]['created'] = date('j M Y g:i a', strtotime($secondaryips[$i]['DomainIp']['created']));
 			$ips[$i+1]['primary'] = 'NO';
 		}

 		if($this->Session->check('success')) {
			$this->set('success', $this->Session->read('success'));
			$this->Session->delete('success');
			
		} else if($this->Session->check('error')) {
			$this->set('error', $this->Session->read('error'));
			$this->Session->delete('error');
		}
 		
 		$this->set('ips', $ips);
 		$this->set('tab', array('2'));
 		
 	}
 	
}
?>