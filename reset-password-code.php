<?php
$_OVERRIDE=true;
include("includes/connection.inc.php");

include("assets/layout/public_header.php");

$r=system_post('/users/changepassword',array("email" => $_REQUEST['email']));


?>

<section id="login-section">

    <div class="container-fluid p-remove">

        <div class="row no-gutters">

            <div class="col-lg-6 col-md-12 bg-blue d-none d-xs-none d-sm-none d-md-none d-lg-block d-xl-block">

                <div class="img-box">
 

                </div>

            </div>

            <div class="col-lg-6 col-md-12">

             <div class="formbox verify-box">

                <h2>Reset Your <span class="theme-y-clr">Password</span></h2>

                <p>Please enter the email associated with your system Account to receive a verification code to your mobile number.</p>

                <form action="reset-complete.php" class="form-settings verification-form systemform" method="post">
                <input type=hidden name=email value="<?=$_REQUEST['email'];?>">
                <div class="form-group">
                <label for="verification-code" >Verification Code</label>
                    <input class="form-control" name=code required type="text" id="verification-code">
            </div>
            <div class="form-group">
                <label for="new-password" >New Password</label>
                   <input class="form-control" name=password type="password"  required id="new-password">
                
            </div>
            <div class="form-group ">
                <label for="confirm-new-password">Confirm New Password</label>
                    <input class="form-control" name=password2 type="password"  required  id="confirm-new-password">
            </div>
            <div class="account-text">
            <p class="mt-3 mb-2 text-center xsm-text">Password must be a minimum of 8 characters and contain at least one uppercase letter, lowercase letter, number, and special character.</p>
  
            </div>
            <button type=submit class="btn btn-xl lg-blue-btn mt-4 mb-4">Submit Password Reset</button>

                </form>

             </div>

            </div>

        </div>

    </div>

</section>


<?php
include("assets/layout/public_footer.php");
?>