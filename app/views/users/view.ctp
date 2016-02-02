<div class="gradient"><h1><span></span>Welcome <?php echo $domain_name; ?></h1></div>

<? if(!$verified_mobile) { ?>

	<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
	<div class="header_03">Verify your Mobile Number</div>
	<div class="pad5 silent_feature">
	
	<? if(empty($verifydata['verifycode'])) { ?>
	
		<span class="f12">Please verify your mobile number</span><br/><br/>
		<form action="/users/verify_mobile/1" method="post">
			<span class="f12"><input type="text" class="inputbox" name="data[mobile]" value="<?=$user_name?>" /></span>
			<span class="f12"><input type="submit" class="button" name="submit" value="verify" /></span>
		</form>
		<br/>
		<span class="f12"><i>Note: Verification code will not be delivered if you are registered with ndnc</i></span>
		
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

<? } else { ?>

	<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<? } ?>

<div class="header_hw"><div class="header_wrapper header_03">Latest News From TRAI</div></div>

<div class="header_03">
	<div class="pad5">According to the New TRAI guidelines, No SMS provider is allowed to deliver SMSes to NDNC registered mobile numbers. So, Starting from 27th Sept, 2011 Freesmsapi will not deliver SMSes to NDNC registered mobile numbers. However, we will display NDNC registered mobile numbers in your delivery reports with delivery status as 'DND'.</div>
	<div class="pad5">All messages sent via Freesmsapi will be sent as “ TD- XXXXXX” as the sender (XXXXXX is the Unique number allotted by Telecom operator to Freesmsapi). Sender ID service will be terminated and you no longer can use your own Sender ID starting 27th Sept, 2011.</div>
	<div class="pad5">No SMS will be send between 9pm and 9am.</div>
</div>

<div class="pad5">&nbsp;</div>

<div class="header_hw"><div class="header_wrapper header_03">New Feature</div></div>

<!--<div class="pad5 header_08">Got "Invalid IP Address, SMS's are accepted only from the IP Address xx.xx.xx.xx" Error.</div>-->
<div class="pad5 header_08">Got error "Invalid Domain" while using API?</div>
<div class="pad5"><a href="/users/changeip">click here to fix it</a></div> 

<div class="pad5">&nbsp;</div>
<hr/>
<div class="pad5">&nbsp;</div>

<div class="pad5 header_08">Widget for sending SMS (PHP version).</div>
<div class="pad5"><a href="/widgets/view">click here to learn more</a></div>

<div class="pad5">&nbsp;</div>

<div class="header_hw"><div class="header_wrapper header_03">Important Information</div></div>

<div class="pad5 header_05">Currently <span class="header_06"><?=$currentstatus?></span>  website(s) has been referred by you.</div><a href="/users/refprogram">click here to know more</a>

<div class="pad5">&nbsp;</div>
<hr/>
<div class="pad5">&nbsp;</div>

<div class="header_03"><span>SMS Info</span></div>
<div class="pad5"><span class="f12">Your daily sms limit is <strong><?php echo $plan_sms; ?> SMS</strong></span></div>

<div class="pad5"><span class="f12">Only Indian and non-NDNC mobile numbers are allowed to recieve SMS</span></div>
<div class="pad5"><span class="f12">Max recipient should be less than or equal to <strong><?=$max_recipient?></strong> in a single request</span></div>
<div class="pad5"><span class="f12">Character limit per SMS is <strong><?=MESSAGE_CHAR_LIMIT?></strong></span></div>

<div class="pad5">&nbsp;</div>
<hr/>
<div class="pad5">&nbsp;</div>

<?php if(IP_LOCK) { ?>
<div class="header_03"><span>IP LOCK</span></div>
<div class="pad5"><span class="f12">Call this API from where your website is hosted ie from <strong><?=$ip?></strong></span></div>
<div class="pad5"><span class="f12">Use <strong>Server-Side Scripting Languages</strong> such as PHP, PERL, JAVA etc</span></div>
<div class="pad5">&nbsp;</div>
<hr/>
<div class="pad5">&nbsp;</div>
<?php } ?>

<div class="header_03"><span>Secret Key</span></div>
<div>
	<div class="pad5"><span class="f12">You can use any of the two as your Secret Key -</span></div>
	<div class="pad5"><span><strong><?php echo $secret_key; ?></strong></span></div>
	<div class="pad5"><span><strong>-- OR --</strong></span></div>
	<div class="pad5"><span><strong>Username + Password</strong></span></div>
	<div class="pad5">(eg: if your Username is "<?=DUMMY_MOBILE?>" and your Password is "freesmsapi", then use the Secret Key as "<?=DUMMY_MOBILE?>freesmsapi")</div>
	<!--<div class="pad5"><span class="f12">OR use this secret key in API to send SMS - <strong><?php echo $secret_key; ?></strong></span></div>-->
	<!--<div class="pad5"><span class="f12">How to use API to send SMS - <a href="/users/help"><em>click here</em></a></span></div>-->
</div>

<div class="pad5">&nbsp;</div>
<hr/>
<div class="pad5">&nbsp;</div>

<div class="header_03"><span>Statistics</span></div>
<div>
	<div class="pad5"><span class="f12">Total number of SMS send -> <strong><?php echo $total_sms; ?></strong></span></div>
	<div class="pad5"><span class="f12">Total number of SMS send TODAY -> <strong><?php echo $total_sms_today; ?>/<?php echo $plan_sms; ?></strong></span></div>
	<!--<div class="pad5"><span class="f12">To view delivery reports (Outbox) -> <a href="/users/showreport"><em>click here</em></a></span></div>-->
</div>

<div class="pad5">&nbsp;</div>
<hr/>
<div class="pad5">&nbsp;</div>

<!--<div style="margin-left:-8px">
    <span><input type="button" class="rc_btn_02" value="Send SMS" onclick="window.location.replace('/messages/sendnow');" /></span>
    <span><input type="button" class="rc_btn_02" value="Reports" onclick="window.location.replace('/users/showreport');" /></span>
    <span><input type="button" class="rc_btn_02" value="Upgrade" onclick="window.location.replace('/users/upgrade');" /></span>
</div>

<div><br/></div>
<div><br/></div>-->

<div class="header_03"><span>Note</span></div>
<div class="pad5">
	<p>
			This is to notify the users of Free SMS API that a few systematic cheating rackets are operating on SMS 
			platform propagating lottery/prize/ unclaimed huge properties etc. This is totally objectionable by 
			the <a href="/img/policecrime.jpg" target="_blank">Police Crime Branch</a> 
			as well against TRAI norms. These messages are under spam and comply for stringent and strict action by 
			the respective departments. All the users are advised to adhere with the instructions and not to promote 
			such activities. Violation of this intimation will automatically invite serious legal action and 
			Free SMS API will not be responsible for the same and shall share the client information to the 
			respective departments.
	</p>
</div>

<?php if(FREE_USER_NOTICE && $show_notice) { ?>

<div title="IP ADDRESS LOCK" align="left" class="f12" id="notice" style="display:none">
	<div class="pad5">We have introduced locking of your <strong>Domain IP</strong> to verify User’s Genuine Identity. All the messages sent through your account using our <strong>API</strong> will be pushed to your customers only if your <strong>Sending Domain IP match with the IP of your registered Domain</strong> at Freesmsapi.</div>
	<?php if(!$no_ip) { ?>
		<div class="pad5">Your domain IP address is <strong>'<?=$domain_ip?>'</strong>, and messages will only be pushed if it is sent from the your IP address, rest of the messages will be rejected.</div>
	<?php } else { ?>
		<div class="pad5">As per our records, your domain is <strong>NOT HOSTED</strong> as we are unable to find IP Address associated with your domain and therefore all the messages will be <strong>rejected</strong>.</div>
	<?php } ?>
	<div class="pad5">We will implement this <strong>IP Lock from <span class="header_03">1st August 2011</span></strong>, and request you to make the necessary changes to your code base before the stipulated deadline to avoid any inconviences later.</div>
	<div class="pad5">Please note that this feature is <strong>NOT applicable</strong> for those users who are sending messages through our <strong>Web Console.</strong></div>
</div>
<script type="text/javascript">
$(function() {
	$("#notice").dialog({
			modal: true,
			width:500,
			buttons: {
				Close: function() {
					$( this ).dialog( "close" );
				}
			}
		});
});
</script>

<? } ?>
