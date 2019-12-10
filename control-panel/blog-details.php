<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/PageDataAccess.inc.php");
require_once("../includes/CategoryDataAccess.inc.php");

$pageTitle = "Blog Details";
$pageDescription = "";

//Set defaults
$page = array();
$page['pageId'] = "";
$page['path'] = "";
$page['title'] = "";
$page['description'] = "";
$page['content'] = "";
$page['categoryId'] = 0;
$page['publishedDate'] = date("m/d/Y", time());
$page['active'] = "no"; // you may want this to default to 'yes'

// Set up the $pda object 
$pda = new PageDataAccess(getDBLink());

// Create an empty array to store input validation errors
// (we'll use this array when we get to the validation code)
$validationErrors = array();

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if(isset($_GET['pageId'])){
		//echo("ABOUT TO EDIT PAGE: " . $_GET['pageId'] . " (UPDATE)");
		$page = $pda->getPageById($_GET['pageId']);
	}

}elseif($_SERVER['REQUEST_METHOD'] == "POST"){
	//echo("The form has been submitted, we need to validate the input, then check the pageId to see if we should insert or update");

	// Get the user input and stuff it into the $page array
	$page['pageId'] = $_POST['pageId'];
	//$page['path'] = $_POST['path'];
	$page['title'] = $_POST['title']; 
	$page['description'] = $_POST['description']; 
	$page['content'] = $_POST['content']; 
	$page['publishedDate'] = $_POST['publishedDate']; 
	$page['categoryId'] = $_POST['categoryId'];
	$page['active'] = $_POST['active'] ?? $page['active'];

	// Validate the input and get validation errors (if there are any)
	$validationErrors = validatePageInput($page);

	if(empty($validationErrors)){

		// we need to convert the published date to Y-m-d format (this code smells to me!)
		$page['publishedDate'] = convertDateForMySQL($page['publishedDate']);
		
		// Insert or Update (depends on $page['pageId'])
		if($page['pageId'] > 0){
			// UPDATE
			$pda->updatePage($page);
		}else{
			// INSERT
			$pda->insertPage($page);
		}
	
		header("Location: " . PROJECT_DIR . "control-panel/blog-list.php");
		exit();
	}
	// else{
	// 	var_dump($validationErrors); 
	// 	// we'll comment the above line out soon, but for now it shows us validation error messages
	// }
}else{
	// we only accept GET and POST requests
	header("Location: " . PROJECT_DIR . "error.php");
	exit();
}

require("../includes/header.inc.php");
?>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>
window.addEventListener("load", function(){

	// STEP 1
	var imageFolderPath = "<?php echo(UPLOAD_FOLDER); ?>";
	var cssPath = "<?php echo(PROJECT_DIR . ""); ?>styles/main.css";
	var popup;
  	
  	// set up the tinymce editor
  	tinymce.init({ 
  		selector:'textarea[name="content"]', 
  		height: 200,
	  	menubar: false,
	 	plugins: [
		    'advlist autolink lists link image charmap print preview anchor textcolor',
		    'searchreplace visualblocks code fullscreen',
		    'insertdatetime media table contextmenu paste code help wordcount'
	  	],
	  	toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
	  	content_css: [cssPath] 
  	});
  	

  	// STEP 2
	// when the 'Insert Image' button is pressed, create a popup window
	document.getElementById("btnInsertImg").addEventListener("click", function(){
		createWindow(400,300);
	});

  	// STEP 3
		// create a pop up window that displays the image-list page
  	// (and handles clicks on the A tags in the image list page)
	  function createWindow(width, height) {

			// Add some pixels to the width and height:
			width = width + 10;
			height = height + 10;

			// If the window is already open,
			// resize it to the new dimensions:
			if (window.popup && !window.popup.closed) {
				window.popup.resizeTo(width, height);
			}

			// Set the window properties:
			var specs = "location=no,scrollbars=no,menubar=no,toolbar=no,resizable=yes,left=0,top=0,width=" + width + ",height=" + height;

			// Set the URL:
			var url = "image-list.php"; // this will be changed to a .php file

			// Create the pop-up window:
			popup = window.open(url, "ImageWindow", specs);
			popup.focus();

			// use event delegation to listen for clicks in the popup window
			popup.addEventListener("click", function(evt){

				if(evt.target.classList.contains("insertImg")){
					// if the target of the click event was one of our A tags,
					// then extract the data from our custom attributes
					var aTag = evt.target;
					var imgName = aTag.dataset.filename;
					var imgDesc = aTag.dataset.filedescription;
					
					insertImg(imgName, imgDesc);

					popup.close();
					
				}

			});
		}

  	// STEP 4
  	// insert an image tag into the tinymce editor
	function insertImg(imgName, imgDesc){
		
		var imgHtml = `<img src="${imageFolderPath}${imgName}" alt="${imgDesc}" />`;
		tinymce.execCommand('mceInsertContent', false, imgHtml);
	}
});
</script>
<main>
	<div class="content-frame">
		<h3>Blog Details</h3>
		
		<form class="control-panel" method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
	
			<input type="hidden" name="pageId" value="<?php echo($page['pageId']); ?>" />
						
			<label>
				Title
				<?php echo(isset($validationErrors['title']) ? wrapValidationMsg($validationErrors['title']) : ""); ?>
			</label>
			<input type="text" name="title" value="<?php echo($page['title']); ?>" />
			
			<label>
				Description
				<?php echo(isset($validationErrors['description']) ? wrapValidationMsg($validationErrors['description']) : ""); ?>
			</label>
			<textarea name="description"><?php echo($page['description']); ?></textarea>
			
			<label>
				Content
				<?php echo(isset($validationErrors['content']) ? wrapValidationMsg($validationErrors['content']) : ""); ?>
			</label>
			<textarea name="content"><?php echo($page['content']); ?></textarea>
			<br>
			<input type="button" value="Insert Image" id="btnInsertImg" />
			
			<label>
				Published Date (mm/dd/yyyy)
				<?php echo(isset($validationErrors['publishedDate']) ? wrapValidationMsg($validationErrors['publishedDate']) : ""); ?>
			</label>
			<input name="publishedDate" value="<?php echo($page['publishedDate']); ?>" />
			
			<label>
				Category
				<?php echo(isset($validationErrors['categoryId']) ? wrapValidationMsg($validationErrors['categoryId']) : ""); ?>
			</label>
			<select name="categoryId">
			<?php
				// fetch the categories from the db
				$cda = new CategoryDataAccess(getDBLink());
				$categories = $cda->getCategoryList(false);
				
				// create the option tags
				echo(createCategoryOptions($categories, $page['categoryId']));
			?>	
			</select>

			<label>
				Active
				<?php echo(isset($validationErrors['active']) ? wrapValidationMsg($validationErrors['active']) : ""); ?>
			</label>
			<input type="radio" name="active" value="yes" <?php echo($page['active'] == "yes" ? "checked" : "") ?> /> YES	
			<input type="radio" name="active" value="no" <?php echo($page['active'] == "no" ? "checked" : "") ?> /> NO
			
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

function validatePageInput($page){

	// we'll populate this array with any errors that we discover.
	$errors = array();

	// validate title
	if(empty($page['title'])){
		$errors['title'] = "You must enter a title";
	}

	// validate description
	if(empty($page['description'])){
		$errors['description'] = "You must enter a description";
	}

	// validate content
	if(empty($page['content'])){
		$errors['content'] = "You must enter content";
	}

	// validate published date
	if(empty($page['publishedDate'])){
		$errors['publishedDate'] = "You must enter a published date";
	}else if(validateDate($page['publishedDate']) == FALSE){
		$errors['publishedDate'] = "The publish date entered is not valid";
	}

	// validate category
	if($page['categoryId'] > 0 == FALSE){
		$errors['categoryId'] = "You must choose a category";
	}

	// validate active
	if($page['active'] != "yes" && $page['active'] != "no"){
		// foul play suspected!
		$errors['active'] = "Active must be 'yes' or 'no'.";
	}

	return $errors;
}
?>
