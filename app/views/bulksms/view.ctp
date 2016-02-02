<div class="gradient"><h1><span></span>Welcome <?=ucwords($bulk_user['BulkUserpersonalinfo']['firstname']).' '.ucwords($bulk_user['BulkUserpersonalinfo']['lastname'])?></h1></div>

<div class="header_hw"><div class="header_wrapper header_03">Latest News From TRAI</div></div>

<!-- <div class="header_03">
	<div class="pad5">According to the New TRAI guidelines, No SMS provider is allowed to deliver SMSes to NDNC registered mobile numbers. So, Starting from 27th Sept, 2011 Freesmsapi will not deliver SMSes to NDNC registered mobile numbers. However, we will display NDNC registered mobile numbers in your delivery reports with delivery status as 'DND'.</div>
	<div class="pad5">All messages sent via Freesmsapi will be sent as “ TD- XXXXXX” as the sender (XXXXXX is the Unique number allotted by Telecom operator to Freesmsapi). Sender ID service will be terminated and you no longer can use your own Sender ID starting 27th Sept, 2011.</div>
	<div class="pad5">No SMS will be send between 9pm and 9am.</div>
</div> -->

<div class="header_03">
	<div class="pad5">By the time you all might aware about the termination charge of 5 paisa + Tax per SMS imposed by TRAI. In order to average the price and provide un-interrupted service we will be deducting 5.6 paise (5 paise + 10.3% Service Tax) per SMS from your account from 03-11-2011 onwards.</div>
	<div class="pad5">For example, if you had purchased a sms pack at 10 paise per sms then according to this rule your new cost will become 15.6 paise per sms.</div>
	<div class="pad5">As you can see we have average out your sms quantity as per the new rule. If you have any query, please feel free to <a href="<?=SERVER?>bulksms/feedback">contact us</a>.</div>
</div>

<div class="pad5">&nbsp;</div>

<div class="header_hw"><div class="header_wrapper header_03">Important Information</div></div>
<!--<div class="header_03"><span>Important Information</span></div>-->
<div class="pad5 f12">
        <p class="pad5">Service started on: <strong><?=date('jS M Y', strtotime($bulk_account['created']))?></strong></p>
        <p class="pad5">Service expire on: <strong><?=date('jS M Y', strtotime($validity))?></strong></p>
        <p class="pad5">Total SMS Reminaing: <strong><?=$t->format_money($bulk_account['quantity'])?> SMS</strong></p>
        <p class="pad5">Total Amount Reminaing: <strong>Rs. <?=$t->format_money($bulk_account['amount'], true)?></strong></p>
        <p class="pad5">&nbsp;</p>
        <div class="header_03"><a href="javascript:void(0)" onclick="$('#recharge_details').slideToggle('slow')">List of Recharge(s)</a></div>
        <div id="recharge_details" style="display:none">
        <?php 
        	$bulk_account_recharge = array_reverse($bulk_account_recharge);
        	foreach($bulk_account_recharge as $k => $v) { 
        ?>
        	<p class="pad5"><?=$k+1?>.&nbsp;On <strong><?=date('jS M Y', strtotime($v['created']))?></strong> with Rs.<strong><?=$t->format_money($v['amount'])?></strong> to avail <strong><?=$t->format_money($v['quantity'])?></strong> SMS.</p>
        <? } ?>
        </div>
</div>

<div><br/></div>
<div><br/></div>

<!--<div style="margin-left:-10px">
    <span><input type="button" class="rc_btn_02" value="Send SMS" onclick="window.location.replace('/bulksms/sendnow');" /></span>
    <span><input type="button" class="rc_btn_02" value="Reports" onclick="window.location.replace('/bulksms/showreport');" /></span>
    <span><input type="button" class="rc_btn_02" value="Feedback" onclick="window.location.replace('/bulksms/feedback');" /></span>
</div>

<div><br/></div>
<div><br/></div>-->

<div style="margin:5px 0px 30px 0px">
	<div class="header_03"><span>Note</span></div>
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