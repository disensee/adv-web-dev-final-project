<?php
require_once("includes/config.inc.php");
$pageTitle = "Home | dylanisensee.com";
$pageDescription = "Welcome to my webiste, it's about me, my hobbies, and web development";
$sideBar = "hobbies-sidebar.inc.php";
require("includes/header.inc.php");
?>
		<main>

			<div class="content-frame">
				<div class="img-container">
					<img src="<?php echo(IMAGES_DIR); ?>motorcycle_home.jpg" alt="KZ400D Cafe Racer">
				</div>
				<h3 class="about-h3">About Me</h3>
				<p>
					Welcome to my website! The content you'll find here is a collection of things I find interesting. 
					Check out the blog section to find posts related to my hobbies and anything else I may decide to share.
					<br>
					<br>
					I am currently enrolled in the Web and Software Development program at Western Technical College in La Crosse, WI, and I also have an AAS in 
					Biomedical Equipment Technology from Southeastern Technical College in Winona, MN. I currently work as a Lead Technician at Gaming Generations in 
					Onalaska, WI. I manage a team of five technicians, and we repair mobile phones, tablets, computers, and game consoles. 
					I am a firm believer in the right to repair movement, and I think everyone should have access to the resources required to repair and reuse the 
					products they own. 
					<br>
					<br>
					Aside from the hobbies I have listed to the right, I enjoy spending time with family and friends and working with hardware. 
					I am a massive music nerd, and love to indulge in several television series. Please feel free to get in touch me with me using 
					the <a href="contact.php">contact</a> page on this site.
				</p>
			</div>
			
		</main>
<?php
if(!empty($sideBar)){
	require("includes/" . $sideBar);
}

require("includes/footer.inc.php")
?>