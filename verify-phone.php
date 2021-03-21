<?php
$_OVERRIDE=true;
include('includes/connection.inc.php');

if(isset($_REQUEST['referrerUsername'])) {
	$_SESSION['referrerUsername']=$_REQUEST['referrerUsername'];
} else {
	if(isset($_COOKIE['ru'])) {
		$_SESSION['referrerUsername']=$_COOKIE['ru'];
	}
}
if(isset($_REQUEST['country'])) {
$country=$_REQUEST['country'];
$countryex=explode("|",$country);
$_SESSION['countryName']=$countryex[1];
$_SESSION['countryAlpha']=$countryex[0];
}

if(isset($_REQUEST['state'])) {
$province=$_REQUEST['state'];
$_SESSION['province']=$_REQUEST['state'];
$_SESSION['state']=$_REQUEST['state'];
}

if(!isset($_REQUEST['state'])) {
	header("Location: signup.php");
	die();
}


	$_SESSION['locval']=true;


$birthday=$_REQUEST['birthday'];

function calculateAge($birthday)
{
    $today = new DateTime();
    $diff = $today->diff(new DateTime($birthday));
    return  $diff->y;
}

if(calculateAge($birthday) < 16) {
	header("Location: /error-16.php"); die();
//	die("Must be over 16 to participate.");
}
$_SESSION['birthday']=$birthday;
$_SESSION['age']=calculateAge($birthday);

$phone=ltrim($_REQUEST['phone'],"+");
$phone=str_replace(array(" ","-","(",")","\t"),"",$phone);

$verify=system_post_raw_noauth("/users/verifyPhone",array("phoneNumber" => $phone));
if($verify != "OK"){ die("Invalid phone number: (".$verify.")"); }
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
                <form class="form-settings signup-form systemform" action="confirm-phone.php" method="post">
     <p class="mt-1 mb-3 md-text">Check your phone for a verification code</p>
     <input type=hidden name=phone value="<?=$phone;?>">
    <p class="small-text ">Phone Number: <?=$phone;?><br/></p>    
    <div class="form-group">
 <label for="verify-code" class="mb-2 small-text float-left">Verification</label>
      <input class="form-control" type=text required name="code" placeholder="Verification Code" required autofocus>
      </div> <div class="checkbox mb-3">
    </div>
    <div class="account-txt">
                     <p class="mt-3 mb-2 xsm-text">Enter the verification code that was sent to your email. If you didn’t receive a verifcation code click here to resend verification code.</p>
                     </div>
  <button class="btn btn-lg btn-primary btn-block" type=submit class="btn btn-lg btn-primary btn-block" value="confirm phone">Submit</button>
      </form>

             

            </div>

        </div>

    </div>

</div>

</section>

  <?php
include("assets/layout/public_footer.php");
?>