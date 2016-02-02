<?php
/*
 * Created on Apr 26, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Upgrade extends AppModel {
 	
 	var $useTable = 'upgrade';
 	
 	function getUpgradeData() {
 		
 		return $this->query("select u.id, u.requestbody, p.id as 'plan_id', p.name as 'plan_name', d.id as 'domain_id', d.name as 'domain_name', u.approve, u.created from upgrade u inner join domain d on u.domain_id=d.id inner join plan p on d.plan_id=p.id and u.approve is null and u.status=0");
 		
 	}
 	
 }
 
?>