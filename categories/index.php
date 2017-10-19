<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

/*
Checks the category parameter is provided to the current page.
If it is not provided, redirect to homepage, otherwise convert
it from URL format to database format and check it exists in the
database. If it doesn't exist, redirect to homepage.
*/
if (!isset($_GET["categoryName"]) || empty($_GET["categoryName"]))
{
	header("LOCATION: /");
	die();
}
else
{
	if ($_GET["categoryName"][strlen($_GET["categoryName"]) - 1] == "/")
		$_GET["categoryName"] = substr($_GET["categoryName"], 0, -1);
	
	$_GET["categoryName"] = str_replace("-", " ", $_GET["categoryName"]);
	$tCategory = get_category_by_name($_GET["categoryName"]);
	if ($tCategory == false)
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
	<title><?php echo $tCategory["name"]; ?> | University Network</title>

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
					<li class="active"><?php echo $tCategory["name"]; ?></li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row category">
				<!-- Info Blocks -->
				<div class="col-md-12">
					<div class="headline"><h2><?php echo $tCategory["name"]; ?></h2></div>
					<p><?php echo $tCategory["description"]; ?></p>
					<?php
					$infoBlockColours = array("sea", "red", "brown", "green", "dark", "blue", "yellow", "aqua", "purple", "orange");
					foreach (get_subjects_from_category($tCategory["categoryId"]) as $subject)
					{
					?>
					<div class="content-boxes-v3 margin-bottom-10 md-margin-bottom-20">
						<i class="icon-custom icon-sm rounded-x icon-bg-<?php echo $infoBlockColours[$i]; ?> fa fa-<?php echo $tCategory["icon"]; ?>"></i>
						<div class="content-boxes-in-v3">
							<h3><a href="/subjects/<?php echo str_replace(" ", "-", strtolower($subject["name"])); ?>/"> <?php echo $subject["name"]; ?></a> <small>(<?php echo number_format(count_modules_in_subject($subject["subjectId"])); ?>)</small></h3>
							<p><?php echo $subject["description"]; ?></p>
						</div>
					</div>
					<?php
					$i++;
					if ($i == 10)
						$i = 0;
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
