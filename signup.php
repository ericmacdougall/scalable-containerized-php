<?php
$_OVERRIDE=true;
include("includes/connection.inc.php");
include("assets/layout/public_header.php");
$country_json = file_get_contents("includes/countries.json");

$countries = json_decode($country_json, true);

$ru="";
if(isset($_REQUEST['ru']) and $_REQUEST['ru'] != "") {
	$ru=$_REQUEST['ru'];
} else {
	if(isset($_COOKIE['ru'])) {
		$ru=$_COOKIE['ru'];
	}
}
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
                    <h2>Create a <span class="theme-y-clr">SYSTEM Account</span></h2>
                    <form class="form-settings signup-form SYSTEMform" action="verify-phone.php" method="post">
			<input type=hidden name=referrerUsername value="<?=$ru;?>">
                        <div class="form-group">

                            <label for="birthdate">Birthdate</label>
                            <input class="form-control" type="date" value="yyyy-mm-dd" id="example-date-input"
                                name=birthday required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="inputphone" class="mt-3 mb-2 small-text float-left">Phone Number</label>
                            <input class="form-control" required type=phone name=phone minlength=11
                                placeholder="+12501231234">
                        </div>
                        <div class="form-group">
                            <label class="mb-2 mt-3 small-text float-left">Country:</label>
                            <select class=form-control id=country onkeyup=loadstates() onchange=loadstates() name=country required>
                                <option value="">--Select Country--</option>
                                <?php
			                    foreach ($countries as $k => $v) { ?>
                                <option value="<?= $v['alpha3']; ?>|<?= urlencode($v['name']); ?>"><?= $v['name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div id=statesdiv></div>
                        </div>
                        <div class="checkbox mb-3">
                        </div>
                        <button style='display:none' id=continue class="btn btn-lg btn-primary btn-block" type=submit
                            class="btn btn-xl btn-primary" value="Verify Phone">Request Verification Code</button>
                   


                    </form>


                </div>

            </div>

        </div>

    </div>

</section>


<script>
	function loadstates() {
		$("#countinue").hide();
		if ($("#country").val() != "") {
			$.get("ajax/states.php?country=" + $("#country").val(), function(rt) {
				$("#statesdiv").html(rt);
				$("#continue").show();
			});
		}
	}
</script>
<?php
include("assets/layout/public_footer.php");
?>
<script>
$(function() {
	setTimeout(function() { loadstates(); },100);
});
</script>