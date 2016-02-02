<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>


<?php 
	if(SHOW_SENDER_ID) 
		$URL = "$server</strong>/bulksms/send?skey=$secret_key&message=YOUR_MESSAGE&senderid=YOUR_SENDERID&mobile=MOBILE_NUMBERS";
	else
		$URL = "$server</strong>/bulksms/send?skey=$secret_key&message=YOUR_MESSAGE&mobile=MOBILE_NUMBERS";
?>

<div class="gradient"><h1><span></span>Send SMS API</h1></div>
    
    <div class="header_hw"><div class="header_wrapper header_03">URL Format</div></div>
    <!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;margin-bottom:10px">Steps to get started</div>-->
	
	<div class="pad10">
		<div class="pad5">
			<div class="header_03">URL</div>
			<div class="header_07"><pre class="brush: plain;">
<?=$server?>/bulksms/send</span>
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
					<? if(SHOW_SENDER_ID) { $next = 4; ?>
					<div class="pad5">3. <strong>senderid</strong> - Your Sender ID, If nothing is set then <strong><?=SMS_SENDER_ID?></strong> is used (<em>case-sensitive</em>)</div>
					<? } else $next = 3; ?>
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
					<div class="pad5">3. <strong>response</strong> - Response Format, can be either JSON or XML (<em>Default is XML</em>)</div>
				</div>
			</di>
		</div>
		<div class="pad5">&nbsp;</div>
		<div class="pad5">
			<div class="header_03">LIMIT</div>
			<div class="pad5">1. Mobile Numbers limit is <strong><?=NUMBER_LIMIT_IN_API?></strong> per request.</div>
		</div>
		<!--<div class="pad5"><span class="f12"><strong>PARAMETERS</strong> - </span></div>
		<div class="pad5"><span class="f12">1. skey - your secret key <strong><?=$secret_key?></strong></span></div>
		<div class="pad5"><span class="f12">2. message - proper url encoded message. (If unsure please refer to <a href="http://www.w3schools.com/tags/ref_urlencode.asp" target="_blank">click here</a>)</span></div>
		<div class="pad5"><span class="f12">3. senderid - if nothing is set then <strong><?=SMS_SENDER_ID?></strong> is used (<em>case-sensitive</em>)</span></div>
		<div class="pad5"><span class="f12">4. tag - tag name (<em>case-sensitive, optional</em>)</span></div>
		<div class="pad5"><span class="f12">5. group - group name (<em>case-sensitive, optional if mobile is passed</em>)</span></div>
		<div class="pad5"><span class="f12">6. mobile - whom to send, Can be comma seperated multiple values (<em>optional is group is passed</em>)</span></div>
		<div class="pad5"><span class="f12">7. response - json or xml (<em>default is xml</em>)</span></div>-->
		
		<div class="pad5">&nbsp;</div>
	    <div class="pad5">
	    	<div class="header_hw"><div class="header_wrapper header_03">URL Example</div></div>
	    	<div><pre class="brush: plain;">
	    	<?=$URL?>
</pre></div>
	    </div>
	</div>	