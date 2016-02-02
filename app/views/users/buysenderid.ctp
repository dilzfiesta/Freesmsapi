<div class="content_col_w520 fr">

    <div class="gradient"><h1><span></span>Buy Sender ID</h1></div>
    
    <?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

    <form method="post">
    
    	<div class="pad5">
    		<span>Sender ID <strong><?=$senderid?></strong> Rs.3000</span>
    	</div>
    
		<div class="pad5">
			<span><input type="text" class="inputboxcc" name="data[ccno1]" maxlength="4" size="3" /></span>
			<span><input type="text" class="inputboxcc" name="data[ccno2]" maxlength="4" size="3" /></span>
			<span><input type="text" class="inputboxcc" name="data[ccno3]" maxlength="4" size="3" /></span>
			<span><input type="text" class="inputboxcc" name="data[ccno4]" maxlength="4" size="3" /></span>
			<span>Password</span>
		</div>
		
		<div class="pad5">
			<select name="data[cc]" class="dropdown">
				<option value="1">VISA</option>
				<option value="2">MASTER CARD</option>
			</select>
			<span>User Type</span>
		</div>
		
		<div class="pad5">
			<span><input type="submit" class="rc_btn_01" value="Buy" /></span>
		</div>
	</form>
    
</div>