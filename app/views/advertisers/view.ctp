<div class="content_col_w520 fr">

    <div class="header_02">Advertisement List</div>
    
    <div class="success">
		<div><?php if(isset($success)) echo $success; ?></div>
	</div>
    
    <?php if(!empty($data)) { 
    		
    		foreach($data as $value) { ?>
    
    	<div class="pad5">&nbsp;</div>
		<div class="pad5 f12">
			<span>Content : </span>
			<span><?=$value['AdvContent']['content']?></span>
		</div>
		<div class="pad5 f12">
			<span>Category : </span>
			<span><?=$value['Category']['name']?></span>
		</div>
		<div class="pad5 f12">
			<span>Launch Date : </span>
			<span><?=$value['AdvContent']['launch_date']?></span>
		</div>
		<div class="pad5 f12">
			<span>Quantity : </span>
			<span><?=$value['AdvContent']['quantity']?></span>
		</div>
		<div class="pad5 f12">
			<span>Send : </span>
			<span><?=$value['AdvContent']['adv_send']?></span>
		</div>
		<div class="pad5 f12">
			<span>Status : </span>
			
			<?php if($value['AdvContent']['adv_send_status'] == 1) { ?>
			
				<span><strong>Still to send</strong></span>
			
			<?php } else if($value['AdvContent']['adv_send_status'] == 2) { ?>
			
				<span><strong>In Progress</strong></span>
			
			<?php } else { ?>
			
				<span><strong>Successfully send</strong></span>
			
			<?php } ?>
			
			
		</div>
		
		<?php if($value['AdvContent']['adv_send_status'] == 1) { ?>
		
			<div class="pad5 f12">
				<span><a href="/advertisers/deletecontent/<?=$value['AdvContent']['id']?>">Delete this content</a></span>
			</div>
		
		<?php } ?>
		
		<div class="pad5">&nbsp;</div>
		
	<?php } 
	
		} else {
	
	?>	
			<div class="pad5 f12"><span>Click <a href="/advertisers/add">here</a></span> to create new advertisement</div>
	
	<?
		
		}
	
	?>
    
</div>