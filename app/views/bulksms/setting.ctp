<div class="gradient"><h1><span></span>SETTINGS</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div>
	<div class="header_hw"><div class="header_wrapper header_03">Change Settings</div></div>

    <form method="post">
		<div class="pad5">
			<span><input type="checkbox" name="data[BulkSetting][daily_report]" <?=!empty($data)?$data['0']['BulkSetting']['daily_report']=='1'?'checked="checked"':'':''?>/></span>
			<span>Send me Daily SMS Report at 10:00 PM on my Mobile Phone</span>
		</div>
		<div class="pad5">&nbsp;</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" name="data[BulkSetting][submit]" class="rc_btn_01" value="Save" /></span>
		</div>
	</form>

</div>