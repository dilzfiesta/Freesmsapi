<div class="header_03">FREE SENDER ID</div>

<table style="width:100%;text-align:left">

	<tr>
		<td><strong>ID</strong></td>
		<td><strong>NAME</strong></td>
		<td><strong>DOMAIN</strong></td>
		<td><strong>PUBLISH</strong></td>
		<td><strong>DND</strong></td>
		<td><strong>STATUS</strong></td>
		<td><strong>ADD</strong></td>
		<td><strong>ACCEPT</strong></td>
		<td><strong>DECLINE</strong></td>
		<td><strong>CREATED</strong></td>
		<td><strong>FIRST APPLIED ON</strong></td>
		<td><strong>REMINDER SEND</strong></td>
	</tr>

<?php  if(!empty($data)) {
			foreach($data as $key => $value) {
				if(empty($value['AliasBuy']['id'])) { 
?>
	
	<?	if($value['Alias']['status'] && $value['Alias']['publish'] && in_array($value['Alias']['id'], $alias_reminder)) $state = "EXPIRED";
		else if($value['Alias']['status']) $state = "CANCELLED";
		else if(in_array($value['Alias']['domain_id'], array_keys($alias_reminder))) $state = "EXPIRED";
		else if(!$value['Alias']['status'] && $value['Alias']['publish']) $state = "ACTIVATED";
		else if(!$value['Alias']['status'] && !$value['Alias']['publish'] && !in_array($value['Alias']['name'], $sender_id_repository)) $state = "PENDING";
		else if(!$value['Alias']['status'] && !$value['Alias']['publish'] && in_array($value['Alias']['name'], $sender_id_repository)) $state = "SEND";
	?>
	
	<tr style="background-color:<?=($state=="ACTIVATED" || $state=="PENDING") ? GREEN_COLOR : ''?>">
		<td><?=$key+1?></td>
		<td><?=$value['Alias']['name']?></td>
		<td><?=$value['Domain']['name']?></td>
		<td><?=$value['Alias']['publish']?></td>
		<td>
			<? if($value['Alias']['dnd'] == 'on') { ?>
				<a href="/admins/removednd/<?=$value['Alias']['id']?>" onclick="return confirm('Are you sure?')"><?=$value['Alias']['dnd']?></a>
			<? } else { echo $value['Alias']['dnd']; } ?>
		</td>
		<td><?=$state?></td>
		<td><?=($state == "PENDING") ? "<a href='javascript:void(0)' onclick='add_alias_to_box(".$value['Alias']['id'].",\"".$value['Alias']['name']."\")'>add</a>" : "-" ?></td>
		<td><?=($state == "PENDING" || $state == "SEND") ? "<a href='/admins/acceptalias/".$value['Alias']['id']."/".urlencode($value['Alias']['name'])."' onclick='return confirm(\"Are you sure?\")'>accept</a>" : "-" ?></td>
		<td>
			<?=($state == "PENDING") ? "<a href='javascript:void(0)' onclick='if(confirm(\"Are you sure?\")) decline(".$value['Alias']['id'].")'>decline</a>" : "-" ?>
		</td>
		<td><?=date('j M Y g:i a', strtotime($value['Alias']['created']))?></td>
		<td><?=date('j M Y g:i a', strtotime($cr_ar[$value['Alias']['domain_id']]))?></td>
		<td><?=(in_array($value['Alias']['domain_id'], array_keys($alias_reminder))) ? $alias_reminder[$value['Alias']['domain_id']] : '-'?></td>
	</tr>
	
<?php } } } else { ?>
	
	<tr><td colspan='5'>NO RECORDS FOUND</td></tr>
	
<?php } ?>

</table>

<div><br/><br/></div>

<div class="header_03">SENDER ID's BOUGHT AFTER PROMOTION EXPIRED</div>

<table style="width:100%;text-align:left">

	<tr>
		<td><strong>ID</strong></td>
		<td><strong>NAME</strong></td>
		<td><strong>DOMAIN</strong></td>
		<td><strong>PHOKAT</strong></td>
		<td><strong>STATUS</strong></td>
		<td><strong>CREATED</strong></td>
		<td><strong>VALIDITY</strong></td>
	</tr>

<?php  if(!empty($data)) { 
			foreach($data as $key => $value) {
				if(!empty($value['AliasBuy']['id'])) { 
?>
	
	<tr style="background-color:<?=(!$value['AliasInvoice']['friends']) ? GREEN_COLOR : ''?>">
		<td><?=$key+1?></td>
		<td><?=$value['Alias']['name']?></td>
		<td><?=$value['Domain']['name']?></td>
		<td><?=$value['AliasInvoice']['friends']?></td>
		<td><?=$value['Alias']['status']?></td>
		<td><?=date('j M Y g:i a', strtotime($value['Alias']['created']))?></td>
		<td><?=date('j M Y g:i a', strtotime($value['AliasInvoice']['validtill']))?></td>
	</tr>
	
<?php } } } else { ?>
	
	<tr><td colspan='6'>NO RECORDS FOUND</td></tr>
	
<?php } ?>
	
</table>
            
<div><br/><br/></div>

<div id="decline_div" style="display:none">
<div>Individual/Personal/Generic names are not allowed, Instead use your Company/Organization name as sender ID and try again.</div>
	<form action="/admins/declinealias" method="post" onsubmit="return confirm('Are you sure?')">
    	<textarea id="decline_text" name="reason"></textarea>
    	<input type="hidden" id="decline_id" name="id" />
    	<br />
    	<input type="submit" value="submit" />
    </form>
</div>  

<div><br/><br/></div>

<div id="send_for_activation">
	<div>Send Sender ID for activation</div>
	<form action="/admins/sendAliasForActivation" method="post" onsubmit="return confirm('Are you sure?')">
    	<textarea id="send_for_activation_text" name="data[name]"></textarea>
    	<input type="hidden" id="send_for_activation_id" name="data[id]" />
    	<br />
    	<input type="submit" value="submit" />
    </form>
</div>

<div><br/><br/></div>

<div class="header_03">BUY SENDER ID</div>
<div>
	<form method="post" action="/admins/buysenderid" onsubmit="return confirm('Are you sure?')">
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[name]" /></span>
			<span>Sender ID</span>
		</div>
		<div class="pad5">
			<select name="data[domain_id]" class="dropdown">
				<?=$domain?>
			</select>
			<span>Domain</span>
		</div>
		<div class="pad5">
			<select name="data[amount]" class="dropdown">
				<option value="400">1 month</option>
				<option value="1000">3 months</option>
				<option value="1800">6 months</option>
				<option value="3000">1 year</option>
			</select>
			<span>Validity</span>
		</div>
		<div class="pad5">
			<span><input type="checkbox" name="data[friends]" /></span>
			<span>For Friends (Phokat wala)</span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="create" /></span>
		</div>
	</form>
</div>

<script>
	function decline(id) {
		document.getElementById('decline_div').style.display = '';
		document.getElementById('decline_text').value = '';
		document.getElementById('decline_id').value = '';
		document.getElementById('decline_id').value = id;
	}
	function add_alias_to_box(id, name) {
		if(id != '' && name != '') {
			document.getElementById('send_for_activation_text').value = document.getElementById('send_for_activation_text').value + '::' + name;
			document.getElementById('send_for_activation_id').value = document.getElementById('send_for_activation_id').value + '::' + id;
		}
	}
</script>