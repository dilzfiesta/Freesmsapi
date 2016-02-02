<?php
/*
 * Created on Apr 29, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class AdvContent extends AppModel {
 	
 	var $useTable = 'adv_content';
 	
 	var $belongsTo = array(
 		'Category' => array(
 			'className' => 'Category',
 			'foreignKey' => 'category_id'
 		)
 	);
 }
 
?>