<?php
 class BulkUser extends AppModel {
 	
 	var $useTable = 'bulk_user';
 	//var $recursive = 2;
 	
 	var $belongsTo = array(
 		'BulkUserpersonalinfo' => array(
 			'foreignKey' => 'bulk_userpersonalinfo_id'
 	));
 	
 	var $hasOne = array(
 		'BulkAccount' => array(
 			'foreignKey' => 'bulk_user_id'
 	));
 	
 }
?>