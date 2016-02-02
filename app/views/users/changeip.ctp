<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<script type="text/javascript" src="/js/sh/shBrushPhp.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>

<div class="gradient"><h1><span></span>Change IP Address</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">IP Address List</div></div>

	<div class="pad5 f13">We have introduced locking of your <strong>Domain IP</strong> to verify User’s Genuine Identity. All the SMS's sent through your account using our <strong>API</strong> will be pushed to your customers only if your <strong>Sending Domain IP matches with the IP's listed below</strong>.</div>
	
	<div class="pad5">&nbsp;</div>
	
	<form method="post" action="<?=SERVER?>users/changeip">
		<div class="pad5 f13">Valid IP Address&nbsp;<span class="star">*</span></div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[DomainIp][ip]" value=""/></span>
			&nbsp;&nbsp;
			<span><a href="#findmyip">Find my Server IP</a></span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="Add" /></span>
		</div>
	</form>	
	
	<div class="pad5">

    	<table cellpadding="4" id="hor-zebra" style="width:100%">
			<thead>
				<tr>
	    			<th scope="col"></th>
	    			<th scope="col" width="200">IP Address</th>
	    			<th scope="col">Primary</th>
	    			<th scope="col">Addition Date</th>
	    			<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
			
			<?php for($i=0; $i<count($ips); $i++) { ?>
			
				<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
					<td><?=$i+1?></td>
					<td><?=$ips[$i]['ip']?></td>
					<td><?=$ips[$i]['primary']?></td>
					<td><?=$ips[$i]['created']?></td>
					<td>
						<? if($ips[$i]['primary'] == 'NO') { ?>
							<a href="/users/changeip/delete/<?=$ips[$i]['id']?>" onclick="return confirm('Are you sure?')">Delete</a></td>
						<? } else { ?>
							<span>-</span>
						<? } ?>
					</td>	
				</tr>
				
			<?php } ?>
			
			</tbody>
		</table>
	
	</div>
	
		
	<div class="pad5">&nbsp;</div>
	
	<a id="findmyip"></a>
	<div class="header_hw"><div class="header_wrapper header_03">How to find your server IP Address</div></div>

	<div class="pad5 f13">All you have to do is call the below URL from the server where your domain is hosted. Once you obtain your IP Address please add it to your valid list as mentioned above. You can use your favourite server-side programming language to get your Outbound IP Address.</div>
	
	<div class="pad5"><pre class="brush: plain;">
		<?=SERVER?>widgets/findmyip
</pre></div>
	
	<div class="pad5">&nbsp;</div>
	
	<div class="pad5 f13">Please find below an example in PHP on how to fetch your Outbound IP Address.</div>
	
	<div class="pad5"><pre class="brush: php;">&lt;?php
		echo file_get_contents("<?=SERVER?>widgets/findmyip");
?&gt;</pre></div>

	<div class="pad5">&nbsp;</div>
	
	<div class="pad5 f13">NOTE: This feature is <strong>NOT applicable</strong> to those users who are sending SMS's through our <a href="<?=SERVER?>messages/sendnow">Web Console</a>.</div>