<?php
 class Alias extends AppModel {
 
 	var $useTable = 'alias';
 	
 	var $belongsTo = array(
 		'Domain' => array(
 			'foreignKey' => 'domain_id'
 		),
 	);
 	
 	var $hasOne = array(
 		'AliasBuy' => array(
 			'foreignKey' => 'alias_id'
 		),
 		'AliasInvoice' => array(
 			'foreignKey' => 'alias_id'
 		),
 	);
 
 }