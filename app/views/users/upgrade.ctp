<div class="gradient"><h1><span></span>Upgrade for Free</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
    
<div class="header_hw"><div class="header_wrapper header_03">Upgrage Your SMS Quota</div></div>
<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;" align="left">Upgrage Your SMS Quota</div>-->

<div><br/></div>

<div class="f12"><span>Yes, its true, just tell us why do you need additional free SMS and we will give it to you for <strong>FREE</strong></span></div>

<form method="post">
	<div class="pad5">
		<span><textarea name="data[Upgrade][requestbody]" class="bigtextarea"></textarea></span>
		<span></span>
	</div>
	<div class="pad5"></div>
	<div class="pad5">
		<span><input type="submit" class="rc_btn_01" value="submit" /></span>
	</div>
</form>

<table cellpadding="4" id="hor-zebra" style="width:100%">
	<thead>
		<tr>
			<th scope="col"></th>
			<th scope="col">Request</th>
			<th scope="col" width="75px">Posted on</th>
			<th scope="col">Status</th>
		</tr>
	</thead>
	<tbody>
	
	<?php if(!empty($upgrade_data)) { ?>
	
	<?php for($i=0; $i<count($upgrade_data); $i++) { ?>
	
		<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
			<td><?=$i+1?></td>
			<td><?=$upgrade_data[$i]['Upgrade']['requestbody']?></td>
			<td><?=date('M j, Y', strtotime($upgrade_data[$i]['Upgrade']['created']))?></td>
			<td><?=($upgrade_data[$i]['Upgrade']['approve'] == null) ? 'PENDING' : (($upgrade_data[$i]['Upgrade']['approve']) ? 'APPROVED' : 'DECLINED')?></td>
		</tr>
		
	<?php } ?>
	
	<?php } else { ?>

		<tr valign="top">
			<td colspan="4" align="center">NO REQUEST MADE SO FAR</td>
		</tr>
	
	<?php } ?>
		
	</tbody>
</table>