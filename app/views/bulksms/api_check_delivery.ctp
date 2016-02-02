<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<script type="text/javascript" src="/js/sh/shBrushXml.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>
<div class="gradient"><h1><span></span>Check Delivery Status</h1></div>

<div class="pad5">
	<div class="header_hw"><div class="header_wrapper header_03">Check Delivery Status</div></div>
	
	<div class="pad20">
		<div><pre class="brush: plain;"><?=$url?>
 
</pre></div>
		<div>
			<div class="header_03">NOTE</div>
			<p>1. <strong>skey</strong> is your Secret Key.
			<br/>2. Replace "RESPONSE_KEY" with your "<strong>rid</strong>".
			<br/>3. <strong>rid</strong> can be comma seperated values.
			<br/>4. To get response in JSON format use "<strong>response=json</strong>" in the URL.</p>
		</div>
	</div>
	
	<div class="pad5">
		<div class="header_03">LIMIT</div>
		<div class="pad5">1. Response Key limit is <strong><?=RID_LIMIT_IN_API?></strong> per request.</div>
	</div>
	<div class="pad5">&nbsp;</div>
	<div>
		<div class="header_03">Example</div>
		<div><pre class="brush: plain;">http://s1.freesmsapi.com/bulksms/response?skey=<?=$skey?>&key=1299740741-2010_08_11,1299705955-2010_08_11&response=xml
 
</pre></div>
	</div>
</div>

<div><br/></div>

<div class="pad5">
	<div class="header_hw"><div class="header_wrapper header_03">Response</div></div>
	
	<div class="pad20">
		<div class="pad5 header_04">XML Format</div>
		<div>
			<div class="header_03">On Success</div>
			<div><p>The following message is displayed when API call was successfull.</p></div>
		</div>
		<div><pre class="brush: xml;"><response>
	<contacts>
		<rid>1299705955-2010_08_11</rid>
		<status>DELIVERED</status>
	</contacts>
	<contacts>
		<rid>1299740741-2010_08_11</rid>
		<status>DELIVERED</status>
	</contacts>
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
		<message>Invalid Response ID</message>
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
		"contacts":[
			{"rid":"1299705955-2010_08_11","status":"DELIVERED"},
			{"rid":"1299740741-2010_08_11","status":"DELIVERED"}
		]
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
			"message":"Invalid Response ID"
		}
	}
}
</pre></div>
		</div>
	</div>
</div>