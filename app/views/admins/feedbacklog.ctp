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
						<td><strong>Domain</strong></td>
						<td><strong>FEEDBACK</strong></td>
						<td><strong>CREATED</strong></td>
						<td><strong>PUBLISH</strong></td>
					</tr>
            
				<?php foreach($feedback as $key => $value) { ?>
					
					<tr valign="top" style="background-color:<?=($value['Feedback']['publish']) ? GREEN_COLOR : ''?>">
						<td><?=$value['Feedback']['id']?></td>
						<td><?=$domain_list[$value['Feedback']['domain_id']]?></td>
						<td><?=nl2br($value['Feedback']['feedback'])?></td>
						<!--<td><?=$value['Feedback']['status']?>&nbsp;<a href="/admins/changestatus/feedback/<?=$value['Feedback']['id']?>">change</a></td>-->
						<td><?=date('j M Y g:i a', strtotime($value['Feedback']['created']))?></td>
						<td><?=$value['Feedback']['publish']?>&nbsp;<a href="/admins/changepublish/feedback/<?=$value['Feedback']['id']?>"  onclick="return confirm('Are you sure?')">change</a></td>
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