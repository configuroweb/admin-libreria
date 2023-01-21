<?php
include_once 'config/Database.php';
include_once 'class/IssueBooks.php';

$database = new Database();
$db = $database->getConnection();

$issueBook = new IssueBooks($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listIssuedBook') {
	$issueBook->listIssuedBook();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getIssueBookDetails') {
	$issueBook->issuebookid = $_POST["issuebookid"];
	$issueBook->getIssueBookDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'issueBook') {
	$issueBook->book = $_POST["book"];
	$issueBook->users = $_POST["users"];
	$issueBook->expected_return_date = $_POST["expected_return_date"];
	$issueBook->return_date = $_POST["return_date"];
	$issueBook->status = $_POST["status"];	
	$issueBook->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateIssueBook') {
	$issueBook->issuebookid = $_POST["issuebookid"];
	$issueBook->book = $_POST["book"];
	$issueBook->users = $_POST["users"];
	$issueBook->expected_return_date = $_POST["expected_return_date"];
	$issueBook->return_date = $_POST["return_date"];
	$issueBook->status = $_POST["status"];	
	$issueBook->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteIssueBook') {
	$issueBook->issuebookid = $_POST["issuebookid"];
	$issueBook->delete();
}

?>