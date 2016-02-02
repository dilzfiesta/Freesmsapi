<?php
/*
 * Created on Feb 26, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class FeedbacksController extends AppController {
 	
 	var $uses = array('Feedback', 'Contact', 'Maillog');
 	
 	function onadd() {
 		
 		App::import('Vendor', 'recaptchalib');
		$publickey = CAPTCHA_PUBLIC_KEY;
		$this->set('recaptcha', recaptcha_get_html($publickey));
		
 	}
 	
 	function contact() {
 		
 		$this->onadd();
 		
 		if($this->isLogin('developer')) {
 			$this->layout = 'after_login';
 			$this->set('tab', array('8'));
 		} else {
 			$this->layout = 'before_login';
 			$this->set('tab', array('5'));
 		}
 		
 		if(isset($this->data)) {
 		
	 		$name = filter_var(trim($this->data['Contact']['name']), FILTER_SANITIZE_STRING);
	 		$email = filter_var(trim($this->data['Contact']['email']), FILTER_SANITIZE_STRING);
	 		$feedback = filter_var(trim($this->data['Contact']['feedback']), FILTER_SANITIZE_STRING);
	 		
	 		if(empty($name) || $name == 'name') $error[] = 'Name is required';
	 		if(empty($email) || $email == 'email') $error[] = 'Email is required';
	 		else if(!$this->checkIfEmail($email)) $error[] = 'Email ID is invalid';
	 		if(empty($feedback) || $feedback == 'feedback') $error[] = 'Message is required';
	 		
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
			
			if(empty($error)) {
				
				$value['name'] = $name;
				$value['feedback'] = $feedback;
				$value['email'] = $email;
				
				/*	Notify Admin	*/
				$to_admins['type'] = 'Contact';
				$to_admins['data'] = $value;
				$this->notifyAdmin($to_admins);
				
				/*	Save Data	*/
				$this->Contact->save($value);
				$success = 'Thank you for your time and patience. Your feedback will definitely help us serve you better.';
				$this->Session->write('success', $success);
				$this->redirect('/feedbacks/contact');
				exit;
				
			} else {
				
				$this->set('name', $name);
				$this->set('email', $email);
				$this->set('feedbackText', $feedback);
				$this->set('error', $error);
				
			}
 		
 		} else {
 			
 			if($this->Session->check('success')) {
 				
 				$this->set('success', $this->Session->read('success'));
 				$this->Session->delete('success');
 				
 			}
 			
 		}
 		
 		$this->getFeedback();
 		
 	}

 	
 	function feedback() {
 		
 		$this->checkAccess('developer');
 		$this->onadd();
 		$this->layout = 'after_login';
 		
 		if(isset($this->data)) {
 		
	 		$feedback = filter_var(trim($this->data['Feedback']['feedback']), FILTER_SANITIZE_STRING);
	 		if(empty($feedback)) $error[] = 'Feedback is required';
			
			if(empty($error)) {
				
				$value['feedback'] = $feedback;
				$value['domain_id'] = $this->Session->read('domain_id');
				
				/*	Notify Admin	*/
				$to_admins['type'] = 'Feedback';
				$to_admins['data'] = $value;
				$this->notifyAdmin($to_admins);
				
				/*	Save Data	*/
				$this->Feedback->save($value);
				$success = 'Thank you for your time and patience. Your feedback will definitely help us serve you better.';
				$this->Session->write('success', $success);
				$this->redirect('/feedbacks/feedback');
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
 		
 		$this->set('tab', array('2'));
 		
 	}
 	
 }
?>