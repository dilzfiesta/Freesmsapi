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
						<td><strong>NAME</strong></td>
						<td><strong>MESSAGE</strong></td>
						<td><strong>EMAIL</strong></td>
						<td><strong>CREATED</strong></td>
					</tr>
            
				<?php foreach($contact as $key => $value) { ?>
					
					<tr valign="top">
						<td><?=$value['Contact']['id']?></td>
						<td><?=$value['Contact']['name']?></td>
						<td><?=nl2br($value['Contact']['feedback'])?></td>
						<td><?=$value['Contact']['email']?></td>
						<td><?=date('j M Y g:i a', strtotime($value['Contact']['created']))?></td>
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
<html>
<?php exit; ?>