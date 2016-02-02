<html>

<?php echo $this->renderElement('title'); ?>

<body>
	
<?php echo $this->renderElement('bulksmsheader', $tab); ?>
	
<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
        
        <?php echo $content_for_layout ?> 
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<?php echo $this->renderElement('footer'); ?>

</body>

<html>