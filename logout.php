<?php
session_start();
$_SESSION["userid"] = '';
$_SESSION["name"] = '';
$_SESSION["role"] = '';
session_destroy();
header("Location:index.php");
