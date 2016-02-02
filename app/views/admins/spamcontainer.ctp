<div class="header_03">SAPM CONTAINER</div>

<table style="width:100%;text-align:left">

	<?php if($count_list == 1) { ?>
		
		<tr>
			<td><strong>ID</strong></td>
			<td><strong>DOMAIN ID</strong></td>
			<td><strong>NAME</strong></td>
			<td><strong>COUNT</strong></td>
			<td><strong>&nbsp;</strong></td>
		</tr>
	
		<?php $count=1; foreach($data as $value) { ?>
		
		<tr valign="top">
			<td><?=$count++?></td>
			<td><?=$value['Domain']['id']?></td>
			<td><?=$value['Domain']['name']?></td>
			<td><?=$value['0']['count']?></td>
			<td><a href="/admins/spamcontainer/detail/<?=$value['Domain']['id']?>">View list</a></td>
		</tr>
		
		<?php } ?>

	<?php } else { ?>
	
		<tr>
			<td><strong>ID</strong></td>
			<td><strong>MOBILE</strong></td>
			<td><strong>MESSAGE</strong></td>
			<td><strong>IP</strong></td>
			<td><strong>CLIENT IP</strong></td>
			<td><strong>CREATED</strong></td>
		</tr>
	
		<?php foreach($data as $key => $value) { ?>
		
		<tr valign="top">
			<td><?=$key?></td>
			<td><?=$value['SpamContainer']['name']?></td>
			<td><?=$value['SpamContainer']['message']?></td>
			<td><?=long2ip($value['SpamContainer']['ip'])?></td>
			<td><?=long2ip($value['SpamContainer']['client_ip'])?></td>
			<td><?=date('j M Y g:i a', strtotime($value['SpamContainer']['created']))?></td>
		</tr>
		
		<?php } ?>
	
	<?php } ?>

</table>

<br/><br/>

<div class="header_03">LAST 10 SPAM MESSAGES SENT OUT</div>
<br/>

<?php if(isset($spam_list)) { ?>
	
	<table style="width:100%;text-align:left">
	
		<tr>
			<td><strong>ID</strong></td>
			<td><strong>MOBILE</strong></td>
			<td><strong>DOMAIN</strong></td>
			<td><strong>MESSAGE</strong></td>
			<td><strong>IP</strong></td>
			<td><strong>CLIENT IP</strong></td>
			<td><strong>CREATED</strong></td>
		</tr>
		
		<?php foreach($spam_list as $key => $value) { ?>
		
		<tr valign="top">
			<td><?=$key+1?></td>
			<td><?=$value['SpamContainer']['name']?></td>
			<td><?=$value['Domain']['name']?></td>
			<td><?=$value['SpamContainer']['message']?></td>
			<td><?=long2ip($value['SpamContainer']['ip'])?></td>
			<td><?=long2ip($value['SpamContainer']['client_ip'])?></td>
			<td><?=date('j M Y g:i a', strtotime($value['SpamContainer']['created']))?></td>
		</tr>	
		
		<?php } ?>

	</table>
<?php } ?>