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
						<td><strong>PRICING ID</strong></td>
						<td><strong>AMOUNT</strong></td>
						<td><strong>IP</strong></td>
						<td><strong>CREATED</strong></td>
					</tr>
            
				<?php foreach($orders as $value) { ?>
					
					<tr valign="top">
						<td><?=$value['MerchantOrder']['id']?></td>
						<td><?=$value['MerchantOrder']['pricing_id']?></td>
						<td><?=$value['MerchantOrder']['amount']?></td>
						<td><?=long2ip($value['MerchantOrder']['ip'])?></td>
						<td><?=date('j M Y g:i a', strtotime($value['MerchantOrder']['created']))?></td>
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