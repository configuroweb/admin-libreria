<?php
class Publisher
{

	private $publisherTable = 'publisher';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function listPublisher()
	{

		$sqlQuery = "SELECT publisherid, name, status
			FROM " . $this->publisherTable . " ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE (publisherid LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR status LIKE "%' . $_POST["search"]["value"] . '%" ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY publisherid DESC ';
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
		while ($publisher = $result->fetch_assoc()) {
			$rows = array();
			$rows[] = $count;
			$rows[] = ucfirst($publisher['name']);
			$rows[] = $publisher['status'];
			$rows[] = '<button type="button" name="update" id="' . $publisher["publisherid"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Editar</span></button>';
			$rows[] = '<button type="button" name="delete" id="' . $publisher["publisherid"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Eliminar</span></button>';
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
				INSERT INTO " . $this->publisherTable . "(`name`, `status`)
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
				UPDATE " . $this->publisherTable . " 
				SET name = ?, status = ?
				WHERE publisherid = ?");

			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->publisherid = htmlspecialchars(strip_tags($this->publisherid));

			$stmt->bind_param("ssi", $this->name, $this->status, $this->publisherid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function delete()
	{
		if ($this->publisherid && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				DELETE FROM " . $this->publisherTable . " 
				WHERE publisherid = ?");

			$this->publisherid = htmlspecialchars(strip_tags($this->publisherid));

			$stmt->bind_param("i", $this->publisherid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function getPublisherDetails()
	{
		if ($this->publisherid && $_SESSION["userid"]) {

			$sqlQuery = "
				SELECT publisherid, name, status
				FROM " . $this->publisherTable . "			
				WHERE publisherid = ? ";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->publisherid);
			$stmt->execute();
			$result = $stmt->get_result();
			$records = array();
			while ($publisher = $result->fetch_assoc()) {
				$rows = array();
				$rows['publisherid'] = $publisher['publisherid'];
				$rows['name'] = $publisher['name'];
				$rows['status'] = $publisher['status'];
				$records[] = $rows;
			}
			$output = array(
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
}
