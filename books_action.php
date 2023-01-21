<?php
include_once 'config/Database.php';
include_once 'class/Books.php';

$database = new Database();
$db = $database->getConnection();

$book = new Books($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listBook') {
	$book->listBook();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getBookDetails') {
	$book->bookid = $_POST["bookid"];
	$book->getBookDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addBook') {
	$book->name = $_POST["name"];
	$book->isbn = $_POST["isbn"];
	$book->no_of_copy = $_POST["no_of_copy"];
	$book->author = $_POST["author"];
	$book->publisher = $_POST["publisher"];
	$book->category = $_POST["category"];
	$book->rack = $_POST["rack"];
	$book->status = $_POST["status"];	
	$book->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateBook') {
	$book->bookid = $_POST["bookid"];
	$book->name = $_POST["name"];
	$book->isbn = $_POST["isbn"];
	$book->no_of_copy = $_POST["no_of_copy"];
	$book->author = $_POST["author"];
	$book->publisher = $_POST["publisher"];
	$book->category = $_POST["category"];
	$book->rack = $_POST["rack"];
	$book->status = $_POST["status"];
	$book->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteBook') {
	$book->bookid = $_POST["bookid"];
	$book->delete();
}

?>