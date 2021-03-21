<?php
$_OVERRIDE=true;
include('includes/connection.inc.php');
$code=alnum($_REQUEST['code']);
$phone=$_REQUEST['phone'];

$params=array("code" => $code, "phoneNumber" => $phone);
//print_r($params);
$resp=system_post_noauth("/users/confirmPhoneCode",$params);

if($resp['message'] != "Confirmed.") {
	die("Couldn't confirm the code you submitted. ".print_r($resp));
}

$_SESSION['confirmed_phone']=$phone;
?>
<?php
include("assets/layout/public_header.php");
?>
  <div class="row">
    <div id= "header" class="header container">
     <div class= "text-white mb-4">
        <p class="lg-text">Create a system Account</p>
      </div>
    </div>
  </div>
  <div class="row">
     <form class="form-signin rounded container col-12" action="verify-phone.php" method="post">
     <label for="verify-code" class="mt-2 mb-2 text-white float-left">Create Username</label>
      <input class="form-control" type=text required name="username" placeholder="Enter your username" required autofocus>
    <label for="email" class="mt-2 mb-2 text-white float-left">Email</label>
      <input class="form-control" type="email" value="email" required>
    <label for="confirm-email" class="mt-2 mb-2 text-white float-left"> Confirm Email</label>
      <input class="form-control" type="email" value="bootstrap@example.com" id="example-email-input">
    <label for="password" class="mt-2 mb-2 text-white float-left">Password</label>
        <input class="form-control" type="password" value="password" required>
    <label for="confirm-password" class="mb-2 text-white float-left">Confirm Password</label>
        <input class="form-control" type="password" value="password2" required>
        <div class="checkbox mb-3">
    </div>
    <p class="mt-3 mb-2 text-white">Password must be a minimum of 8 characters and contain at least one uppercase letter, lowercase letter, number, and special character.</p>
    <button class="btn btn-lg btn-primary btn-block" type=submit class="btn btn-xl btn-primary" value="Create Account">Submit</button>
  

      </form>
  </div>
  <?php
include("assets/layout/public_footer.php");
?>