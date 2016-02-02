<?php
/*
 * Created on Mar 11, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Advertiser extends AppModel {
 	
 	var $useTable = 'advertiser';
 	
 	var $belongsTo = array(
 		'AdvPlan' => array(
 			'className' => 'AdvPlan',
 			'ForeignKey' => 'adv_plan_id'
 		)
 	);
 }
 
?>