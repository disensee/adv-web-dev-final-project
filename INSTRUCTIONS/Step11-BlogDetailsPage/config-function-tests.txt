<?php
require_once("../includes/config.inc.php");

// some test data for us to us
$goodDate1 = "02/05/2018";
$goodDate2 = "2/5/2018";


$badDate1 = "blah";
$badDate2 = "2/5/blah";
$badDate3 = "2.7/5/2018";

$goodDate1ConvertedForMySQL = "2018-02-05"; // this is what we expect to get when we convert $goodDate1 into MySQL format

echo("<h3>Test validateDate()</h3>");

if(validateDate($goodDate1)){
	echo("PASS: Validated $goodDate1 correctly");
}else{
	echo("FAIL: Did NOT validate $goodDate1 correctly!");
}

echo("<br>");

if(validateDate($goodDate2)){
	echo("PASS: Validated $goodDate2 correctly");
}else{
	echo("FAIL: Did NOT validate $goodDate2 correctly!");
}

echo("<br>");

if(validateDate($badDate1) == FALSE){
	echo("PASS: Validated $badDate1 correctly");
}else{
	echo("FAIL: Did NOT validate $badDate1 correctly!");
}

echo("<br>");

if(validateDate($badDate2) == FALSE){
	echo("PASS: Validated $badDate2 correctly");
}else{
	echo("FAIL: Did NOT validate $badDate2 correctly!");
}

echo("<br>");

if(validateDate($badDate3) == FALSE){
	echo("PASS: Validated $badDate3 correctly");
}else{
	echo("FAIL: Did NOT validate $badDate3 correctly!");
}

echo("<h3>Test convertDateForMySQL()</h3>");
if(convertDateForMySQL($goodDate1) == $goodDate1ConvertedForMySQL){
	echo("PASS: Converted $goodDate1 successfully");
}else{
	echo("FAIL: Did NOT convert $goodDate1 successfully");
}

echo("<br>");

$testResult = NULL;
// We expect convertDateForMySQL() to throw an exception if it can't convert a string to MySQL format
try{
	convertDateForMySQL($badDate1);
	$testResult = "FAIL: convertDateForMySQL() did not throw and exception";
}catch(Exception $e){
	$testResult = "PASS: convertDateForMySQL() correctly threw and exception";
}

echo($testResult);

?>