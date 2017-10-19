<?php
include_once("assets/php/config.php");
include_once("assets/php/functions.php");
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Home | University Network</title>
	
	<?php include_once("assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/page_search.css">
</head>

<body>
	<div class="wrapper">
		<?php include_once("assets/php/navbar.php"); ?>

		<!--=== Search Block ===-->
		<div class="search-block parallaxBg">
			<div class="container">
				<div class="col-md-6 col-md-offset-3">
					<h1>Find <span class="color-green">your</span> answers</h1>

					<form method="POST">
						<div class="input-group">
							<input type="text" class="form-control" name="search" placeholder="Search keywords related to your question...">
							<span class="input-group-btn">
								<button class="btn-u btn-u-lg" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div><!--/container-->
		<!--=== End Search Block ===-->

		<!--=== Content ===-->
		<div class="container content">
			<!-- Top Categories -->
			<div class="headline"><h2>Top Categories</h2></div>
			<div class="row category margin-bottom-20">
				<?php
				$infoBlockColours = array("sea", "red", "brown", "green", "dark", "blue", "yellow", "aqua", "purple", "orange");
				$i = 0;
				$categories = get_categories_popularity(10);
				?>
				<!-- Info Blocks -->
				<div class="col-md-4 col-sm-6">
					<?php					
					for ($j = 0; $j < 5; $j++)
					{
					?>
					<div class="content-boxes-v3 margin-bottom-10 md-margin-bottom-20">
						<i class="icon-custom icon-sm rounded-x icon-bg-<?php echo $infoBlockColours[$i]; ?> fa fa-<?php echo $categories[$j]["icon"]; ?>"></i>
						<div class="content-boxes-in-v3">
							<h3><a href="/categories/<?php echo str_replace(" ", "-", strtolower($categories[$j]["name"]));?>/"> <?php echo $categories[$j]["name"]; ?></a> <small>(<?php echo number_format(count_subjects_in_category($categories[$j]["categoryId"])); ?>)</small></h3>
							<p><?php echo $categories[$j]["description"]; ?></p>
						</div>
					</div>
					<?php
					$i++;
					}
					?>
				</div>
				<!-- End Info Blocks -->

				<!-- Info Blocks -->
				<div class="col-md-4 col-sm-6 md-margin-bottom-40">
					<?php
					for ($j = 5; $j < 10; $j++)
					{
					?>
					<div class="content-boxes-v3 margin-bottom-10 md-margin-bottom-20">
						<i class="icon-custom icon-sm rounded-x icon-bg-<?php echo $infoBlockColours[$i]; ?> fa fa-<?php echo $categories[$j]["icon"]; ?>"></i>
						<div class="content-boxes-in-v3">
							<h3><a href="/categories/<?php echo str_replace(" ", "-", strtolower($categories[$j]["name"]));?>/"> <?php echo $categories[$j]["name"]; ?></a> <small>(<?php echo number_format(count_subjects_in_category($categories[$j]["categoryId"])); ?>)</small></h3>
							<p><?php echo $categories[$j]["description"]; ?></p>
						</div>
					</div>
					<?php
					$i++;
					}
					?>
				</div>
				<!-- End Info Blocks -->

				<!-- Begin Section-Block -->
				<div class="col-md-4 col-sm-12" style="list-style: none;">
					<?php
					foreach (get_latest_questions(3) as $question)
					{
						$cModule = get_module_by_id($question["moduleId"]);
						$isAnswered = $question["answerId"] != NULL;
						
						$cUser = get_user_from_uid($question["userId"]);
					?>
					<li>
						<div class="content-boxes-v3 block-grid-v1 rounded">
							<img class="rounded-x pull-left block-grid-v1-img" src="assets/img/profile-images/<?php echo $cUser["image"]; ?>" alt="">
							<div class="content-boxes-in-v3">
								<h3><a href="/modules/<?php echo str_replace(" ", "-", strtolower($cModule["name"])); ?>/<?php echo clean_text_for_url($question["question"]);?>/q<?php echo $question["questionId"]; ?>/p1" style="font-size: 14px;"><?php echo $question["question"]; ?></a></h3>
								<p><?php if(strlen($question["text"]) > 80) echo substr($question["text"], 0, 77) . "..."; else echo $question["text"]; ?></p>
								<ul class="list-inline margin-bottom-5">
									<li>By <a class="color-green" href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a></li>
									<li><i class="fa fa-clock-o"></i> <?php echo time_to_ago($question["timestamp"]); ?></li>
								</ul>
								<ul class="list-inline block-grid-v1-add-info">
									<li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Views"><i class="fa fa-eye"></i> <?php echo number_format($question["views"]); ?></a></li>
									<li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Answers"><i class="fa fa-reply"></i> <?php echo number_format(count_answers_for_question($question["questionId"])); ?></a></li>
									<?php
									if ($isAnswered)
										echo '<li><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Answered"><i class="fa fa-check"></i></a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					}
					?>
				</div>
				<!-- End Section-Block -->
			</div>
			<!-- End Top Categories -->
		</div><!--/container-->
		<!--=== End Content ===-->

		<!--=== Parallax Counter ===-->
		<?php
		$statVals = get_homepage_stats();
		?>
		<div class="parallax-counter-v2 parallaxBg1">
			<div class="container">
				<ul class="row list-row">
					<li class="col-md-3 col-sm-6 col-xs-12 md-margin-bottom-30">
						<div class="counters rounded">
							<span class="counter"><?php echo $statVals[0]; ?></span>
							<h4>Questions</h4>
						</div>
					</li>
					<li class="col-md-3 col-sm-6 col-xs-12 md-margin-bottom-30">
						<div class="counters rounded">
							<span class="counter"><?php echo $statVals[1]; ?></span>
							<h4>Answers</h4>
						</div>
					</li>
					<li class="col-md-3 col-sm-6 col-xs-12 sm-margin-bottom-30">
						<div class="counters rounded">
							<span class="counter"><?php echo $statVals[2]; ?></span>
							<h4>Students</h4>
						</div>
					</li>
					<li class="col-md-3 col-sm-6 col-xs-12">
						<div class="counters rounded">
							<span class="counter"><?php echo $statVals[3]; ?></span>
							<h4>Modules</h4>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<!--=== End Parallax Counter ===-->

		<?php include_once("assets/php/footer.php"); ?>
	</div><!--/End Wrapepr-->

	<!-- JS Global Compulsory -->
	<script type="text/javascript" src="assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery/jquery-migrate.min.js"></script>
	<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!-- JS Implementing Plugins -->
	<script type="text/javascript" src="assets/plugins/back-to-top.js"></script>
	<script type="text/javascript" src="assets/plugins/smoothScroll.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery.parallax.js"></script>
	<script type="text/javascript" src="assets/plugins/counter/waypoints.min.js"></script>
	<script type="text/javascript" src="assets/plugins/counter/jquery.counterup.min.js"></script>
	<script type="text/javascript" src="assets/plugins/owl-carousel/owl-carousel/owl.carousel.js"></script>
	<!-- JS Page Level -->
	<script type="text/javascript" src="assets/js/app.js"></script>
	<script type="text/javascript" src="assets/js/plugins/owl-carousel.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			App.init();
			App.initCounter();
			App.initParallaxBg();
			OwlCarousel.initOwlCarousel();
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
