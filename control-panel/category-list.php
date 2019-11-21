<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/CategoryDataAccess.inc.php");
$pageTitle = "Category List";
$pageDescription = "";

require("../includes/header.inc.php");
?>
<main>
	<div class="content-frame">
		<h3>Category List</h3>
		<a href="category-details.php">Add New Category</a>
        <?php

        $cda = new CategoryDataAccess(getDBLink());
        $categories = $cda->getCategoryList(false);
        echo(displayCategories($categories));

        ?>
	</div>
</main>
		
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");

function displayCategories($categories){
    //build a table
    $html = "<table border=\"1\">";

    //column headers
    $html .=    "<tr>
                    <th>Name</th>
                    <th>Active</th>
                    <th>Edit</th>
                </tr>";

    //table rows (loop through the blog pages)
    foreach($categories as $category){
        $html .= "<tr>";
        $html .= "<td>{$category['name']}</td>";
        $html .= "<td>{$category['active']}</td>";
        $html .= "<td><a href=\"category-details.php?categoryId={$category['categoryId']}\">EDIT</a></td>";
        $html .= "</tr>";
    }

    //closing table tag
    $html .= "</table>";

    return $html;
}
?>