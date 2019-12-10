<?php
class PageDataAccess{
	
	private $link;

	// CONSTRUCTOR
	function __construct($link){
		$this->link = $link;
	}

	function getLink(){
		return $this->link;
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

	function createPaginatedList($start, $display){
		if(isset($_GET['p']) && is_numeric($_GET['p'])){ //number of posts already determined
			$pages = $_GET['p'];
		}else{ //need to deterimine amount of pages
		
			//count the number of records:
			$qStr = "SELECT COUNT(pageId) FROM pages";
			$result = @mysqli_query($this->link, $qStr);
			$row = @mysqli_fetch_array($result, MYSQLI_NUM);
			$records = $row[0];
		
			//calculate the number of pages
			if($records > $display){//more than one page
				$pages = ceil($records/$display);
			}else{
				$pages = 1;
			}
		}
		
		$qStr = "SELECT pageId, path, title, publishedDate, active FROM pages WHERE active = 'yes' ORDER BY publishedDate DESC LIMIT $start, $display";
		
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
	
		echo(createBlogList($pageList));
		//Make the links to other pages if necessary
		if($pages > 1){
			echo('<div id="page-link-container"');
			echo('<br><p>');
	
			//determine what page the script is on
			$current_page = ($start/$display) + 1;
	
			//If it's not the first page, make a previous link
			if($current_page != 1){
				echo('<a href="index.php?s=' . ($start - $display) . '&p=' . $pages . '">Previous</a>');
			}
	
			//make all numbered pages:
			for($i=1; $i <= $pages; $i++){
				if($i != $current_page){
					echo('<a href="index.php?s=' . (($display * ($i -1))) . '&p=' . $pages . '">' . $i . '</a>');
				}else{
					echo($i . ' ');
				}
			}
			//If it's not the last page, make a next button
			if($current_page != $pages){
				echo('<a href="index.php?s=' . ($start + $display) . '&p=' . $pages . '">Next</a>');
			}
	
			echo('</p>');
			echo('</div>');
		}
	}

	// Get a page by it's id
	function getPageById($id){

		// prevent SQL injection attack by 'scrubbing' the $id
		$id = mysqli_real_escape_string($this->link, $id);

		$qStr = "SELECT 
					pageId, 
					path, 
					title,
					description,
					content,
					categories.categoryId, 
					categories.name as categoryName,
					DATE_FORMAT(publishedDate,'%m/%e/%Y') as publishedDate, 
					pages.active 
				FROM pages
				INNER JOIN categories on pages.categoryId = categories.categoryId
				WHERE pageId = {$id}";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$page = array();
			$page['pageId'] = htmlentities($row['pageId']);
			$page['path'] = htmlentities($row['path']);
			$page['title'] = htmlentities($row['title']);
			$page['description'] = htmlentities($row['description']);
			$page['content'] = sanitizeHtml($row['content']); // Note that we are not using htmlentities() here!!!
			$page['categoryId'] = htmlentities($row['categoryId']);
			$page['categoryName'] = htmlentities($row['categoryName']);
			$page['publishedDate'] = htmlentities($row['publishedDate']);
			$page['active'] = htmlentities($row['active']);
			return $page;
		}else{
			return false;
		}
	}
	//inserts a row into the pages table
	function insertPage($page){
		//prevent SQL injection
		$page['path'] = mysqli_real_escape_string($this->link, $page['path']);
		$page['title'] = mysqli_real_escape_string($this->link, $page['title']);
		$page['description'] = mysqli_real_escape_string($this->link, $page['description']);
		$page['content'] = mysqli_real_escape_string($this->link, $page['content']);
		$page['categoryId'] = mysqli_real_escape_string($this->link, $page['categoryId']);
		$page['publishedDate'] = mysqli_real_escape_string($this->link, $page['publishedDate']);
		$page['active'] = mysqli_real_escape_string($this->link, $page['active']);

		//build the SQL query
		$qStr = "INSERT INTO pages (
				path,
				title,
				description,
				content,
				categoryId,
				publishedDate,
				active
			) VALUES (
				'{$page['path']}',
				'{$page['title']}',
				'{$page['description']}',
				'{$page['content']}',
				'{$page['categoryId']}',
				'{$page['publishedDate']}',
				'{$page['active']}'
		)";
		//die($qStr);
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			//add the pageId that was assigned by the database
			$page['pageId'] = mysqli_insert_id($this->link);
			//then return the page (with the pageId)
			return $page;
		}else{
			$this->handleError("unable to insert page");
		}
		return false;
	}
	//updates a row in the pages table
	function updatePage($page){
		
		//prevent SQL injection
		$page['pageId'] = mysqli_real_escape_string($this->link, $page['pageId']);
		$page['path'] = mysqli_real_escape_string($this->link, $page['path']);
		$page['title'] = mysqli_real_escape_string($this->link, $page['title']);
		$page['description'] = mysqli_real_escape_string($this->link, $page['description']);
		$page['content'] = mysqli_real_escape_string($this->link, $page['content']);
		$page['categoryId'] = mysqli_real_escape_string($this->link, $page['categoryId']);
		$page['publishedDate'] = mysqli_real_escape_string($this->link, $page['publishedDate']);
		$page['active'] = mysqli_real_escape_string($this->link, $page['active']);

		//build the SQL query
		$qStr = "UPDATE pages SET
					path = '{$page['path']}',
					title = '{$page['title']}',
					description = '{$page['description']}',
					content = '{$page['content']}',
					categoryId = '{$page['categoryId']}',
					publishedDate = '{$page['publishedDate']}',
					active = '{$page['active']}'
				WHERE pageId = '{$page['pageId']}'";

		//die($qStr);

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			return $page;
		}else{
			$this->handleError("unable to update page");
		}
		return false;
	}
}


			