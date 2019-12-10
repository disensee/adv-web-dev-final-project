<?php

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);
	exit();
}

session_start();
set_error_handler("customErrorHandler");

//detect if the code is running on localhost or the live server
if($_SERVER['SERVER_NAME'] == "localhost"){
    //settings for the dev environment
    define("PROJECT_DIR", "/my-new-site/");
    define("IMAGES_DIR", PROJECT_DIR . "images/");
    define("DEBUG_MODE", true);
	define("ADMIN_EMAIL", "isenseed@students.westerntc.edu");
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB_NAME", "my-new-site");
	define("UPLOAD_FOLDER", PROJECT_DIR . "uploaded-files/");
	define("SERVER_UPLOAD_FOLDER", $_SERVER['DOCUMENT_ROOT'] . UPLOAD_FOLDER);
	define("THUMBNAIL_FOLDER", UPLOAD_FOLDER . "thumbnails/");
	define("SERVER_THUMBNAIL_FOLDER", SERVER_UPLOAD_FOLDER . "thumbnails/");
}else{
    //settings for live site
    define("PROJECT_DIR", "/");
    define("IMAGES_DIR", PROJECT_DIR . "images/");
    define("DEBUG_MODE", false);
	define("ADMIN_EMAIL", "dylan@dylanisensee.com");
	define("DB_HOST", "localhost");
	define("DB_USER", "dylanise_dylan");
	define("DB_PASSWORD", "lI5w~#U[Le*)");
	define("DB_NAME", "dylanise_my-new-site");
	define("UPLOAD_FOLDER", PROJECT_DIR . "uploaded-files/");
	define("SERVER_UPLOAD_FOLDER", $_SERVER['DOCUMENT_ROOT'] . UPLOAD_FOLDER);
	define("THUMBNAIL_FOLDER", UPLOAD_FOLDER . "thumbnails/");
	define("SERVER_THUMBNAIL_FOLDER", SERVER_UPLOAD_FOLDER . "thumbnails/");
}
//turn errors on if debug mode is true (code running on localhost)
if(DEBUG_MODE){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

/////////////////////////////////////////////////////////////////
//GLOBAL FUNCTIONS(can be called from any page)/////////////////
///////////////////////////////////////////////////////////////

// A 'wrapper' function for the mail() function 
function sendEmail($to, $subject, $msg, $headers=""){

	// In the future we can put code here to customize
	// the look of the emails that are generated by our site

	// NOTE: I added this to be able to send emails as HTML
	rtrim($headers);
	$defaultHeaders = 'To: Website Admin <'. $to .'>' . "\r\n";
  	$defaultHeaders .= 'MIME-Version: 1.0'  . "\r\n";
  	$defaultHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  	$headers = $defaultHeaders . $headers . "\r\n";

	if(!DEBUG_MODE){
		if(mail($to, $subject, $msg, $headers)){
			return true;
		}else{
			return false;
		}
	}
	return true;
}
// Instead of using the defualt PHP error handler function that gets called
// when and error occurs, we want this function to be called
function customErrorHandler($errno, $errstr, $errfile, $errline){

	$errorMsg = "<br><b>THIS IS OUR CUSTOM ERROR HANDLER</b>";
	$errorMsg .= "<br>ERROR NUMBER: " . $errno;
	$errorMsg .= "<br>ERROR MSG: " . $errstr;
	$errorMsg .= "<br>FILE: " . $errfile;
	$errorMsg .= "<br>LINE NUMBER: " . $errline;
	// In the future we may want to include many more details in our 
	// custom error message

	if(DEBUG_MODE){
		// in debug mode we display all details of the error in the browser
		echo($errorMsg);
	}else{
		// when not debugging (on the live site) we don't want users to see ugly error messages
		// so instead, we get an email with the gory details and redirect users to our friendly error page.
		sendEmail(ADMIN_EMAIL, "WEBSITE ERROR!", $errorMsg . getAllSuperGlobals());
		header("Location: " . PROJECT_DIR . "error.php");
		exit();
	}
}

function getAllSuperGlobals(){

	$str = "<br>POST VARS:<br>" . print_r($_POST, true);
	$str .= "<br>GET VARS:<br>" .  print_r($_GET, true);
	$str .= "<br>SERVER VARS:<br>" .   print_r($_SERVER, true);
	$str .= "<br>FILE VARS:<br>" .  print_r($_FILES, true);
	$str .= "<br>COOKIE VARS:<br>" .  print_r($_COOKIE, true);
	$str .= "<br>REQUEST VARS:<br>" .  print_r($_REQUEST, true);
	$str .= "<br>ENV VARS:<br>" .  print_r($_ENV, true);

	// Only include the SESSION super global if the site is using sessions
	if(isset($_SESSION)){
		$str .= "<br>SESSION VARS:<br>" .  print_r($_SESSION, true);
	}

	return $str;
}
//this is our connection object for connecting to the database
$link = null;

//creates the connection/link object (if it has not already been created)
function getDBLink(){

	global $link;

	if($link == null){

		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if(!$link){
			throw new Exception(mysqli_connect_error());
		}
	}

	return $link;
}

//redirect to 404 page
function redirectTo404Page(){
	header("HTTP/1.0 404 Not Found");
	header("Location: " . PROJECT_DIR . "404.php");
}

// Removes 'dangerous' html tags and attributes from a string of html.
function sanitizeHtml($inputHTML){
       
    // we'll allow these tags, but no others (we are white-listing)
    $allowed_tags = array('<sub>','<sup>','<div>','<span>','<h1>','<h2>','<br>','<h3>','<h4>','<h5>','<h6>','<h7>','<i>','<b>','<a>','<ul>','<ol>','<em>','<li>','<pre>','<hr>','<blockquote>','<p>','<img>','<strong>','<code>');

    //replace dangerous attributes...
    $bad_attributes = array('onerror','onmousemove','onmouseout','onmouseover','onkeypress','onkeydown','onkeyup','onclick','onchange','onload','javascript:');
    $inputHTML = str_replace($bad_attributes,"x",$inputHTML);
   
    $allowed_tags = implode('',$allowed_tags);
    $inputHTML = strip_tags($inputHTML, $allowed_tags);

    return $inputHTML;
}

function validateDate($dateStr){
	$dateParts = explode('/', $dateStr);

	if(count($dateParts) != 3){ //prevents "blah" from passing validation
		return false;
	}

	if(strpos($dateStr, ".") !== FALSE){ //prevents "2.7/5/2018" from passing validation
		return false;
	}

	if(intval($dateParts[0]) > 0 && intval($dateParts[1]) > 0 && intval($dateParts[2]) > 0){
		//prevents "2/5/blah" from passing validation
		//for info on checkdate() go to http://php.net/manual/en/function/checkdate.php
		return checkdate($dateParts[0], $dateParts[1], $dateParts[2]);
	}else{
		return false;
	}
	return true;
}

function convertDateForMySQL($dateStr){
	$dt = new DateTime($dateStr);
	return $dt->format('Y-m-d');
}

function wrapValidationMsg($str){
	return "<span class=\"validation-message\">${str}</span>";
}