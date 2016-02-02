<?php
/*
 * Created on Feb 23, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class DomainsController extends AppController {
 	
 	var $uses = array('Domain', 'Monitor', 'Plan', 'ValidNumber');
 	
 	function beforeFilter() {
		
		if(!IS_TEST_SERVER) { $this->redirect('/'); exit; }
	
 	}
 	
 	function check() {
 		
 		
 		
 	}
 	
 	function insertMobile() {

		set_time_limit(0);
		Configure::write('debug', 2);
 		
		$content = file_get_contents('http://en.wikipedia.org/wiki/Mobile_telephone_numbering_in_India');
		preg_match_all('/\d{4}/', $content, $result);
		$dd = array_unique($result['0']);
	
		for($i=0; $i<count($dd); $i++) {
			if($dd[$i] <= 9999 && $dd[$i] >= 7000)
				$new_dd[] = $dd[$i];
		}

		foreach($new_dd as $key => $value)
			$new[$key]['name'] = $value;
		
		$this->ValidNumber->saveAll($new);
		
	 	exit;	
 	}
 	
 	
 }
?>
