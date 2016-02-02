<div class="content_col_w520 fr">

	<?=$this->renderElement('signupandlogin')?>
	
    <div class="gradient"><h1><span></span>Frequently Asked Questions</h1></div>
    
    <div>
	    <div class="pad5 f12">
	        <div class="header_05">How do I Signup?</div>
	        <div><p>You will need a valid Domain name to signup. Also, the email address required should match the registered domain name.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Can I register without a domain?</div>
	        <div><p>No. Domain registration is mandatory for free users. However for our PREMIUM USERS (bulk sms users), this process has been waived off.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	                
	    <div class="pad5 f12">
	        <div class="header_05">How do I activate my account?</div>
	        <div><p>The password will be sent to you on your registered email address. Simply, enter the password in the login page to access your account.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Do I need to verify my phone number to access my account at Freesmsapi?</div>
	        <div><p>It is not mandatory but advisable to do so. You can verify your phone number after you login for the first time. An activation code will be sent to you. Enter the activation code in the Activation Console to successfully verify your mobile number.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Account lifespan?</div>
	        <div><p>The account will be deactivated if there is no activity for 60 days.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">I haven't received my activation code?</div>
	        <div><p><a href="<?=SERVER?>feedbacks/contact">Contact Us</a> & we'll send activation code again.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Is my registration information kept private?</div>
	        <div><p>FREE SMS API does not share customer data with any third parties.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Can I use FREE SMS API outside of the India?</div>
	        <div><p>No. This is only available to subscribers with Indian phone numbers.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">How much does this cost?</div>
	        <div><p><strong>FREE SMS API</strong> does not charge for text messages. The SMS’s are completely free and without any advertisements. However, our trademark will be appended in the body of the free message for identification purposes.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">How many text messages can I send?</div>
	        <div><p>Initially you can send <?= MAX_RECIPIENT ?> text messages per day. You can increase your sms quota with the help of <a href="<?= SERVER ?>users/referralprogram">referral program</a>.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <?php if(SHOW_SENDER_ID) { ?>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Will I get a free Sender ID?</div>
	        <div><p>Yes. you will receive a Sender ID on a promotion basis for <?=ALIAS_TRIAL_PERIOD?> days. You still be able to send SMS with default Sender ID - <strong>fsmsapi</strong> even after the promotion period expire.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">How can I send free messages with my company name as the SENDER ID after expiration of the promotion period?</div>
	        <div><p>We have SENDER ID plans where we offer multiple user defined sender ID’s with the paid package. Kindly check for the pricing after logging into your account.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <?php } ?>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Do you offer any bulk message service?</div>
	        <div><p>Yes, we do. Kindly check our <a href="<?=SERVER?>users/pricing">Bulk SMS Pricing</a> page</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Can I send SMS's to DND registered users?</div>
	        <div><p>No. As per the new rules set by TRAI (Telecom Regulatory Authority of India), no users are allowed to send sms to DND users except for banks and educational institution.</p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">Is the API service absolutely free?</div>
	        <div><p>Yes. The basic service is completely free. However, advanced customization services required on the APIs offered are chargeable. Kindly <a href="<?=SERVER?>feedbacks/contact">Contact Us</a> to know about our <strong>advanced API integration services and features</strong></p></div>
	    </div>
	    
	    <div class="pad10">&nbsp;</div>
	    
	    <div class="pad5 f12">
	        <div class="header_05">How do I report an issue with FREE SMS API's service?</div>
	        <div><p>Use our <a href="<?=SERVER?>feedbacks/contact">Contact Us</a> form for any questions or concerns and we will get back to you shortly.</p></div>
	    </div>
	    
    </div>
    
</div>