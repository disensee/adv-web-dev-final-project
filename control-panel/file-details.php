<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/FileDataAccess.inc.php");

$pageTitle = "Blog Details";
$pageDescription = "";

//Set defaults
$file = array();
$file['fileId'] = 0;
$file['fileName'] = "";
$file['fileDescription'] = "";
$file['fileExtension'] = "";
$file['fileSize'] = 0;

// Set up the $fda object 
$fda = new FileDataAccess(getDBLink());

// Create an empty array to store input validation errors
// (we'll use this array when we get to the validation code)
$validationErrors = array();
// we'll allow these types of files to be uploaded
$allowed_file_types = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png');
// the images must be less than 1MB (1000000 bytes)
$max_file_size = 1000000;

if($_SERVER['REQUEST_METHOD'] == "GET"){
	
	if(isset($_GET['fileId'])){
		$file = $fda->getFileById($_GET['fileId']); 
	}

}elseif($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get the user input and stuff it into the $page array
	$file['fileId'] = $_POST['fileId']; // hidden input
	$file['fileName'] = $_POST['fileName']; // hidden input
	$file['fileSize'] = $_POST['fileSize']; // hidden input
	$file['fileExtension'] = $_POST['fileExtension']; // hidden input
	$file['fileDescription'] = $_POST['fileDescription']; 
	
	// Validate the input and get validation errors (if there are any)
	$validationErrors = validateFileInput($file, $allowed_file_types, $max_file_size);

	if(empty($validationErrors)){

		// STEP 1 - process the file upload
		if($_FILES['upload']['error'] == UPLOAD_ERR_OK){
	
            // get details of the uploaded file and store them in the $file array
            $file['fileName'] = $_FILES['upload']['name'];
            $file['fileSize'] = $_FILES['upload']['size']; 
            $file['fileExtension'] = $fda->getFileExtension($file['fileName']);
        
            // set the full path of where the uploaded file should be moved to
            $filePath = SERVER_UPLOAD_FOLDER . $file['fileName'];
        
              // move the file from the tmp dir to it's final destination
              if(!move_uploaded_file($_FILES['upload']['tmp_name'], $filePath) ){
                  throw new Exception("Unable to move uploaded file");
              }
        }

		// Insert or Update (depends on $file['fileId'])
		if($file['fileId'] > 0){
			// UPDATE
			$file = $fda->updateFile($file);
		}else{
			// INSERT
			$file = $fda->insertFile($file);
		}

		// STEP 2 - WHAT HAPPENS IF YOU UPLOAD FILES WITH THE SAME NAMES? 
	    // Now that the image has been uploaded, let's rename it so that it
        // is named by the file id, rather than the original name
        $newFileName = $file['fileId'] . "." . $file['fileExtension'];
        $newFilePath = SERVER_UPLOAD_FOLDER . $newFileName;

        if( !rename($filePath, $newFilePath) ){
            throw new Exception("Unable too rename file");
        }
                    
                header("Location: " . PROJECT_DIR . "control-panel/file-list.php");
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
		<h3>File Details</h3>
		
		<!--DON'T FORGET TO SET THE enctype ATTRIBUTE ON THE FORM TAG!!!!-->
		<form class="control-panel" method="POST" enctype="multipart/form-data" action="<?php echo($_SERVER['PHP_SELF']) ?>">
			
			<input type="hidden" name="fileId" value="<?php echo($file['fileId']); ?>" />
			<input type="hidden" name="fileName" value="<?php echo($file['fileName']); ?>" />
			<input type="hidden" name="fileExtension" value="<?php echo($file['fileExtension']); ?>" />
			<input type="hidden" name="fileSize" value="<?php echo($file['fileSize']); ?>" />
			
			<?php
			// STEP 3 - display the image (if there is one to display)
			if($file['fileId'] > 0 && !empty($file['fileExtension'])){
                $realFileName = $file['fileId'] . "." . $file['fileExtension'];
                echo('<img id="mainImg" src="' . UPLOAD_FOLDER . $realFileName .'" />');
            }
			?>

			<label>
			Select a file
			<?php
            // STEP 4 - display error messages related to the file upload (if there are any to display)
            echo(isset($validationErrors['imageRequired']) ? wrapValidationMsg($validationErrors['imageRequired']) : "");
            echo(isset($validationErrors['invalidImageType']) ? wrapValidationMsg($validationErrors['invalidImageType']) : "");
            echo(isset($validationErrors['invalidImageSize']) ? wrapValidationMsg($validationErrors['invalidImageSize']) : "");
			?>
			</label>
			<input type="file" name="upload" />
						
			<label>
				Description
				<?php echo(isset($validationErrors['fileDescription']) ? wrapValidationMsg($validationErrors['fileDescription']) : ""); ?>
			</label>

			<textarea name="fileDescription"><?php echo($file['fileDescription']); ?></textarea>
						
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

//////////////////////
// Functions
/////////////////////

function validateFileInput($file, $allowed_file_types, $max_file_size){

	// we'll populate this array with any errors that we discover.
	$errors = array();

	if(empty($file['fileDescription'])){
		$errors['fileDescription'] = "You must enter a description";
	}

	// STEP 5 - Finish validation code
	// on inserts, there must be a file uploaded
    // http://php.net/manual/en/features.file-upload.errors.php
    if($file['fileId'] == 0 && $_FILES['upload']['error'] == UPLOAD_ERR_NO_FILE){
        $errors['imageRequired'] = "You must upload an image";
    }

    if($_FILES['upload']['error'] == UPLOAD_ERR_OK){
        
        // validate the file type
        if( !in_array($_FILES['upload']['type'], $allowed_file_types) ){
            $errors['invalidImageType'] = "The file must be an image.";
        }
        
        // validate the file size
        if($_FILES['upload']['size'] > $max_file_size){
            $errors['invalidImageSize'] = "The file is too big.";
        }
    }

	return $errors;
}

?>
