<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (is_logged_in())
{
	header("LOCATION: /");
	die();
}


if (isset($_POST["email"]))
{
	$email = htmlspecialchars($_POST["email"]);
	if (!does_email_exist($email))
		$erMsg = "Sorry, that email is not registered with us.";
	else
	{
		do
		{
			$resetCode = sprintf("%06d", mt_rand(1, 999999999999));
		}
		while (get_user_from_reset_code($resetCode) !== FALSE);
		update_user_field_by_email($email, "resetCode", $resetCode);
		
		$jUser = get_user_from_reset_code($resetCode);
		mail($email, "Reset your University Network account password.", "Hello " . $jUser["username"] . ", your password reset link is http://universitynetwork.co.uk/forgot/" . $resetCode . " . If you did not attempt to reset your password, you can disregard this email.", "From: no-reply@unvierstynetwork.co.uk");
		$gdMsg = "The reset password email has been sent!";
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Forgot | University Network</title>
	
	<?php include_once("../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="../assets/css/pages/page_log_reg_v1.css">
</head>

<body>
	<div class="wrapper">
		<?php include_once("../assets/php/navbar.php"); ?>

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row">
				<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
					<form class="reg-page" method="POST">
						<div class="reg-header">
							<h2>Forgot your password</h2>
						</div>
						<?php
						if (isset($erMsg))
							echo '<div class="alert alert-danger fade in"><strong>Error!</strong> '.$erMsg.'</div>';
						if (isset($gdMsg))
							echo '<div class="alert alert-success fade in"><strong>Success!</strong> '.$gdMsg.'</div>';
						?>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
							<input type="email" placeholder="Email" name="email" class="form-control" required>
						</div>

						<div class="row">
							<div class="col-md-12">
								<button class="btn-u pull-right" type="submit">Send reset email</button>
							</div>
						</div>

						<hr style="margin: 15px 0px;">

						<h4>Remember your Password?</h4>
						<p><a class="color-green" href="/login/">Click here</a> to login.</p>
					</form>
				</div>
			</div><!--/row-->
		</div><!--/container-->
		<!--=== End Content Part ===-->

		<?php include_once("../assets/php/footer.php"); ?>
	</div><!--/wrapper-->

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
	<?php
	if (isset($runCountdown))
	{
		echo 'var timeToGo = '.($_SESSION["loginTimer"] - time()).';';
	?>
	window.setInterval(function(){
		var txt;
		if (timeToGo > -1)
		{
			if (timeToGo == 0)
				document.getElementById("timeMsg").outerHTML = "";
			else if (timeToGo == 1)
				txt = "1 second";
			else if (timeToGo < 60)
				txt = timeToGo + " seconds";
			else
				txt = Math.floor(timeToGo / 60) + " minutes";
			 
			 document.getElementById("timeRem").innerText = txt;
			 
			 timeToGo--;
		}
	}, 1000);			
	<?php
	}
	?>
	
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
