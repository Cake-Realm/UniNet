<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Universities | University Network</title>

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
					<li class="active">Universities</li>
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
					$infoBlockColours = array("sea", "red", "brown", "green", "dark", "blue", "yellow", "aqua", "purple", "orange");
					$i = 0;
					foreach (get_countries() as $cCountry)
					{
						$j = 0;
						echo '<div class="headline"><h2>'.$cCountry["name"].'</h2></div>';
						foreach (get_universities_in_country($cCountry["countryId"]) as $cUni)
						{
							$uniCount = count_users_at_university($cUni["uniId"]);
							if ($j % 2 == 0)
								echo '<div class="row">';
							?>
							<div class="col-md-6">
								<div class="content-boxes-v3 margin-bottom-10 md-margin-bottom-20">
									<i class="icon-custom icon-sm rounded-x icon-bg-<?php echo $infoBlockColours[$i]; ?> fa fa-university"></i>
									<div class="content-boxes-in-v3">
										<h3><a href="/university/<?php echo str_replace(" ", "-", strtolower($cUni["name"])); ?>/"> <?php echo $cUni["name"]; ?></a> <small>(<?php echo number_format($uniCount); ?>)</small></h3>
										<p>This university has <?php echo number_format($uniCount); ?> student<?php if ($uniCount != 1) echo 's'; ?> registered with this site.</p>
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
