<html>

<?php echo $this->renderElement('title'); ?>

<body>

<?php echo $this->renderElement('adminheader'); ?>

<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
    
        <div class="">

            <div class="header_02">Admin Sections</div>
            	
            	<div class="header_03">Account Info</div>
            	
            	<div class="pad5">
					<p>Service started on: <strong><?=date('j M Y', strtotime($bulk_account['created']))?></strong></p>
					<p>&nbsp;</p>
					<p>Amount remaining: <strong><?=$bulk_account['amount']?></strong></p>
					<p>&nbsp;</p>
					<p>Total Quantity: <strong><?=$bulk_account['quantity']?></strong></p>
				</div>

				<div><br/><br/></div>

				<div class="header_03">Account Recharge Info</div>
				
            	<table style="width:100%;text-align:left">
            
            		<thead>
						<th><strong>ID</strong></th>
						<th><strong>COST PER SMS</strong></th>
						<th><strong>AMOUNT</strong></th>
						<th><strong>VALIDITY</strong></th>
						<th><strong>QUANTITY</strong></th>
						<th><strong>CREATED</strong></th>
					</thead>
            
				<?php $count=1; foreach($bulk_account_recharge as $key => $value) { ?>
					
					<tr>
						<td><?=$count++?></td>
						<td><?=$value['BulkAccountRecharge']['costpersms']?></td>
						<td><?=$value['BulkAccountRecharge']['amount']?></td>
						<td><?=date('j M Y', strtotime($value['BulkAccountRecharge']['validtill']))?></td>
						<td><?=$value['BulkAccountRecharge']['quantity']?></td>
						<td><?=date('j M Y g:ia', strtotime($value['BulkAccountRecharge']['created']))?></td>
					</tr>
					
				<?php } ?>
				
				</table>
				
				<div class="pad5">
					<form action="/admins/bulksmsrecharge" method="post" onsubmit="return confirm('Are you sure?')">
						<div class="pad5">
							<span><input type="text" class="inputbox" name="data[amount]" /></span>
							<span>Amount</span>
						</div>
						<div class="pad5">
							<span><input type="text" class="inputbox" name="data[costpersms]" /></span>
							<span>Costpersms</span>
						</div>
						<div class="pad5">
							<span><input type="text" class="inputbox" name="data[quantity]" /></span>
							<span>Quantity</span>
						</div>
						<div class="pad5">
							<span><input type="text" class="inputbox" name="data[validtill]" /></span>
							<span>Valid till (yyyy-mm-dd)</span>
						</div>
						<div class="pad5" style="margin-left:-5px">
							<span><input type="submit" class="rc_btn_01" value="Add" /></span>
							<span class="f12">
								<input type="hidden" name="data[bulk_account_id]" value="<?=$bulk_account['id']?>" />
								<input type="hidden" name="data[user_id]" value="<?=$user_id?>" />
							</span>
						</div>
					</form>
				</div>
				
				<div><br/><br/></div>

				<div class="header_03">Feedback</div>
				
            	<table style="width:100%;text-align:left">
            
            		<thead>
						<th><strong>ID</strong></th>
						<th><strong>FEEDBACK</strong></th>
						<th><strong>CREATED</strong></th>
					</thead>
            
				<?php $count=1; foreach($bulk_feedback as $key => $value) { ?>
					
					<tr>
						<td><?=$count++?></td>
						<td><?=wordwrap($value['BulkFeedback']['feedback'], 70, "<br/>", true)?></td>
						<td><?=date('j M Y g:ia', strtotime($value['BulkFeedback']['created']))?></td>
					</tr>
					
				<?php } ?>
				
				</table>
				
				<div><br/><br/></div>

				<div class="header_03">Groups</div>
				
            	<table style="width:100%;text-align:left">
            
            		<thead>
						<th><strong>ID</strong></th>
						<th><strong>NAME</strong></th>
						<th><strong>NARRATION</strong></th>
						<th><strong>STATUS</strong></th>
						<th><strong>CREATED</strong></th>
					</thead>
            
				<?php $count=1; foreach($bulk_group as $key => $value) { ?>
					
					<tr>
						<td><?=$count++?></td>
						<td><?=$value['BulkGroup']['name']?></td>
						<td><?=wordwrap($value['BulkGroup']['narration'], 70, "<br/>", true)?></td>
						<td><?=($value['BulkGroup']['status']) ? 'DELETED' : 'ACTIVE'?></td>
						<td><?=date('j M Y g:ia', strtotime($value['BulkGroup']['created']))?></td>
					</tr>
					
				<?php } ?>
				
				</table>
				
				<div><br/><br/></div>

				<div class="header_03">Sender ID</div>
				
            	<table style="width:100%;text-align:left">
            
            		<thead>
						<th><strong>ID</strong></th>
						<th><strong>NAME</strong></th>
						<th><strong>DND FILTER</strong></th>
						<th><strong>STATUS</strong></th>
						<th><strong>CREATED</strong></th>
						<th><strong>ACCEPT</strong></th>
					</thead>
            
				<?php 	$count=1; 
						foreach($bulk_senderid as $key => $value) { 
					
							if($value['BulkSenderid']['status']) $state = "CANCELLED BY USER";
							else if(!$value['BulkSenderid']['status'] && $value['BulkSenderid']['publish']) $state = "ACTIVATED";
							else if(!$value['BulkSenderid']['status'] && !$value['BulkSenderid']['publish']) $state = "ACTIVATION PENDING";
					
				?>
					
					<tr style="background-color:<?=($state=="ACTIVATED" || $state=="ACTIVATION PENDING") ? GREEN_COLOR : ''?>">
						<td><?=$count++?></td>
						<td><?=$value['BulkSenderid']['name']?></td>
						<td>
							<? if($value['BulkSenderid']['dnd'] == 'on') { ?>
								<a href="/admins/bulkremovednd/<?=$value['BulkSenderid']['id']?>" onclick="return confirm('Are you sure?')"><?=$value['BulkSenderid']['dnd']?></a>
							<? } else { echo $value['BulkSenderid']['dnd']; } ?>
						</td>
						<td><?=$state?></td>
						<td><?=date('j M Y g:ia', strtotime($value['BulkSenderid']['created']))?></td>
						<td><?=($state != "ACTIVATION PENDING") ? "-" : "<a href='/admins/bulkacceptalias/".$value['BulkSenderid']['id']."' onclick=\"return confirm('Are you sure?')\">accept</a>"?></td>
					</tr>
					
				<?php } ?>
				
				</table>
				
				<div><br/><br/></div>

				<div class="header_03">Tags</div>
				
            	<table style="width:100%;text-align:left">
            
            		<thead>
						<th><strong>ID</strong></th>
						<th><strong>NAME</strong></th>
						<th><strong>STATUS</strong></th>
						<th><strong>CREATED</strong></th>
					</thead>
            
				<?php $count=1; foreach($bulk_sms_tag as $key => $value) { ?>
					
					<tr>
						<td><?=$count++?></td>
						<td><?=wordwrap($value['BulkSmsTag']['name'], 70, "<br/>", true)?></td>
						<td><?=($value['BulkSmsTag']['status']) ? 'DELETED' : 'ACTIVE'?></td>
						<td><?=date('j M Y g:ia', strtotime($value['BulkSmsTag']['created']))?></td>
					</tr>
					
				<?php } ?>
				
				</table>
				
				<div><br/><br/></div>

				<div class="header_03">SMS LOG</div>
				
            	<table style="width:100%;text-align:left">
            
            		<thead>
						<th><strong>ID</strong></th>
						<th><strong>MESSAGE</strong></th>
						<th><strong>GROUP</strong></th>
						<th><strong>MOBILE</strong></th>
						<th><strong>SENDER ID</strong></th>
						<th><strong>TAG</strong></th>
						<th><strong>SMS</strong></th>
						<th><strong>CREATED</strong></th>
					</thead>
            
				<?php $count=1; foreach($bulk_sms_log as $key => $value) { ?>
					
					<tr>
						<td><?=$count++?></td>
						<td><?=wordwrap($value['BulkSmsLog']['message'], 40, "<br />", true)?></td>
						<td><?=!empty($value['BulkSmsLog']['bulk_group_id']) ? $groups[$value['BulkSmsLog']['bulk_group_id']] : '-'?></td>
                        <td><?=!empty($value['BulkSmsLog']['numbers']) ? wordwrap($value['BulkSmsLog']['numbers'], '11', '<br/>', true) : '-'?></td>
						<td><?=($value['BulkSmsLog']['bulk_senderid_id']) ? $senderid[$value['BulkSmsLog']['bulk_senderid_id']] : BULK_SMS_SENDER_ID?></td>
						<td><?=$tag[$value['BulkSmsLog']['bulk_tag_id']]?></td>
						<td><?=$value['BulkSmsLog']['sms_count']?></td>
						<td><?=date('j M Y g:ia', strtotime($value['BulkSmsLog']['created']))?></td>
					</tr>
					
				<?php } ?>
				
				</table>
            
        </div>  
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<div><br/><br/></div>

<?php echo $this->renderElement('footer'); ?>

</div>
</body>
</html>
<?php exit; ?>
