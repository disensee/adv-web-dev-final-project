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

	
	
}

