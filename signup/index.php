<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (is_logged_in())
	header("LOCATION: /");

if (isset($_POST["username"], $_POST["email"], $_POST["university"], $_POST["course"], $_POST["password"], $_POST["cPassword"]))
{
	$erNum = 0;
	
	$username = htmlspecialchars($_POST["username"]);
	$email = htmlspecialchars($_POST["email"]);
	$password = $_POST["password"];
	$university = $_POST["university"];
	$course = $_POST["course"];
	
	$usernameREGEX = preg_match("/^[A-Za-z0-9_]+$/", $username);
	$usrLength = check_string_length($username, 4, 16);
	$usrUsed = does_username_exist($username);
	
	$emailREGEX = strpos($email, '@') !== FALSE;
	$emailLength = check_string_length($email, 3, 254);
	$emailUsed = does_email_exist($email);
	
	$pasLength = check_string_length($password, 8, 16);
	$pasSame = strcmp($password, $_POST["cPassword"]) == 0;
	$uniExist = does_university_exist($university) || $university == 0;
	$courseExist = does_subject_exist($course) || $course == 0;
	
	/*
	Maybe add a spam check to make sure this IP hasn't created too
	many accounts
	*/
	
	if (!isset($_POST["agreement"]))
	{
		$erMsg[$erNum] = "You must accept the terms and conditions.";
		$erNum++;
	}
	
	//USERNAME
	if ($usernameREGEX == 0 || $usernameREGEX == FALSE)
	{
		$erMsg[$erNum] = "Username can only contain alphanumeric characters and _.";
		$erNum++;
	}
	if ($usrLength < 0)
	{
		$erMsg[$erNum] = "Username needs to be atleast 4 characters long.";
		$erNum++;
	}
	if ($usrLength > 0)
	{
		$erMsg[$erNum] = "Username needs to be less than or equal to 16 characters long.";
		$erNum++;
	}
	if ($usrUsed)
	{
		$erMsg[$erNum] = "The entered username is already in use.";
		$erNum++;
	}
	if (is_banned_word($username))
	{
		$erMsg[$erNum] = "The entered username is a forbidden word.";
		$erNum++;
	}
	
	//EMAIL
	if ($emailREGEX == 0 || $emailREGEX == FALSE)
	{
		$erMsg[$erNum] = "Email must contain a @ symbol.";
		$erNum++;
	}
	if ($emailLength < 0)
	{
		$erMsg[$erNum] = "Email needs to be atleast 3 characters long.";
		$erNum++;
	}
	if ($emailLength > 0)
	{
		$erMsg[$erNum] = "Email needs to be less than or equal to 254 characters long.";
		$erNum++;
	}
	if ($emailUsed)
	{
		$erMsg[$erNum] = "The entered email is already in use.";
		$erNum++;
	}
	
	//PASSWORD
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
	
	//UNIVERSITY
	if (!$uniExist)
	{
		$erMsg[$erNum] = "The selected University does not exist.";
		$erNum++;
	}
	
	//COURSE
	if (!$courseExist)
	{
		$erMsg[$erNum] = "The selected Course does not exist.";
		$erNum++;
	}
	
	//REGISTRATION
	if ($erNum == 0)
	{
		register($username, $email, $password, $university, $course);
		header("LOCATION: /signup/verify/");
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Sign Up | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/page_log_reg_v1.css">
	<link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
	<style>
	.select2-container--default .select2-selection--single {
		border-radius: 0px;
		border-color: #ccc;
	}
	</style>
</head>

<body>
	<div class="wrapper">
		<?php include_once("../assets/php/navbar.php"); ?>

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					<form class="reg-page" method="POST">
						<div class="reg-header">
							<h2>Register a new account</h2>
							<p>Already Signed Up? Click <a href="/login/" class="color-green">Sign In</a> to login your account.</p>
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
						<label>Username <span class="color-red">*</span></label>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" name="username" maxlength="16" required="" class="form-control" <?php if (isset($_POST["username"])) echo 'value="'.htmlspecialchars($_POST["username"]).'"';?>>
						</div>

						<label>University</label>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-university"></i></span>
							<select class="form-control" name="university">
								<option value="0">None / Other</option>
								<?php
								foreach (get_countries() as $cCountry)
								{
									echo '<optgroup label="'.$cCountry["name"].'">';
									foreach (get_universities_in_country($cCountry["countryId"]) as $cUni)
									{
										echo '<option value="'.$cUni["uniId"].'"';
										if ($_POST["university"] == $cUni["uniId"])
											echo ' selected';
										echo '>'.$cUni["name"].'</option>';
									}
									echo '</optgroup>';
								}
								?>
							</select>
						</div>
						
						<label>Course</label>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span>
							<select class="form-control" name="course">
								<option value="0">Other</option>
								<?php
								foreach (get_categories_alphabetically() as $cCategory)
								{
									echo '<optgroup label="'.$cCategory["name"].'">';
									foreach (get_subjects_from_category($cCategory["categoryId"]) as $cSubject)
									{
										echo '<option value="'.$cSubject["subjectId"].'"';
										if ($_POST["course"] == $cSubject["subjectId"])
											echo ' selected';
										echo '>'.$cSubject["name"].'</option>';
									}
									echo '</optgroup>';
								}
								?>
							</select>
						</div>

						<label>Email Address <span class="color-red">*</span></label>
						<div class="input-group margin-bottom-20">
							<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
							<input type="email" name="email" maxlength="254" required="" class="form-control" <?php if (isset($_POST["email"])) echo 'value="'.htmlspecialchars($_POST["email"]).'"';?>>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<label>Password <span class="color-red">*</span></label>
								<div class="input-group margin-bottom-20">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" name="password" maxlength="16" required="" class="form-control">
								</div>
							</div>
							<div class="col-sm-6">
								<label>Confirm Password <span class="color-red">*</span></label>
								<div class="input-group margin-bottom-20">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" name="cPassword" maxlength="16" required="" class="form-control">
								</div>
							</div>
						</div>

						<hr style="margin: 0px 0px 15px 0px;">

						<div class="row">
							<div class="col-lg-6 checkbox">
								<label>
									<input type="checkbox" name="agreement">
									I accept the <a href="/policy/terms" class="color-green">Terms and Conditions</a>
								</label>
							</div>
							<div class="col-lg-6 text-right">
								<button class="btn-u" type="submit">Register</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!--/container-->
		<!--=== End Content Part ===-->

		<?php include_once("../assets/php/footer.php"); ?>
	</div>

	<!-- JS Global Compulsory -->
	<script type="text/javascript" src="/assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/plugins/jquery/jquery-migrate.min.js"></script>
	<script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!-- JS Implementing Plugins -->
	<script type="text/javascript" src="/assets/plugins/back-to-top.js"></script>
	<script type="text/javascript" src="/assets/plugins/smoothScroll.js"></script>
	<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
	<!-- JS Page Level -->
	<script type="text/javascript" src="/assets/js/app.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			App.init();
			$('select').select2();
		});
	</script>
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/plugins/placeholder-IE-fixes.js"></script>
	<![endif]-->
</body>
</html>
