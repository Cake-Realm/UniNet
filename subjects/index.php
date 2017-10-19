<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

/*
Checks the subject parameter is provided to the current page.
If it is not provided, redirect to homepage, otherwise convert
it from URL format to database format and check it exists in the
database. If it doesn't exist, redirect to homepage.
*/
if (!isset($_GET["subjectName"]) || empty($_GET["subjectName"]))
{
	header("LOCATION: /");
	die();
}
else
{
	if ($_GET["subjectName"][strlen($_GET["subjectName"]) - 1] == "/")
		$_GET["subjectName"] = substr($_GET["subjectName"], 0, -1);
	
	$_GET["subjectName"] = str_replace("-", " ", $_GET["subjectName"]);
	$tSubject = get_subject_by_name($_GET["subjectName"]);
	if ($tSubject == false)
	{
		header("LOCATION: /");
		die();
	}
}

$tCategory = get_category_from_id($tSubject["categoryId"]);
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?php echo $tSubject["name"]; ?> | University Network</title>

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
					<li><a href="/categories/<?php echo str_replace(" ", "-", strtolower($tCategory["name"]));?>/"><?php echo $tCategory["name"]; ?></a></li>
					<li class="active"><?php echo $tSubject["name"]; ?></li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row category">
				<!-- Info Blocks -->
				<div class="col-md-12">
					<div class="headline"><h2><?php echo $tSubject["name"]; ?></h2></div>
					<p><?php echo $tSubject["description"]; ?></p>
					<?php
					$infoBlockColours = array("sea", "red", "brown", "green", "dark", "blue", "yellow", "aqua", "purple", "orange");
					$modules = get_modules_from_subject($tSubject["subjectId"]);
					if ($modules == false)
					{
						echo '<div class="alert alert-danger fade in"><strong>Oh snap!</strong> There are currently no modules added for this subject.</div>';
					}
					else
					{
						$j = 0;
						foreach ($modules as $module)
						{
							if ($j % 2 == 0)
								echo '<div class="row">';
							
						 ?>
						<div class="col-md-6">
							<div class="content-boxes-v3 margin-bottom-10 md-margin-bottom-20">
								<i class="icon-custom icon-sm rounded-x icon-bg-<?php echo $infoBlockColours[$i]; ?> fa fa-<?php echo $tCategory["icon"]; ?>"></i>
								<div class="content-boxes-in-v3">
									<h3><a href="/modules/<?php echo str_replace(" ", "-", strtolower($module["name"])); ?>/p1"> <?php echo $module["name"]; ?></a> <small>(<?php echo number_format(count_questions_in_module($module["moduleId"])); ?>)</small></h3>
									<p><?php echo $module["description"]; ?></p>
								</div>
							</div>
						</div>
						<?php
							if ($j % 2 == 1)
								echo '</div>';
							
							$i++;
							if ($i == 10)
								$i = 0;
							$j++;
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
