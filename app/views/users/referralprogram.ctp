<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>

<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>

	<div class="gradient"><h1><span></span>Referral Program</h1></div>
	
	<div class="header_hw"><div class="header_wrapper header_03">Website Referral Program Scheme</div></div>
	
	<div class="pad5">
		<div class="header_06">What Should I Do?</div>
		<div>
			<p>1. Copy the below <strong>Referral Link</strong> and paste it in your Website.
			<br/>2. On every successful registration using the below link, you will get a <strong>Referral Point</strong>.
			<br/>3. Referral Points are nothing but the total number of websites referred by you.</p>
			<pre class="brush: plain;"><?=$ref_link?></pre>
		</div>
	</div>
	
	<div class="pad5">&nbsp;</div>
	
	<div class="pad5">
		<div class="header_06">What Will I Get?</div>
		<p>1. On every 5 websites you refer, an additional 50 SMS's will be added to your existing SMS quota.
		<br/>2. Please refer to the following table for more details.</p>
	</div>
	
	<div class="pad5">&nbsp;</div>
	
	<table cellpadding="4" id="hor-zebra" style="width:50%;margin-top:0px">
		<thead>
			<tr>
				<th scope="col"></th>
				<th scope="col">Websites Referred</th>
				<th scope="col">Increase in SMS Quota</th>
			</tr>
		</thead>
		<tbody>
		
		<?php $count = 0; foreach($data as $k => $v) { ?>
		
			<tr align="center" valign="top" <?=((++$count%2)==0)?'class="odd"':''?>>
				<td><?=$count?></td>
				<td><?=$k?></td>
				<td><?=$plan[$v]?></td>
			</tr>
			
		<?php } ?>
			
		</tbody>
	</table>
	
</div>	