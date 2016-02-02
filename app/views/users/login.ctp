<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>
	
	<div class="gradient"><h1><span></span>Client Area Login</h1></div>
    
    <div>
    
    	<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
			
			<!--<div class="pad10 f12">You must login to access this page. This login is same for Free Users as well as Premiun Users.</div>-->
			<form method="post">
				<table cellpadding="0" cellspacing="0" border="0" align="center" class="frame" style="width:100%">
				    <tbody><tr>
				      <td><table border="0" align="center" cellpadding="10" cellspacing="0">
				          <tbody><tr>
				            <td width="150" align="right" class="fieldarea">Username&nbsp;<span class="star">*</span></td>
				            <td><input type="text" id="name" class="inputbox" name="data[User][name]" style="width:45%" /></td>
				          </tr>
				          <tr>
				            <td width="150" align="right" class="fieldarea">Password&nbsp;<span class="star">*</span></td>
				            <td><input type="password" class="inputbox" name="data[User][password]" style="width:45%" /></td>
				          </tr>
				          <? if(isset($recaptcha)) { ?>
					          <tr valign="top">
					            <td width="150" align="right" class="fieldarea">Are you Human&nbsp;<span class="star">*</span></td>
					            <td><?=$recaptcha?></td>
					          </tr>
						  <? } ?>
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
				            <td><input type="submit" class="submit_button" value="Login"></td>
				          </tr>
				        </tbody></table></td>
				    </tr>
				  </tbody>
				</table>
			</form>
	
			<div class="f10 pad5"><label>Forgotten Your Password?</label> <a href="<?=SERVER?>users/forgotpassword">Request your Password</a></div>
	</div>
    
</div>
<script>
	document.getElementById('name').focus();
</script>