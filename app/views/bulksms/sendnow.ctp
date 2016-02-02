<div class="gradient"><h1><span></span>Send SMS</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">Send SMS</div></div>

<? if($quantity == '0') { ?>

	<div class="silent_feature" style="margin:0px">
		<div class="header_05">Kindly recharge your account to continue sending SMS's</div>
	</div>

<? } else { ?>

	<!--<div class="header_03">Please note:</div>-->
	<div>
		<div style="padding:1px">1. Max <strong><?=MAX_CHARS?></strong> Chars is allowed (<strong><?=MAX_CHARS/ONE_SMS_CHARS?></strong> SMS)</div>
		<div style="padding:1px">2. Do not send lottery, fraudulent, spam, abusive, hate messages</div>
		<div style="padding:1px">3. Kindly type message content in the text box area, avoid copy/paste and special characters.</div>
		<div style="padding:1px">4. Please <strong>do not</strong> add '<strong>0</strong>' or '<strong>+91</strong>' in front of Mobile Number.</div>
	</div>
	
	<div class="pad5">&nbsp;</div>
		
	<form id="frm" method="post">
	
		<?php if(SHOW_SENDER_ID) { ?>
			<? if(!empty($senderid)) { ?>
			<div class="pad5">
				<span><select id="bulk_senderid_id" name="data[bulk_senderid_id]" class="dropdown" style="width:150px"><?=$senderid?></select></span>
				<span class="f12">Select Sender ID, <a href="/bulksms/myaccount"><em>create sender ID</em></a></span>
			</div>
			<? } else { ?>
			<div class="pad5">
				<span class="f12">Default Sender ID is <strong><?=SMS_SENDER_ID?></strong>. To create a new sender ID <a href="/bulksms/myaccount"><em>click here</em></a></span>
			</div>
			<? } ?>
		<? } else { ?>
			<div class="header_03">
				<div>As per the new guidelines by TRAI dated 27th September 2011, Sender ID service is terminated.</div>
				<div>Please <a href="/bulksms/myaccount" target="_blank">click here</a> for more information</div>
			</div>	
		<? } ?>
		
		<div class="pad5">
			<span><select id="bulk_group_id" name="data[bulk_group_id]" class="dropdown" style="width:150px" onchange="B_S_A_F_C(this)"><?=$groupname?></select></span>
			<span class="f12">Select Group, <a href="/bulksms/groups"><em>create groups</em></a></span>
		</div>
		<div id="group_fetch_list" class="pad5" style="display:none;"><a href="javascript:void(0)" onclick="B_S_G_C()">Fetch Contact List</a></div>
		<div id="group_mobile_list" class="pad5 silent_feature" style="display:none;height:80px;overflow:auto"></div>
		<div class="pad5">
			<span><select id="bulk_tag_id" name="data[bulk_tag_id]" class="dropdown" style="width:150px"><?=$tag?></select></span>
			<span class="f12">Select Tag, <a href="/bulksms/myaccount#tag"><em>create tags</em></a></span>
		</div>
		<div class="pad5">
			<span><textarea id="number" name="data[number]" class="textarea" style="width:280px"><?=isset($message)?!isset($success)?$number:'':''?></textarea></span>
			<spanclass="f12">Mobile Numbers (Seperated by comma)</span>
		</div>
		<div class="pad5">
			<span><textarea id="message" name="data[message]" class="bigtextarea" onclick="textCounter(this)" onKeyDown="textCounter(this)" onKeyUp="textCounter(this)"><?=isset($message)?!isset($success)?$message:'':''?></textarea></span>
			<span id="counter" class="f12"></span>
		</div>
		<div class="pad5" style="margin-left:-2px">
			<span>
				<select class="dropdown" name="data[day]" id="s_day">
					<option value=''>Day</option>
					<? for($i=1; $i<=31; $i++) {
							$selected = '';
							if(isset($day)) if($i==$day) $selected = 'selected="selected"';
							echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
						}
					?>
				</select>
			</span>
			<span>
				<? $month_array = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'); ?>
				<select class="dropdown" name="data[month]" id="s_month">
					<option value=''>Month</option>
					<? for($i=1; $i<=12; $i++) {
							$selected = '';
							if(isset($month)) if($i==$month) $selected = 'selected="selected"';
							echo '<option value="'.$i.'" '.$selected.'>'.$month_array[$i].'</option>';
						}
					?>
				</select>
			</span>
			<span>
				<select class="dropdown" name="data[year]" id="s_year">
					<option value=''>Year</option>
					<? for($i=2011; $i<=2013; $i++) {
							$selected = '';
							if(isset($year)) if($i==$year) $selected = 'selected="selected"';
							echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
						}
					?>
				</select>
			</span>
			<span>
				<select class="dropdown" name="data[hours]" id="s_hours">
					<option value=''>Hours</option>
					<? for($i=0; $i<=23; $i++) {
							$selected = '';
							if(isset($hour)) if($i==$hour) $selected = 'selected="selected"';
							echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
						}
					?>
				</select>
			</span>
			<span>
				<select class="dropdown" name="data[minutes]" id="s_minutes">
					<option value=''>Minutes</option>
					<option value="0" <?=isset($minutes) ? $minutes=='0' ? 'selected="selected"' : '' : ''?>>00</option>
					<option value="15" <?=isset($minutes) ? $minutes=='15' ? 'selected="selected"' : '' : ''?>>15</option>
					<option value="30" <?=isset($minutes) ? $minutes=='30' ? 'selected="selected"' : '' : ''?>>30</option>
					<option value="45" <?=isset($minutes) ? $minutes=='45' ? 'selected="selected"' : '' : ''?>>45</option>
				</select>
			</span>
			<span id="counter" class="f12">Schedule date</span>
		</div>
		<div><br/></div>
		<div id="s_but">
			<span><input type="button" class="rc_btn_01" value="Send Now" onclick="chk_sm()" style="margin-left:-7px" /></span>&nbsp;
			<span><input type="button" class="rc_btn_01" value="Schedule" onclick="chk_sc()" /></span>
			<span><input type="hidden" name="data[type]" id="type" /></span>
			
		</div>
		<div id="loader" style="display:none">
			<div style="float:left"><img src="/img/loading.gif" width="40px"/></div><div id="s_text" style="float:left;margin-top:12px;margin-left:5px;font-size:18px;"></div>
		</div>
	</form>

<? } ?>

<script type="text/javascript">
function textCounter(field, cntfield) {
	var n = extra = '';
	var maxlimit = <?=MAX_CHARS?>;
	var onesms = <?=ONE_SMS_CHARS?>;
	var counter = document.getElementById('counter');
	if (field.value.length > maxlimit) field.value = field.value.substring(0, maxlimit);
	else {
		if(/\./.test(field.value.length/onesms)) extra = 1; else extra = 0;
		n = Number(Math.round(Math.floor(field.value.length/onesms))) + extra;
		if(field.value.length == '0') n = 0;
		counter.innerHTML = '<strong>'+ field.value.length +'</strong> Chars - (<strong>'+ n +'</strong> SMS)';
	}
}
function chk_sm() {
	var data = document.getElementById('message').value;
	if(data == '') {
		alert('Message Cannot Be Empty');
		return false;
	}
	
	if(confirm('Are you sure?')) {
		$('#type').val('1');
		$('#s_but').hide();
		$('#s_text').html('SENDING SMS');
		$('#loader').show();
		$('#frm').submit();
	}
}
function chk_sc() {
	var data = document.getElementById('message').value;
	if(data == '') {
		alert('Message Cannot Be Empty');
		return false;
	}

	var emp = false;
	$('select').each(function(index, elem) {
		if(elem.value == '') {
			emp = true;
		}
	});
	
	if(!emp) {
		$('#s_but').hide();
		$('#s_text').html('SCHEDULING SMS');
		$('#loader').show();
		$('#frm').submit();
	} else {
		$('#type').val('0');
		alert('Invalid Date Entered');
	}
}
</script>