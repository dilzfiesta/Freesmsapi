<div class="content_col_w520 fr">

    <div class="header_02">Create New Advertisement</div>
    
    <div class="error">
		<?php if(isset($error)) { 
				foreach($error as $value) { ?>
					<div><?=$value?></div>
		<?php 	} 
			} else $content = '';
		?>
	</div>
	
	<div class="success">
		<div><?php if(isset($success)) echo $success; ?></div>
	</div>

	<?php if($remaining_quantity > 0) { ?>

    <form method="post">
		<div class="pad5">
			<span><textarea class="textarea" name="data[AdvContent][content]"><?=$content?></textarea></span>
			<span>Advertisement content</span>
			<span>(40 characters)</span>
		</div>
		<div class="pad5">
			<span><select class="dropdown" name="data[AdvContent][date]"><?=$date?></select></span>&nbsp;
			<span><select class="dropdown" name="data[AdvContent][month]"><?=$month?></select></span>&nbsp;
			<span><select class="dropdown" name="data[AdvContent][year]"><?=$year?></select></span>&nbsp;
			<span>Launch date</span>
		</div>
		<div class="pad5">
			<span><select class="dropdown" name="data[AdvContent][category_id]"><?=$category?></select></span>
			<span>Select a category</span>
		</div>
		<div class="pad5">
			<span><input type="text" class="inputbox" style="width:60px" name="data[AdvContent][quantity]" value="<?=$quantity?>"/></span>
			<span>Quantity (should be less than <?=$remaining_quantity?>)</span>
		</div>
		<div class="pad5">&nbsp;</div>
		<div class="pad5">
			<span><input type="submit" class="rc_btn_01" value="Save" /></span>
		</div>
	</form>
	
	<?php } else { ?>
	
		<div class="pad5 f12">
			<span>Your alloted quota of 100 free messages is exhausted.</span><br/>
			<span>Please visit us later for lucrative advertisement plans.</span>
		</div>
	
	<?php } ?>
    
</div>