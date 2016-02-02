<div class="gradient"><h1><span></span>List of Groups</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">Add Group</div></div>
<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;" align="left">Add Group</div>-->

<div><br/></div>

<div>
	<form method="post">
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[BulkGroup][name]" value="<?=$name?>" /></span>
			<span>Group Name (<i>eg: Dealers, Clients etc</i>)</span>
		</div>
		<div class="pad5">
			<textarea name="data[BulkGroup][narration]" class="textarea"><?=$narration?></textarea>
			<span>Narration (<i>Optional, Small Description</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="submit" class="rc_btn_01" value="create group" /></span>
			<input type="hidden" name="data[BulkGroup][id]" value="<?=(isset($group_id)?$group_id:'')?>" />
		</div>
	</form>
</div>

<table cellpadding="4" id="hor-zebra" style="width:100%">
	<thead>
		<tr>
			<th scope="col"></th>
			<th scope="col">Name</th>
			<th scope="col">Narration</th>
			<th scope="col">Contacts</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	
	<?php if(!empty($data)) { ?>
	
	<?php for($i=0; $i<count($data); $i++) { ?>
	
		<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
			<td><?=$i+1?></td>
			<td><?=$data[$i]['BulkGroup']['name']?></td>
			<td><?=$data[$i]['BulkGroup']['narration']?></td>
			<td><?=$contacts[$data[$i]['BulkGroup']['id']]?></td>
			<td>
				<? if(strtolower($data[$i]['BulkGroup']['name']) != 'general') { ?>
				<a href="/bulksms/groups/<?=$data[$i]['BulkGroup']['id']?>">Edit</a>&nbsp;/&nbsp;<a onclick="return confirm(<?=in_array($data[$i]['BulkGroup']['id'], $schedulelist)?'\'Deleting this group will delete all the Scheduled SMS for this Group. Delete anyway?\'':'\'Are you sure?\''?>)" href="/bulksms/delete/groups/<?=$data[$i]['BulkGroup']['id']?>">Delete</a>
				<? } else echo '&nbsp;'; ?>
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