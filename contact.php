<?php
require_once("includes/config.inc.php");
$pageTitle = "Contact | dylanisensee.com";
$pageDescription = "Contact me. I'll get back to you ASAP.";
$sideBar = "hobbies-sidebar.inc.php";
require("includes/header.inc.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
	// Get the data entered by the user
	$firstName = isset($_POST['txtFirstName']) ? $_POST['txtFirstName'] : NULL;	
	$lastName = isset($_POST['txtLastName']) ? $_POST['txtLastName'] : NULL;	
	$email = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : NULL;	
	$comments = isset($_POST['txtComments']) ? $_POST['txtComments'] : NULL;

	if(validateContactData($firstName, $lastName, $email, $comments)){

		$scrubbed = array_map('prevent_spam', $_POST);
		
		if(!empty($scrubbed['txtFirstName']) && !empty($scrubbed['txtLastName']) && !empty($scrubbed['txtEmail']) && !empty($scrubbed['txtComments'])){

			$msg = "First Name: {$scrubbed['txtFirstName']}\nLast Name: {$scrubbed['txtLastName']}\nEmail: {$scrubbed['txtEmail']}\nComments: {$scrubbed['txtComments']}";
			$msg = wordwrap($body, 70);
		
			sendEmail(ADMIN_EMAIL, "Contact Form", $msg , "From: " . $email); // NOTE: we can uncomment the third param after we update the sendEmail() function!!!
			header("Location: " . PROJECT_DIR . "contact-confirmation.php");
			$scrubbed = [];
			exit();
		}else{
			$spam_error = '<p style = "font-weight: bold; color: red">Invalid contact form data</p>';
		}	
	}else{

		// Foul play suspected (the client-side validation has been bypassed)!
		$msg = getAllSuperGlobals(); 
		sendEmail(ADMIN_EMAIL, "Security Warning!", $msg);
		header("Location: " . PROJECT_DIR . "error.php");
		exit();	
	}

}
?>
<script src="<?php echo(PROJECT_DIR); ?>js/contact-form.js"></script>
		<main>

			<div class="content-frame">
				
				<h3>Contact Me</h3>
				<?php echo($spam_error) ?? "";?>
				<form id="contactForm" method="POST" action="">
				    <table border="1">
				        <tr>
				            <td align="right" valign="bottom">First Name:</td>
				            <td>
				                <div class="validation-message" id="vFirstName"></div>
				                <input type="text" id="txtFirstName" name="txtFirstName" value="<?php echo($scrubbed['txtFirstName']) ?? "";?>">
				            </td>
				        </tr>
				        <tr>
				            <td align="right" valign="bottom">Last Name:</td>
				            <td>
				                <div class="validation-message" id="vLastName"></div>
				                <input type="text" id="txtLastName" name="txtLastName" value="<?php echo($scrubbed['txtLastName']) ?? "";?>">
				            </td>
				        </tr>
				        <tr>
				            <td align="right" valign="bottom">Email:</td>
				            <td>
				                <div class="validation-message" id="vEmail"></div>
				                <input type="text" id="txtEmail" name="txtEmail" value="<?php echo($scrubbed['txtEmail']) ?? "";?>">
				            </td>
				        </tr>
				        <tr>
				            <td align="right" valign="top">Comments:</td>
				            <td>
				                <div class="validation-message" id="vComments"></div>
				                <textarea id="txtComments" name="txtComments"><?php echo($scrubbed['txtComments']) ?? "";?></textarea>
				            </td>
				        </tr>
				        <tr>
				            <td align="right">&nbsp;</td>
				            <td>
				                <input type="submit" value="SUBMIT">
				            </td>
				        </tr>
				    </table>
				</form>

			</div>
			
		</main>
<?php
if(!empty($sideBar)){
	require("includes/" . $sideBar);
}

require("includes/footer.inc.php");

////////////////////////////
//Functions for this page//
//////////////////////////

function validateContactData($firstName, $lastName, $email, $comments){
	if(empty($firstName) || empty($lastName) || empty($comments) || empty($email)){
		return false;
	}

	if(filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE){
		return false;
	}

	return true;
}

function prevent_spam($value){
	$very_bad = ['to:', 'cc:', 'bcc:', 'content-type:', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:'];

	foreach($very_bad as $v){
		if(stripos($value, $v) !== false){
			return '';
		}
	}

	$value = str_replace(["\r", "\n", "%0a", "%0d"], ' ', $value);

	return trim($value);
}
?>


