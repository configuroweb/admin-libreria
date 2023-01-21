<?php
include_once 'config/Database.php';
include_once 'class/Rack.php';

$database = new Database();
$db = $database->getConnection();

$rack = new Rack($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listRack') {
	$rack->listRack();
}

if(!empty($_POST['action']) && $_POST['action'] == 'getRackDetails') {
	$rack->rackid = $_POST["rackid"];
	$rack->getRackDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addRack') {
	$rack->name = $_POST["name"];
	$rack->status = $_POST["status"];	
	$rack->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateRack') {
	$rack->rackid = $_POST["rackid"];
	$rack->name = $_POST["name"];
	$rack->status = $_POST["status"];
	$rack->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteRack') {
	$rack->rackid = $_POST["rackid"];
	$rack->delete();
}

?>