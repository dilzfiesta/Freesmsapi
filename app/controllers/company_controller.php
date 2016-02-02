<?php
 class CompanyController extends AppController {
 
 	var $uses = array('Feedback');
 	
 	function index() {
 		
 		//$this->redirect('/company/aboutus');
 		
 		if($this->Session->read('user_type') == 'developer' && $this->Session->check('user_id')) {
			$this->redirect('/users/view');
			exit;
		} else if($this->Session->read('user_type') == 'bulksms' && $this->Session->check('user_id')) {
			$this->redirect('/bulksms/view');
			exit;
		}
		
		$this->set('tab', array('1'));
		$this->layout = 'index';
 		
 	}
 	
 	function aboutus() {
 		
 		$this->set('tab', array('1'));
		$this->getFeedback();
 		$this->layout = 'before_login';
 		
 	}
 	
 	function careers() {
 		
 		$this->set('tab', array('1'));
		$this->getFeedback();
 		$this->layout = 'before_login';
 		
 	}
 	
 	function services() {
 		
 		$this->set('tab', array('3'));
		$this->getFeedback();
 		$this->layout = 'before_login';
 		
 	}
 	
 	function features() {
 		
 		$this->set('tab', array('2'));
		$this->getFeedback();
 		$this->layout = 'before_login';
 		
 	}
 
 }
?>