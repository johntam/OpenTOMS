<?php foreach($options as $key => $val) { ?>
<option value="<?php echo $key; ?>"
<?php
	//set the initial selected to what's in $selected
	if ($val == $selected) {
		echo ' selected="selected"';
	}
?>
><?php echo $val; ?></option>
<?php } ?>