<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>

    <div class="gradient"><h1><span></span>Registration Form</h1></div>
    
    <div>
    
    	<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
    
	   <!-- <div class='error'>
			<?php
				if(isset($error)) {
					foreach($error as $value) {
						?>
						
						<?php
					}
				} else {
					$mobile = '';
					$email = '';
					$domain = '';
					$checkbox = '';
					$referral = '';
				}
			?>
		</div>
		
		<div class='success'>
			<?php if(isset($success)) { ?>
					<div><?=$success?></div>
			<?php }	?>
		</div> -->

		<form method="post">
			<table cellpadding="0" cellspacing="0" border="0" align="center" class="frame" style="width:100%">
			    <tbody><tr>
			      <td><table border="0" align="center" cellpadding="10" cellspacing="0">
			          <tbody><tr>
			            <td width="150" align="right" class="fieldarea">Indian Mobile Number&nbsp;<span class="star">*</span></td>
			            <td><input type="text" id="name" class="inputbox" name="data[UserTmp][name]" value="<?=$mobile?>" style="width:45%"/>&nbsp;<span>(<i>eg: 9892098920</i>)</span></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">Domain Name&nbsp;<span class="star">*</span></td>
			            <td><input type="text" class="inputbox" name="data[UserTmp][domain]" value="<?=$domain?>" style="width:45%" />&nbsp;<span>(<i>eg: freesmsapi.com</i>)</span></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">Domain Email Address&nbsp;<span class="star">*</span></td>
			            <td><input type="text" class="inputbox" name="data[UserTmp][email]" value="<?=$email?>" style="width:45%"/>&nbsp;<span>(<i>eg: info@freesmsapi.com</i>)</span></td>
			          </tr>
			          <tr valign="top">
			            <td width="150" align="right" class="fieldarea">Are you Human&nbsp;<span class="star">*</span></td>
			            <td><?=$recaptcha?></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">Referral Website</td>
			            <td><input type="text" class="inputbox" name="data[UserTmp][referral]" value="<?=isset($ref) ? $ref : $referral?>" style="width:45%"/>&nbsp;<span>(<i>Optional, see our <a href="/users/referralprogram" target="_blank">Referral Program</a></i>)</span></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea"><input id="ig" type="checkbox" name="data[UserTmp][agree]" <?=$checkbox?>/></td>
			            <td><label for="ig">I agree with the </label><a href="<?=SERVER?>users/tnc" target="_blank"><strong>Terms & Condition</strong></a></td>
			          </tr>
			          <tr>
			            <td width="150" align="right" class="fieldarea">&nbsp;</td>
			            <td><input type="submit" class="submit_button" value="Register"></td>
			          </tr>
			        </tbody></table></td>
			    </tr>
			  </tbody>
			</table>
		</form>
	</div>
	
	<div class="silent_feature list" style="margin-left:0px;margin-right:0px">
		<ul style="margin-left:15px">
			<li style="padding:3px">The email address should match the domain name.</li>
			<li style="padding:3px">The password for your <strong>Freesmsapi account</strong> registration will be sent to you on your registered Email Address.</li>
			<li style="padding:3px">Please provide a valid Indian Mobile number. The mobile verification is mandatory before you commence using our services. The activation key would be sent to you on your registered mobile phone after logging into your account.</li>
		</ul>
	</div>
    
</div>
<script>
	document.getElementById('name').focus();
</script>