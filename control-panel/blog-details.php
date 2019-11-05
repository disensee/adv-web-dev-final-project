<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/PageDataAccess.inc.php");
require_once("../includes/CategoryDataAccess.inc.php");

$pageTitle = "Blog Details";
$pageDescription = "";


if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if(isset($_GET['pageId'])){
		echo("ABOUT TO EDIT PAGE: " . $_GET['pageId'] . " (UPDATE)");
	}else{
		echo("ABOUT TO CREATE NEW BLOG PAGE (INSERT)");
	}

}elseif($_SERVER['REQUEST_METHOD'] == "POST"){
	echo("The form has been submitted, we need to validate the input, then check the pageId to see if we should insert or update");
}else{
	// we only accept GET and POST requests
	header("Location: " . PROJECT_DIR . "error.php");
	exit();
}

require("../includes/header.inc.php");
?>
<main>
	<div class="content-frame">
		<h3>Blog Details</h3>
		
		<form class="control-panel" method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
			<input type="hidden" name="pageId" />
			<!--
			<label>Path</label>
			<input type="text" name="path" />
			-->
			<label>Title</label>
			<input type="text" name="title" />
			<label>Description</label>
			<textarea name="description"></textarea>
			<label>Content</label>
			<textarea name="content"></textarea>
			<label>Published Date (mm/dd/yyyy)</label>
			<input name="publishedDate" />
			<label>Category</label>
			<select name="categoryId">
			<?php
				//fetch the categories from the db
				$cda = new CategoryDataAccess(getDBLink());
				$categories = $cda->getCategoryList(false);

				//create the option tags
				echo(createCategoryOptions($categories));
			?>
			</select>
			<label>Active</label>
			<input type="radio" name="active" value="yes"> YES	
			<input type="radio" name="active" value="no"> NO
			<br>	
			<input type="submit" value="SAVE" />	
		</form>
		
	</div>
</main>
		
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");

function createCategoryOptions($categories, $selectedCategoryId = null){

	$html = "<option value=\"0\">Choose one...</option>";

	foreach($categories as $row){
		$selectedAttr = ($row['categoryId'] == $selectedCategoryId ? " selected " : "");
		$html .= "<option value=\"{$row['categoryId']}\" $selectedAttr>{$row['name']}</option>";
	}

	return $html;
}
?>
