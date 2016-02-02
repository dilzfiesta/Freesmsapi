<div class="gradient"><h1><span></span>Address Book</h1></div>

<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));  ?>

<div class="header_hw"><div class="header_wrapper header_03">View Contacts</div></div>
<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;" align="left">View Contacts</div>-->

<div style="margin-left:-5px">
	<span style="padding:0px 4px"><label class="f13">Sort By : </label>&nbsp;<select id="bulk_group_id" class="dropdown" style="width:150px;height:28px" onchange="B_S_S_G(this.value)"><?=$groupdata?></select></span>
	<!--<span style="padding:0px 4px"><select id="s_group_id" class="dropdown" style="width:150px" onchange="B_S_M_G(this.value)"><?=$s_group?></select></span>
	<span style="padding:0px 4px"><select id="c_group_id" class="dropdown" style="width:150px" onchange="B_S_C_G(this.value)"><?=$c_group?></select></span>
	<!--<span style="padding:0px 4px"><input type="button" style="padding:3px 5px;" value="View Groups" onclick="javascript:window.location.replace('/bulksms/groups')" /></span>
	<span style="padding:0px 4px"><input type="button" style="padding:3px 5px;" value="Add Contact" onclick="javascript:window.location.replace('/bulksms/addressbook/add')" /></span>-->
</div>

<div style="margin-left:-5px;padding-bottom:10px;padding-top:10px">
	<form method='post'>
        <div class="pad5 f14">
        	<div style="padding-top:10px">
        		<label class="f13">Search By : </label>
        		<span style="padding:0px 4px"><input type="text" name="data[firstname]" class="inputbox" value="<?=(isset($firstname)?$firstname:'firstname')?>" onclick="javascript:if(this.value=='firstname') this.value='';"></span></span>
        		<span style="padding:0px 4px"><input type="text" name="data[lastname]" class="inputbox" value="<?=(isset($lastname)?$lastname:'lastname')?>" onclick="javascript:if(this.value=='lastname') this.value='';"></span></span>
        		<span style="padding:0px 4px"><input type="text" name="data[mobile]" class="inputbox" value="<?=(isset($mobile)?$mobile:'mobile')?>" onclick="javascript:if(this.value=='mobile') this.value='';"></span></span>
        		<span style="padding:0px 4px"><input type='submit' value='Search' class="button"/></span>
	        </div>
        </div>
    </form>
</div>

<div>&nbsp;</div>

<!--<div class="silent_feature" style="margin-left:0px; margin-right:0px; margin-bottom:-20px"><strong><? echo $paginator->counter(array('format' => __('Page %page% of %pages%, &nbsp;&nbsp;showing %current% records out of %count% total, &nbsp;&nbsp;starting on record %start%, &nbsp;&nbsp;ending on %end%', true))); ?></strong></div>-->

<div style="margin-left:0px; margin-right:0px; margin-bottom:-14x;margin-top:5px">
	<!--<span style="float:left"><strong>SELECT</strong>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="B_S_S_CK(true)">ALL</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="B_S_S_CK(false)">NONE</a></span>-->
	
	<span style="float:left;padding:4px;background-color:#E0E0E0;border:1px solid #cfcdcd"><input type="checkbox" onclick="B_S_S_CK(this)" /></span>
	<span style="float:left;padding-left:5px;"><input type="button" class="button" onclick="B_S_D_CN()" value="Delete" /></span>
	<span style="float:left;padding-left:5px"><select id="s_group_id" class="dropdown" style="width:150px;height:28px" onchange="B_S_M_G(this.value)"><?=$s_group?></select></span>
	<span style="float:left;padding-left:5px"><select id="c_group_id" class="dropdown" style="width:150px;height:28px" onchange="B_S_C_G(this.value)"><?=$c_group?></select></span>
	
	
	<!--<span style="float:left;margin-left:25%" class="f13"><strong>Showing <?=empty($groupname)?'All':$groupname?> Group</strong></span>-->
	<span style="float:right;padding:5px"><strong><? echo $paginator->counter(array('format' => __('Page %page% of %pages% (Total %count% Contacts)', true))); ?></strong></span>
</div>

<table cellpadding="4" id="hor-zebra" style="width:100%">
	<thead>
		<tr>
			<th scope="col"></th>
			<th scope="col"><?php echo $paginator->sort('Firstname', 'BulkAddressbook.firstname', array('url' => $paginator->params['pass']));?></th>
			<th scope="col"><?php echo $paginator->sort('Lastname', 'BulkAddressbook.lastname', array('url' => $paginator->params['pass']));?></th>
			<th scope="col"><?php echo $paginator->sort('Mobile', 'BulkAddressbook.mobile', array('url' => $paginator->params['pass']));?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	
	<?php if(!empty($data)) { ?>
	
	<?php for($i=0; $i<count($data); $i++) { ?>
	
		<tr id="tr_<?=$data[$i]['BulkAddressbook']['id']?>" valign="top" <?=(($i%2)==0)?'class="odd"':''?>>
			<td align="left" width="40px"><?=$i+1?>&nbsp;<input type="checkbox" class="d_cb" value="<?=$data[$i]['BulkAddressbook']['id']?>" /></td>
			<td><span id="s_fn_<?=$data[$i]['BulkAddressbook']['id']?>"><?=!empty($data[$i]['BulkAddressbook']['firstname']) ? $data[$i]['BulkAddressbook']['firstname'] : '-'?></span></td>
			<td><span id="s_ln_<?=$data[$i]['BulkAddressbook']['id']?>"><?=!empty($data[$i]['BulkAddressbook']['lastname']) ? $data[$i]['BulkAddressbook']['lastname'] : '-'?></span></td>
			<td><span id="s_m_<?=$data[$i]['BulkAddressbook']['id']?>"><?=$data[$i]['BulkAddressbook']['mobile']?></span></td>
			<td align="left" width="20px"><a href="javascript:void(0)" onclick="B_S_E(<?=$data[$i]['BulkAddressbook']['id']?>)">Edit</a>&nbsp;/&nbsp;<a onclick="return confirm('Are you sure?')" href="/bulksms/delete/addressbook/<?=$data[$i]['BulkAddressbook']['id']?>">Delete</a></td>
		</tr>
		
		<tr id="tr_e_<?=$data[$i]['BulkAddressbook']['id']?>" valign="top" <?=(($i%2)==0)?'class="odd"':''?> style="display:none">
			<td></td>
			<td><input type="text" class="inputbox" style="width:120px" id="fn_<?=$data[$i]['BulkAddressbook']['id']?>" value="<?=$data[$i]['BulkAddressbook']['firstname']?>" /></td>
			<td><input type="text" class="inputbox" style="width:120px" id="ln_<?=$data[$i]['BulkAddressbook']['id']?>" value="<?=$data[$i]['BulkAddressbook']['lastname']?>" /></td>
			<td><input type="text" class="inputbox" style="width:120px" id="m_<?=$data[$i]['BulkAddressbook']['id']?>" value="<?=$data[$i]['BulkAddressbook']['mobile']?>" /></td>
			<td align="left" width="20px">
				<a href="javascript:void(0)" onclick="B_S_E_S(<?=$data[$i]['BulkAddressbook']['id']?>, this)"><img src="/img/right.png" width="30px" alt="save"/></a>
				<a href="javascript:void(0)" onclick="B_S_E_C(<?=$data[$i]['BulkAddressbook']['id']?>)"><img src="/img/cancel.png" width="30px" alt="cancel"/></a>
			</td>
			<!--<td><input type="button" value="save" onclick="B_S_E_S(<?=$data[$i]['BulkAddressbook']['id']?>, this)" />&nbsp<input type="button" value="cancel" onclick="B_S_E_C(<?=$data[$i]['BulkAddressbook']['id']?>)" /></td>-->
		</tr>
		
	<?php } ?>
	
	<?php } else { ?>

		<tr valign="top">
			<td colspan="5" align="center">NO ENTRIES FOUND</td>
		</tr>
	
	<?php } ?>
		
	</tbody>
</table>

<?php if($paginator->counter(array('format' => __('%pages%', true))) > 1) { ?> 

<div style="margin-top:-30px">
	<ul id="pagination-flickr">
		<?php 
				$prev = $paginator->prev('<< '.__('prev', true), array('url' => $paginator->params['pass']), null, array('class'=>'disabled'));
				if(strstr($prev, 'disabled')) {
					$class = 'previous-off';
					$link = null;
				} else {
					$class = "prev";
					preg_match('/<(a.*) href=\"(.*?)\"(.*)<\/a>/', $prev, $patterns);
					$link = $patterns['2'];
				}
				
				echo ($link) ? '<li class="previous"><a href="'.$link.'">&laquo; Previous</a></li>' : '<li class="previous-off">&laquo; Previous</li>';
									
		?>
		
		<?php 	$numbers = $paginator->numbers(array('url' => $paginator->params['pass']));
				$n = explode('" | "', $numbers);
				$n = explode('|', $n['0']);
				
				foreach($n as $v) {
					
					if(strstr($v, 'current')) {
						$class = 'active';
						$link = null;
					} else {
						$class = 'notactive';
						preg_match('/<(a.*) href=\"(.*?)\"(.*)<\/a>/', $v, $patterns);
						$link = $patterns['2'];
					}
					$data = strip_tags($v);
					
					echo ($link) ? '<li class="'.$class.'"><a href="'.$link.'">'.$data.'</a></li>' : '<li class="'.$class.'">'.$data.'</li>';
					
				}
		?>
		
		<?php 
				$next = $paginator->next(__('next', true).' >> ', array('url' => $paginator->params['pass']), null, array('class'=>'disabled'));
				if(strstr($next, 'disabled')) {
					$class = 'next-off';
					$link = null;
				} else {
					$class = "next";
					preg_match('/<(a.*) href=\"(.*?)\"(.*)<\/a>/', $next, $patterns);
					$link = $patterns['2'];
				}
				
				echo ($link) ? '<li class="next"><a href="'.$link.'">Next &raquo;</a></li>' : '<li class="next-off">Next &raquo;</li>';
									
		?>
		
	</ul> 
</div>

<? } ?>