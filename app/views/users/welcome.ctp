<?php
define("DEFAULT_FILE", "worklist.txt"); // Default file name to use.

if (!isset($_GET['file'])) {
    $file = DEFAULT_FILE;
}
else {
    $file = $_GET['file'];
}


if (isset($_POST['bsubmit']))
{	
	$fh = fopen($file, 'w') or die("Can't open file.");
    fwrite($fh, stripslashes($_POST['body']));
    fclose($fh);
	$message='Notes updated';
}

?>

<center>

	<table width=400>
		<tr>
			<td>
				<h1>Welcome to ASAP</h1>
			</td>
		</tr>
	
		<tr>
			<td style='border: 2px dashed #003b53; padding:10px; font-family:verdana; font-size:10px; color: #003b53;' align='center'>
				Please feel free to ammend the notes below:<br><br>


				<FORM action='<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'welcome')); ?>' method='post'>
					<textarea name='body'  rows="25" cols="100"  style="font-family: Verdana; padding: 5px; background-color: LightYellow"><?php
						if (file_exists($file)) {
							readfile($file);
						}
					?></textarea><br><br>
					
					<?php if (isset($message)) {echo $message;} ?> 

					<INPUT type="submit" name="bsubmit" value="Update">
				</FORM>
			</td>
		</tr>
	</table>
</center>