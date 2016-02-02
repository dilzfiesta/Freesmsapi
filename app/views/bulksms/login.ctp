<div class="content_col_w520 fr">

    <div class="gradient"><h1><span></span>Bulk SMS Login</h1></div>
    
    <?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

    <form method="post">
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkUser][username]" /></span>
			<span>Username</span>
		</div>
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[BulkUser][password]" /></span>
			<span>Password</span>
		</div>
		<div class="pad5">
			<span><input type="submit" class="rc_btn_01" value="login" /></span>
		</div>
	</form>
    
</div>