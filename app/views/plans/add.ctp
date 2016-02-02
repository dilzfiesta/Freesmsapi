<html>

<?php echo $this->renderElement('title'); ?>

<body>
<div id="maincontent">
	<form method="post" action="/plans/save"  enctype="multipart/form-data">
		<div>
			<span><input type="text" name="data[Plan][name]" /></span>
			<span><input type="text" name="data[Plan][interval]" /></span>
			<span><input type="file" name="data[Plan][image]" /></span>
			<span><input type="text" name="data[Plan][id]" value="<?php echo $id; ?>"/></span>
			<span><input type="submit" value="save" /></span>
		</div>
	</form>
</div>
</body>
</html>
<?php exit; ?>