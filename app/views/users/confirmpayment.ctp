<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>
	
    <div class="gradient" style="margin-right:45px"><h1><span></span>Confirm Payment</h1></div>
    
    <div class="pad5">
    	<div class="header_06">Congragulations, You're one step away from becoming a Freesmsapi Premium Member!</div>
    	<div class="pad5">&nbsp;</div>
    	<div class="pad5">
    		<div class="pad5 header_03">1. Package Selected (<a href="<?=SERVER?>users/pricing"><i>change</i></a>)</div>
	    	<div class="pad5">
		    	<table id="hor-zebra" style="width:100%;margin-top:1px;">
				    <thead>
				    	<tr>
				        	<th scope="col">Credits</th>
				            <th scope="col">Cost Per SMS (INR)</th>
				            <th scope="col">Validity</th>
				            <th scope="col">Amount (INR)</th>
				        </tr>
				    </thead>
				    <tbody>
				    	<tr class="">
				            <td width="23%"><strong><?=$app->format_money($data['credit'])?></strong></td>
				            <td width="23%"><strong><?=$app->format_money($data['cost'],2)?> paise</strong></td>
				            <td width="23%"><strong><?=$data['validity']==12?'1 Year':'6 Months'?></strong></td>
				            <td width="23%"><strong>Rs. <?=$app->format_money($data['totalcost'])?>/-</strong></td>
				        </tr>
				    </tbody>
				</table>
	    	</div>
	    </div>
    	
    	<div class="pad5">
    		<div class="pad5 header_03">2. Total Amount</div>
    		<div class="pad5 f13"> Total Amount Payable is <strong>Rs. <?=$amount?>/-</strong> (Inc. <?=SERVICE_TAX?>% Service Tax)</div>
    	</div>
    	
    	<div class="pad5">&nbsp;</div>
    	<div class="pad5">&nbsp;</div>
    	
    	<div class="pad5">
    		<div class="pad5 header_03">3. Payment Mode</div>
    		<div class="pad5">
				<select name="data[method]" class="dropdown f13" onchange="paymentType(this)">
					<? if(SHOW_PAYMENT_MODULE) { ?>
					<option value="0">Select</option>
					<option value="1">Credit/Debit/Net Banking</option>
					<? } ?>
					<option value="2" selected>Direct Deposit</option>
					<? /* ?><option value="3">Demand Draft</option><? */ ?>
				</select>
    		</div>
    	</div>
    </div>
    
    <div class="pad5"><br/></div>
    
    <? if(SHOW_PAYMENT_MODULE) { ?>
    <div id="paynow" class="pad5 silent_feature" style="display:none;">
    	<form name="ecom" method="post" action="https://test.timesofmoney.com/direcpay/secure/dpMerchantTransaction.jsp">
	    	<div class="pad5">
	    		<table class="payment_table">
	    			<tr>
	    				<td valign="top"><img src="/img/direcpay_logo.gif" /></td>
	    				<td>
	    					<p class="pad5 f13"><strong>Credit Card:</strong>&nbsp;<img src="/img/payment.gif" align="absmiddle" /></p>
	    					<p class="pad5 f13"><strong>Visa / Master ATM (Debit) cards:</strong>&nbsp;Andhra Bank, Axis Bank, Barclays Bank, Canara Bank, Citibank, Corporation Bank, Deutsche Bank, HDFC Bank, Indian Overseas Bank, ICICI Bank, Karur Vysya Bank, Karnataka Bank, Kotak Virtual Visa Debit Cards, The Federal Bank, The Syndicate Bank, Union Bank of India</p>
	    					<p class="pad5 f13"><strong>Net Banking:</strong>&nbsp;YES Bank, Industrial Development Bank of India, HDFC Bank, Axis Bank, Bank of India, Kotak Bank, ICICI Bank, Deutsche Bank, Corporation Bank, State Bank of Mysore</p>
	    					<p class="pad5">&nbsp;</p>
	    					<p class="pad5 f13">Please provide your Email Address</p>
	    					<p><input type="input" class="inputbox" id="paymentemail" /></p>
	    					<p class="pad5 f13">(Activation Link will be send on this Email Address)</p>
				    		<p><input type="button" value="Pay Now" class="rc_btn_02" onclick="encodeTxnRequest();"/></p>
	    				</td>
	    			</tr>
	    		</table>
	    	</div>
	    	<div class="pad5">
				<input type="hidden" name="custName" value="">
				<input type="hidden" name="custAddress" value="">
				<input type="hidden" name="custCity" value="">
				<input type="hidden" name="custState" value="">
				<input type="hidden" name="custPinCode" value="">
				<input type="hidden" name="custCountry" value="IN">
				<input type="hidden" name="custPhoneNo1" value="91">
				<input type="hidden" name="custPhoneNo2" value="">
				<input type="hidden" name="custPhoneNo3" value="">
				<input type="hidden" name="custMobileNo" value="">
				<input type="hidden" name="custEmailId" value="">
				<input type="hidden" name="otherNotes" value="">
				<input type="hidden" name="editAllowed” value="N">
				<input type="hidden" name="requestparameter" id="requestparameter" value="">
			</div>
		</form>
    </div>
    <? } ?>
    
    <div id="directdeposit" class="pad5 silent_feature" <? if(SHOW_PAYMENT_MODULE) { echo 'style="display:none;"'; } ?>>
		<div class="pad10">
			<table>
				<tr><td align="right" style="padding:5px"><span class="f13">Company Name:</span></td><td><span class="f13"><strong><?=PARENT_COMPANY?></strong></span></td></tr>
				<tr><td align="right" style="padding:5px"><span class="f13">Corporation Bank Account No:</span></td><td><span class="f13"><strong>CBCA01000547</strong></span></td></tr>
				<tr><td align="right" style="padding:5px"><span class="f13">IFSC/NEFT/RTGS Code:</span></td><td><span class="f13"><strong>CORP0000163</strong></span></td></tr>
				<tr>
					<td align="right" valign="top" style="padding-right:5px">
						<p class="f13">Bank Address:</p>
					</td>
					<td>
						<p class="f13"><strong>48/50, Abdul Rehman Street,<br/>Mumbai - 400003<br/>Maharashtra</strong></p>
					</td>
				</tr>
			</table>
		</div>
		<div class="pad5"><p class="f13">After depositing the Total Amount of <strong>Rs. <?=$amount?>/-</strong>, send an Email to <strong><?=INTERNAL_SENDER?></strong> along with the <strong>Copy of Bank Receipt</strong> and don't forget to mention your <strong>Reference ID: <?=$reference_id?></strong>.</p></div>
		<div class="pad5 f13">As soon as we get the confirmation, we will Email back your <strong>Activation Details</strong>.</div>
    </div>
    
    <? /* ?>
    <div id="demanddraft" class="pad5 silent_feature" style="display:none;">
    	<div class="pad5 f13">Please pay Rs. <?=$amount?>/- by Demand Draft favouring <strong><?=PARENT_COMPANY?></strong>.</div>
    	<div class="pad5">
    		<table class="payment_table">
    			<tr valign="top">
    				<td align="right"><p class="f13"><strong>Mail To:</strong></p></td>
    				<td><p class="f13"><?=PARENT_COMPANY?><br/>307/20, 1st Floor, Krishna Niwas,<br/>Yusuf Meher Ali Road,<br/>Mumbai - 400003.</p></td>
    			</tr>
    		</table>
    	</div>
    	<div class="pad5 f13">Please mention the <strong>Reference ID: <?=$reference_id?></strong> when you send the Demand Draft.</div>
		<div class="pad5 f13">You can contact <strong><?=INTERNAL_SENDER?></strong> for any queries regarding your transactions.</div>
    </div>
    <? */ ?>
    
</div>
<script type="text/javascript" src="/js/jquery.js"></script> 
<script type="text/javascript" src="/js/payment.js"></script>