<?php
require_once("includes/config.inc.php");
$pageTitle = "Thank you for contacting me";
$pageDescription = "I will get back to you as soon as possible";
$sideBar = "hobbies-sidebar.inc.php";
require("includes/header.inc.php");
?>
		<main>

			<div class="content-frame">
				<h1>Thank You</h1>
                <p>Thank you for contacting me. I will get back to you as soon as possible.</p>
			</div>
			
		</main>
<?php
if(!empty($sideBar)){
	require("includes/" . $sideBar);
}

require("includes/footer.inc.php")
?>