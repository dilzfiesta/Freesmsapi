<?php
 class BulkAccount extends AppModel {
 	
 	var $useTable = 'bulk_account';
 	
 	var $hasMany = array(
 		'BulkAccountRecharge' => array(
 			'foreignKey' => 'Bulk_account_id'
 	));
 	
 }
?>