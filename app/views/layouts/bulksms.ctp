<html>

<?php echo $this->renderElement('title'); ?>

<body>
	
<?php echo $this->renderElement('bulksmsheader', $tab); ?>
	
<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
    
        <div class="fl" style="width:200px">
        
            <a href="/bulksms/view"><div class="small_logo">&nbsp;</div></a>

            <div class="margin_bottom_10 border_bottom"></div>
            <div class="margin_bottom_30"></div>
        
            <?php echo $this->renderElement('bulksmsleftbar'); ?>
        
        	<div class="margin_bottom_10"></div>
        	<!--<div class="margin_bottom_10 border_bottom"></div>-->
        
        	<div class="margin_bottom_20"></div>
        </div>        <!-- end of a section -->
        
        <div class="fr" style="width:700px;margin:0px 0px 40px 20px;">
        
        	<?php echo $content_for_layout ?>
    
    	</div>
    	
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<?php echo $this->renderElement('inner_footer'); ?>

</body>

<html>