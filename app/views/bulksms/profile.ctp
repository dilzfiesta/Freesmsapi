<div class="gradient"><h1><span></span>MY PROFILE</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div>
	<div class="header_hw"><div class="header_wrapper header_03">Change Profile Details</div></div>

    <form method="post">
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][firstname]" value="<?=$data['firstname']?>"/></span>
			<span>First Name</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][lastname]" value="<?=$data['lastname']?>"/></span>
			<span>Last Name</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][email]" value="<?=$data['email']?>"/></span>
			<span>Email</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][mobile]" onkeypress="return numKey(event);" value="<?=$data['mobile']?>"/></span>
			<span>Mobile Number</span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="change" /></span>
		</div>
	</form>

</div>