<?php if(!empty($error)) { ?>

	<div class="error">
	
	<?php		if(is_array($error)) {
					foreach($error as $value) { 
	?>
					<div><?=$value?></div>
	
	<?php			}
				} else {
	?>
					<div><?=$error?></div>
	
	<?php		}	?> 
	
	</div> 
	
<?php	}	?>


<?php if(!empty($success)) { ?>

	<div class="success">
	
	<?php		if(is_array($success)) {
					foreach($success as $value) { 
	?>
					<div><?=$value?></div>
	
	<?php			}
				} else {
	?>
					<div><?=$success?></div>
	
	<?php		}	?> 
	
	</div> 
	
<?php	}	?>