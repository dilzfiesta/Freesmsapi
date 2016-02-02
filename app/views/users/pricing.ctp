<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>
	
    <div class="gradient" style="margin-right:45px"><h1><span></span>Bulk SMS Pricing Plan</h1></div>
    
    <div>
    	<div class="header_hw"><div class="header_wrapper header_03">The pricing package includes the following features:</div></div>
	    <div class="pad5">
		    <div class="pricing_menu"><ul>
				<li>Send Individual or Group SMS with <strong>800</strong> characters</li>
				<?php if(SHOW_SENDER_ID) { ?>
				<li>Free Multiple Alpha Numeric Sender ID's (8 Char)</li>
				<?php } ?>
				<li>Create Multiple Groups</li>
				<li>Upload your MS Excel Contact list into your Address Book</li>
				<li>Schedule and automate the SMS Delivery</li>
				<li>Developer API (You can integrate SMS solution with your software and can be automated)</li>
				<?php if(SHOW_SENDER_ID) { ?> <li>NDNC Nos. can be filtered without loss of any credits.Non DND routes as per requirement</li><?php } ?>
				<li>Real time delivery report and unlimited storage of all reports</li>
		    </ul></div>
	    </div>
	    
	    <div class="pad5"><br/><br/></div>
	    
	    
    	<div class="pad5">
    		<form name="bulkpackages" method="post" action="/users/confirmpayment">
		        <div class="header_06">6 Months Pre Paid Bulk SMS Plans</div>
		    	<table id="hor-zebra" style="width:100%;margin-top:1px">
				    <thead>
				    	<tr>
				    		<th scope="col">&nbsp;</th>
				        	<th scope="col">Credits</th>
				            <th scope="col">Cost Per SMS (INR)</th>
				            <th scope="col">Validity</th>
				            <th scope="col">Amount (INR)</th>
				        </tr>
				    </thead>
				    <tbody>
				    <?php foreach($package1 as $key => $value) { ?>
				    	<tr class="<?=$key%2==0?'odd':''?>">
				    		<td width="8%"><input type="radio" name="data[payment1]" value="<?=$value['Pricing']['id']?>" <?=$key==0?'checked':''?>/></td>
				            <td width="23%"><?=$app->format_money($value['Pricing']['credit'])?></td>
				            <td width="23%">Rs. <?=$app->format_money($value['Pricing']['cost'],2)?></td>
				            <td width="23%"><?=$value['Pricing']['validity']?> months</td>
				            <td width="23%">Rs. <?=$app->format_money($value['Pricing']['totalcost'])?>/-</td>
				        </tr>
					<?php } ?>
				    </tbody>
				</table>
		    	<div align="right"><input type="submit" value="Buy Now" class="rc_btn_02" style="margin-top:-40px"/></div>
	    	</form>
    	</div>	
    
		<div class="pad5">
			<form name="bulkpackages" method="post" action="/users/confirmpayment">
		        <div class="header_06">1 Year Pre Paid Bulk SMS Plans</div>
		    	<table id="hor-zebra" style="width:100%;margin-top:1px">
				    <thead>
				    	<tr>
				    		<th scope="col">&nbsp;</th>
				        	<th scope="col">Credits</th>
				            <th scope="col">Cost Per SMS (INR)</th>
				            <th scope="col">Validity</th>
				            <th scope="col">Amount (INR)</th>
				        </tr>
				    </thead>
				    <tbody>
				    <?php foreach($package2 as $key => $value) { ?>
				    	<tr class="<?=$key%2==0?'odd':''?>">
				    		<td width="8%"><input type="radio" name="data[payment2]" value="<?=$value['Pricing']['id']?>" <?=$key==0?'checked':''?>/></td>
				            <td width="23%"><?=$app->format_money($value['Pricing']['credit'])?></td>
				            <td width="23%">Rs. <?=$app->format_money($value['Pricing']['cost'],2)?></td>
				            <td width="23%">1 year</td>
				            <td width="23%">Rs. <?=$app->format_money($value['Pricing']['totalcost'])?>/-</td>
				        </tr>
					<?php } ?>
				    </tbody>
				</table>
		    	<div align="right"><input type="submit" value="Buy Now" class="rc_btn_02" style="margin-top:-40px"/></div>
	    	</form>
    	</div>	
	    
	    
	    <div class="silent_feature" style="margin:20px 0px -20px 0px">
		    <div class="f13" style="padding:5px"><strong>Service tax of 10.3% is included in the price</strong></div>
		    
		    <div class="f13"style="padding:5px">
		    	<span>Please contact us at <strong>sales@freesmsapi.com</strong> for requirements of above <strong><span style="color:#FD4800">2 Lac SMS's</span> and <span style="color:#FD4800">Extended Validity</span> options</strong>.</span>
		    </div>
	    </div>
    </div>
</div>