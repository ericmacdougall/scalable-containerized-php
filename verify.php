<?php
$_OVERRIDE=true;
include('includes/connection.inc.php');

$birthday=$_REQUEST['birthday'];

function calculateAge($birthday)
{
    $today = new DateTime();
    $diff = $today->diff(new DateTime($birthday));
    return  $diff->y;
}

if(calculateAge($birthday) < 16) {
	die("Must be over 16 to participate.");
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
  <div class="row">
    <div id= "header" class="header container">
     <div class= "text-white mb-4">
        <h1>Create a system Account</h1>
      </div>
    </div>
  </div>
  <div class="row">
     <form class="form-signin rounded container col-12" action="confirm-phone.php" method="post">
     <h2 class="mt-1 mb-3 text-white">Check your phone for a verification code</h2>
     <input type=hidden name=phone value="<?=$phone;?>">
    Phone Number: <?=$phone;?><br/>     
    <label for="verify-code" class="mb-2 text-white float-left">Verification</label>
      <input class="form-control" type=text required name="code" placeholder="Verification Code" required autofocus>
        <div class="checkbox mb-3">
    </div>
    <p class="mt-3 mb-2 text-white">Enter the verification code that was sent to your email. If you didn’t receive a verifcation code click here to resend verification code.</p>
  <button class="btn btn-lg btn-primary btn-block" type=submit class="btn btn-xl btn-primary" value="confirm phone">Submit</button>
      </form>
  </div>
  <?php
include("assets/layout/public_footer.php");
?>