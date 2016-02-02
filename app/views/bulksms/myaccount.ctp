<div class="gradient"><h1><span></span>MY ACCOUNT</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
<?php echo $this->renderElement('success_error', array('error'=>isset($tag_error)?$tag_error:'', 'success'=>isset($tag_success)?$tag_success:''));  ?>

<div id="change_alias">
	<a href="#" id="sender"></a>
	<div class="header_hw"><div class="header_wrapper header_03">Create Sender ID</div></div>
	<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;margin-bottom:10px;">Create Sender ID</div>-->

	<?php if(SHOW_SENDER_ID) { ?>

	<div>
		<span>1. Sender ID should not be greater than <strong>8 Chars</strong>.
		<br/>2. Sender ID should not contain spaces and is <strong>case-sensitive</strong>.</span><br/><br/>
	</div>

	<form method="post">
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkSenderid][name]" value="" /></span>
			<span>Sender ID (<i>eg: Dealers, Clients etc</i>)</span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="create sender ID" /></span>
			<input type="hidden" name="data[BulkSenderid][id]" value="<?=(isset($senderid_id)?$senderid_id:'')?>" />
		</div>
	</form>
    
    <table cellpadding="4" id="hor-zebra" style="width:100%">
		<thead>
			<tr>
    			<th scope="col"></th>
    			<th scope="col" width="200">Name</th>
    			<th scope="col">DND Filter</th>
    			<th scope="col">Status</th>
    			<th scope="col">Creation date</th>
    			<th></th>
			</tr>
		</thead>
		<tbody>
		
		<?php if(!empty($data)) { ?>
		
		<?php for($i=0; $i<count($data); $i++) { ?>
		
			<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
				<td><?=$i+1?></td>
				<td><?=$data[$i]['BulkSenderid']['name']?></td>
				<td><?=strtoupper($data[$i]['BulkSenderid']['dnd'])?></td>
				<td><?=($data[$i]['BulkSenderid']['publish']) ? 'Active' : 'Pending Approval'?></td>
				<td width="120"><?=date('j M Y g:i a', strtotime($data[$i]['BulkSenderid']['created']))?></td>
				<td><a href="/bulksms/delete/senderid/<?=$data[$i]['BulkSenderid']['id']?>" onclick="return confirm('Are you sure?')">Delete</a></td>
			</tr>
			
		<?php } ?>
		
		<?php } else { ?>

    		<tr valign="top">
				<td colspan="5" align="center">NO ENTRIES FOUND</td>
			</tr>
    	
    	<?php } ?>
			
		</tbody>
	</table>
	
	<div class="f12" style="margin:-20px 0px 20px 0px">
		<span>If you like to remove DND filter please follow the below mentioned instructions<span><br/>
		<div class="list">
			<ul style="margin:10px 0px 10px 15px">
				<li>Create a Sender ID (Should not be a personal names).</li>
				<li>Print the content of the <a href="/example/NDNC_DECLARATION.doc" target="_blank">Document</a> onto you company's letter head.</li>
				<li>Return the scanned copy of the document with an authorized signature and stamp of the company/organization.</li>
			</ul>
		</div>
		<span>Once verified your DND filter will be removed and you will be able to send SMS to all DND and Non-DND mobile users.</span>
	</div>
	
	<?php } else { ?>
	
	<? /* ?><div class="f12" style="margin:-20px 0px 20px 0px">
		<div><em>TIP: Shorten your domain name or your company name to use as your new Sender ID</em></div>
		<div><em>NOTE: It will take 24 hours to verify and activate your new Sender ID</em></div>
	</div><? */ ?>
	
	<div class="error" style="margin-bottom:-10px">
		<div class="pad5">According to the New TRAI guidelines, No SMS provider is allowed to deliver SMSes to NDNC registered mobile numbers. So, Starting from 27th Sept, 2011 Freesmsapi will not deliver SMSes to NDNC registered mobile numbers. However, we will display NDNC registered mobile numbers in your delivery reports with delivery status as 'DND'.</div>
		<div class="pad5">All messages sent via Freesmsapi will be sent as “ TD- XXXXXX” as the sender (XXXXXX is the Unique number allotted by Telecom operator to Freesmsapi). Sender ID service will be terminated and you no longer can use your own Sender ID starting 27th Sept, 2011.</div>
	</div>
	
	<?php } ?>
	
</div>

<div style="margin-top:30px">&nbsp;</div>

<div id="change_tag">
	<a href="#" id="tag"></a>
	<div class="header_hw"><div class="header_wrapper header_03">Create Message Tags</div></div>
	<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;margin-bottom:10px">Create Message Tags</div>-->

	<form method="post">
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkSmsTag][name]" value="" /></span>
			<span>Tags (<i>eg: Personal, Marketing, Promotional etc</i>)</span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="create message tag" /></span>
			<input type="hidden" name="data[BulkSmsTag][id]" value="<?=(isset($bulk_sms_tag_id)?$bulk_sms_tag_id:'')?>" />
		</div>
	</form>
    
    <table cellpadding="4" id="hor-zebra" style="width:100%">
		<thead>
			<tr>
    			<th scope="col"></th>
    			<th scope="col" width="200">Name</th>
    			<th scope="col">Creation date</th>
    			<th></th>
			</tr>
		</thead>
		<tbody>
		
		<?php if(!empty($tagdata)) { ?>
		
		<?php for($i=0; $i<count($tagdata); $i++) { ?>
		
			<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
				<td><?=$i+1?></td>
				<td width="300"><?=$tagdata[$i]['BulkSmsTag']['name']?></td>
				<td width="220"><?=date('j M Y g:i a', strtotime($tagdata[$i]['BulkSmsTag']['created']))?></td>
				<td width="100">
					<? if(strtolower($tagdata[$i]['BulkSmsTag']['name']) != 'general') { ?>
					<a href="/bulksms/delete/tag/<?=$tagdata[$i]['BulkSmsTag']['id']?>" onclick="return confirm('Are you sure?')">Delete</a>
					<? } else echo '&nbsp';  ?>
				</td>
			</tr>
			
		<?php } ?>
		
		<?php } else { ?>

    		<tr valign="top">
				<td colspan="5" align="center">NO ENTRIES FOUND</td>
			</tr>
    	
    	<?php } ?>
			
		</tbody>
	</table>
	
</div>

<div style="margin-top:20px">&nbsp;</div>

<div id="change_password">
	<a href="#" id="pass"></a>
	<div class="header_hw"><div class="header_wrapper header_03">Change Password</div></div>
    <!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;margin-bottom:10px">Change Password</div>-->

    <form method="post">
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[BulkUser][password]" value=""/></span>
			<span>Your old password</span>
		</div>
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[BulkUser][new_password]" value=""/></span>
			<span>New password (Should be greater or equal to 8 characters)</span>
		</div>
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[BulkUser][retype_password]" value=""/></span>
			<span>Retype new password</span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="change password" /></span>
		</div>
	</form>

</div>
