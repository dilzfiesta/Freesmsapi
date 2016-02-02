<?php
/*
 * Created on Apr 29, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Category extends AppModel {
 	
 	var $useTable = 'category';
 
 	var $hasMany = array(
 		'AdvContent' => array(
 			'className' => 'AdvContent',
 			'foreignKey' => 'category_id'
 		),
 		'Domain' => array(
 			'className' => 'Domain',
 			'foreignKey' => 'category_id'
 		)
 	);
 
 }
 
?>