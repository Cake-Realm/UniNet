<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (!is_logged_in())
{
	header("LOCATION: /");
	die();
}

if (isset($_POST["passwordo"], $_POST["password"], $_POST["passwordc"]))
{
	$erNum = 0;
	
	$oldPassword = $_POST["passwordo"];
	$newPassword = $_POST["password"];
	$confirmPassword = $_POST["passwordc"];
	
	$pasLength = check_string_length($newPassword, 8, 16);
	$pasSame = strcmp($newPassword, $confirmPassword) == 0;
	
	if ($myUser["password"] != crypt($oldPassword, $myUser["password"]))
	{
		$erMsg[$erNum] = "Current password is incorrect.";
		$erNum++;
	}
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
		$password = crypt($newPassword, '$2y$12$' . $salt);
		update_user_field($myUser["uid"], "password", $password);
		$gdMsg = "Password has been changed.";
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Account Settings | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<style>
	.sidebar-nav-v1 > li.active, .sidebar-nav-v1 > li.active:hover {
		border-left: 2px solid #72c02c !important;
		background: #fff;
	}
	.sidebar-nav-v1 > li.active > a {
		color: #555;
	}
	</style>
</head>

<body>
	<div class="wrapper">
		<?php include_once("../assets/php/navbar.php"); ?>

		<!--=== Breadcrumbs ===-->
		<div class="breadcrumbs">
			<div class="container">
				<ul class="pull-right breadcrumb">
					<li><a href="/">Home</a></li>
					<li><a href="/profile/<?php echo clean_text_for_url_username($myUser["username"]); ?>/">Profile</a></li>
					<li class="active">Settings</li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->
		
		<!--=== Settings ===-->
		<div class="container content">
			<div class="row col-md-10 col-md-offset-1">
				<!-- Content -->
				<div class="col-md-3">
					<ul class="list-group sidebar-nav-v1" id="sidebar-nav">
						<!-- Profile -->
						<li class="list-group-item"><a href="/settings/">Profile</a></li>
						<!-- End Profile -->
						
						<!-- Account -->
						<li class="list-group-item active"><a href="/settings/account/">Account</a></li>
						<!-- End Account -->
					</ul>
				</div>
				<div class="col-md-9">
					<form method="POST" class="sky-form">
						<header>Change password</header>
						<fieldset>
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
							if (isset($gdMsg))
							{
								?>
								<div class="alert alert-success fade in alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>Success!</strong> <?php echo $gdMsg; ?>
								</div>
								<?php
							}
							?>
							
							<section>
								<label class="input">
									<i class="icon-append fa fa-lock"></i>
									<input type="password" name="passwordo" maxlength="16" placeholder="Current Password" id="password" required>
									<b class="tooltip tooltip-bottom-right">Enter your current password</b>
								</label>
							</section>
							
							<section>
								<label class="input">
									<i class="icon-append fa fa-lock"></i>
									<input type="password" name="password" maxlength="16" placeholder="Password" id="password" required>
									<b class="tooltip tooltip-bottom-right">Enter your new password</b>
								</label>
							</section>

							<section>
								<label class="input">
									<i class="icon-append fa fa-lock"></i>
									<input type="password" name="passwordc" maxlength="16" placeholder="Confirm password" required>
									<b class="tooltip tooltip-bottom-right">Re-type your new password to prevent misspelling</b>
								</label>
							</section>
						</fieldset>
						<footer>
							<button type="submit" class="btn-u">Update Password</button>
						</footer>
					</form>
				</div>
				<!-- End Content -->
			</div>
		</div>
		<!--=== End Settings ===-->

		<?php include_once("../assets/php/footer.php"); ?>
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
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/plugins/placeholder-IE-fixes.js"></script>
	<![endif]-->
</body>
</html>
