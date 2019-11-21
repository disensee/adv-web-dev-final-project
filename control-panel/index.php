<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
$pageTitle = "Control Panel";
$pageDescription = "";
//$sideBar = "hobbies-sidebar.inc.php";

require("../includes/header.inc.php");
?>
<main>
	<div class="content-frame">
		<h3>Control Panel</h3>
		<a href="blog-list.php">Blog List</a>
		<br>
		<a href="file-list.php">File List</a>
		<br>
		<a href="category-list.php">Category List</a>
	</div>
</main>
		
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");
?>