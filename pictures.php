<?php
require_once("includes/config.inc.php");
$pageTitle = "Pictures | dylanisensee.com";
$pageDescription = "Photo gallery";
$sideBar = "hobbies-sidebar.inc.php";
require("includes/header.inc.php");
?>
<script src="<?php echo(PROJECT_DIR); ?>js/photo-gallery.js"></script> 
		<main class="pictures-main">

			<div class="content-frame">
				
				<h3 id="pictures-h3">Image Gallery</h3>
				<div id="image-gallery">
    				<img id="mainImg" src="" />
  				</div>
				<br>
				<div id="btn-holder">  
					<input type="button" id="btnPrev" value=" < " />
					&nbsp;
					<input type="button" id="btnNext" value=" > " />
				</div>
			</div>
			
		</main>
<?php
require("includes/footer.inc.php")
?>

