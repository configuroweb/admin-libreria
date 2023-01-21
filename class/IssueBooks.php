<?php
class IssueBooks
{

	private $issuedBookTable = 'issued_book';
	private $bookTable = 'book';
	private $userTable = 'user';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function listIssuedBook()
	{

		$sqlQuery = "SELECT issue_book.issuebookid, issue_book.issue_date_time, issue_book.expected_return_date, issue_book.return_date_time, issue_book.status, book.name As book_name, book.isbn, user.first_name, user.last_name 
			FROM " . $this->issuedBookTable . " issue_book		    
			LEFT JOIN " . $this->bookTable . " book ON book.bookid = issue_book.bookid
			LEFT JOIN " . $this->userTable . " user ON user.id = issue_book.userid ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE (issue_book.issuebookid LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR issue_book.issue_date_time LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR issue_book.status LIKE "%' . $_POST["search"]["value"] . '%" ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY issue_book.issuebookid DESC ';
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
		while ($issueBook = $result->fetch_assoc()) {
			$rows = array();
			$rows[] = $count;
			$rows[] = ucfirst($issueBook['book_name']);
			$rows[] = ucfirst($issueBook['isbn']);
			$rows[] = ucfirst($issueBook['first_name']) . " " . ucfirst($issueBook['last_name']);
			$rows[] = ucfirst($issueBook['issue_date_time']);
			$rows[] = ucfirst($issueBook['expected_return_date']);
			$rows[] = ucfirst($issueBook['return_date_time']);
			$rows[] = $issueBook['status'];
			$rows[] = '<button type="button" name="update" id="' . $issueBook["issuebookid"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Editar</span></button>';
			$rows[] = '<button type="button" name="delete" id="' . $issueBook["issuebookid"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Eliminar</span></button>';
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

		if ($this->book && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				INSERT INTO " . $this->issuedBookTable . "(`bookid`, `userid`, `expected_return_date`, `return_date_time`, `status`)
				VALUES(?, ?, ?, ?, ?)");

			$this->book = htmlspecialchars(strip_tags($this->book));
			$this->users = htmlspecialchars(strip_tags($this->users));
			$this->expected_return_date = htmlspecialchars(strip_tags($this->expected_return_date));
			$this->return_date = htmlspecialchars(strip_tags($this->return_date));
			$this->status = htmlspecialchars(strip_tags($this->status));

			$stmt->bind_param("iisss", $this->book, $this->users, $this->expected_return_date, $this->return_date, $this->status);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function update()
	{

		if ($this->issuebookid && $this->book && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				UPDATE " . $this->issuedBookTable . " 
				SET bookid = ?, userid = ?, expected_return_date = ?, return_date_time = ?, status = ?
				WHERE issuebookid = ?");

			$this->book = htmlspecialchars(strip_tags($this->book));
			$this->users = htmlspecialchars(strip_tags($this->users));
			$this->expected_return_date = htmlspecialchars(strip_tags($this->expected_return_date));
			$this->return_date = htmlspecialchars(strip_tags($this->return_date));
			$this->status = htmlspecialchars(strip_tags($this->status));

			$stmt->bind_param("iisssi", $this->book, $this->users, $this->expected_return_date, $this->return_date, $this->status, $this->issuebookid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function delete()
	{
		if ($this->issuebookid && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				DELETE FROM " . $this->issuedBookTable . " 
				WHERE issuebookid = ?");

			$this->issuebookid = htmlspecialchars(strip_tags($this->issuebookid));

			$stmt->bind_param("i", $this->issuebookid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function getIssueBookDetails()
	{
		if ($this->issuebookid && $_SESSION["userid"]) {

			$sqlQuery = "SELECT issue_book.issuebookid, issue_book.issue_date_time, issue_book.expected_return_date, issue_book.return_date_time, issue_book.status, issue_book.bookid, issue_book.userid, book.name AS book_name
			FROM " . $this->issuedBookTable . " issue_book		    
			LEFT JOIN " . $this->bookTable . " book ON book.bookid = issue_book.bookid
			LEFT JOIN " . $this->userTable . " user ON user.id = issue_book.userid
			WHERE issue_book.issuebookid = ?";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->issuebookid);
			$stmt->execute();
			$result = $stmt->get_result();
			$records = array();
			while ($issueBook = $result->fetch_assoc()) {
				$rows = array();
				$rows['issuebookid'] = $issueBook['issuebookid'];
				$rows['bookid'] = $issueBook['bookid'];
				$rows['book_name'] = $issueBook['book_name'];
				$rows['status'] = $issueBook['status'];
				$rows['userid'] = $issueBook['userid'];
				$rows['expected_return_date'] = date('Y-m-d\TH:i:s', strtotime($issueBook['expected_return_date']));
				$rows['return_date_time'] = date('Y-m-d\TH:i:s', strtotime($issueBook['return_date_time']));
				$records[] = $rows;
			}
			$output = array(
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
}
