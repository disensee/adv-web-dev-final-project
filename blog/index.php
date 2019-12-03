<?php
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");
$pageTitle = "Blog";
$pageDescription = "Welcome to my blog. This page is where I will make blog posts about my interests.";
$sideBar = "hobbies-sidebar.inc.php";
require("../includes/header.inc.php");

$pda = new PageDataAccess(getDBLink());
$activePages = $pda->getPageList();

if(!empty($_POST['txt_search'])){
	header('Location: search-results.php');
}

?>
		<main>

			<div class="content-frame">
				
				<h1>Blog</h1>
				<form id="blogSearch" method ="POST" name="search" action="">
					<input type="text" name="txt_search" placeholder="Search...">
					<input type="submit" name="btn_search" value="Search">
				</form>
				<?php echo(createBlogList($activePages)); ?>

			</div>
			
		</main>
<?php

if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");

//wraps the blog pages in an unordered list
function createBlogList($pages){

	$html = "<ul class=\"blog-list\">";

	foreach ($pages as $p) {
		$html .= "<li>";
		$html .= 	"<a href=\"blog-post.php?pageId=" . $p['pageId'] . "\">";
		$html .= 		$p['title'];
		$html .= 	"</a>";
		$html .= "</li>";
		
	}

	$html .= "</ul>";
	return $html;
}
?>


