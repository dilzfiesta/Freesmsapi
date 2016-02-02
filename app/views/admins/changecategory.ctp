<html>

<?php echo $this->renderElement('title'); ?>

<body>

<?php echo $this->renderElement('adminheader'); ?>

<div id="templatemo_content_wrapper">

    <div id="templatemo_content">
    
        <div class="">

            <div class="header_02">Admin Sections</div>
            
            <br/><br/>
            
            <div class="header_03">Category Status</div>
            
        	<table cellspacing="4" cellpadding="4" class="f12">
        	
        		<tr>
        			<td><strong>ID</strong></td>
        			<td><strong>CATEGORY</strong></td>
        			<td><strong>ADV QUANTITY</strong></td>
        			<td><strong>DOMAIN COUNT</strong></td>
        		</tr>
        	
            <?php foreach($category as $key => $value) { ?>
            	
            	<tr>
            		<td><?=$value['Category']['id']?></td>
            		<td><?=$value['Category']['name']?></td>
            		
            		<?php $adv_quantity = 0; foreach($value['AdvContent'] as $v) $adv_quantity = $adv_quantity + $v['quantity']; ?>
            		
            		<td><?=$adv_quantity?></td>
            		<td><?=count($value['Domain'])?></td>
            	</tr>
            	
			<?php } ?>
			
			</table>   
            
            <div><br/><br/></div>
            
            <div class="header_03">Change category for <?=$domain?></div>
            
            <br/>
            
            <form method="post">
            
	            <select name="data[Domain][category_id]" class="dropdown">
	            
	            	<?php foreach($category as $key => $value) { ?>
	            		
	            		<option value="<?=$value['Category']['id']?>"><?=$value['Category']['name']?></option>
	            		
	            	<?php } ?>
	            	
	            </select>
	            
	            <br/><br/>
	            
	            <span><input type="submit" class="rc_btn_01" value="submit" /></span>
	            <span><input type="hidden" name="data[Domain][id]" value="<?=$domain_id?>" /></span>
	            
			</form>
            
        </div>  
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<?php echo $this->renderElement('footer'); ?>

</div>
</body>
<html>
<?php exit; ?>