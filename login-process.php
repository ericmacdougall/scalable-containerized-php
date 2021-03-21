<?php
$_OVERRIDE=true;
$_delaysessionwrite=true;
include("includes/connection.inc.php");

$response=system_post_noauth("/users/login",array("email" => $_REQUEST['email'], "password" => $_REQUEST['password']));
if($response['title'] == "Login successful" && $response['AuthenticationResult']['AccessToken'] != "") {


	$_SESSION['system_access_token']=$response['AuthenticationResult']['AccessToken'];
	if(isset($response['userId'])) $_SESSION['system_userId']=$response['userId'];
	$_SESSION['system_email']=$response['email'];
	$_SESSION['system_username']=$response['username'];

	$_SESSION['rolePermissions']=$response['rolePermissions'];


	if (hasPermission('Special Permission')) {
		header("Location: /special-page.php");
		die();	
	}
	if(isset($_REQUEST['url']) and $_REQUEST['url'] != "") {
		header("Location: ".$_REQUEST['url']);
	} else {

 		header("Location: index.php");
	}
} else { 
//	print_r($response);
	header("Location: /error-no-user.php");
}
session_write_close();
?>
