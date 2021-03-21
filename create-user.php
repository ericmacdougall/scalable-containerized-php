<?php
$_OVERRIDE=true;
$_delaysessionwrite=true;
include("includes/connection.inc.php");
$username=alnum($_REQUEST['username']);
if($_REQUEST['username'] != $username) {
	header("Location: error-username.php");
	die();
//	die("Username must be alphanumeric characters only.");
}
if($_REQUEST['password'] != $_REQUEST['password2']) {
	header("Location: error-password-entry-missmatch.php");
	die("Password entry mismatch.");
}
$password=$_REQUEST['password'];
$email=$_REQUEST['email'];
$phone=$_SESSION['confirmed_phone'];
if($phone != $_REQUEST['phone']) {
	die("Issue with phone number.");
}

$ru="";
if(isset($_SESSION['referrerUsername'])) {
	$ru=$_SESSION['referrerUsername'];
} else {
	if(isset($_COOKIE['ru'])) {
		$ru=$_COOKIE['ru'];
	}
}
$create_response=system_post_noauth("/users",array("data" => array( "type" => "users", "attributes" => array(
		"password" => $password,
		"accountCountry" => $_SESSION['countryName'],
		"referrerUsername" => $ru,
		"geoCountry" => $_SESSION['countryAlpha'],
		"geoState" => $_SESSION['state'],
		"email" => $email,
		"userName" => $username,
		"accountPhone" => $phone,
		"birthday" => $_SESSION['birthday'],
		"age" => $_SESSION['age']))));


$user_id=$create_response['data']['id'];

if($user_id != "") {


$response=system_post_noauth("/users/login",array("email" => $_REQUEST['email'], "password" => $_REQUEST['password']));
if($response['title'] == "Login successful" && $response['AuthenticationResult']['AccessToken'] != "") {
	$_SESSION['system_access_token']=$response['AuthenticationResult']['AccessToken'];
	if(isset($response['userId'])) $_SESSION['system_userId']=$response['userId'];
	$_SESSION['system_email']=$response['email'];
	$_SESSION['system_username']=$response['username'];

	$_SESSION['system_userdata']=$response;
	$_SESSION['rolePermissions']=$response['rolePermissions'];

	header("Location: index.php");
} else { 
	?>
Login failed
<?php
	print_r($response);
}




} else {
	if($create_response['errors'][0]['title']=="Phone number already in use.") {
		header("Location: /error-phone-exists.php");
	} else if(stristr($create_response['errors'][0]['title'],"Password did not conform with policy")) {
		header("Location: /error-password.php?text=".urlencode($create_response['errors'][0]['title']));
	} else {
		//print_r($create_response);
		header("Location: /error-email-exists.php");
	}
 } 

session_write_close();

?>