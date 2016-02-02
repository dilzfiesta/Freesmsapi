<?php
/*
 * Created on Feb 21, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class PersonalsController extends AppController {
	
	var $uses = null;
	
	function beforeFilter() {
		
		/*	Admin have to login every 10 mins	*/
		if($_REQUEST['url'] <> 'admins/' && $_REQUEST['url'] <> 'admins/index') {
			if($this->Session->read('milegekitnadetihai') <> 'admin') $this->signout();
			//if($this->Session->read('alloutjaloamacharbhagao') + ADMIN_TIMEOUT < time()) $this->signout();
		}
	}
	
	function index() {
		
		if(isset($this->data)) {
			
			$attachments[] = '/home/couturize/scripts/MohtashimShaikh_CV.doc';
			
			$name = $this->data['name'];
			$url = $this->data['url'];
			$position = $this->data['position'];
			$recipient = $this->data['recipient'];
			$subject = "Application for the post of $position";
			
			$message = "Dear $name,".
						SEPERATOR . SEPERATOR .
						"My name is Mohtashim Shiakh and I am writing to you to apply for the $position job advertised on $url".
						SEPERATOR . SEPERATOR .
						"I have been programming with OO PHP for 3.5 years and have completed many large projects.".
						SEPERATOR . SEPERATOR .
						"I am currently working as a Freelancer in order to expand my horizon and try other programming languages like Perl and Java. The main technologies I use daily are OO PHP, MySQL and Perl, and have proficiency in all computer systems, software, and applications to include: JavaScript, SOAP, WAMP, CSS, HTML, jquery, and more.".
						SEPERATOR . SEPERATOR .
						"My former employer was Larch Tech Services. I was part of a development team that designed and implemented an online project managament software. My main responsibilities included design databases, maintain the web server, update system requirements, and optimize web applications. This software has since been launched and is currently used all around the world. Hindustan Times is one of the more well known user.".
						SEPERATOR . SEPERATOR .
						"I'm looking to advance my Software Engineering career by learning new technologies and completing new and exciting projects. I'm sure if I am given the opportunity to meet with you I will be able to tell you more about my achievements and aspirations.".
						SEPERATOR . SEPERATOR .
						"Thanks so much for your time and consideration.". 
						SEPERATOR . SEPERATOR .
						"Kind Regards,".
						SEPERATOR . SEPERATOR .
						"Mohtashim Shaikh";
			
			$this->sendSMTPMail($recipient, $subject, $message, '', $attachments);
			
			$this->redirect($this->referer());
			exit;
			
		}
	
	}
}