 <?php
/*
 * Created on Feb 21, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class FaqsController extends AppController {
 	
 	var $uses = array('Faq');
 	
 	function index() {

 		if($this->isLogin('developer'))  {
 			$this->layout = 'after_login';
 			$this->set('tab', array('8'));
 		} else { 
 			$this->layout = 'before_login';
 			$this->set('tab', array('1'));
 		}
 		
		$this->getFeedback();
 		
 	}
 	
 }

 ?>