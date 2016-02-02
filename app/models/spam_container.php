<?php
 class SpamContainer extends AppModel {
 
 	var $useTable = 'spam_container';
 	
 	var $belongsTo = array(
		'Domain' => array(
			'className' => 'Domain',
			'foreignKey' => 'domain_id',
		)
	);
 	
 }
?>