<?php
require_once("../includes/config.inc.php");
require_once("../includes/CategoryDataAccess.inc.php");


$cda = new CategoryDataAccess(getDBLink());

echo("ACTIVE CATEGORIES");
$activeCategories = $cda->getCategoryList();
var_dump($activeCategories);

echo("ALL CATEGORIES");
$allCategories = $cda->getCategoryList(false);
var_dump($allCategories);

echo("ERROR TEST");

try{
	$cats = $cda->getCategoryList("blah");
	echo("TEST FAILED (maybe): should have thrown an error!??? Maybe, I'm not sure how we want this code to behave");
}catch(Exception $e){
	echo("Yes! the handleError() function worked and threw an exception!");
}

?>