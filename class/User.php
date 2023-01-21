<?php
class User
{

	private $userTable = 'user';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function login()
	{
		if ($this->email && $this->password) {
			$sqlQuery = "
				SELECT * FROM " . $this->userTable . " 
				WHERE email = ? AND password = ?";
			$stmt = $this->conn->prepare($sqlQuery);
			$password = md5($this->password);
			$stmt->bind_param("ss", $this->email, $password);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$user = $result->fetch_assoc();
				$_SESSION["userid"] = $user['id'];
				$_SESSION["role"] = $user['role'];
				$_SESSION["name"] = $user['email'];
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	public function loggedIn()
	{
		if (!empty($_SESSION["userid"])) {
			return 1;
		} else {
			return 0;
		}
	}

	public function isAdmin()
	{
		if (!empty($_SESSION["userid"]) && $_SESSION["role"] == 'admin') {
			return 1;
		} else {
			return 0;
		}
	}

	public function listUsers()
	{

		$sqlQuery = "SELECT id, first_name, last_name, email, password, role
			FROM " . $this->userTable . " ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= ' WHERE (id LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR email LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR password LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR role LIKE "%' . $_POST["search"]["value"] . '%" ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY id DESC ';
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
		while ($user = $result->fetch_assoc()) {
			$rows = array();
			$rows[] = $count;
			$rows[] = ucfirst($user['first_name']) . " " . ucfirst($user['last_name']);
			$rows[] = $user['email'];
			$rows[] = $user['role'];
			$rows[] = '<button type="button" name="update" id="' . $user["id"] . '" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit">Editar</span></button>';
			$rows[] = '<button type="button" name="delete" id="' . $user["id"] . '" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete">Eliminar</span></button>';
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
		if ($this->role && $this->email && $this->password && $_SESSION["userid"]) {
			$stmt = $this->conn->prepare("
				INSERT INTO " . $this->userTable . "(`first_name`, `last_name`, `email`, `password`, `role`)
				VALUES(?, ?, ?, ?, ?)");
			$this->role = htmlspecialchars(strip_tags($this->role));
			$this->email = htmlspecialchars(strip_tags($this->email));
			$this->first_name = htmlspecialchars(strip_tags($this->first_name));
			$this->last_name = htmlspecialchars(strip_tags($this->last_name));
			$this->password = md5($this->password);
			$stmt->bind_param("sssss", $this->first_name, $this->last_name, $this->email, $this->password, $this->role);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function update()
	{

		if ($this->role && $this->email && $_SESSION["userid"]) {

			$updatePass = '';
			if ($this->password) {
				$this->password = md5($this->password);
				$updatePass = ", password = '" . $this->password . "'";
			}

			$stmt = $this->conn->prepare("
				UPDATE " . $this->userTable . " 
				SET first_name = ?, last_name = ?, email = ?, role = ? $updatePass
				WHERE id = ?");

			$this->role = htmlspecialchars(strip_tags($this->role));
			$this->email = htmlspecialchars(strip_tags($this->email));
			$this->first_name = htmlspecialchars(strip_tags($this->first_name));
			$this->last_name = htmlspecialchars(strip_tags($this->last_name));

			$stmt->bind_param("ssssi", $this->first_name, $this->last_name, $this->email, $this->role, $this->id);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function delete()
	{
		if ($this->id && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				DELETE FROM " . $this->userTable . " 
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if ($stmt->execute()) {
				return true;
			}
		}
	}

	public function getUserDetails()
	{
		if ($this->user_id && $_SESSION["userid"]) {

			$sqlQuery = "
				SELECT id, first_name, last_name, email, password, role
				FROM " . $this->userTable . "			
				WHERE id = ? ";

			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->user_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$records = array();
			while ($user = $result->fetch_assoc()) {
				$rows = array();
				$rows['id'] = $user['id'];
				$rows['first_name'] = $user['first_name'];
				$rows['last_name'] = $user['last_name'];
				$rows['email'] = $user['email'];
				$rows['role'] = $user['role'];
				$records[] = $rows;
			}
			$output = array(
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}

	function getUsersList()
	{
		$stmt = $this->conn->prepare("
		SELECT id, first_name, last_name 
		FROM " . $this->userTable . " 
		WHERE role = 'user'");
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
}
