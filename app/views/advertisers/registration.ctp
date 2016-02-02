<div class="content_col_w520 fr">

    <div class="header_02">Registration Form</div>
    
    <div class='error'>
		<?php
			if(isset($error)) {
				foreach($error as $value) {
					?>
					<div><?=$value?></div>
					<?php
				}
			} else {
				$mobile = $fname= $lname = $email = $company_name = $description = '';
				$checkbox = '';
			}
		?>
	</div>
	
	<div class='success'>
		<?php if(isset($success)) { ?>
				<div><?=$success?></div>
		<?php }	?>
	</div>

    <form method="post">
    	<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Advertiser][fname]" value="<?=$fname?>"/></span>
			<span>First name (<i>eg: sandeep</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Advertiser][lname]" value="<?=$lname?>"/></span>
			<span>Last name (<i>eg: ghia</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Advertiser][mobile]" value="<?=$mobile?>"/></span>
			<span>Mobile number (<i>eg: 9699419699</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Advertiser][email]" value="<?=$email?>"/></span>
			<span>Email ID (<i>eg: sandeepghia@gmail.com</i>)</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" name="data[Advertiser][company_name]" value="<?=$company_name?>"/></span>
			<span>Company name (<i>eg: palsan services pvt ltd</i>)</span>
		</div>
		<div class="pad5">
			<span><textarea class="textarea" name="data[Advertiser][description]"><?=$description?></textarea></span>
			<span>Brief description about your company (<i>eg: import/export, IT services, web portal etc</i>)</span>
		</div>
		<div class="pad5">
			<span><?=$recaptcha?></span>
		</div>
		<div class="pad5">
			<span><input type="checkbox" name="data[Advertiser][agree]" <?=$checkbox?>/></span>
			<span>I agree with the <a href="/advertisers/tnc" target="_blank"><strong>Terms & Condition</strong></a></span>
		</div>
		<div class="pad5">
			<span><input type="submit" class="rc_btn_01" value="register" /></span>
		</div>
	</form>
    
</div>