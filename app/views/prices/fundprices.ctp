<!-- File: /app/views/prices/fundprices.ctp -->

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td><h1>Fund Prices and Estimates</h1></td>
	</tr>
	
	<tr>
		<td>
			<div id="file-uploader-demo1">		
			<noscript>			
				<p>Please enable JavaScript to use file uploader.</p>
				<!-- or put a simple form for upload here -->
			</noscript>
		</td>
	</tr>
	</div>
</table>


<table style="width: 40%;margin-left:30%;margin-right:30%;">	
	
</table>

<script type="text/javascript">
	$(document).ready(function() {
		var uploader = new qq.FileUploader({
			element: document.getElementById('file-uploader-demo1'),
			action: '/fileuploader.php',
			params: {
				host:	'<?php echo $host; ?>',
				username:	'<?php echo $username; ?>',
				password:	'<?php echo $password; ?>',
				database:	'<?php echo $database; ?>'
			},
			debug: true
		});
	});
</script>

<?php echo $this->Html->script('jquery-ui-1.8.18.custom.min.js'); ?>
<?php echo $this->Html->css('ui-lightness/jquery-ui-1.8.18.custom.css'); ?>
<?php echo $this->Html->script('fileuploader.js',array('inline' => false)); ?>