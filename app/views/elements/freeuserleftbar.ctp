<?php
	$l_1 = array('sendnow', 'showreport');
	$l_2 = array('help', 'api_response', 'view');
	$l_3 = array('myaccount', 'changeip', 'feedback', 'profile', 'delete');
	$l_4 = array('pricingsenderid', 'refprogram');
	$pointer = explode('/', $_SERVER['REQUEST_URI']);
	
	if($pointer[1] == 'users' && $pointer[2] == 'view') $stat = '-1c';
	else if(in_array($pointer[2], $l_1)) $stat = '0c';
	else if(in_array($pointer[2], $l_2)) $stat = '1c';
	else if(in_array($pointer[2], $l_3)) $stat = '2c';
	else if(in_array($pointer[2], $l_4)) $stat = '3c';
	else $stat = '-1c';
	
	if(isset($stat)) @setcookie('submenuheader', $stat);
?>
<div id="nav">
	<div class="boxnav">
		<h3 class="titlenav"><a href="/users/view"  headerindex="0h"><span class="accordprefix">Home</span></a></h3>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="0h"><span class="accordprefix">SMS</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/messages/sendnow">Send</a></li>
			<li><a href="/users/showreport">Delivery Reports</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="1h"><span class="accordprefix">API Code</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/users/help">API Code</a></li>
			<li><a href="/users/api_response">API Response</a></li>
			<li><a href="/widgets/view">SMS Widget</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="2h"><span class="accordprefix">My Account</span></a></h3>
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<li><a href="/users/myaccount#sender">Sender ID</a></li>
			<li><a href="/users/myaccount#pass">Change Password</a></li>
			<li><a href="/users/changeip">Change IP Address</a></li>
			<li><a href="/feedbacks/feedback">Feedback</a></li>
			<li><a href="/users/profile">Profile Details</a></li>
			<li><a href="/users/delete">Delete Account</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="boxnav">
		<?php if(SHOW_SENDER_ID) { ?>
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="3h"><span class="accordprefix">Pricing Plan</span></a></h3>
		<?php } else { ?>
		<h3 class="titlenav"><a href="javascript:void(0)" class="submenuheader" headerindex="3h"><span class="accordprefix">Referral Program</span></a></h3>
		<?php } ?>
		
		<ul class="menunav submenu" contentindex="0c" style="display: none;">
			<?php if(SHOW_SENDER_ID) { ?>
			<li><a href="/users/pricingsenderid">Sender ID Pricing Plan</a></li>
			<? } ?>
			<li><a href="/users/refprogram">Referral Program</a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>