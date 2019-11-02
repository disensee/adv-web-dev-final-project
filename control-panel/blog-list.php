<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/PageDataAccess.inc.php");
$pageTitle = "Blog List";
$pageDescription = "";

require("../includes/header.inc.php");
?>
<main>
	<div class="content-frame">
		<h3>Blog List</h3>
		<a href="blog-details.php">Add New Blog Page</a>
        <?php

        $pda = new PageDataAccess(getDBLink());
        $pages = $pda->getPageList(false);
        echo(displayPages($pages));

        ?>
	</div>
</main>
		
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");

function displayPages($pages){
    //build a table
    $html = "<table border=\"1\">";

    //column headers
    $html .=    "<tr>
                    <th>Title</th>
                    <th>Publish Date</th>
                    <th>Active</th>
                    <th>Edit</th>
                </tr>";

    //table rows (loop through the blog pages)
    foreach($pages as $page){
        $html .= "<tr>";
        $html .= "<td>{$page['title']}</td>";
        $html .= "<td>{$page['publishedDate']}</td>";
        $html .= "<td>{$page['active']}</td>";
        $html .= "<td><a href=\"blog-details.php?pageId={$page['pageId']}\">EDIT</a></td>";
        $html .= "</tr>";
    }

    //closing table tag
    $html .= "</table>";

    return $html;
}
?>