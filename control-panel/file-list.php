<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");
require_once("../includes/FileDataAccess.inc.php");

$pageTitle = "File List";
$pageDescription = "";

require("../includes/header.inc.php");
?>
<main>
	<div class="content-frame">
		<h3>File List</h3>
		<a href="file-details.php">Add New File</a>
		<?php
		// Construct a FileDataAccess object (pass in the 'link' to the db)
		$fda = new FileDataAccess(getDBLink());
		
		// Invoke the getFileList() method and assign the return value to a variable named $files
		// Be careful: unlike getPageList(), getFileList() does not take any parameters.
		$files = $fda->getFileList();

		// invoke displayFiles() and pass $files in as the argument/param, then echo out the return value 
		echo(displayFiles($files));
		?>
		
	</div>
</main>
		
<?php
if(!empty($sideBar)){
	require("../includes/" . $sideBar);
}

require("../includes/footer.inc.php");

/////////////////
// FUNCTIONS
/////////////////

// Wraps files in an html table
function displayFiles($files){

	// create a starting table tag and stor it in a var named $html
	$html = "<table border = \"1\">";

	// create column headers
    $html .= "<tr>
               <th>File Name</th>
               <th>File Description</th>
               <th>File Extension</th>
               <th>File Size</th>
               <th>Edit</th>
            </tr>";
	
	// create table rows (loop through the files)
	foreach($files as $file){
        $html .= "<tr>";
        $html .= "<td>{$file['fileName']}</td>";
        $html .= "<td>{$file['fileDescription']}</td>";
        $html .= "<td>{$file['fileExtension']}</td>";
        $html .= "<td>{$file['fileSize']}</td>";
        $html .= "<td><a href=\"file-details.php?fileId={$file['fileId']}\">EDIT</a></td>";
        $html .= "</tr>";
    }

	// add the closing table tag
	$html .= "</table>";
	// return the html string
    return $html;
}

?>
