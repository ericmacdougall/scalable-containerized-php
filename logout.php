<?php
include("includes/connection.inc.php");
$_SESSION=array();
session_destroy();
header("Location:login.php");