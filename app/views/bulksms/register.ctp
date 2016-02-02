<div class="content_col_w520 fr">

    <div class="gradient"><h1><span></span>Registration Form</h1></div>
    
    <?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

    <form method="post">
    	<div class="pad5"><strong>GENERAL INFO</strong></div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][firstname]" value="<?=isset($firstname)?$firstname:''?>" /></span>
			<span>Firstname (<i>eg: John</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][lastname]" value="<?=isset($lastname)?$lastname:''?>" /></span>
			<span>Lastname (<i>eg: Smith</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][email]" value="<?=isset($email)?$email:''?>" /></span>
			<span>Email Address (<i>eg: johnsmith@gmail.com</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUserpersonalinfo][mobile]" value="<?=isset($mobile)?$mobile:''?>" /></span>
			<span><img src="/img/indian-flag-small.jpg"> Indian mobile number (<i>eg: 9699419699</i>)</span>
		</div>
		
		<div style="padding:10px;0px">&nbsp;</div>
		
		<div class="pad5"><strong>LOGIN INFO</strong></div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUser][username]" value="<?=isset($username)?$username:''?>" /></span>
			<span>Username (<i>min. 6 chars long</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[BulkUser][password]" /></span>
			<span>Password (<i>min. 8 chars long</i>)</span>
		</div>
		
		<div>&nbsp;</div>
		
		<div class="pad5">
			<span><input type="submit" class="rc_btn_01" value="register" /></span>
		</div>
	</form>
    
</div>