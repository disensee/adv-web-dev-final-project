<?php
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");
$pageTitle = "Search Results";
$pageDescription = "";
$sideBar = "hobbies-sidebar.inc.php";
require("../includes/header.inc.php");

$pda = new PageDataAccess(getDBLink());
?>

<h3>Search Results</h3>

<div class="search-result-container">
<?php


if(isset($_POST['btn_search'])){
    $search = mysqli_real_escape_string($pda->getLink(), $_POST['txt_search']);
    $qStr = "SELECT pageId, title, description, content, publishedDate
            FROM pages
            WHERE MATCH (title, description, content)
            AGAINST ('$search')
            AND active = 'yes'
            ORDER BY publishedDate DESC";
    
    $result = mysqli_query($pda->getLink(), $qStr); 
    $num_rows = mysqli_num_rows($result);

    echo("There are " . $num_rows . " result(s)");
    echo("<br> <br>");
    if($num_rows > 0){
        while($page = mysqli_fetch_assoc($result)){
            $html = "<div class='article-container'>";
            $html .= "<h4><a href=\"blog-post.php?pageId={$page['pageId']}\">{$page['title']}</h4></a>";
            $html .= "<p>{$page['description']}</p>";
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
require("../includes/footer.inc.php");
?>