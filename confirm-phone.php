<?php
$_OVERRIDE=true;
include('includes/connection.inc.php');
$code=alnum($_REQUEST['code']);
$phone=$_REQUEST['phone'];

$params=array("code" => $code, "phoneNumber" => $phone);
//print_r($params);
$resp=system_post_noauth("/users/confirmPhoneCode",$params);

if($resp['message'] != "Confirmed.") {
	header("Location: error-couldnt-confirm-code.php");
	die();
//	die("Couldn't confirm the code you submitted. ".print_r($resp));
}

$_SESSION['confirmed_phone']=$phone;
?>
<?php
include("assets/layout/public_header.php");
?>

<section id="login-section">

    <div class="container-fluid p-remove">

        <div class="row no-gutters d-flex justify-content-center">

            <div class="col-lg-6 col-md-12 bg-blue d-none d-xs-none d-sm-none d-md-none d-lg-block d-xl-block">

                <div class="img-box">



                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="formbox">
                    <h2>Create a <span class="theme-y-clr">system Account</span></h2>
                    <form class="form-settings signup-form systemform" action="create-user.php" method="post">
                      <div class="form-group">
                      <input type=hidden name=phone value=<?=$phone;?>>
<label for="username">Username</label>
<input class="form-control" type=text required name="username" placeholder="Enter your username" required autofocus>

</div>

<div class="form-group">

<label for="email">Email</label>

<input type="email" class="form-control" name="email" id="email" placeholder="info@gmail.com" required>

</div>

<div class="form-group">

<label for="password">Password</label>

<input type="password" class="form-control" id="password" name="password" placeholder="●●●●●●" required> 

</div>

<div class="form-group">

<label for="confiormpassword">Confirm Password</label>

<input type="password" class="form-control" id="password2" name="password2" placeholder="●●●●●●" required> 

</div>

<p>Password must be a minimum of 8 characters and contain at least one uppercase letter, lowercase letter, number, and special character.</p>

<button type="submit" class="btn">Submit</button>

</form>

<div class="account-txt">

<a href="/login.php">Already have a system Account?</a>

<a href="/login.php" class="mt-2">If you have already created a system Account on the system app you can use it to sign in here.</a>

</div>


                    </form>


                </div>

            </div>

        </div>

    </div>

</section>


  <?php
include("assets/layout/public_footer.php");
?>