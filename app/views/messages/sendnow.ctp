<div class="gradient"><h1><span></span>Send SMS</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">Send SMS</div></div>

<? if(!isset($verified_mobile) || $verified_mobile) { ?>

<div>
	<p>
		<span>1. You can send SMS to only <strong><?=MAX_RECIPIENT?></strong> recipients at a time.
		<br/>2. All duplicate numbers are automatically removed.
		<br/>3. All numbers are National Do Not Disturb Registry (DND) filtered.
		<br/>4. Do not send lottery, fraudulent, spam, abusive, hate messages.
		<br/>5. Please <strong>do not</strong> add '<strong>0</strong>' or '<strong>+91</strong>' in front of Mobile Number.</span>
	</p>
	
	<div><br/></div>
	
	<form method="post" enctype="multipart/form-data">
		<div class="pad5">
			<span><input type="file" name="data[file]" style="width:250px"/></span>
			<!--<span><a href="/example/csv_example.csv"><img src="/img/csv_file.png" height="50px" alt="Comma Seperated Value File" /></a>&nbsp;<a href="/example/excel_example.xls"><img src="/img/xls_file.png" height="50px" alt="MS Excel File" /></a></span>-->
		</div>
		<div class="pad5">
			<span>Please refer to example file for more details (<a href="/example/csv_example.csv">CSV</a>, <a href="/example/excel_example.xls">Excel</a>)</span>
		</div>
		<div class="pad5">&nbsp;</div>
		
		<? if(SHOW_SENDER_ID) { ?>
			<? if(!empty($senderid_data)) { ?>
			
			<div class="pad5">
				<span><select name="data[senderid]" class="dropdown" style="width:150px"><?=$senderid_data?></select></span>
				<span class="f12">Select Sender ID, <a href="/users/myaccount"><em>create sender ID</em></a></span>
			</div>
			
			<? 	$message_limit = MESSAGE_CHAR_LIMIT;
			
				} else { ?>
			
			<div class="pad5">
				<span class="header_07">Default Sender ID is <strong><?=SMS_SENDER_ID?></strong>.</span>
			</div>
			
			<? 	if(NOW > VERIFY_START_DATE) $message_limit = MESSAGE_MOBILE_CHAR_LIMIT;
				else $message_limit = MESSAGE_CHAR_LIMIT;
			
				}
			} else {
				$message_limit = MESSAGE_CHAR_LIMIT;
			}
		?>
		
		<div class="pad5">
			<span><textarea name="data[recipient]" class="bigtextarea" style="height:70px"><?=isset($recipient)?!isset($success)?$recipient:'':''?></textarea></span>
			<span class="f12">Mobile numbers (Comma seperated)</span>
		</div>
		<div class="pad5">
			<span><textarea name="data[message]" class="bigtextarea" onKeyDown="textCounter(this)" onKeyUp="textCounter(this)"><?=isset($message)?!isset($success)?$message:'':''?></textarea></span>
			<span id="counter" class="f12"><strong><?=$message_limit?></strong> characters left</span>
		</div>
		
		<? if(NOW > VERIFY_START_DATE && empty($senderid_data) && isset($verifymobile)) { ?>
		
		<div class="pad5">
			<span><strong><?=$verifymobile?></strong> will be appended to every message you send.</span><br/>
			<span><Strong>TIP</strong>: Create a <a href="/users/myaccount">Sender ID</a> to remove the above mobile number from message and use all <?=MESSAGE_CHAR_LIMIT?> Characters.</span>
		</div>
		
		<? } ?>
		
		<div class="pad5">
			<div class="pad5 header_07">Note: Freesmsapi doesn't Send SMS to DND registered numbers. To Receive Messages from Freesmsapi you must de-Register by texting <span style="color:green">STOP DND to 1909</span>.</div>
		</div>
		
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="Send" /></span>
		</div>
	</form>
</div>  

<? } else { ?>

	<div class="header_05">Please verify your Mobile Number to enjoy Uninterrupted Service.</div>
	<div class="pad5 silent_feature">
	
	<? if(empty($verifydata['verifycode'])) { ?>
	
		<span class="f12">Please verify your mobile number</span><br/><br/>
		<form action="/users/verify_mobile/1" method="post">
			<span class="f12"><input type="text" class="inputbox" name="data[mobile]" value="<?=$user_name?>" /></span>
			<span class="f12"><input type="submit" class="button" name="submit" value="verify" /></span>
		</form>
		
	<? } else { ?>
	
		<span class="f12">Please enter the verification code to complete the process</span><br/><br/>
		<form action="/users/verify_mobile/2" method="post">
			<span class="f12"><input type="text" class="inputbox" name="data[verifycode]" /></span>
			<span class="f12"><input type="submit" class="button" name="submit" value="verify" /></span>
		</form>
		<span class="f12"><a href="/users/verify_mobile/3">resend code<a> | <a href="/users/verify_mobile/4">start with new mobile number</a></span>
	
	<? } ?>
	
	</div>
	<div><br/></div>

<? } ?>

<script type="text/javascript">
function textCounter(field) {
	var maxlimit = <?=$message_limit?>;
	var counter = document.getElementById('counter');
	if (field.value.length > maxlimit) field.value = field.value.substring(0, maxlimit);
	else counter.innerHTML = '<strong>'+ Number(maxlimit - field.value.length) +'</strong> characters left';
}
</script>