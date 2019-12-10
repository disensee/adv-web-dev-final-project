<?php
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");
$pageTitle = "Blog | dylanisensee.com";
$pageDescription = "Welcome to my blog. This page is where I will make blog posts about my interests.";
$sideBar = "hobbies-sidebar.inc.php";
require("../includes/header.inc.php");

$pda = new PageDataAccess(getDBLink());

$display = 5;

//Determine where in the database to start returning results
if(isset($_GET['s']) && is_numeric($_GET['s'])){
	$start = $_GET['s'];
}else{
	$start = 0;
}


?>
		<main>

			<div class="content-frame">
				
				<h3>Blog</h3>
				<form id="blogSearch" method ="POST" action="search-results.php">
					<input type="text" name="txt_search" placeholder="Search...">
					<input type="submit" name="btn_search" value="Search">
				</form>
				<?php echo($pda->createPaginatedList($start, $display)); ?>
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
		$html .= "<li class='blog-page-list'>";
		$html .= 	"<a href=\"blog-post.php?pageId=" . $p['pageId'] . "\">";
		$html .= 		$p['title'];
		$html .= 	"</a>";
		$html .= "</li>";
		
	}

	$html .= "</ul>";
	return $html;
}
?>


