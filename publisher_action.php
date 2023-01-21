<?php
include_once 'config/Database.php';
include_once 'class/Publisher.php';

$database = new Database();
$db = $database->getConnection();

$publisher = new Publisher($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listPublisher') {
	$publisher->listPublisher();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getPublisherDetails') {
	$publisher->publisherid = $_POST["publisherid"];
	$publisher->getPublisherDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addPublisher') {
	$publisher->name = $_POST["name"];
	$publisher->status = $_POST["status"];	
	$publisher->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updatePublisher') {
	$publisher->publisherid = $_POST["publisherid"];
	$publisher->name = $_POST["name"];
	$publisher->status = $_POST["status"];
	$publisher->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deletePublisher') {
	$publisher->publisherid = $_POST["publisherid"];
	$publisher->delete();
}

?>