<?php
require_once("../includes/config.inc.php");
$pageTitle = "Blog";
$pageDescription = "Welcome to my blog. This page is where I will make blog posts about my interests.";
$sideBar = "hobbies-sidebar.inc.php";
require("../includes/header.inc.php");
?>
		<main>

			<div class="content-frame">
				
				<h1>Blog</h1>
				<p>This page will display a list of recent blog posts</p>

			</div>
			
		</main>
<?php

if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php")
?>


