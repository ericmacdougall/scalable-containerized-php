<?php
$_OVERRIDE=true;
include("includes/connection.inc.php");
include("assets/layout/public_header.php");
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

                <form action="reset-password-code.php" class="form-settings verification-form systemform">

                <div class="form-group">

                        <label for="email">Email</label>

                        <input type="email" name="email" class="form-control" id="email" placeholder="info@gmail.com" required>

                    </div>

                    <button type="submit" class="btn">Send Verification Code</button>

                    

                </form>

             </div>

            </div>

        </div>

    </div>

</section>

<?php
include("assets/layout/public_footer.php");
