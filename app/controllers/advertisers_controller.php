<?php
/*
 * Created on Mar 11, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class AdvertisersController extends AppController {
 	
 	var $uses = array('Advertiser', 'AdvPlan', 'AdvContent', 'Category', 'ValidNumber', 'BlacklistDomain', 'Maillog');
  	
  	function beforeFilter() {
		
		if(ONLY_DEVELOPER_SECTION) {
			
			$this->redirect('/');
			exit;
			
		}
		
	}

 	function registration() {
 		
 		$this->onregistration();
 		$this->layout = 'before_login';
 		
 		if(isset($this->data)) {
 			
 			$fname = filter_var(trim($this->data['Advertiser']['fname']), FILTER_SANITIZE_STRING);
 			$lname = filter_var(trim($this->data['Advertiser']['lname']), FILTER_SANITIZE_STRING);
 			$mobile = filter_var($this->data['Advertiser']['mobile'], FILTER_SANITIZE_STRING);
 			$email = filter_var($this->data['Advertiser']['email'], FILTER_SANITIZE_EMAIL);
 			$company_name = filter_var($this->data['Advertiser']['company_name'], FILTER_SANITIZE_STRING);
 			$description = filter_var(trim($this->data['Advertiser']['description']), FILTER_SANITIZE_STRING);
 			
 			
 			if(empty($fname)) $error[] = 'First name is required';
 			
 			if(empty($lname)) $error[] = 'Last name is required';
 			
 			if(!is_numeric($mobile)) $error[] = 'Mobile number is invalid';
	 		else if(strlen($mobile) <> 10) $error[] = 'Mobile number should be of 10 digits';
	 		else if(!$this->checkNumber($mobile)) $error[] = 'Only Indian mobiles are allowed';
	 		else if($this->Advertiser->find('count', array('conditions' => array('mobile' => $mobile)))) $error[] = 'Mobile number already in use';
	 		
	 		if(!$email) $error[] = 'Email ID is required';
 			else if(!$this->checkIfEmail($email)) $error[] = 'Email ID is invalid';
 			else if($this->Advertiser->find('count', array('conditions' => array('email' => $email)))) $error[] = 'Email ID already in use';
	 		
 			if(empty($company_name)) $error[] = 'Company name is required';
 			
	 		if(empty($description)) $error[] = 'Company description is required';
	 		
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
			
			if(!isset($this->data['Advertiser']['agree']) || $this->data['Advertiser']['agree'] != 'on') {
	 			$error[] = 'You have to agree to the Terms and Condition as specified';
	 		}
	 		
	 		
	 		if(!isset($error)) {

 				$secret_key = $this->generateRandomNumber(8);
 				$data['fname'] = $fname;
 				$data['lname'] = $lname;
 				$data['mobile'] = $mobile;
 				$data['email'] = $email;
 				$data['company_name'] = $company_name;
 				$data['description'] = $description;
	 			$data['password'] = $secret_key;
	 			
	 			if($this->Advertiser->save($data)) {
	 				
	 				$this->_sendRegistration($email, $secret_key);
	 				$this->Session->write('success', 'Thank you for creating an account with us.<br/>Login details has been mailed to '.$email);
	 				$this->redirect('/advertisers/registration');
	 				exit;
	 				
	 			} else $error[] = 'Problem saving data';
	 					
			} else {
 			
	 			$checkbox = (isset($this->data['Advertiser']['agree']) && $this->data['Advertiser']['agree'] == 'on') ? 'checked="checked"' : '';
	 			$this->set('error', $error);
	 			$this->set('fname', $fname);
	 			$this->set('lname', $lname);
	 			$this->set('mobile', $mobile);
	 			$this->set('email', $email);
	 			$this->set('company_name', $company_name);
	 			$this->set('description', $description);
	 			$this->set('checkbox', $checkbox);
	 			
	 		}
 		
 		} else {
 			
 			if($this->Session->check('success')) {
 				
 				$this->set('success', $this->Session->read('success'));
 				$this->Session->delete('success');
 				
 			}
 			
 		}
	
 	}

	function onregistration() {
		
		App::import('Vendor', 'recaptchalib');
		$publickey = CAPTCHA_PUBLIC_KEY;
		$this->set('recaptcha', recaptcha_get_html($publickey));
 		$this->set('tab', array('2'));
		$this->getFeedback();
		
	}

	function deletecontent($id) {
		
		$this->checkAccess('advertiser');
		
		$advertiser_id = $this->Session->read('user_id');
		$condition['conditions']['0'] = 'AdvContent.adv_send <> AdvContent.quantity';
		$condition['conditions']['AdvContent.id'] = $id;
		$condition['conditions']['AdvContent.advertiser_id'] = $advertiser_id; 
		$count = $this->AdvContent->find('count', $condition);
		$success = '';
		
		if($count > 0) {
			
			$this->AdvContent->id = $id;
			$this->AdvContent->saveField('status', '1');
			$success = 'Content successfully deleted';
			
		}
		
		$this->set('success', $success);
		$this->view();
		$this->render('/advertisers/view');
		
	}

	function view() {
		
		$this->checkAccess('advertiser');
		$this->layout = 'advertiser';
		
		$advertiser_id = $this->Session->read('user_id');
		$remaining_quantity = $this->_getRemainingQuantity();
		
		$condition['conditions']['AdvContent.status'] = '0';
		$condition['conditions']['AdvContent.advertiser_id'] = $advertiser_id;
		$data = $this->AdvContent->find('all', $condition);

		
		//redirect to ADD if no advertisement has been created yet
		if(empty($data)) {
			
			$this->redirect('/advertisers/add');
			exit;
			
		}
		
		$this->set('tab', array('1'));
	 	$this->getFeedback();
	 	
	 	for($i=0; $i<count($data); $i++) {
	 		
	 		if($data[$i]['AdvContent']['adv_send'] == '0') $data[$i]['AdvContent']['adv_send_status'] = 1; //'<strong>Still to send</strong>';
	 		else if($data[$i]['AdvContent']['adv_send'] <> $data[$i]['AdvContent']['quantity']) $data[$i]['AdvContent']['adv_send_status'] = 2;//'<strong>In Progress</strong>';
	 		else $data[$i]['AdvContent']['adv_send_status'] = 3;//'<strong>Successfully send</strong>';
	 		
	 		$data[$i]['AdvContent']['launch_date'] = date('D, j M Y', strtotime($data[$i]['AdvContent']['launch_date']));
	 		
	 	}
	 	
		$this->set('data', $data);
		$this->set('remaining_quantity', $remaining_quantity);
		
	}

	function add() {
		
		$this->checkAccess('advertiser');
		$this->layout = 'advertiser';
		
		$date = $month = $year = $category = $quantity = '';
		$month_arr = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		
		// get total amount of quantity left
		$remaining_quantity = $this->_getRemainingQuantity();
		
		if(isset($this->data)) {
			
			$content = urldecode(trim($this->data['AdvContent']['content']));
			$category_id = filter_var(trim($this->data['AdvContent']['category_id']), FILTER_SANITIZE_STRING);
			$launch_year = filter_var(trim($this->data['AdvContent']['year']), FILTER_SANITIZE_STRING);
			$launch_month = filter_var(trim($this->data['AdvContent']['month']), FILTER_SANITIZE_STRING);
			$launch_date = filter_var(trim($this->data['AdvContent']['date']), FILTER_SANITIZE_STRING);
			$quantity = filter_var(trim($this->data['AdvContent']['quantity']), FILTER_SANITIZE_STRING);
			
			$launch_datetime = strtotime($launch_year.'-'.$launch_month.'-'.$launch_date);
			
			if(empty($content)) $error[] = 'Advertisement content is required';
			if(strlen($content) > ADVERTISEMENT_CHAR_LIMIT) $error[] = 'Advertisement content should be less than 40 characters';
			if(empty($category_id)) $error[] = 'Category is required';
			if($launch_datetime < time()) $error[] = 'Please provide a future date';
			
			if(empty($quantity)) $error[] = 'Quantity is required';
			else if(!is_numeric($quantity)) $error[] = 'Quantity should be valid';
			else if($quantity > $remaining_quantity) $error[] = 'Quantity should be equal or less than '.$remaining_quantity;
			
			
			if(!isset($error)) {
				
				$data['content'] = $content;
				$data['adv_send'] = '0';
				$data['advertiser_id'] = $this->Session->read('user_id');
				$data['category_id'] = $category_id;
				$data['launch_date'] = date('Y-m-d', $launch_datetime);
				$data['quantity'] = $quantity;
				$this->AdvContent->save($data);
				
				$success = 'Advertisement saved successfully.<br/>You will be notified when to advertisement is send.';
				$this->set('success', $success);
				
				// deduct current quantity from max quantity
				$remaining_quantity = $remaining_quantity - $quantity;
				$content = $category_id = $launch_year = $launch_month = $launch_date = $quantity = '';
				
			} else {
			
				$this->set('content', $content);
				$this->set('error', $error);
				
			}
			
		}
		

		// date
		for($i=1; $i<32; $i++) {
			
			if(isset($launch_date) && $launch_date == $i) $date .= '<option value="'.$i.'" selected>'.$i.'</option>'; 
			else $date .= '<option value="'.$i.'">'.$i.'</option>';
			
		}
		
		
		// month
		for($i=1; $i<count($month_arr); $i++) {
			
			if(isset($launch_month) && $launch_month == $i) $month .= '<option value="'.$i.'" selected>'.$month_arr[$i].'</option>';
			else $month .= '<option value="'.$i.'">'.$month_arr[$i].'</option>';
			
		}
		
		
		// year
		for($i=2010; $i<2016; $i++) {
		
			if(isset($launch_year) && $launch_year == $i) $year .= '<option value="'.$i.'" selected>'.$i.'</option>';
			else $year .= '<option value="'.$i.'">'.$i.'</option>';
		
		}

		
		// category list
		$condition['conditions']['status'] = '0';
		$categorylist = $this->Category->find('list', $condition);
		
		foreach($categorylist as $k => $v) {
			
			if(isset($category_id) && $category_id == $k) $category .= '<option value="'.$k.'" selected>'.$v.'</option>';
			else $category .= '<option value="'.$k.'" />'.$v.'</option>';
			
		}
		

		$this->set('tab', array('3'));
	 	$this->getFeedback();
		
		$this->set('category', $category);
		$this->set('quantity', $quantity);
		$this->set('remaining_quantity', $remaining_quantity);
		$this->set('date', $date);
		$this->set('month', $month);
		$this->set('year', $year);
		
	}
	
	function _getRemainingQuantity() {
		
		$advertiser_id = $this->Session->read('user_id');
		$total_limit = $this->Session->read('adv_plan_limit');

		$remaining_quantity = $this->AdvContent->find('all', array('conditions'=>array('AdvContent.advertiser_id'=>$advertiser_id, 'AdvContent.status'=>'0'), 'fields'=>array('SUM(AdvContent.quantity) as max_quantity'), 'group' => 'AdvContent.advertiser_id'));
		if($remaining_quantity) $remaining_quantity = $total_limit - $remaining_quantity['0']['0']['max_quantity'];
		else $remaining_quantity = $total_limit;
		
		return $remaining_quantity;
		
	}

	function _sendRegistration($email, $password) {
 		
 		$subject = 'Login Information';
 		$message = 'Dear user,'.
 					SEPERATOR . SEPERATOR .
 					'Thank you for registering at '.SERVERNAME.'. You may now log in using the following username and password.'.
 					SEPERATOR . SEPERATOR .
 					'username : '.$email.
 					SEPERATOR .
 					'password : '.$password.
 					SEPERATOR . SEPERATOR .
 					'You can now log in by clicking on this link or copying and pasting it in your browser.'.
 					SEPERATOR . SEPERATOR .
 					'<a href="'.SERVER.'/advertisers/login" target="_blank">'.SERVER.'/advertisers/login</a>'.
 					SEPERATOR . SEPERATOR .
 					'After logging in, please change your password by selecting "Setting" tab from top navigation bar.'.
 					SEPERATOR . SEPERATOR .
 					MAIL_FOOTER;
 		
 		$this->sendMail($email, $subject, $message);
 		//$this->write($subject);$this->write($message);
 		$this->autoRender = false;
 		
 	}

	function tnc() {
		
		$this->layout = 'advertiser';
		$this->set('tab', array('2'));
		$this->getFeedback();
		
	}
 	
 }
?>