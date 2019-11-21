<?php
class CategoryDataAccess{
	
	private $link;

	// CONSTRUCTOR
	function __construct($link){
		$this->link = $link;
	}

	// We'll invoke this method when we encounter a database error
	function handleError($msg){
		throw new Exception($msg);
	}

	// Get a listing of all blog pages
	function getCategoryList($activeOnly = true){

		$qStr = "SELECT categoryId, name, active FROM categories";
		
		if($activeOnly){
			$qStr .= " WHERE active = 'yes'";
		}

		$qStr .= " ORDER BY name ASC";
		//die($qStr);

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$categoryList = array();

		while($row = mysqli_fetch_assoc($result)){
			$category = array();
			$category['categoryId'] = htmlentities($row['categoryId']);
			$category['name'] = htmlentities($row['name']);
			$category['active'] = htmlentities($row['active']);
			$categoryList[] = $category;
		}

		return $categoryList;
	}

	// Get a category by it's id
	function getCategoryById($id){

		// prevent SQL injection attack by 'scrubbing' the $id
		$id = mysqli_real_escape_string($this->link, $id);

		$qStr = "SELECT 
					categoryId, 
					name, 
					active
				FROM categories
				WHERE categoryId = {$id}";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$category = array();
			$category['categoryId'] = htmlentities($row['categoryId']);
			$category['name'] = htmlentities($row['name']);
			$category['active'] = htmlentities($row['active']);
			return $category;
		}else{
			return false;
		}
	}
	function insertCategory($category){
		//prevent SQL injection
		$category['name'] = mysqli_real_escape_string($this->link, $category['name']);
		$category['active'] = mysqli_real_escape_string($this->link, $category['active']);

		//build the SQL query
		$qStr = "INSERT INTO categories (
				name,
				active
			) VALUES (
				'{$category['name']}',
				'{$category['active']}'
		)";
		//die($qStr);
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			//add the categoryId that was assigned by the database
			$category['categoryId'] = mysqli_insert_id($this->link);
			//then return the category (with the categoryId)
			return $category;
		}else{
			$this->handleError("unable to insert category");
		}
		return false;
	}

	function updateCategory($category){
		
		//prevent SQL injection
		$category['categoryId'] = mysqli_real_escape_string($this->link, $category['categoryId']);
		$category['name'] = mysqli_real_escape_string($this->link, $category['name']);
		$category['active'] = mysqli_real_escape_string($this->link, $category['active']);

		//build the SQL query
		$qStr = "UPDATE categories SET
					name = '{$category['name']}',
					active = '{$category['active']}'
				WHERE categoryId = '{$category['categoryId']}'";

		//die($qStr);

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			return $category;
		}else{
			$this->handleError("unable to update category");
		}
		return false;
	}
}

