<html> 
 
<?=$this->renderElement('title')?> 

<body>
 
	<?=$this->renderElement('header')?>

	<div id="templatemo_content_wrapper">

    	<div id="templatemo_content">
	
			<?php echo $content_for_layout ?>
		
		</div>
	
	</div> 

	<?=$this->renderElement('footer')?>
	
</body>
 
</html>

<script type="text/javascript" language="javascript" src="<?=SERVER?>js/tooltip.js"></script>