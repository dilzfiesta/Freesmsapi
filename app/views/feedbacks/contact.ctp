<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>
	
    <div class="gradient"><h1><span></span>Contact Us</h1></div>

	<? /* ?><div>
		<div class="pad5 header_05">Registered Office</div>
		<div class="pad5">
			<p>Contact Person : Mohtashim Shaikh</p>
			<p>Email : <a href="mailto:mohtashim@freesmsapi.com">mohtashim@freesmsapi.com</a></p>
			<p>307/20, 1st Floor, Krishna Niwas,</p>
			<p>Yusuf Meher Ali Road,</p>
			<p>Mumbai - 400003.</p>
		</div>
	</div>
	
	<div class="pad5">&nbsp;</div>
	<hr/>
	<div class="pad5">&nbsp;</div><? */ ?>
	
    <?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:'')); ?>
    
    <div><p>If you need to contact us for any reason, please fill in the form below. It will be automatically routed to the appropriate person.</p></div>
	<div class="pad5">&nbsp;</div>

	<form method="post">
		<table cellpadding="0" cellspacing="0" border="0" align="center" class="frame" style="width:100%">
		    <tbody><tr>
		      <td><table border="0" align="center" cellpadding="10" cellspacing="0">
		          <tbody><tr>
		            <td width="150" align="right" class="fieldarea">Name&nbsp;<span class="star">*</span></td>
		            <td><input type="text" class="inputbox" name="data[Contact][name]" value="<?=isset($name)?!isset($success)?$name:'':''?>" style="width:45%"/></td>
		          </tr>
		          <tr>
		            <td width="150" align="right" class="fieldarea">Email Address&nbsp;<span class="star">*</span></td>
		            <td><input type="text" class="inputbox" name="data[Contact][email]" value="<?=isset($email)?!isset($success)?$email:'':''?>" style="width:45%"/>&nbsp;<span>(<em>will not be published</em>)</span></td>
		          </tr>
		          <tr valign="top">
		            <td width="150" align="right" class="fieldarea">Message&nbsp;<span class="star">*</span></td>
		            <td><textarea name="data[Contact][feedback]" class="textbox"><?=isset($feedbackText)?!isset($success)?$feedbackText:'':''?></textarea></td>
		          </tr>
		          <tr valign="top">
		            <td width="150" align="right" class="fieldarea">Are you Human&nbsp;<span class="star">*</span></td>
		            <td><?=$recaptcha?></td>
		          </tr>
		          <tr>
		            <td width="150" align="right" class="fieldarea">&nbsp;</td>
		            <td><input type="submit" class="submit_button" value="Submit"></td>
		          </tr>
		        </tbody></table></td>
		    </tr>
		  </tbody>
		</table>
	</form>
	
		
</div>
