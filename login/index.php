<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (is_logged_in())
{
	if (isset($_GET["do"]) && $_GET["do"] == "logout")
	{
		update_user_field($_SESSION["uid"], "rememberMeHash", NULL);
		unset($_SESSION["uid"]);
		setcookie("m3kvyJS7q2GFyb89", "", time()-3600, "/");
		
		header("LOCATION: /login/");
		die();
	}
	else
	{
		header("LOCATION: /");
		die();
	}
}
else
{
	if (isset($_GET["do"]) && $_GET["do"] == "logout")
	{
		header("LOCATION: /login/");
		die();
	}
}

if (isset($_SESSION["loginTimer"]))
	{
		if ($_SESSION["loginTimer"] - time() < 0)
		{
			unset($_SESSION["loginAttempts"]);
			unset($_SESSION["loginTimer"]);
		}
	}

if (isset($_POST["username"], $_POST["password"]))
{
	if (!isset($_SESSION["loginAttempts"]) || $_SESSION["loginAttempts"] < 5)
	{
		$loginEr = login(htmlspecialchars($_POST["username"]), $_POST["password"], isset($_POST["rememberme"]));
		if ($loginEr == 3)
		{
			header("LOCATION: /signup/verify/");
			die();
		}
		else if ($loginEr == 0)
		{
			header("LOCATION: /");
			die();
		}
		else
			$erMsg = "Sorry, those details don't match. Check you've typed them correctly.";
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Login | University Network</title>
	
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
							<h2>Login to your account</h2>
						</div>
						<?php
						if (isset($_SESSION["acVer"]) && $_SESSION["acVer"] == true)
							echo '<div class="alert alert-success fade in"><strong>Success!</strong> Your account email has been verified. You can now login.</div>';
						if (isset($_SESSION["loginAttempts"]))
						{
							if ($_SESSION["loginAttempts"] == 5)
							{
								$runCountdown = true;
								echo '<div class="alert alert-danger fade in" id="timeMsg"><strong>Error!</strong> You have run out of login attempts. Please wait <span id="timeRem">15 minutes</span> before trying again.</div>';
							}
							else
								echo '<div class="alert alert-warning fade in"><strong>Warning!</strong> You have used '.$_SESSION["loginAttempts"].' out of 5 login attempts. After all 5 have been used, you will be unable to login for 15 minutes.</div>';
						}
						if (isset($erMsg) && $_SESSION["loginAttempts"] != 5)
							echo '<div class="alert alert-danger fade in"><strong>Error!</strong> '.$erMsg.'</div>';
						?>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" placeholder="Username" name="username" class="form-control">
						</div>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-lock"></i></span>
							<input type="password" placeholder="Password" name="password" class="form-control">
						</div>

						<div class="row">
							<div class="col-md-6 checkbox">
								<label><input type="checkbox" name="rememberme"> Stay signed in</label>
							</div>
							<div class="col-md-6">
								<button class="btn-u pull-right" type="submit">Login</button>
							</div>
						</div>

						<hr>

						<h4>Forget your Password ?</h4>
						<p><a class="color-green" href="/forgot/">Click here</a> to reset your password.</p>
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
