<?php
class Books
{

	private $bookTable = 'book';
	private $issuedBookTable = 'issued_book';
	private $categoryTable = 'category';
	private $authorTable = 'author';
	private $publisherTable = 'publisher';
	private $rackTable = 'rack';
	private $userTable = 'user';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function listBook()
	{

		$sqlQuery = "SELECT book.bookid, book.picture, book.name, book.status, book.isbn, book.no_of_copy, book.updated_on, author.name as author_name, category.name AS category_name, rack.name As rack_name, publisher.name AS publisher_name 
			FROM " . $this->bookTable . " book		    
			LEFT JOIN " . $this->authorTable . " author ON author.authorid = book.authorid
			LEFT JOIN " . $this->categoryTable . " category ON category.categoryid = book.categoryid
			LEFT JOIN " . $this->rackTable . " rack ON rack.rackid = book.rackid
			LEFT JOIN " . $this->publisherTable . " publisher ON publisher.publisherid = book.publisherid ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE (book.bookid LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR book.name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR book.status LIKE "%' . $_POST["search"]["value"] . '%" ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY book.bookid DESC ';
		}

		if ($_POST["length"] != -1) {
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();

		$stmtTotal = $this->conn->prepare($sqlQuery);
		$stmtTotal->execute();
		$allResult = $stmtTotal->get_result();
		$allRecords = $allResult->num_rows;

		$displayRecords = $result->num_rows;
		$records = array();
		$count = 1;
		while ($book = $result->fetch_assoc()) {
			$rows = array();
			if (!$book['picture']) {
				$book['picture'] = 'default.jpg';
			}
			$rows[] = '<img src="images/' . $book['picture'] . '" width="80" height="90">';
			$rows[] = ucfirst($book['name']);
			$rows[] = ucfirst($book['isbn']);
			$rows[] = ucfirst($book['author_name']);
			$rows[] = ucfirst($book['publisher_name']);
			$rows[] = ucfirst($book['category_name']);
			$rows[] = ucfirst($book['rack_name']);
			$rows[] = ucfirst($book['no_of_copy']);
			$rows[] = $book['status'];
			$rows[] = $book['updated_on'];
			$rows[] = '<button type="button" name="update" id="' . $book["bookid"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Editar</span></button>';
			$rows[] = '<button type="button" name="delete" id="' . $book["bookid"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Eliminar</span></button>';
			$records[] = $rows;
			$count++;
		}

		$output = array(
			"draw"	=>	intval($_POST["draw"]),
			"iTotalRecords"	=> 	$displayRecords,
			"iTotalDisplayRecords"	=>  $allRecords,
			"data"	=> 	$records
		);

		echo json_encode($output);
	}

	public function insert()
	{

		if ($this->name && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				INSERT INTO " . $this->bookTable . "(`name`, `status`, `isbn`, `no_of_copy`, `categoryid`, `authorid`, `rackid`, `publisherid`)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->isbn = htmlspecialchars(strip_tags($this->isbn));
			$this->no_of_copy = htmlspecialchars(strip_tags($this->no_of_copy));
			$this->author = htmlspecialchars(strip_tags($this->author));
			$this->publisher = htmlspecialchars(strip_tags($this->publisher));
			$this->category = htmlspecialchars(strip_tags($this->category));
			$this->rack = htmlspecialchars(strip_tags($this->rack));
			$this->status = htmlspecialchars(strip_tags($this->status));

			$stmt->bind_param("sssiiiii", $this->name, $this->status, $this->isbn, $this->no_of_copy, $this->category, $this->author, $this->rack, $this->publisher);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function update()
	{

		if ($this->name && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				UPDATE " . $this->bookTable . " 
				SET name = ?, status = ?, isbn = ?, no_of_copy = ?, categoryid = ?, authorid = ?, rackid = ?, publisherid = ?
				WHERE bookid = ?");

			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->isbn = htmlspecialchars(strip_tags($this->isbn));
			$this->no_of_copy = htmlspecialchars(strip_tags($this->no_of_copy));
			$this->author = htmlspecialchars(strip_tags($this->author));
			$this->publisher = htmlspecialchars(strip_tags($this->publisher));
			$this->category = htmlspecialchars(strip_tags($this->category));
			$this->rack = htmlspecialchars(strip_tags($this->rack));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->bookid = htmlspecialchars(strip_tags($this->bookid));

			$stmt->bind_param("sssiiiiii", $this->name, $this->status, $this->isbn, $this->no_of_copy, $this->category, $this->author, $this->rack, $this->publisher, $this->bookid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function delete()
	{
		if ($this->bookid && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				DELETE FROM " . $this->bookTable . " 
				WHERE bookid = ?");

			$this->bookid = htmlspecialchars(strip_tags($this->bookid));

			$stmt->bind_param("i", $this->bookid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function getBookDetails()
	{
		if ($this->bookid && $_SESSION["userid"]) {

			$sqlQuery = "SELECT book.bookid, book.picture, book.name, book.status, book.isbn, book.no_of_copy, book.updated_on, author.authorid, category.categoryid, rack.rackid, publisher.publisherid 
			FROM " . $this->bookTable . " book		    
			LEFT JOIN " . $this->authorTable . " author ON author.authorid = book.authorid
			LEFT JOIN " . $this->categoryTable . " category ON category.categoryid = book.categoryid
			LEFT JOIN " . $this->rackTable . " rack ON rack.rackid = book.rackid
			LEFT JOIN " . $this->publisherTable . " publisher ON publisher.publisherid = book.publisherid 
			WHERE bookid = ? ";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->bookid);
			$stmt->execute();
			$result = $stmt->get_result();
			$records = array();
			while ($book = $result->fetch_assoc()) {
				$rows = array();
				$rows['bookid'] = $book['bookid'];
				$rows['name'] = $book['name'];
				$rows['status'] = $book['status'];
				$rows['isbn'] = $book['isbn'];
				$rows['no_of_copy'] = $book['no_of_copy'];
				$rows['categoryid'] = $book['categoryid'];
				$rows['rackid'] = $book['rackid'];
				$rows['publisherid'] = $book['publisherid'];
				$rows['authorid'] = $book['authorid'];
				$records[] = $rows;
			}
			$output = array(
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}

	function getAuthorList()
	{
		$stmt = $this->conn->prepare("
		SELECT authorid, name 
		FROM " . $this->authorTable);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}

	function getCategoryList()
	{
		$stmt = $this->conn->prepare("
		SELECT categoryid, name 
		FROM " . $this->categoryTable);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}

	function getPublisherList()
	{
		$stmt = $this->conn->prepare("
		SELECT publisherid, name 
		FROM " . $this->publisherTable);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}

	function getRackList()
	{
		$stmt = $this->conn->prepare("
		SELECT rackid, name 
		FROM " . $this->rackTable);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}

	function getBookList()
	{
		$stmt = $this->conn->prepare("
		SELECT book.bookid, book.name, issue_book.status
		FROM " . $this->bookTable . " book
		LEFT JOIN " . $this->issuedBookTable . " issue_book ON issue_book.bookid = book.bookid");
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}

	function getTotalBooks()
	{
		$stmt = $this->conn->prepare("
		SELECT *
		FROM " . $this->bookTable);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}


	function getTotalIssuedBooks()
	{
		$stmt = $this->conn->prepare("
		SELECT * 
		FROM " . $this->issuedBookTable . " 
		WHERE status = 'Issued'");
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}


	function getTotalReturnedBooks()
	{
		$stmt = $this->conn->prepare("
		SELECT * 
		FROM " . $this->issuedBookTable . " 
		WHERE status = 'Returned'");
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}
}
