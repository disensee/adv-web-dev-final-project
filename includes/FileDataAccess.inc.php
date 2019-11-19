<?php
class FileDataAccess{
	
	private $link;

	// CONSTRUCTOR
	function __construct($link){
		$this->link = $link;
	}

	// We'll invoke this method when we encounter a database error
	function handleError($msg){
		throw new Exception($msg);
	}

	// Insert a file into the database
	function insertFile($file){

		// prevent SQL injection ('scrub' the $file param)
        $file['fileName'] = mysqli_real_escape_string($this->link, $file['fileName']);
        $file['fileDescription'] = mysqli_real_escape_string($this->link, $file['fileDescription']);
        $file['fileExtension'] = mysqli_real_escape_string($this->link, $file['fileExtension']);
        $file['fileSize'] = mysqli_real_escape_string($this->link, $file['fileSize']);
		// create the SQL statement/query
        $qStr = "INSERT INTO files (
                fileName,
                fileDescription,
                fileExtension,
                fileSize
            ) VALUES (
                '{$file['fileName']}',
                '{$file['fileDescription']}',
                '{$file['fileExtension']}',
                '{$file['fileSize']}'
            )";
            //die($qStr);
		// execute the query
        $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		// if the result is valid (not false)
        if($result){
            // add the fileId to the $file array
            $file['fileId'] = mysqli_insert_id($this->link);
             // return the $file array
             return $file;
            // if the result is false (not valid)
        }else{
			// invoke the handleError() method and pass in a msg as a param
            $this->handleError("unable to insert file");
		}
		// return false
        return false;
	}

	// Get a listing of all files
	function getFileList(){

		// create the SQL statement/query
		$qStr = "SELECT fileId, fileName, fileDescription, fileExtension, fileSize
				FROM files";
		// execute the query
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		// create a $fileList array
		$fileList = array();
		// loop through the rows 
		while($row = mysqli_fetch_assoc($result)){
			// create a $file array
			$file = array();
			// populate the $file array with data from the database (use htmlentities() to prevent XSS attacks!)
			$file['fileId'] = htmlentities($row['fileId']);
			$file['fileName'] = htmlentities($row['fileName']);
			$file['fileDescription'] = htmlentities($row['fileDescription']);
			$file['fileExtension'] = htmlentities($row['fileExtension']);
			$file['fileSize'] = htmlentities($row['fileSize']);
			// add the $file array to the $fileList array
			$fileList[] = $file;
		}
		// return the $fileList
		return $fileList;
	}


	// Get a file by it's ID
	function getFileById($id){

		// prevent SQL injection attack by 'scrubbing' the $id
		$id = mysqli_real_escape_string($this->link, $id);
		// create the SQL statement to select a file by it's fileId
		$qStr = "SELECT
					fileId,
					fileName,
					fileDescription,
					fileExtension,
					fileSize
				FROM files
				WHERE fileId = {$id}";

		// execute the query and get the result
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		// Check to see that we got 1 and only 1 row from the result
		if(mysqli_num_rows($result) == 1){		
			// get the row from the result
			$row = mysqli_fetch_assoc($result);
			// create an array called $file
			$file = array();
			// 'scrub' the columns in the row (use htmlentities() ) and transfer it into the $file array
			$file['fileId'] = htmlentities($row['fileId']);
			$file['fileName'] = htmlentities($row['fileName']);
			$file['fileDescription'] = htmlentities($row['fileDescription']);
			$file['fileExtension'] = htmlentities($row['fileExtension']);
			$file['fileSize'] = htmlentities($row['fileSize']);
			// return the $file
			return $file;
			// If we didn't get 1 row back, return false
		}else{
			return false;
		}
	}

	// updates a row in the files table
	function updateFile($file){

		// prevent SQL injection by 'scrubbing' the values in the $file array
		$file['fileId'] = mysqli_real_escape_string($this->link, $file['fileId']);
		$file['fileName'] = mysqli_real_escape_string($this->link, $file['fileName']);
		$file['fileDescription'] = mysqli_real_escape_string($this->link, $file['fileDescription']);
		$file['fileExtension'] = mysqli_real_escape_string($this->link, $file['fileExtension']);
		$file['fileSize'] = mysqli_real_escape_string($this->link, $file['fileSize']);
		// build the SQL query
		$qStr = "UPDATE files SET
					fileName = '{$file['fileName']}',
					fileDescription = '{$file['fileDescription']}',
					fileExtension = '{$file['fileExtension']}',
					fileSize = '{$file['fileSize']}'
				WHERE fileId = '{$file['fileId']}'";
		// execute the query and get the results
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		// If the result exists (not false), and return the $file
		if($result){
			return $file;
		// If the result is false, then invoke the handleError() method and return false
		}else{
			$this->handleError("unable to update file");
		}
		return false;
	}
}


