<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
				<h1>ASAP To Do List</h1>
			</td>
		</tr>
	
		<tr>
			<td style='border: 2px dashed #003b53; padding:10px; font-family:verdana; font-size:10px; color: #003b53;' align='center'>
				Please feel free to ammend the notes below:<br><br>


				<FORM action='<?php echo $this->Html->url(array('controller'=>'miscpages', 'action'=>'worklist')); ?>' method='post'>
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
