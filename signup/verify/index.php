<?php
include_once("../../assets/php/config.php");
include_once("../../assets/php/functions.php");

if (is_logged_in())
	header("LOCATION: /");

if (isset($_GET["emailCode"]))
	$_POST["emailCode"] = $_GET["emailCode"];
if (isset($_POST["emailCode"]))
{
	if (does_email_code_exist($_POST["emailCode"]))
	{
		$cUser = get_user_from_email_code($_POST["emailCode"]);
		update_user_field($cUser["uid"], "emailVerified", "1");
		$_SESSION["acVer"] = true;
		header("LOCATION: /login/");
	}
	else
		$erMsg = "The verification you entered does not exist.";
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Sign Up | University Network</title>

	<?php include_once("../../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/page_log_reg_v1.css">
</head>

<body>
	<div class="wrapper">
		<?php include_once("../../assets/php/navbar.php"); ?>

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					<form class="reg-page" method="POST">
						<div class="reg-header" style="margin-bottom: 15px;">
							<h2>Verify your account email</h2>
							<p>Check your email you signed up with for your verification code.</p>
						</div>
						<?php
						if (isset($erMsg))
						{
							?>
							<div class="alert alert-danger fade in alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>Error!</strong> <?php echo $erMsg; ?>
							</div>
							<?php
						}
						?>
						<label>Verification Code <span class="color-red">*</span></label>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-ticket"></i></span>
							<input type="text" placeholder="000000000000" name="emailCode" maxlength="12" required="" class="form-control" <?php if (isset($_POST["emailCode"])) echo 'value="'.htmlspecialchars($_POST["emailCode"]).'"';?>>
						</div>

						<hr style="margin: 0px 0px 15px 0px;">

						<div class="row">
							<div class="col-lg-6">
							</div>
							<div class="col-lg-6 text-right">
								<button class="btn-u" type="submit">Verify</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!--/container-->
		<!--=== End Content Part ===-->

		<?php include_once("../../assets/php/footer.php"); ?>
	</div>

	<!-- JS Global Compulsory -->
	<script type="text/javascript" src="/assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/plugins/jquery/jquery-migrate.min.js"></script>
	<script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!-- JS Implementing Plugins -->
	<script type="text/javascript" src="/assets/plugins/back-to-top.js"></script>
	<script type="text/javascript" src="/assets/plugins/smoothScroll.js"></script>
	<!-- JS Page Level -->
	<script type="text/javascript" src="/assets/js/app.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			App.init();
		});
	</script>
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/plugins/placeholder-IE-fixes.js"></script>
	<![endif]-->
</body>
</html>
