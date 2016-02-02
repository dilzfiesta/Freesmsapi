<div class="gradient"><h1><span></span>Delete My Account</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">Delete My Account</div></div>

<form method="post" onsubmit="return confirm('Are you sure?')">
	<div class="pad5 f13">Reason to delete your account&nbsp;<span class="star">*</span></div>
	<div class="pad5">
		<span><textarea name="data[reason]" class="textbox"><?=isset($reason)?$reason:''?></textarea></span>
	</div>
	<div class="pad5">&nbsp;</div>
	<div class="pad5 f13">Current password&nbsp;<span class="star">*</span></div>
	<div class="pad5">
		<span><input type="password" class="inputbox" name="data[password]" /></span>
	</div>
	<div class="pad5">&nbsp;</div>
	<p class="f13">Please be sure you want to delete your entire account as <strong>you will not be able to reactivate it after the account has been deleted.</strong> You are always welcome to sign up again for a new account.</p>
	<div class="pad5" style="margin-left:-5px">
		<span><input type="submit" class="rc_btn_01" value="Delete" /></span>
	</div>
</form>
<?=isset($redirect) ? $redirect : ''?>