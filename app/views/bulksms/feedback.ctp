<div class="gradient"><h1><span></span>Send us a message</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">Feedback</div></div>
<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;" align="left">Feedback</div>-->

<div class="f12"><span>Please feel free to send us your comments or suggesstions.</span></div>

<form method="post">
	<div class="pad5">
		<span><textarea name="data[BulkFeedback][feedback]" class="textbox"><?=isset($feedbackText)?!isset($success)?$feedbackText:'':''?></textarea></span>
		<span>Feedback</span>
	</div>
	<div class="pad5" style="margin-left:-5px">
		<span><input type="submit" class="rc_btn_01" value="submit" /></span>
	</div>
</form>