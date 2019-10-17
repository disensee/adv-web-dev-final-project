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

?>