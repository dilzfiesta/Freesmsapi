<?php
	$l_1 = array('sendnow', 'showreport', 'showdetailedreport', 'schedulereport');
	$l_2 = array('addressbook', 'groups');
	$l_3 = array('help', 'api_response', 'api_example', 'api_balance_check', 'api_check_delivery', 'api_schedule_sms', 'api_schedule_sms_response');
	$l_4 = array('myaccount', 'feedback', 'setting', 'profile');
	$pointer = explode('/', $_SERVER['REQUEST_URI']);
	
	if(in_array($pointer[2], $l_1)) $stat = '0c';
	else if(in_array($pointer[2], $l_2)) $stat = '1c';
	else if(in_array($pointer[2], $l_3)) $stat = '2c';
	else if(in_array($pointer[2], $l_4)) $stat = '3c';
	else $stat = '-1c';
	
	if(isset($stat)) setcookie('submenuheader', $stat);
?>
<div id="nav">
	<div class="boxnav">
		<h3 class="titlenav"><a href="/bulksms/view"  headerindex="0h"><span class="accordprefix">Home</span></a></h3>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="0h"><span class="accordprefix">SMS</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/bulksms/sendnow">Send</a></li>
			<li><a href="/bulksms/showreport">Delivery Reports</a></li>
			<li><a href="/bulksms/schedulereport">Scheduled SMS Reports</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="1h"><span class="accordprefix">Address Book</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/bulksms/addressbook/view">View</a></li>
			<li><a href="/bulksms/addressbook/add">Add Contact</a></li>
			<li><a href="/bulksms/groups">Groups</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="2h"><span class="accordprefix">API</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/bulksms/help">Send SMS</a></li>
			<li><a href="/bulksms/api_response">Send SMS Response</a></li>
			<li><a href="/bulksms/api_schedule_sms">Schedule SMS</a></li>
			<li><a href="/bulksms/api_schedule_sms_response">Schedule SMS Response</a></li>
			<li><a href="/bulksms/api_check_delivery">Check Delivery</a></li>
			<li><a href="/bulksms/api_balance_check">Check Balance</a></li>
			<li><a href="/bulksms/api_example">Language Examples</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="3h"><span class="accordprefix">My Account</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/bulksms/myaccount#sender">Sender ID</a></li>
			<li><a href="/bulksms/myaccount#pass">Change Password</a></li>
			<!--<li><a href="/bulksms/help">API Code</a></li>
			<li><a href="/bulksms/api_response">API Response</a></li>-->
			<li><a href="/bulksms/feedback">Feedback</a></li>
			<li><a href="/bulksms/profile">Profile Details</a></li>
			<li><a href="/bulksms/setting">General Setting</a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>