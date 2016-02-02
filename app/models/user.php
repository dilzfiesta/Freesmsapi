<?php
/*
 * Created on Feb 21, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class user extends AppModel {
 	
 	var $useTable = 'user';
 
 	var $hasOne = array(
		'Domain' => array(
			'className' => 'Domain',
			'foreignKey' => 'user_id',
			'dependent' => true
		),
	);
 }
 
?>