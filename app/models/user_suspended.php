<?php
 class UserSuspended extends AppModel {
 	
 	//var $useTable = null;
 	var $useTable = 'user_suspended';
 	
 	var $belongsTo = array(
 		'UserSuspendedReason' => array(
 			'className' => 'UserSuspendedReason',
 			'foreignKey' => 'reason_id'
 		)
 	);
 }
?>