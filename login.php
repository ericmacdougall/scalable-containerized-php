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

             <div class="formbox">

                <h2>Login to Your <span class="theme-y-clr">system Account</span></h2>
                <form  action="login-process.php" class="form-settings login-form systemform">
                <input type=hidden name=url value="<?php if(isset($_REQUEST['url'])) { echo $_REQUEST['url']; } ?>">
  
                    <div class="form-group">

                        <label for="email">Email</label>

                        <input type="email" class="form-control" name="email" id="inputEmail" placeholder="info@gmail.com" required>

                    </div>

                    <div class="form-group">

                        <label for="password">Password</label>

                        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="●●●●●●" required> 

                    </div>

                    <p>Your system Account is the same account for both the app and the website. If you have created an account on either you can login to both with the same login info.</p>

                    <button type="submit" class="btn">Login</button>

                    <div class="frgt text-right">

                        <a href="<?=$_HOST;?>/reset-password.php">Forgot Password?</a>

                    </div>

                </form>

                <div class="account-txt">

                    <a href="<?=$_HOST;?>/signup.php">Dont have a system account? Click here to sign up!</a>

                </div>

             </div>

            </div>

        </div>

    </div>

</section>

  <?php
include("assets/layout/public_footer.php");
?>

