<div class="content_col_w520 fr">

	<div class="gradient"><h1><span></span>Registration Complete</h1></div>
	
	<div style="padding-top:20px">

	    <div class="success"><div class="pad5 f12">Your account has been created successfully.</div></div>
	    
	    <div class="f13">Your information has been received. Please check your inbox right away for the login details. We just sent you from "<?=INTERNAL_SENDER?>" with the subject "Login Information".</div>
	    
		<div><br/></div>
		<div class="pad5"><span class="f13"><strong>Username</strong> - <?=$mobile?></span></div>
		<div class="pad5"><span class="f13"><strong>Password</strong> - Sent on <?=$email?></span></div>
		<div><br/></div>
		
		<div class="f13">(please also check your bulk/junk/spam folder as sometimes email messages are mistakenly filtered as such)</div>
		
		<div><br/></div>
		<div><br/></div>
		
		<div class="pad5">
	    	<table cellspacing="0" cellpadding="0" align="center" class="signup_btn" onclick="window.location='<?=SERVER?>users/login';">
	    		<tbody>
	    			<tr>
						<td class="SPRITE_signup_button_grey_l"></td>
						<td class="SPRITE_signup_button_grey_m">
							<a class="signup_btn_link" style="color:#000;text-decoration:none;" href="<?=SERVER?>users/login"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Click here to login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></a></td>
						<td class="SPRITE_signup_button_grey_r"></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	    <!-- <div><br/></div>
	    
	    <div class="pad5 f12"><strong>NOTE</strong>: If your mobile number is registered with <strong>National Do Not Call Registry (NDNC Registry)</strong> you wont be able to recieve any SMS.</div> -->

	</div>
    
</div>