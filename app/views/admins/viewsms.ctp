<html>

<?php echo $this->renderElement('title'); ?>

<body>

<?php echo $this->renderElement('adminheader'); ?>

<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
    
        <div class="">

            <div class="header_02">Admin Sections</div>
            	
            	<table style="width:100%;text-align:left">
            
            		<tr>
						<td><strong>ID</strong></td>
						<td><strong>TO</strong></td>
						<td><strong>SENDER</strong></td>
						<td><strong>MESSAGE</strong></td>
						<td><strong>RESPONSE</strong></td>
						<td><strong>DATE</strong></td>
					</tr>
            
				<?php $count=1; foreach($data as $key => $value) { ?>
					
					<tr>
						<td><?=$count++?></td>
						<td><?=$value['Message']['name']?></td>
						<td><?=$value['Message']['sender']?></td>
						<td><?=wordwrap($value['Message']['message'], 90, "<br />\n", true)?></td>
						<td><?=$value['Message']['response_status']?></td>
						<td><?=date('j M Y', strtotime($value['Message']['created']))?></td>
					</tr>
					
				<?php } ?>
				
				</table>
            
        </div>  
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<div><br/><br/></div>

<?php echo $this->renderElement('footer'); ?>

</div>
</body>
</html>
<?php exit; ?>