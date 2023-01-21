<?php
session_start();
class Database
{

	private $host  = 'localhost';
	private $user  = 'root';
	private $password   = "";
	private $database  = "admin-libreria";

	public function getConnection()
	{
		$conn = new mysqli($this->host, $this->user, $this->password, $this->database);
		if ($conn->connect_error) {
			die("Error failed to connect to MySQL: " . $conn->connect_error);
		} else {
			return $conn;
		}
	}
}
