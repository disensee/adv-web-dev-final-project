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
				<?php echo(createPaginatedList($start, $display)); ?>

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


function createPaginatedList($start, $display){
	global $pda;

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
	
	$qStr = "SELECT pageId, path, title, DATE_FORMAT(publishedDate,'%m/%e/%Y') as publishedDate, active FROM pages WHERE active = 'yes' LIMIT $start, $display";
	
	//die($qStr); //using die() after $qStr is a good way to see the query while testing

	$result = mysqli_query($pda->getLink(), $qStr) or $this->handleError(mysqli_error($pda->getLink()));

	$pageList = array();

	while($row = mysqli_fetch_assoc($result)){
		$page = array();
		$page['pageId'] = htmlentities($row['pageId']);
		$page['path'] = htmlentities($row['path']);
		$page['title'] = htmlentities($row['title']);
		$page['publishedDate'] = htmlentities($row['publishedDate']);
		$page['active'] = htmlentities($row['active']);
		$pageList[] = $page;
	}

	echo(createBlogList($pageList));
	//Make the links to other pages if necessary
	if($pages > 1){
		echo('<br><p>');

		//determine what page the script is on
		$current_page = ($start/$display) + 1;

		//If it's not the first page, make a previous link
		if($current_page != 1){
			echo('<a href="index.php?s=' . ($start - $display) . '&p=' . $pages . '">Previous</a>');
		}

		//make all numbered pages:
		for($i=1; $i <= $pages; $i++){
			if($i != $current_page){
				echo('<a href="index.php?s=' . (($display * ($i -1))) . '&p=' . $pages . '">' . $i . '</a>');
			}else{
				echo($i . ' ');
			}
		}
		//If it's not the last page, make a next button
		if($current_page != $pages){
			echo('<a href="index.php?s=' . ($start + $display) . '&p=' . $pages . '">Next</a>');
		}

		echo('</p>');
	}
}
?>


