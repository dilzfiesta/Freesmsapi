<div class="gradient"><h1><span></span>Address Book</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($error)?$error:'', 'success'=>isset($success)?$success:''));   ?>

<div class="header_hw"><div class="header_wrapper header_03">Add Contact</div></div>
<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;" align="left">Add Contact</div>-->

<div><br/></div>

<div>
	<table width="100%">
		<tr>
			<td width="46%" valign="top" style="background-color:#FFE;padding:5px;">
				<form method="post">
					<div class="pad5">
						<span><input type="text" class="inputbox" name="data[BulkAddressbook][firstname]" value="<?=$firstname?>" /></span>
						<span class="f12">Firstname (<i>eg: John</i>)</span>
					</div>
					<div class="pad5">
						<span><input type="text" class="inputbox" name="data[BulkAddressbook][lastname]" value="<?=$lastname?>" /></span>
						<span class="f12">Lastname (<i>eg: Smith</i>)</span>
					</div>
					<div class="pad5">
						<span><input type="text" class="inputbox" name="data[BulkAddressbook][mobile]" value="<?=$mobile?>" /></span>
						<span class="f12">Mobile (<i>eg: 9699419699</i>)</span>
					</div>
					<div class="pad5">
						<span><select name="data[BulkAddressbook][bulk_group_id]" class="dropdown" style="width:150px"><?=$groupdata?></select></span>
						<span class="f12">Group Name</span>
					</div>
					<div class="pad5" style="margin:10px 0px 0px -5px">
						<span><input type="submit" class="rc_btn_01" value="add" /></span>
					</div>
				</form>
			</td>
			<td width="8%" align="center" valign="top" style="padding-top:10px">
				<span style="font-size:18px">OR</span>
			</td>
			<td width="46%" valign="top" style="background-color:#FFE;padding:5px">
				<form method="post" enctype="multipart/form-data">
					<div class="pad5">
						<span><input type="file" name="data[file]" style="width:250px"/></span>
						<!--<span><a href="/example/bulk_csv_example.csv"><img src="/img/csv_file.png" height="50px" alt="Comma Seperated Value File" /></a>&nbsp;<a href="/example/bulk_excel_example.xls"><img src="/img/xls_file.png" height="50px" alt="MS Excel File" /></a></span>-->
					</div>
					<div class="pad5">
						<span class="f12">Please refer to example file for more details (<a href="/example/bulk_csv_example.csv">CSV</a>, <a href="/example/bulk_excel_example.xls">Excel</a>)</span>
					</div>
					<div class="pad5">
						<span><select name="data[BulkAddressbook][bulk_group_id]" class="dropdown" style="width:150px"><?=$groupdata?></select></span>
						<span class="f12">Group Name</span>
					</div>
					<div class="pad5" style="margin:10px 0px 0px -5px">
						<span><input type="submit" class="rc_btn_01" value="add" /></span>
					</div>
				</form>
			</td>
		</tr>
	</table>
	
</div>