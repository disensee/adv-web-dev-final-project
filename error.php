<?php
require_once("includes/config.inc.php");
$pageTitle = "Error | dylanisensee.com";
$pageDescription = "Sorry. There was an error.";
$sideBar = "hobbies-sidebar.inc.php";
require("includes/header.inc.php");
?>
		<main>

			<div class="content-frame">
				<h1>Sorry, there was an error.</h1>
                <p>We have been notified and will fix it ASAP.</p>
			</div>
			
		</main>
<?php
if(!empty($sideBar)){
	require("includes/" . $sideBar);
}

require("includes/footer.inc.php")
?>


