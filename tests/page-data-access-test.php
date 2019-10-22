<?php
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");

$pda = new PageDataAccess(getDBLink());

// echo("ACTIVE PAGES");
// $activePages = $pda->getPageList();
// var_dump($activePages);

echo("ALL PAGES");
$allPages = $pda->getPageList(false);
var_dump($allPages);

echo("SINGLE PAGE");
$page = $pda->getPageById(1);
var_dump($page);

echo("SINGLE PAGE W/ERROR");
try{
    $page = $pda->getPageById("blah");
    var_dump($page);
}catch(Exception $e){
    echo(" Yes! The handleError() function worked and threw an exception!");
}
?>