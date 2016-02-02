<div class="gradient" align="center"><h1><span></span>Scheduled SMS Reports</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="pad5">
	
	<div class="header_hw"><div class="header_wrapper header_03">All Scheduled SMS</div></div>
	<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px; margin-bottom:-20px">All Scheduled SMS</div>-->
	
	<table cellpadding="4" id="hor-zebra" style="width:100%">
		<thead>
			<tr>
    			<th scope="col"></th>
    			<th scope="col">Message</th>
    			<th scope="col">Group</th>
    			<!--<th scope="col">SenderID</th>
    			<th scope="col">Tag</th>-->
    			<th scope="col">Mobile</th>
    			<th scope="col">Schedule</th>
    			<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
		
		<?php if(!empty($sc_data)) { ?>
		
		<?php for($i=0; $i<count($sc_data); $i++) { ?>
		
			<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
				<td><?=$i+1?></td>
				<td><?=wordwrap($sc_data[$i]['BulkSmsSchedule']['message'], 40, "<br/>", true)?></td>
				<td><?=isset($sc_data[$i]['BulkGroup']['name']) ? $sc_data[$i]['BulkGroup']['name'] : '-'?></td>
				<!--<td>< ?=(!isset($senderid[$sc_data[$i]['BulkSmsSchedule']['bulk_senderid_id']])) ? SMS_SENDER_ID : $senderid[$sc_data[$i]['BulkSmsSchedule']['bulk_senderid_id']]?></td>
				<td>< ?=$tags[$sc_data[$i]['BulkSmsSchedule']['bulk_tag_id']]?></td>-->
				<td width="20px"><?=!empty($sc_data[$i]['BulkSmsSchedule']['numbers']) ? wordwrap($sc_data[$i]['BulkSmsSchedule']['numbers'], 11, "\n", true) : '-'?></td>
				<td width="120"><?=date('j M Y g:i a', strtotime($sc_data[$i]['BulkSmsSchedule']['scheduledate']))?></td>
				<td width="20"><a href="/bulksms/delete/schedule/<?=$sc_data[$i]['BulkSmsSchedule']['id']?>" onclick="return confirm('Are you sure?')">Delete</a></td>
			</tr>
			
		<?php } ?>
		
		<?php } else { ?>

    		<tr valign="top">
				<td colspan="6" align="center">NO ENTRIES FOUND</td>
			</tr>
    	
    	<?php } ?>
			
		</tbody>
	</table>
	
</div>