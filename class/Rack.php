<?php
class Rack
{

	private $rackTable = 'rack';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function listRack()
	{

		$sqlQuery = "SELECT rackid, name, status
			FROM " . $this->rackTable . " ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE (rackid LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR status LIKE "%' . $_POST["search"]["value"] . '%" ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY rackid DESC ';
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
		while ($rack = $result->fetch_assoc()) {
			$rows = array();
			$rows[] = $count;
			$rows[] = ucfirst($rack['name']);
			$rows[] = $rack['status'];
			$rows[] = '<button type="button" name="update" id="' . $rack["rackid"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Editar</span></button>';
			$rows[] = '<button type="button" name="delete" id="' . $rack["rackid"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Eliminar</span></button>';
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
				INSERT INTO " . $this->rackTable . "(`name`, `status`)
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
				UPDATE " . $this->rackTable . " 
				SET name = ?, status = ?
				WHERE rackid = ?");

			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->rackid = htmlspecialchars(strip_tags($this->rackid));

			$stmt->bind_param("ssi", $this->name, $this->status, $this->rackid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function delete()
	{
		if ($this->rackid && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				DELETE FROM " . $this->rackTable . " 
				WHERE rackid = ?");

			$this->rackid = htmlspecialchars(strip_tags($this->rackid));

			$stmt->bind_param("i", $this->rackid);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function getRackDetails()
	{
		if ($this->rackid && $_SESSION["userid"]) {

			$sqlQuery = "
				SELECT rackid, name, status
				FROM " . $this->rackTable . "			
				WHERE rackid = ? ";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->rackid);
			$stmt->execute();
			$result = $stmt->get_result();
			$records = array();
			while ($rack = $result->fetch_assoc()) {
				$rows = array();
				$rows['rackid'] = $rack['rackid'];
				$rows['name'] = $rack['name'];
				$rows['status'] = $rack['status'];
				$records[] = $rows;
			}
			$output = array(
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
}
