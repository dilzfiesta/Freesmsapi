<html>

<?php echo $this->renderElement('title'); ?>

<body>
	
<?php echo $this->renderElement('adminheader', $tab); ?>
	
<div id="templatemo_content_wrapper">

    <div id="templatemo_content" style="width:1200px">
        
       	<div class="header_02">Admin Sections</div>
        
        <?php echo $content_for_layout ?>
        
        <div><br/><br/></div> 
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<?php echo $this->renderElement('footer'); ?>

</body>

<html>