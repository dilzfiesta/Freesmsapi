<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<script type="text/javascript" src="/js/sh/shBrushXml.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>
<div class="gradient"><h1><span></span>Check Balance</h1></div>

<div class="pad5">
	<div class="header_hw"><div class="header_wrapper header_03">API To Check Balance</div></div>
	<div><pre class="brush: plain;"><?=$server?>/bulksms/balance?skey=<?=$secret_key?>
</pre></div>
</div>

<div><br/><br/></div>

<div class="pad5">
	<div class="header_hw"><div class="header_wrapper header_03">Response</div></div>
	
	<div class="pad20">
		<div class="pad5 header_04">XML Format</div>
		<div>
			<div class="header_03">On Success</div>
			<div><p>The following message is displayed when API call was successfull.</p></div>
		</div>
		<div><pre class="brush: xml;"><response>
	<success>
		<amount>122.11</amount>
		<quantity>1316</quantity>
		<validtill>2010-12-31 00:00:00</validtill>
	</success>
</response>
</pre></div>
	</div>
	
	<div class="pad20">
		<div>
			<div class="header_03">On Error</div>
			<div><p>The following message is displayed when API call has encountered some error.</p></div>
		</div>
		
		<div class="pad10">
			<div>When Secret Key is Invalid.</div>
			<div><pre class="brush: xml;"><response>
	<error>
		<message>Invalid Secret Key</message>
	</error>
</response>
</pre></div>
		</div>
	</div>
</div>

<div><br/></div>

<div class="pad5">
	
	<div class="pad20">
		<div class="pad5 header_04">JSON Format</div>
		<div>
			<div class="header_03">On Success</div>
			<div><p>The following message is displayed when API call was successfull.</p></div>
		</div>
		<div><pre class="brush: plain;">{
	"response":{
		"success":{
			"amount":"122.11",
			"quantity":"1316",
			"validtill":"2010-12-31 00:00:00"
		}
	}
}
</pre></div>
	</div>
	
	<div class="pad20">
		<div>
			<div class="header_03">On Error</div>
			<div><p>The following message is displayed when API call has encountered some error.</p></div>
		</div>
		
		<div class="pad10">
			<div>When Secret Key is Invalid.</div>
			<div><pre class="brush: plain;">{
	"response":{
		"error":{
			"message":"Invalid Secret Key"
		}
	}
}
</pre></div>
		</div>
	</div>
</div>