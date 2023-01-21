<?php
class Author
{

	private $authorTable = 'author';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function listAuthor()
	{

		$sqlQuery = "SELECT authorid, name, status
			FROM " . $this->authorTable . " ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE (authorid LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR status LIKE "%' . $_POST["search"]["value"] . '%" ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY authorid DESC ';
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
		while ($author = $result->fetch_assoc()) {
			$rows = array();
			$rows[] = $count;
			$rows[] = ucfirst($author['name']);
			$rows[] = $author['status'];
			$rows[] = '<button type="button" name="update" id="' . $author["authorid"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Editar</span></button>';
			$rows[] = '<button type="button" name="delete" id="' . $author["authorid"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Eliminar</span></button>';
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
				INSERT INTO " . $this->authorTable . "(`name`, `status`)
				VALUES(?, ?)");

			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->status = htmlspecialchars(strip_tags($this->status));

			$stmt->bind_param("ss", $this->name, $this->status);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function update()
	{

		if ($this->name && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				UPDATE " . $this->authorTable . " 
				SET name = ?, status = ?
				WHERE authorid = ?");

			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->authorid = htmlspecialchars(strip_tags($this->authorid));

			$stmt->bind_param("ssi", $this->name, $this->status, $this->authorid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function delete()
	{
		if ($this->authorid && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				DELETE FROM " . $this->authorTable . " 
				WHERE authorid = ?");

			$this->authorid = htmlspecialchars(strip_tags($this->authorid));

			$stmt->bind_param("i", $this->authorid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function getAuthorDetails()
	{
		if ($this->authorid && $_SESSION["userid"]) {

			$sqlQuery = "
				SELECT authorid, name, status
				FROM " . $this->authorTable . "			
				WHERE authorid = ? ";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->authorid);
			$stmt->execute();
			$result = $stmt->get_result();
			$records = array();
			while ($author = $result->fetch_assoc()) {
				$rows = array();
				$rows['authorid'] = $author['authorid'];
				$rows['name'] = $author['name'];
				$rows['status'] = $author['status'];
				$records[] = $rows;
			}
			$output = array(
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
}
