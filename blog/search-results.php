<?php
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");
$pageTitle = "Search Results";
$pageDescription = "";
$sideBar = "hobbies-sidebar.inc.php";
require("../includes/header.inc.php");

$pda = new PageDataAccess(getDBLink());
?>

<h1>Search Results</h1>

<div class="search_result_containter">
<?php

//var_dump($_POST);

if(isset($_POST['btn_search'])){
    $search = mysqli_real_escape_string($pda->getLink(), $_POST['txt_search']);
    $qStr = "SELECT pageId, title, description, content
            FROM pages
            WHERE MATCH (title, description, content)
            AGAINST ('$search')
            AND active = 'yes'";
    
    $result = mysqli_query($pda->getLink(), $qStr); 
    $num_rows = mysqli_num_rows($result);

    echo("There are " . $num_rows . " result(s)");
    echo("<br>");
    if($num_rows > 0){
        while($page = mysqli_fetch_assoc($result)){
            $html = "<div class='article_container'>";
            $html .= "<h1><a href=\"blog-post.php?pageId={$page['pageId']}\">{$page['title']}</h1></a>";
            $html .= "<p>{$page['description']}</p>";
            //$html .= "<p>{$page['content']}</p>";
            $html .= "</div>";

            echo $html;
        }
    }else{
         echo("No results found");
     }
}

?>
</div>
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");
?>