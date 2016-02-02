<?php

 class Domain extends AppModel {
 	
 	var $useTable = 'domain';
 	
 	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'Plan' => array(
			'className' => 'Plan',
			'foreign_key' => 'plan_id',
		),
		/*'Category' => array(
			'className' => 'Category',
			'foreign_key' => 'category_id',
		)*/
	);
 	
 } 

?>