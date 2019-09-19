<?php
$pageTitle = "Welcome to my site";
$pageDescription = "Welcome to my webiste, it's about me, my hobbies, and web development";
require("includes/header.inc.php")
?>
		<main>

			<div class="content-frame">
				<h1>About Me</h1>
				<div class="img-container">
					<img src="images/Desert.jpg" alt="A desert!">
				</div>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
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


