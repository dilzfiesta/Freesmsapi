<script>
function checkIt(evt) {
    evt = (evt) ? evt : window.event
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    else return true;
}
</script>

<div class="gradient" align="center"><h1><span></span>SMS Delivery Reports</h1></div>
    
<div class="pad5">

	<div align="center">
    	<form method='post'>
	        <div class="pad5 f14">
	        	<div class="header_hw" align="left"><div class="header_wrapper header_03">SMS Search detailed</div></div>
	        	<!--<div align="left" class="backlink" ><a href="/bulksms/showreport"><em><< back to reports</em></a></div>-->
	        	<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px" align="left">SMS Search detailed</div>-->
	        	<div style="padding-top:10px">
	        	<table class="normal_table">
		        	<tr>
		        		<td class="f12">Mobile: </td>
		        		<td><input type="text" class="inputbox" name="data[name]" size="20" maxlength="10" value="<?=(isset($name)?$name:'')?>" onkeypress="return checkIt(event)"></td>
		        	</tr>
		        	<tr>
		        		<td class="f12">Status: </td>
		        		<td>
		        			<select name="data[status]" class="dropdown">
		        				<option <?=(empty($status))?'selected':''?>>ALL</option>
		        				<option value="1" <?=($status == 1)?'selected':''?>>DELIVERED</option>
		        				<option value="2" <?=($status == 2)?'selected':''?>>UNDELIVERED</option>
		        				<option value="3" <?=($status == 3)?'selected':''?>>PENDING</option>
		        				<option value="4" <?=($status == 4)?'selected':''?>>EXPIRED</option>
		        			</select>
		        		</td>
		        	</tr>
		        </table>
		        </div>
		        <div style="padding-top:10px">
		        	<!--<input type='button' value='back' class="button" onclick="window.location.replace('/bulksms/showreport');"/>-->
		        	<input type='submit' value='find' class="rc_btn_01" style="margin-left:40px"/>
		        </div>
	        </div>
	    </form>
	</div>

	<div class="header_03" style="margin-bottom:-30px">SMS Status Summary</div>
	<table cellpadding="4" id="hor-zebra" style="width:100%">
		<thead>
			<tr>
    			<th scope="col"></th>
    			<th scope="col">Status</th>
    			<th scope="col">Count</th>
			</tr>
		</thead>
		<tbody>
		
		<?php if(!empty($data)) { ?>
		
		<?php $i=1; foreach($response_status as $key => $value) { ?>
		
			<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
				<td><?=$i++?></td>
				<td><?=$key?></td>
				<td><?=$value?></td>
			</tr>
			
		<?php } ?>
		
		<?php } else { ?>

    		<tr valign="top">
				<td colspan="5" align="center">NO ENTRIES FOUND</td>
			</tr>
    	
    	<?php } ?>
			
		</tbody>
	</table>
	

	<div class="header_03" style="margin-bottom:-30px">SMS Detailed List</div>
	<table cellpadding="4" id="hor-zebra" style="width:100%">
		<thead>
			<tr>
    			<th scope="col"></th>
    			<th scope="col">Mobile Number</th>
    			<th scope="col">Status</th>
    			<th scope="col" width="60px">Send on</th>
			</tr>
		</thead>
		<tbody>
		
		<?php if(!empty($data)) { ?>
		
		<?php $data_count = count($data); for($i=0; $i<$data_count; $i++) { ?>
		
			<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
				<td><?=$i+1?></td>
				<td><?=$data[$i]['BulkSmsLogDetail']['mobile']?></td>
				<td><?=$data[$i]['BulkSmsLogDetail']['response_status']?></td>
				<td width="80"><?=date('j M Y', strtotime($data[$i]['BulkSmsLogDetail']['created']))?></td>
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

<div class="pad15">&nbsp;</div>