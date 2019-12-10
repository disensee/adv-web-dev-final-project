<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/CategoryDataAccess.inc.php");

$pageTitle = "Category Details";
$pageDescription = "";

//Set defaults
$category = array();
$category['categoryId'] = "";
$category['name'] = "";
$category['active'] = "no";


// Set up the $pda object 
$cda = new CategoryDataAccess(getDBLink());

// Create an empty array to store input validation errors
// (we'll use this array when we get to the validation code)
$validationErrors = array();

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if(isset($_GET['categoryId'])){
		$category = $cda->getCategoryById($_GET['categoryId']);
	}

}elseif($_SERVER['REQUEST_METHOD'] == "POST"){
	// Get the user input and stuff it into the category array
	$category['categoryId'] = $_POST['categoryId'];
	$category['name'] = $_POST['name']; 
	$category['active'] = $_POST['active']; 

	// Validate the input and get validation errors (if there are any)
	$validationErrors = validateCategoryInput($category);

	if(empty($validationErrors)){
	
		// Insert or Update (depends on $category['categoryId'])
		if($category['categoryId'] > 0){
			// UPDATE
			$cda->updateCategory($category);
		}else{
			// INSERT
			$cda->insertCategory($category);
		}
	
		header("Location: " . PROJECT_DIR . "control-panel/category-list.php");
		exit();
	}
}else{
	// we only accept GET and POST requests
	header("Location: " . PROJECT_DIR . "error.php");
	exit();
}

require("../includes/header.inc.php");
?>

<main>
	<div class="content-frame">
		<h3>Category Details</h3>
		
		<form class="control-panel" method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
	
			<input type="hidden" name="categoryId" value="<?php echo($category['categoryId']); ?>" />
						
			<label>
				Name
				<?php echo(isset($validationErrors['name']) ? wrapValidationMsg($validationErrors['name']) : ""); ?>
			</label>
			<input type="text" name="name" value="<?php echo($category['name']); ?>" />

			<label>
				Active
				<?php echo(isset($validationErrors['active']) ? wrapValidationMsg($validationErrors['active']) : ""); ?>
			</label>
			<input type="radio" name="active" value="yes" <?php echo($category['active'] == "yes" ? "checked" : "") ?> /> YES	
			<input type="radio" name="active" value="no" <?php echo($category['active'] == "no" ? "checked" : "") ?> /> NO
			
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


function validateCategoryInput($category){

	$errors = array();

	// validate name
	if(empty($category['name'])){
		$errors['name'] = "You must enter a category name";
	}

	// validate active
	if($category['active'] != "yes" && $category['active'] != "no"){
		$errors['active'] = "Active must be 'yes' or 'no'.";
	}

	return $errors;
}
?>
