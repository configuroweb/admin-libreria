<?php
include_once 'config/Database.php';
include_once 'class/Author.php';

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listAuthor') {
	$author->listAuthor();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getAuthorDetails') {
	$author->authorid = $_POST["authorid"];
	$author->getAuthorDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addAuthor') {
	$author->name = $_POST["name"];
	$author->status = $_POST["status"];	
	$author->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateAuthor') {
	$author->authorid = $_POST["authorid"];
	$author->name = $_POST["name"];
	$author->status = $_POST["status"];
	$author->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteAuthor') {
	$author->authorid = $_POST["authorid"];
	$author->delete();
}

?>