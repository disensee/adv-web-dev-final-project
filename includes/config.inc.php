<?php

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
}else{
    //settings for live site
    define("PROJECT_DIR", "/my-new-site/");
    define("IMAGES_DIR", PROJECT_DIR . "images/");
    define("DEBUG_MODE", true);
	define("ADMIN_EMAIL", "isenseed@students.westerntc.edu");
	define("DB_HOST", "xxxxxx");
	define("DB_USER", "xxxxxx");
	define("DB_PASSWORD", "xxxxxx");
	define("DB_NAME", "my-new-site");
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