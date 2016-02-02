<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPhp.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/>
<script type="text/javascript">SyntaxHighlighter.all();</script>
<div class="gradient"><h1><span></span>SMS Widget</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">Widget View</div></div>

<div><br/></div>

<div class="pad5" align="center"><img src="/img/widget.png" /></div>

<div><br/></div>
<div><br/></div>

<div class="header_hw"><div class="header_wrapper header_03">Widget Download</div></div>

<div class="pad5" align="center">
	<table cellspacing="0" cellpadding="0" align="left" class="signup_btn">
		<tbody>
			<tr>
				<td class="SPRITE_signup_button_grey_l"></td>
				<td class="SPRITE_signup_button_grey_m">
					<a class="signup_btn_link" style="color:#000;text-decoration:none;" href="<?=SERVER?>widgets/download/web"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WEB WIDGET&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></a></td>
				<td class="SPRITE_signup_button_grey_r"></td>
				<td>&nbsp;&nbsp;</td>
				<td><div class="header_05">Suitable for Normal websites accessed from Web Browsers like Firefox, Internet Explorer, Chrome etc.</div></td>
			</tr>
		</tbody>
	</table>
	
	<div><br/></div>
	<div><br/></div>
	<div><br/></div>
	<div><br/></div>
	
	<table cellspacing="0" cellpadding="0" align="center" class="signup_btn">
		<tbody>
			<tr>
				<td class="SPRITE_signup_button_grey_l"></td>
				<td class="SPRITE_signup_button_grey_m">
					<a class="signup_btn_link" style="color:#000;text-decoration:none;" href="<?=SERVER?>widgets/download/wap"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WAP WIDGET&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></a></td>
				<td class="SPRITE_signup_button_grey_r"></td>
				<td>&nbsp;&nbsp;</td>
				<td><div class="header_05">Suitable for WAP based websites accessed from Mobile Browsers like Opera Mini.</div></td>
			</tr>
		</tbody>
	</table>
</div>

<div><br/></div>
<div><br/></div>

<div class="header_hw"><div class="header_wrapper header_03">Steps to Install (For PHP Based Websites)</div></div>
<div class="pricing_menu">
	<ul>
		<li>Download suitable ZIP file from above.</li>
		<li>Unzip it into a folder.</li>
		<li>Open <strong>freesmsapi_config.php</strong> in your favourite text editor.</li>
		<li>Change <strong>FREESMSAPI_SECRET_KEY</strong> with your Secret Key.</li>
		<li>Now, open <strong>freesmsapi_index.php</strong> in a browser and start sending SMS.</li>
	</ul>
</div>

<div><br/></div>
<div><br/></div>

<div class="header_hw"><div class="header_wrapper header_03">Widget Config File</div></div>
<div class="pad5 f12">
	<span><pre class="brush: php;">
	&lt;?php
	@session_start();

	/*	Change this with your Secret Key	*/
	define("FREESMSAPI_SECRET_KEY", "put your secret key here");
	</pre></span>
</div>
