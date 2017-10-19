<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (is_logged_in())
{
	header("LOCATION: /");
	die();
}

if (isset($_GET["resetCode"]))
{
	$resetCode = htmlspecialchars($_GET["resetCode"]);
	$pUser = get_user_from_reset_code($resetCode);
	if ($pUser == FALSE)
	{
		header("LOCATION: /");
		die();
	}
	
	if (isset($_POST["password"], $_POST["cpassword"]))
	{
		$erNum = 0;
		
		$password = $_POST["password"];
		
		$pasLength = check_string_length($password, 8, 16);
		$pasSame = strcmp($password, $_POST["cpassword"]) == 0;
		
		if (!$pasSame)
		{
			$erMsg[$erNum] = "Password and confirm password do not match.";
			$erNum++;
		}
		if ($pasLength > 0)
		{
			$erMsg[$erNum] = "Password needs to be less than or equal to 16 characters long.";
			$erNum++;
		}
		if ($pasLength < 0)
		{
			$erMsg[$erNum] = "Password needs to be atleast 8 characters long.";
			$erNum++;
		}
		
		if ($erNum == 0)
		{			
			$salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
			$password = crypt($password, '$2y$12$' . $salt);
			update_user_field($pUser["uid"], "password", $password);
			header("LOCATION: /login/");
		}
	}
}
else
{
	header("LOCATION: /");
	die();
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>New Password | University Network</title>
	
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
							<h2>Create new password</h2>
						</div>
						<?php
						if (isset($erMsg))
						{
							foreach ($erMsg as $cMsg)
							{
								?>
								<div class="alert alert-danger fade in alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>Error!</strong> <?php echo $cMsg; ?>
								</div>
								<?php
							}
						}
						?>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-lock"></i></span>
							<input type="password" placeholder="Password" name="password" class="form-control" required>
						</div>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-lock"></i></span>
							<input type="password" placeholder="Confirm Password" name="cpassword" class="form-control" required>
						</div>
						<div class="row">
							<div class="col-md-12">
								<button class="btn-u pull-right" type="submit">Save new password</button>
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
