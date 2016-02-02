<?php
 class BulkSmsSchedule extends AppModel {
 	
 	var $useTable = 'bulk_sms_schedule';
 	
 	var $belongsTo = array(
 		'BulkGroup' => array(
 			'foreignKey' => 'bulk_group_id'
 		)
 	);
 	
 }
?>