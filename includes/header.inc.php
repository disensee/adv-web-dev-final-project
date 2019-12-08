<?php

$contentClass = empty($sideBar) ? "single-column" : "";

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo($pageTitle); ?></title>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo($pageDescription); ?>">
    <meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="<?php echo(PROJECT_DIR); ?>styles/reset.css">
	<link rel="stylesheet" type="text/css" href="<?php echo(PROJECT_DIR); ?>styles/main.css">
	<script type="text/javascript" src="<?php echo(PROJECT_DIR); ?>js/main.js"></script>
</head>
<body>
	<header>
		<h1>Hello. I'm Dylan.</h1>
	</header>
	<nav id="main-nav">
		<ul>
			<li><a href="<?php echo(PROJECT_DIR); ?>index.php">Home</a></li>
			<li><a href="<?php echo(PROJECT_DIR); ?>pictures.php">Pictures</a></li>
			<li><a href="<?php echo(PROJECT_DIR); ?>blog/index.php">Blog</a></li>
			<li><a href="<?php echo(PROJECT_DIR); ?>contact.php">Contact</a></li>
			<?php
			 if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == "yes"){
				 echo('<li><a href=" ' . PROJECT_DIR . 'control-panel/index.php">Control Panel</a></li>');
			 }
			?>
		</ul>
	</nav>
	<div id="content" class="<?php echo($contentClass); ?>">