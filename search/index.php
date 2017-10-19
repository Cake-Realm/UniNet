<?php
$pageSize = 10;
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

if (isset($_GET["search"]) && isset($_GET["page"]) && !empty($_GET["page"]))
{
	$pageNum = $_GET["page"];
	$ansCount = count_items_in_search($search);
	if ($ansCount == 0)
		$numPages = 1;
	else
		$numPages = ceil($ansCount / $pageSize);
	
	if (!is_numeric($pageNum) || $pageNum < 1 || $pageNum > $numPages)
	{
		header("LOCATION: /search/");
		die();
	}
	$search = htmlspecialchars(str_replace("-", " ", clean_text_for_url3($_GET["search"])));
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Search | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/page_search_inner.css">
</head>

<body>
	<div class="wrapper">
		<?php include_once("../assets/php/navbar.php"); ?>

		<!--=== Search Block Version 2 ===-->
		<div class="search-block-v2">
			<div class="container">
				<div class="col-md-6 col-md-offset-3">
					<h2>Find Your Answers</h2>
					<form method="POST">
						<div class="input-group">
							<input type="text" class="form-control" name="search" placeholder="Search keywords related to your question..."<?php if (isset($search) && !empty($search)) echo " value='$search'";?>>
							<span class="input-group-btn">
								<button class="btn-u" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div><!--/container-->
		<!--=== End Search Block Version 2 ===-->

		<?php
		if (isset($search) && !empty($search))
		{
			$results = search($search, $pageNum, $pageSize);
		?>
		<!--=== Search Results ===-->
		<div class="container s-results margin-bottom-50">
			<span class="results-number"><?php if ($results == false) echo '0'; else echo number_format(count($results)); ?> results</span>
			
			<?php
			if ($results != false)
			{
				foreach ($results as $result)
				{
					$cModule = get_module_by_id($result["moduleId"]);
					$cUser = get_user_from_uid($result["userId"]);
				?>
				<!-- Begin Inner Results -->
				<div class="inner-results">
					<h3><a href="/modules/<?php echo str_replace(" ", "-", strtolower($cModule["name"])); ?>/<?php echo clean_text_for_url($result["question"]);?>/q<?php echo $result["questionId"]; ?>/p1"><?php echo $result["question"]; ?></a></h3>
					<p><?php echo $result["text"]; ?></p>
					<ul class="list-inline down-ul">
						<li><?php echo time_to_ago($result["timestamp"]); ?> - By <a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a></li>
						<li><?php if ($result["views"] == 1) echo "1 view"; else echo number_format($result["views"]) . " views"; ?></li>
					</ul>
				</div>
				<!-- Begin Inner Results -->

				<hr>
				<?php
				}
			}
			else
			{
				echo '<div class="alert alert-danger fade in"><strong>Oh snap!</strong> No results found for your search.</div>';
			}
			?>
			
			<div class="margin-bottom-30"></div>
			
			<?php
			draw_pageination(1, $numPages, $pageNum, "/search/p", "/".urlencode($search));
			?>
		</div><!--/container-->
		<!--=== End Search Results ===-->
		<?php
		}
		?>
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
