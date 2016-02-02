<div class="gradient"><h1><span></span>MY PROFILE</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div>
	<div class="header_hw"><div class="header_wrapper header_03">Change Profile Details</div></div>

    <form method="post">
    	<div class="pad5 f13">First Name</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Userpersonalinfo][firstname]" value="<?=$data['firstname']?>"/></span>
		</div>
		<div class="pad5">&nbsp;</div>
		
		<div class="pad5 f13">Last Name</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Userpersonalinfo][lastname]" value="<?=$data['lastname']?>"/></span>
		</div>
		<div class="pad5">&nbsp;</div>
		
		<div class="pad5 f13">Alternate Email Address</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Userpersonalinfo][alternate_email]" value="<?=$data['alternate_email']?>"/></span>
		</div>
		<div class="pad5">&nbsp;</div>
		
		<div class="pad5 f13">Alternate Mobile Number</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Userpersonalinfo][alternate_mobile]" onkeypress="return numKey(event);" value="<?=$data['alternate_mobile']?>"/></span>
		</div>
		<div class="pad5">&nbsp;</div>
		
		<div class="pad5 f13">Address</div>
		<div class="pad5">
			<span><textarea class="textbox" name="data[Userpersonalinfo][address]"><?=$data['address']?></textarea></span>
		</div>
		<div class="pad5">&nbsp;</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value=" Save " /></span>
		</div>
	</form>

</div>