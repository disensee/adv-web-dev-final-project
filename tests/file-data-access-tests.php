<?php
require_once("../includes/config.inc.php");
require_once("../includes/FileDataAccess.inc.php");

$fda = new FileDataAccess(getDBLink());

echo("<h3>TEST insertFile()</h3>");
$file = array();
$file['fileName'] = "test.png";
$file['fileDescription'] = "test description";
$file['fileExtension'] = "png";
$file['fileSize'] = "1000";

$file = $fda->insertFile($file);
var_dump($file);

//die();// REMOVE THIS AFTER YOU CONFIRM THAT THE PREVIOUS TEST PASSES 



echo("<h3>TEST getFileList()</h3>");
$fileList = $fda->getFileList();
var_dump($fileList);

//die();// REMOVE THIS AFTER YOU CONFIRM THAT THE PREVIOUS TEST PASSES



echo("<h3>TEST getFileById()</h3>");
$file = $fda->getFileById($file['fileId']);
var_dump($file);

//die();// REMOVE THIS AFTER YOU CONFIRM THAT THE PREVIOUS TEST PASSES



echo("<h3>TEST updateFile()</h3>");
$file['fileName'] = "Updated File Name";
$file = $fda->updateFile($file);
var_dump($file);