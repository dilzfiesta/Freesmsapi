<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>
	
    <div class="gradient"><h1><span></span>Client Area Password Recovery</h1></div>
    
    <div>
	    <?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
	    
		<div class="pad5 f12"><p>Please type in the Email Address and Mobile Number that we have listed on your account and we will Email you your Password.</p></div>
		<div class="pad5">&nbsp;</div>
		
		<form method="post">
			<table cellpadding="0" cellspacing="0" border="0" align="center" class="frame" style="width:100%">
			    <tbody><tr>
			      <td><table border="0" align="center" cellpadding="10" cellspacing="0">
			          <tbody><tr>
			            <td width="150" align="right" class="fieldarea">Mobile number&nbsp;<span class="star">*</span></td>
			            <td><input type="text" id="name" class="inputbox" name="data[User][name]" value="" style="width:45%" /></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">Email Address&nbsp;<span class="star">*</span></td>
			            <td><input type="text" class="inputbox" name="data[User][email]" value="" style="width:45%" /></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">User Type&nbsp;<span class="star">*</span></td>
			            <td>
			            	<select name="data[User][type]" class="dropdown">
								<option value="developer">Free User</option>
								<option value="bulksms">Premium User</option>
							</select>
						</td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">&nbsp;</td>
			            <td><input type="submit" class="submit_button" value="Mail My Password"></td>
			          </tr>
			        </tbody></table></td>
			    </tr>
			  </tbody>
			</table>
		</form>
	
		<div class="f10 pad5"><label>Password Recovered?</label> <a href="<?=SERVER?>users/login">Click here to Login</a></div>
		
    </div>
    
</div>
<script>
	document.getElementById('name').focus();
</script>