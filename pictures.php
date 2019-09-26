<?php
require_once("includes/config.inc.php");
$pageTitle = "Pictures";
$pageDescription = "Photo gallery";
require("includes/header.inc.php");
?>
<script src="<?php echo(PROJECT_DIR); ?>js/photo-gallery.js"></script>
		<main>

			<div class="content-frame">
				
				<h1>Pictures</h1>
				<div id="image-gallery">
    				<img id="mainImg" src="" />
  				</div>
  				<br>
  				<input type="button" id="btnPrev" value="Prev" />
  				&nbsp;
  				<input type="button" id="btnNext" value="Next" />
			
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

