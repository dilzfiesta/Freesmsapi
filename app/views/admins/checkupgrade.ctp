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
						<td><strong>PLAN</strong></td>
						<td><strong>REQUEST BODY</strong></td>
						<td><strong>APPLIED ON</strong></td>
						<td><strong>UPGRADE</strong></td>
						<td><strong>DECLINE</strong></td>
						<!--<td><strong>REMOVE FROM LIST</strong></td>-->
					</tr>
            
				<?php  if(!empty($data)) { foreach($data as $key => $value) { ?>
					
					<tr>
						<td><?=$key+1?></td>
						<td><?=$value['d']['domain_name']?></td>
						<td><?=$value['p']['plan_name']?></td>
						<td><?=$value['u']['requestbody']?></td>
						<td><?=date('j M Y g:i a', strtotime($value['u']['created']))?></td>
						<td><form action='/admins/changeupgrage/<?=$value['d']['domain_id']?>/<?=$value['u']['id']?>' method='post'><select name='data[select]' onchange='if(confirm("Are you sure?")) this.parentNode.submit();'><?=$plan?></select></form></td>
						<td><a href='/admins/declineupgrade/<?=$value['d']['domain_id']?>/<?=$value['u']['id']?>' onclick="return confirm('Are you sure?')">decline</a></td>
						<!--<td><a href='/admins/removeupgrade/<?=$value['u']['id']?>'>remove</a></td>-->
					</tr>
					
				<?php } } else { ?>
					
					<tr><td colspan='8'>NO RECORDS FOUND</td></tr>
					
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