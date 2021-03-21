<?php
if($_REQUEST['email'] != "" && $_REQUEST['password'] != "" && $_REQUEST['password'] == $_REQUEST['password2'] && $_REQUEST['code'] != "") {
} else { die("Please fill in the form completely and ensure you enter the same password both times."); }
$_OVERRIDE=true;
include("includes/connection.inc.php");
include("assets/layout/public_header.php");

$r=system_post('/users/confirmpassword',array("email" => $_REQUEST['email'], "newPassword" => $_REQUEST['password'], "otp" => $_REQUEST['code']));


?>

<section id="login-section">

    <div class="container-fluid p-remove">

        <div class="row no-gutters">

            <div class="col-lg-6 col-md-12 bg-blue">

                <div class="img-box">
 
                </div>

            </div>

            <div class="col-lg-6 col-md-12">

             <div class="formbox systemform verify-box">

                <h2>Reset Your <span class="theme-y-clr">Password</span></h2>

                <p><?php if($r['errors'][0]['title'] != "") { echo $r['errors'][0]['title']; } else { ?>Reset Complete. If everything matches, your account should be updated.<?php } ?></p>


                    <a href=login.php  class="btn mt-5">Return to Login</a>

                    


             </div>

            </div>

        </div>

    </div>

</section>


<?php
include("assets/layout/public_footer.php");
