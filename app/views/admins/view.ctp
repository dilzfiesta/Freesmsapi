<div class='pad5 f12'>
	<? foreach($quantity_left as $k => $v) { ?>
		<div><?=$k?> => <?=$v?></div>
	<? } ?>
</div>
<br/>
<table style="width:100%;text-align:left">

	<thead>
		<th><strong>ID</strong></th>
		<th><strong>NAME</strong></th>
		<!--<th><strong>PLAN</strong></th>-->
		<th><strong>SMS</strong></th>
		<th><strong>SEND</strong></th>
		<!--<th><strong>CATEGORY</strong></th>-->
		<!--<th><strong>IP ADDRESS</strong></th>-->
		<th><strong>USER EMAIL</strong></th>
		<th><strong>MOBILE</strong></th>
		<th><strong>VERIFIED</strong></th>
		<th><strong>CREATED</strong></th>
		<th><strong>LAST LOGIN</strong></th>
		<th><strong>SUSPEND</strong></th>
		<th><strong>SMS</strong></th>
	</thead>

<?php 	$count=1; 
		foreach($data as $key => $value) { 
		
			
		$suspended = false;
		// suspended (terminated)
		if($value['User']['status'] || (isset($user_suspend_list[$value['User']['id']]) && in_array($user_suspend_list[$value['User']['id']], array(1,5)))) { 
			$color = '#e5b5b7';
			$suspended = true;
		
		// forced to use widget	
		//} else if(isset($user_suspend_list[$value['User']['id']]) && in_array($user_suspend_list[$value['User']['id']], array(2,3))) {
		} else if($value['Domain']['widget']) {
			$color = '#fffcb5';
		} else {
			$color = '#FFFFFF';
		}
?>
	
	<tr style="background-color:<?=$color?>" alt="<?=$value['User']['reasontodelete']?>">
		<td><?=$count++?></td>
		<td><?=$value['Domain']['name']?></td>
		<!--<td><?=$value['Plan']['name']?></td>-->
		<td><?=$value['Plan']['sms']?></td>
		<td><?=$total_message[$value['Domain']['id']]?></td>
		<!--<td><?=$value['Category']['name']?>&nbsp;<a href="/admins/changecategory/<?=$value['Domain']['id']?>">change</a></td>-->
		<!--<td><?=$value['Domain']['ip']?>&nbsp;<a href="/admins/changeip/<?=$value['Domain']['id']?>">check</a></td>-->
		<td><?=$value['User']['email']?></td>
		<td><?=$value['User']['name']?></td>
		<td><?=$value['User']['verify'] ? $value['User']['verifymobile'] : '-'?></td>
		<td><?=date('j M Y g:i a', strtotime($value['Domain']['created']))?></td>
		<td><?=date('j M Y g:i a', strtotime($value['Domain']['updated']))?></td>
		<!--<td><?=$value['Domain']['status']?>&nbsp;<a href="/admins/changestatus/domain/<?=$value['Domain']['id']?>">change</a></td>-->
		<? if(!$suspended) { ?>
		<td><a href="javascript:void(0)" onclick="suspend_dialog();$('#suspend_user_id').val(<?=$value['User']['id']?>);">suspend</a></td>
		<? } else { ?>
		<td>&nbsp;</td>
		<? } ?>
		<td><a href="/admins/viewsms/<?=$value['Domain']['id']?>">view</a></td>
	</tr>
	
<?php } ?>

</table>

<br/><br/><br/>

<table style="width:100%;text-align:left">

	<thead>
		<th><strong>ID</strong></th>
		<th><strong>NAME</strong></th>
		<th><strong>DOMAIN</strong></th>
		<th><strong>EMAIL</strong></th>
		<th><strong>CREATED</strong></th>
		<th><strong>INC REG MAIL</strong></th>
	</thead>

<?php $count=1; foreach($user_tmp as $key => $value) { ?>
	
	<tr>
		<td><?=$count++?></td>
		<td><?=$value['UserTmp']['name']?></td>
		<td><?=$value['UserTmp']['domain']?></td>
		<td><?=$value['UserTmp']['email']?></td>
		<td><?=date('j M Y g:i a', strtotime($value['UserTmp']['created']))?></td>
		<td><?=(in_array($value['UserTmp']['id'], $inc_reg)) ? 'YES' : 'NO'?></td>
	</tr>
	
<?php } ?>

</table>

<div id="suspend" align="left" style="display:none">
	<form action="/admins/usersuspend" method="post" onsubmit="return confirm('Are you sure?')">
		<div><h2>Select reason</h2></div>
		<br/>
		<div>
			<select name="data[reason_id]" class="dropdown">
				<? foreach($reason as $v) { ?>
					<option value="<?=$v['UserSuspendedReason']['id']?>"><?=$v['UserSuspendedReason']['reason']?></option>
				<? } ?>
			</select>
		</div>
		<br/>
		<div>
			<input type="submit" value="Suspend User" class="submit_button" />
			<input type="hidden" id="suspend_user_id" name="data[user_id]" />
		</div>
	</form>
</div>

<div><br/><br/></div>

<script>
function suspend_dialog() {
	$( "#suspend" ).dialog({
			modal: true,
			buttons: {
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
}
</script>