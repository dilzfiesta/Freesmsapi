<html>

<?php echo $this->renderElement('title'); ?>

<body>
	
<?php echo $this->renderElement('header', $tab); ?>
	
<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
    
        <div class="content_col_w320 fl">
        
        	<a href="<?=SERVER?>"><div class="logo">&nbsp;</div></a>

            <div class="margin_bottom_10 border_bottom"></div>
            <div class="margin_bottom_20"></div>
        
            <?php echo $this->renderElement('feedback', $feedback); ?>
        
        	<div class="margin_bottom_20"></div>
        </div>        <!-- end of a section -->
        
        <?php echo $content_for_layout ?> 
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<?php echo $this->renderElement('footer'); ?>

</body>

<html>