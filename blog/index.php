<?php
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");
$pageTitle = "Blog";
$pageDescription = "Welcome to my blog. This page is where I will make blog posts about my interests.";
$sideBar = "hobbies-sidebar.inc.php";
require("../includes/header.inc.php");

$pda = new PageDataAccess(getDBLink());
$activePages = $pda->getPageList();

$display = 5;

var_dump($_GET);

if(isset($_GET['p']) && is_numeric($_GET['p'])){ //number of posts already determined
	$pages = $_GET['p'];
}else{ //need to deterimine amount of pages

	//count the number of records:
	$qStr = "SELECT COUNT(pageId) FROM pages";
	$result = @mysqli_query($pda->getLink(), $qStr);
	$row = @mysqli_fetch_array($result, MYSQLI_NUM);
	$records = $row[0];

	//calculate the number of pages
	if($records > $display){//more than one page
		$pages = ceil($records/$display);
	}else{
		$pages = 1;
	}
}

//Determine where in the database to start returning results
if(isset($_GET['s']) && is_numeric($_GET['s'])){
	$start = $_GET['s'];
}else{
	$start = 0;
}

?>
		<main>

			<div class="content-frame">
				
				<h1>Blog</h1>
				<form id="blogSearch" method ="POST" action="search-results.php">
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


