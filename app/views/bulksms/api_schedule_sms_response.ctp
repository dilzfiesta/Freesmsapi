<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<script type="text/javascript" src="/js/sh/shBrushXml.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>
<div class="gradient"><h1><span></span>Schedule SMS API Response</h1></div>
    
<!--<div class="header_hw"><div class="header_wrapper header_03">API Response in XML and JSON Format</div></div>-->

<div class="pad5">
	<!--<div class="header_05">XML Format</div>-->
	<div class="header_hw"><div class="header_wrapper header_03">XML Format</div></div>
	
	<div class="pad20">
		<div>
			<div class="header_03">On Success</div>
			<div><p>The following message is displayed when API call was successfull.</p></div>
		</div>
		<div><pre class="brush: xml;"><response>
	<success>
		<message>Message Successfully Scheduled on 1st Nov, 2010 10:30 am</message>
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
			<div>1. When past date is supplied.</div>
			<div><pre class="brush: xml;"><response>
	<error>
		<message>Please provide future date</message>
	</error>
</response>
</pre></div>

			<div>2. When Date is Incorrect.</div>
			<div><pre class="brush: xml;"><response>
	<error>
		<message>Please provide minutes as multiple of 15. Eg: 00, 15, 30, 45, and Date should be of the format (Year-Month-Day_Hour:Minute)</message>
	</error>
</response>
</pre></div>

			<div>3. When Mobile Number(s) are not valid Indian Number(s).</div>
			<div><pre class="brush: xml;"><response>
	<error>
		<message>Invalid mobile number(s)</message>
		<number>6009001009</number>
		<number>3091072255</number>
	</error>
</response>
</pre></div>
		</div>
	</div>
</div>

<div><br/></div>

<div class="pad5">
	<!--<div class="header_05">JSON Format</div>-->
	<div class="header_hw"><div class="header_wrapper header_03">JSON Format</div></div>
	
	<div class="pad20">
		<div>
			<div class="header_03">On Success</div>
			<div><p>The following message is displayed when API call was successfull.</p></div>
		</div>
		<div><pre class="brush: plain;">{
	"response":{
		"success":{
			"message":"Message Successfully Scheduled on 1st Nov, 2010 10:30 am"
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
			<div>1. When past date is supplied.</div>
			<div><pre class="brush: plain;">{
	"response":{
		"error":{
			"message":"Please provide future date"
		}
	}
}
</pre></div>

			<div>2. When Date is Incorrect.</div>
			<div><pre class="brush: plain;">{
	"response":{
		"error":{
			"message":"Please provide minutes as multiple of 15. Eg: 00, 15, 30, 45, and Date should be of the format (Year-Month-Day_Hour:Minute)"
		}
	}
}
</pre></div>

			<div>3. When Mobile Number(s) are not valid Indian Number(s).</div>
			<div><pre class="brush: plain;">{
	"response":{
		"error":{
			"message":"Invalid Mobile Number(s)",
			"number":["6009001009","3091072255"]
		}
	}
}
</pre></div>
		</div>
	</div>
</div>