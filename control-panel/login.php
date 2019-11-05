<?php
require_once("../includes/config.inc.php");

$pageTitle = "Login";
$pageDescription = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

	$userNameEntered = $_POST['txtUserName'] ?? NULL;
	$passwordEntered = $_POST['txtPassword'] ?? NULL;

	$userName = "disensee";
	$password = "PassworD9109!";

	if($userNameEntered == $userName && $passwordEntered == $password){
        
        session_regenerate_id(true);
        $_SESSION['authenticated'] = "yes";

		header("Location: index.php");
		exit();
	}

}

require("../includes/header.inc.php");
?>
<main>
	<div class="content-frame">
		<h3>Login</h3>
		<form method="POST" action="">
			<label for="txtUserName">User Name</label>
			<br>
			<input type="text" name="txtUserName" id="txtUserName" />
			<br>
			<label for="txtPassword">Password</label>
			<br>
			<input type="password" name="txtPassword" id="txtPassword" />
			<br>
			<input type="submit" value="Log In">
		</form>
	</div>
</main>
		
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");
?>