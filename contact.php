<?php
$pageTitle = "Contact Me";
$pageDescription = "Contact me. I'll get back to you ASAP.";
require("includes/header.inc.php")
?>
<script src="js/contact-form.js"></script>
		<main>

			<div class="content-frame">
				
				<h1>Contact Me</h1>
				
				<form id="contactForm" method="POST" action="">
				    <table border="1">
				        <tr>
				            <td align="right" valign="bottom">First Name:</td>
				            <td>
				                <div class="validation-message" id="vFirstName"></div>
				                <input type="text" id="txtFirstName" name="txtFirstName">
				            </td>
				        </tr>
				        <tr>
				            <td align="right" valign="bottom">Last Name:</td>
				            <td>
				                <div class="validation-message" id="vLastName"></div>
				                <input type="text" id="txtLastName" name="txtLastName">
				            </td>
				        </tr>
				        <tr>
				            <td align="right" valign="bottom">Email:</td>
				            <td>
				                <div class="validation-message" id="vEmail"></div>
				                <input type="text" id="txtEmail" name="txtEmail">
				            </td>
				        </tr>
				        <tr>
				            <td align="right" valign="top">Comments:</td>
				            <td>
				                <div class="validation-message" id="vComments"></div>
				                <textarea id="txtComments" name="txtComments"></textarea>
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
		<aside>
			<div class="content-frame">
				<h3>My Hobbies</h3>
				<ol>
					<li>Music</li>
					<li>Guitar</li>
					<li>Coding</li>
				</ol>
				<div class="img-container">
					<img src="images/Lighthouse.jpg" alt="A lighthouse">
				</div>
			</div>
		</aside>
<?php
require("includes/footer.inc.php")
?>


