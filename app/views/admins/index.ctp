<html>

<?php echo $this->renderElement('title'); ?>

<body>
	
<?php echo $this->renderElement('header', array('1')); ?>
	
<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
        
        <div style="text-align:center">

			<div style="text-align:left">
           		<img src="/img/freesmsapi_170.png" />
            </div> 
            
            <div style="margin-bottom:20%">
            
	            <div class="header_02">Admin Login</div>
	            
	            <div>
		            <form method="post">
						<div class="pad5">
							<span><input type="text" class="inputbox" name="data[username]" /></span>
						</div>
						<div class="pad5">
							<span><input type="password" class="inputbox" name="data[password1]" /></span>
						</div>
						<div class="pad5">
							<span><input type="password" class="inputbox" name="data[password2]" /></span>
						</div>
						<div class="pad5">
							<span><input type="submit" class="rc_btn_01" value="login" /></span>
						</div>
					</form>
				</div>
				
			</div>
            
        </div>  
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<?php echo $this->renderElement('footer'); ?>

</body>
<html>
<?php exit; ?>