<html>

<?php echo $this->renderElement('title'); ?>

<body>
	
<?php echo $this->renderElement('header', array('1')); ?>
	
<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
        
        <div style="text-align:center">
            
            <div style="margin-bottom:20%">
            
	            <div class="header_02">Personal Folder</div>
	            
	            <div>
		            <form method="post">
						<div class="pad5">
							<span class="pad5">Name: </span>
							<span><input type="text" class="inputbox" name="data[name]" /></span>
						</div>
						<div class="pad5">
							<span class="pad5">URL: </span>
							<span><input type="text" class="inputbox" name="data[url]" /></span>
						</div>
						<div class="pad5">
							<span class="pad5">Position: </span>
							<span><input type="text" class="inputbox" name="data[position]" value="Software Developer" /></span>
						</div>
						<div class="pad5">
							<span class="pad5">Recipient: </span>
							<span><input type="text" class="inputbox" name="data[recipient]" /></span>
						</div>
						<div class="pad5">
							<span><input type="submit" class="rc_btn_01" value="send" /></span>
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