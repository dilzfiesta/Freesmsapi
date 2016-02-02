<div class="gradient"><h1><span></span>MY ACCOUNT</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($alias_error)?$alias_error:'', 'success'=>isset($alias_success)?$alias_success:''));  ?>
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>
<?php echo $this->renderElement('success_error', array('error'=>isset($secretkey_error)?$secretkey_error:'', 'success'=>isset($secretkey_success)?$secretkey_success:''));  ?>

<div id="change_alias">
	<!--<a href="#" id="sender"></a>-->
	<div class="header_hw"><div class="header_wrapper header_03">Sender ID</div></div>
	
	<? if(SHOW_SENDER_ID && $trial_expired) { ?>
		<div class='error' style="margin-top:20px">Trial period of 30 days has been expired. Please check our pricing plan to continue enjoying Sender ID service.</div>
	<? } ?>

	<?php if(SHOW_SENDER_ID) { ?>

	<form method="post">
		<p>
			<span>1. Sender ID is free on <strong>Trial basis for 30 days</strong> from the time your first sender ID is set.
			<br/>2. Only <strong><?=MAX_FREE_ALIAS?> Free Sender ID</strong> is allowed per account.
			<br/>3. The default is <strong><em>fsmsapi</em></strong> which can be changed to your company name.
			<br/>4. Please note that Sender ID should not be the name of a person or any other company name then yours.
			<br/>5. Sender ID is <strong>Case Sensitive</strong>.</span>
			<br/>6. Sender ID should not contain any <strong>Spaces</strong> and <strong>Special Characters</strong> other than dot(.), hyphen(-) and underscore(_).<br/><br/>
		</p>
		
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Alias][name]" value=""/></span>
			<span>Sender ID should be less than or equal to <strong>8 characters</strong></span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="<?=$trial_expired ? 'Buy' : 'Create'?>" /></span>
		</div>
	</form>
		
	<div class="pad5">

    	<table cellpadding="4" id="hor-zebra" style="width:100%">
			<thead>
				<tr>
	    			<th scope="col"></th>
	    			<th scope="col" width="200">Sender ID</th>
	    			<th scope="col">DND Filter</th>
	    			<th scope="col">Status</th>
	    			<th scope="col">Creation Date</th>
	    			<th></th>
				</tr>
			</thead>
			<tbody>
			
			<?php if(!empty($senderid)) { ?>
			
			<?php for($i=0; $i<count($senderid); $i++) { ?>
			
				<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
					<td><?=$i+1?></td>
					<td><?=$senderid[$i]['Alias']['name']?></td>
					<td><?=strtoupper($senderid[$i]['Alias']['dnd'])?></td>
					<td>
						<?
							if($trial_expired) {
								if(!empty($senderid[$i]['AliasInvoice']['validtill']))
									$state = date('Y-m-d', strtotime($senderid[$i]['AliasInvoice']['validtill'])) < date('Y-m-d') ? 'BUY' : 'Active till '.date('jS M, Y', strtotime($senderid[$i]['AliasInvoice']['validtill']));
								else if(SHOW_ONLY_TO_ME) $state = '<a href="/users/buysid/'.$senderid[$i]['Alias']['id'].'">BUY</a>';
								else $state = '<a href="/users/pricingsenderid">BUY</a>';
							} else {
								$state = ($senderid[$i]['Alias']['publish']) ? 'Active' : 'Pending Approval';
							}
							echo $state;
						?>
					</td>
					<td width='120px'><?=date('j M Y g:i a', strtotime($senderid[$i]['Alias']['created']))?></td>
					<td><a href="/users/deletealias/<?=$senderid[$i]['Alias']['id']?>" onclick="return confirm('Are you sure?')">Delete</a></td>
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
		
	<!-- <div class="f12" style="margin:-20px 0px 20px 0px">
		<span>If you like to remove DND filter please follow the below mentioned instructions<span><br/>
		<div class="list">
			<ul style="margin:10px 0px 10px 15px">
				<li>Create a Sender ID (Should not be a personal names).</li>
				<li>Print the content of the <a href="/example/NDNC_DECLARATION.doc" target="_blank">Document</a> onto you company's letter head.</li>
				<li>Return the scanned copy of the document with an authorized signature and stamp of the company/organization.</li>
			</ul>
		</div>
		<span>Once verified your DND filter will be removed and you will be able to send SMS to all DND and Non-DND mobile users.</span>
	</div> -->
	
	<div class="f12" style="margin:-20px 0px 20px 0px">
		<div><em>TIP: Shorten your domain name or your company name to use as your new Sender ID</em></div>
		<div><em>NOTE: It will take 24 hours to verify and activate your new Sender ID</em></div>
	</div>
	
	<?php } else { ?>
	
	<div class="error" style="margin-bottom:-10px">
		<div class="pad5">According to the New TRAI guidelines, No SMS provider is allowed to deliver SMSes to NDNC registered mobile numbers. So, Starting from 27th Sept, 2011 Freesmsapi will not deliver SMSes to NDNC registered mobile numbers. However, we will display NDNC registered mobile numbers in your delivery reports with delivery status as 'DND'.</div>
		<div class="pad5">All messages sent via Freesmsapi will be sent as “ TD- XXXXXX” as the sender (XXXXXX is the Unique number allotted by Telecom operator to Freesmsapi). Sender ID service will be terminated and you no longer can use your own Sender ID starting 27th Sept, 2011.</div>
	</div>
	
	<?php } ?>
	
</div>

<div style="margin-top:30px">&nbsp;</div>

<div id="change_secret_key">

	<div class="header_hw"><div class="header_wrapper header_03">Change Secret Key</div></div>

    <div class="pad5">
    	<div class="pad5"><span class="f12">You can use any of the two as your Secret Key -</span></div>
		<div class="pad5"><span><strong><?php echo $secret_key; ?></strong></span>&nbsp;|&nbsp;<a href="/users/changesecretkey">Change your Secret Key</a></div>
		<div class="pad5"><span><strong>-- OR --</strong></span></div>
		<div class="pad5"><span><strong>Username + Password</strong></span></div>
		<div class="pad5">(eg: if your Username is "9699419699" and your Password is "freesmsapi", then use the Secret Key as "9699419699freesmsapi")</div>
    </div>
    
</div>

<div style="margin-top:20px">&nbsp;</div>

<div id="check_ip_address">

	<div class="header_hw"><div class="header_wrapper header_03">Check IP Address</div></div>

    <div class="pad5">
    	<?php if(!$no_ip) { ?>
    		<div class="pad5"><span class="f12">Your IP Address is <strong><?=$domain_ip?></strong>, <a href="/users/checkipaddress">Check again</a></span></div>
    	<?php } else { ?>
    		<div class="pad5"><span class="f12">We are unable to find IP Address associated with your domain, <a href="/users/checkipaddress">Check again</a></span></div>
    	<?php } ?>
    </div>
    
</div>

<div style="margin-top:20px">&nbsp;</div>

<div id="change_password">
	<a href="#" id="pass"></a>
	<div class="header_hw"><div class="header_wrapper header_03">Change Password</div></div>

    <form method="post" action="<?=SERVER?>users/myaccount">
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[User][password]" value=""/></span>
			<span>Your old password</span>
		</div>
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[User][new_password]" value=""/></span>
			<span>New password (Should be greater or equal to 8 characters)</span>
		</div>
		<div class="pad5">
			<span><input type="password" class="inputbox" name="data[User][retype_password]" value=""/></span>
			<span>Retype new password</span>
		</div>
		<div class="pad5" style="margin-left:-5px">
			<span><input type="submit" class="rc_btn_01" value="change password" /></span>
		</div>
	</form>

</div>
