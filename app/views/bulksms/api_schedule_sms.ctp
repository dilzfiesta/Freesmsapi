<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>


<?php 
	if(SHOW_SENDER_ID)
		$URL = "$server</strong>/bulksms/schedulesms?skey=$secret_key&message=YOUR_MESSAGE&date=FUTURE_DATE&senderid=YOUR_SENDERID&mobile=MOBILE_NUMBERS";
	else
		$URL = "$server</strong>/bulksms/schedulesms?skey=$secret_key&message=YOUR_MESSAGE&date=FUTURE_DATE&mobile=MOBILE_NUMBERS";
?>

<div class="gradient"><h1><span></span>Schedule SMS API</h1></div>
    
    <div class="header_hw"><div class="header_wrapper header_03">URL Format</div></div>
	
	<div class="pad10">
		<div class="pad5">
			<div class="header_03">URL</div>
			<div class="header_07"><pre class="brush: plain;">
<?=$server?>/bulksms/schedulesms</span>
</pre></div>
		</div>
		<div class="pad5">&nbsp;</div>
		<div class="pad5">
			<div class="header_03">METHOD</div>
			<div class="pad5">1. POST</div>
			<div class="pad5">2. GET</div>
		</div>
		<div class="pad5">&nbsp;</div>
			<div class="pad5">
				<div class="header_03">MANDATORY PARAMETERS</div>
				<div>
					<div class="pad5">1. <strong>skey</strong> - Your secret key "<?=$secret_key?>" (<em>without quotes</em>)</div>
					<div class="pad5">2. <strong>message</strong> - Proper url encoded message. (If unsure please refer to <a href="http://www.w3schools.com/tags/ref_urlencode.asp" target="_blank">click here</a>)</div>
					<div class="pad5">3. <strong>date</strong> - Schedule date like <strong>2010-09-23_16:15</strong>, in 24 Hour Format (Year-Month-Day_Hour:Minute)<br/><span style="margin-left:50px;">Minutes should be multiple of 15. Eg: 00, 15, 30, 45.</span>
					</div>
					<? if(SHOW_SENDER_ID) { $next = 5; ?>
					<div class="pad5">4. <strong>senderid</strong> - Your Sender ID, If nothing is set then <strong><?=SMS_SENDER_ID?></strong> is used (<em>case-sensitive</em>)</div>
					<? } else { $next = 4; } ?>
					<div class="pad5"><?=$next?>. <strong>mobile</strong> - Indian Mobile Number, Can be comma seperated multiple values (<em>optional is group is passed</em>)</div>
				</div>
			</div>
		</div>
		<div class="pad5">&nbsp;</div>
			<div class="pad5">
				<div class="header_03">OPTIONAL PARAMETERS</div>
				<div>
					<div class="pad5">1. <strong>group</strong> - Group Name</div>
					<div class="pad5">2. <strong>tag</strong> - Tag Name</div>
				</div>
			</di>
		</div>
		<div class="pad5">&nbsp;</div>
		<div class="pad5">
			<div class="header_03">LIMIT</div>
			<div class="pad5">1. Only <strong>One</strong> SMS can Scheduled per request.</div>
			<div class="pad5">2. Mobile Numbers limit is <strong><?=NUMBER_LIMIT_IN_API?></strong> per request.</div>
		</div>
		<div class="pad5">&nbsp;</div>
	    <div class="pad5">
	    	<div class="header_hw"><div class="header_wrapper header_03">URL Example</div></div>
	    	<div><pre class="brush: plain;">
	    	<?=$URL?>
</pre></div>
	    </div>
	</div>	