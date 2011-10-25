<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<!-- Include external files and scripts here (See HTML helper for more info.) -->
<?php echo $scripts_for_layout ?>
<?php echo $this->Html->css('asap.generic'); ?>
</head>
<body>
<img src="/img/asaplogo.png" alt="ASAP Logo" width="340" height="85" /> 
</br></br>

<!-- If you'd like some sort of menu to 
show up on all of your views, include it here -->
<div id="header">
    <div id="menu">
		<ul id="navbar">
			<li><a href="/trades/">Ticket</a></li>
			<li><a href="#">Standing Data</a><ul>
				<li><a href="/funds">Funds</a></li>
				<li><a href="/secs">Securities</a></li>
				<li><a href="/TradeTypes">Trade Types</a></li>
				<li><a href="/reasons">Reasons</a></li>
				<li><a href="/brokers">Brokers</a></li>
				<li><a href="/traders">Traders</a></li></ul>
			</li>
			<!-- ... and so on ... -->
		</ul>
	</div>
</div>

<!-- Here's where I want my views to be displayed -->
<?php echo $content_for_layout ?>

<?php echo $this->Session->flash('auth'); ?>

<!-- Add a footer to each displayed page -->
<div id="footer"></div>

</body>
</html>
