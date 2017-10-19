<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (!isset($_GET["uniName"]) || empty($_GET["uniName"]))
{
	header("LOCATION: /");
	die();
}
else
{
	if ($_GET["uniName"][strlen($_GET["uniName"]) - 1] == "/")
		$_GET["uniName"] = substr($_GET["uniName"], 0, -1);
	
	$_GET["uniName"] = str_replace("-", " ", $_GET["uniName"]);
	$tUniversity = get_university_by_name($_GET["uniName"]);
	if ($tUniversity == false)
	{
		header("LOCATION: /");
		die();
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?php echo $tUniversity["name"]; ?> | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
</head>

<body>
	<div class="wrapper">
		<?php include_once("../assets/php/navbar.php"); ?>
		
		<!--=== Breadcrumbs ===-->
		<div class="breadcrumbs">
			<div class="container">
				<ul class="pull-right breadcrumb">
					<li><a href="/">Home</a></li>
					<li><a href="/university/">Universities</a></li>
					<li class="active"><?php echo $tUniversity["name"]; ?></li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row category">
				<!-- Info Blocks -->
				<div class="col-md-12">
					<?php
					$subjects = get_user_subjects_in_university($tUniversity["uniId"]);
					if ($subjects == false)
						echo '<div class="alert alert-danger fade in"><strong>Oh snap!</strong> There are currently no users registered to this University.</div>';
					else
					{
						foreach ($subjects as $subject)
						{
							if ($subject["subjectId"] == 0)
								echo '<div class="headline"><h2>Other Course</h2></div>';
							else
							{
								$cSubject = get_subject_from_id($subject["subjectId"]);
								echo '<div class="headline"><h2>'.$cSubject["name"].'</h2></div>';
							}
							echo '<div class="row">';
							$users = get_users_from_university_in_subject($tUniversity["uniId"], $subject["subjectId"]);
							foreach ($users as $user)
							{
								$userRep = get_reputation($user["uid"]);
								?>
								<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="row">
												<div class="col-md-4 col-sm-4 col-xs-4 text-center">
													<img class="img-circle" width="70%" height="70%" src="/assets/img/profile-images/default.jpg" alt="">
												</div>
												<div class="col-md-8 col-sm-8 col-xs-8">
													<h4 class="no-margin"><a href="/profile/<?php echo clean_text_for_url_username($user["username"]); ?>/"><?php echo $user["username"]; ?></a></h4>
													<p class="no-margin">Joined <?php echo date("jS F Y", $user["register"]); ?>.</p>
													<p class="no-margin">Reputation: <?php echo number_format($userRep); ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
							echo '</div>';
						}
					}
					?>
				</div>
				<!-- End Info Blocks -->
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
