<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

/*
Checks the name parameters are provided to the current page.
If it is not provided, redirect to homepage
*/
if (!isset($_GET["name"]) || empty($_GET["name"]) || !is_logged_in())
{
	header("LOCATION: /");
	die();
}
else
{
	$cUser = get_user_from_username($_GET["name"]);
	
	if ($cUser == false || $cUser["uid"] != $myUser["uid"])
	{
		header("LOCATION: /");
		die();
	}
	
	$userUni = get_university_from_id($cUser["uniId"]);
}

if (isset($_POST["acceptrequest"]))
{
	$acUser = get_user_from_uid($_POST["acceptrequest"]);
	if ($acUser != false)
	{
		if ($myUser["uid"] != $acUser["uid"] && !are_friends($myUser["uid"], $acUser["uid"]) && is_request_active($acUser["uid"], $myUser["uid"]))
			request_friend($myUser["uid"], $acUser["uid"]);
	}
}
else if (isset($_POST["rejectrequest"]))
{
	$acUser = get_user_from_uid($_POST["rejectrequest"]);
	if ($acUser != false)
	{
		if ($myUser["uid"] != $acUser["uid"] && !are_friends($myUser["uid"], $acUser["uid"]) && is_request_active($acUser["uid"], $myUser["uid"]))
			remove_request_friend($acUser["uid"], $myUser["uid"]);
	}
}
else if (isset($_POST["removefriend"]))
{
	$acUser = get_user_from_uid($_POST["removefriend"]);
	if ($acUser != false)
	{
		if ($myUser["uid"] != $acUser["uid"] && are_friends($myUser["uid"], $acUser["uid"]))
			remove_friendship($myUser["uid"], $acUser["uid"]);
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?php echo $cUser["username"]; ?> Friends | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/profile.css">
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
					<li><a href="/university/<?php echo str_replace(" ", "-", strtolower($userUni["name"])); ?>/"><?php echo $userUni["name"]; ?></a></li>
					<li><a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a></li>
					<li class="active">Friends</li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->
		
		<!--=== Profile ===-->
		<div class="container content profile">
			<div class="row">
				<!-- Profile Content -->
				<div class="col-md-12">
					<div class="profile-body">
						<?php
						$friendReqs = get_friend_requests($cUser["uid"]);
						if (count($friendReqs) > 0)
						{
						?>
						<div class="panel panel-profile">
							<div class="panel-heading overflow-h">
								<h2 class="panel-title heading-sm">Friend Request<?php if (count($friendReqs) > 1) echo 's'; ?> (<?php echo number_format(count($friendReqs));?>)<span class="pull-right"><i class="fa fa-user-plus"></i></span></h2>
							</div>
							<div class="panel-body">
								<?php
								foreach ($friendReqs as $friendReq)
								{
									$friendReqDetails = get_user_from_uid($friendReq["senderId"]);
								?>
								<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4" style="text-align: center;">
									<a href="/profile/<?php echo clean_text_for_url_username($friendReqDetails["username"]); ?>/">
										<img src="/assets/img/profile-images/<?php echo $friendReqDetails["image"]; ?>" width="100%" style="border-radius: 10px 10px 0px 0px; max-width: 100px;"></img>
									</a>
									<a class="btn-u btn-u-xs btn-u-dark" style="border-radius: 0px; padding: 5px; width: 100%; max-width: 100px;" href="/profile/<?php echo clean_text_for_url_username($friendReqDetails["username"]); ?>/">
										<div><?php echo $friendReqDetails["username"]; ?></div>
									</a>
									<form method="POST">
										<input type="hidden" name="acceptrequest" value="<?php echo $friendReqDetails["uid"]; ?>">
										<button type="submit" class="btn-u btn-u-xs btn-u-green" style="border-radius: 0px; padding: 5px; width: 100%; max-width: 100px;"><i class="fa fa-check"></i> Accept</button>
									</form>
									<form method="POST">
										<input type="hidden" name="rejectrequest" value="<?php echo $friendReqDetails["uid"]; ?>">
										<button type="submit" class="btn-u btn-u-xs btn-u-default" style="border-radius: 0px 0px 10px 10px; padding: 5px; width: 100%; max-width: 100px;"><i class="fa fa-close"></i> Reject</button>
									</form>
								</div>
								<?php
								}
								?>
							</div>
						</div>
						<hr style="margin: 15px 0px;">
						<?php
						}
						$friends = get_friends($cUser["uid"]);
						if (count($friends) == 0)
							echo '<div class="alert alert-danger fade in"><strong>Oh snap!</strong> You currently have no friends, you can add people as a friend via their profile.</div>';
						else
						{
						?>
						<div class="panel panel-profile">
							<div class="panel-heading overflow-h">
								<h2 class="panel-title heading-sm">Friends (<?php echo number_format(count($friends));?>)<span class="pull-right"><i class="fa fa-users"></i></span></h2>
							</div>
							<div class="panel-body">
								<?php
								foreach ($friends as $friend)
								{
									if ($friend["userId1"] == $cUser["uid"])
										$friendId = $friend["userId2"];
									else
										$friendId = $friend["userId1"];
									$friendDetails = get_user_from_uid($friendId);
								?>
								<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4" style="text-align: center;">
									<a href="/profile/<?php echo clean_text_for_url_username($friendDetails["username"]); ?>/">
										<img src="/assets/img/profile-images/<?php echo $friendDetails["image"]; ?>" width="100%" style="border-radius: 10px 10px 0px 0px; max-width: 100px;"></img>
									</a>
									<a class="btn-u btn-u-xs btn-u-dark" style="border-radius: 0px; padding: 5px; width: 100%; max-width: 100px;" href="/profile/<?php echo clean_text_for_url_username($friendDetails["username"]); ?>/">
										<div><?php echo $friendDetails["username"]; ?></div>
									</a>
									<form method="POST">
										<input type="hidden" name="removefriend" value="<?php echo $friendDetails["uid"]; ?>">
										<button type="submit" class="btn-u btn-u-xs btn-u-red" style="border-radius: 0px 0px 10px 10px; padding: 5px; width: 100%; max-width: 100px;"><i class="fa fa-close"></i> Remove</button>
									</form>
								</div>
								<?php
								}
								?>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				</div>
				<!-- End Profile Content -->
			</div>
		</div>
		<!--=== End Profile ===-->

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
