<?php
/*
 * Created on Feb 22, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class PlansController extends AppController {
 	
 	var $uses = array('Plan');
 	
 	function beforeFilter() {
		
		if(!IS_TEST_SERVER) { $this->redirect('/'); exit; }
		
	}
 	
 	function image($id) {
 		
 		$condition['conditions']['id'] = $id;
 		$image = $this->Plan->find('all', $condition);//print_r($image);exit;
 		header('Content-type: ' . $image['0']['Plan']['type']);
	    header('Content-length: ' . $image['0']['Plan']['size']); // some people reported problems with this line (see the comments), commenting out this line helped in those cases
	    header('Content-Disposition: inline; filename="'.$image['0']['Plan']['name'].'"');
	    echo $image['0']['Plan']['image'];
 		
 	}
 	
 	function add($id=null) {	exit;//not for outsiders
 		
 		if(!empty($id))
 			$this->set('id', $id);
 		
 	}
 	
 	function save() {	exit;//not for outsiders
 		
 		//echo '<pre>'; print_r($this->data); 
 		
 		if($this->data['Plan']['image']['name'] != '') {
 			
	 		if($this->data['Plan']['image']['type'] == 'image/jpeg') 
	 			$file = imagecreatefromjpeg($this->data['Plan']['image']['tmp_name']);
	 		else if($this->data['Plan']['image']['type'] == 'image/png')
	 			$file = imagecreatefrompng($this->data['Plan']['image']['tmp_name']);
	 		else if($this->data['Plan']['image']['type'] == 'image/gif')
	 			$file = imagecreatefromgif($this->data['Plan']['image']['tmp_name']);
	 			
	 		$x = imagesx($file);
	 		$y = imagesy($file);
	 		$fileData = fread(fopen($this->data['Plan']['image']['tmp_name'], "r"), $this->data['Plan']['image']['size']);
	 			
	 		$value['image'] = $fileData;
	 		$value['size'] = $this->data['Plan']['image']['size'];
	 		$value['type'] = $this->data['Plan']['image']['type'];
	 		$value['dimension'] = $x .'x'.$y;
	 		$value['interval'] = $this->data['Plan']['interval'];
 		
 		}
 		if(!empty($this->data['Plan']['id'])) $value['id'] = $this->data['Plan']['id'];
 		$value['name'] = $this->data['Plan']['name'];
 		
 		$this->Plan->save($value);
 		
 		$this->autoRender = false;
 		
 	}
 	
 }
?>