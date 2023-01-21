<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listUsers') {
	$user->listUsers();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getUserDetails') {
	$user->user_id = $_POST["id"];
	$user->getUserDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addUser') {
	$user->role = $_POST["role"];
	$user->first_name = $_POST["first_name"];
	$user->last_name = $_POST["last_name"];
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];
	$user->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateUser') {
	$user->id = $_POST["id"];
	$user->role = $_POST["role"];
	$user->first_name = $_POST["first_name"];
	$user->last_name = $_POST["last_name"];
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];
	$user->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
	$user->id = $_POST["id"];
	$user->delete();
}

?>