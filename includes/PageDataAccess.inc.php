<?php
class PageDataAccess{
	
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
	function getPageList($activeOnly = true){

		$qStr = "SELECT pageId, path, title, DATE_FORMAT(publishedDate,'%m/%e/%Y') as publishedDate, active FROM pages";
		
		if($activeOnly){
			$qStr .= " WHERE active = 'yes'";
		}

		$qStr .= " ORDER BY publishedDate DESC";
		//die($qStr); //using die() after $qStr is a good way to see the query while testing

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$pageList = array();

		while($row = mysqli_fetch_assoc($result)){
			$page = array();
			$page['pageId'] = htmlentities($row['pageId']);
			$page['path'] = htmlentities($row['path']);
			$page['title'] = htmlentities($row['title']);
			$page['publishedDate'] = htmlentities($row['publishedDate']);
			$page['active'] = htmlentities($row['active']);
			$pageList[] = $page;
		}

		return $pageList;
	}
	
}
			