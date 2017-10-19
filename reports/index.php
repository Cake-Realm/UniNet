<?php
$pageSize = 10;
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");
$erNum = 0;

if (!is_logged_in() || $myUser["usergroup"] == USER)
{
	header("LOCATION: /");
	die();
}
if (!isset($_GET["page"]) || empty($_GET["page"]))
{
	header("LOCATION: /reports/p1");
	die();
}

$pageNum = $_GET["page"];
$ansCount = count_reports();

if ($ansCount == 0)
	$numPages = 1;
else
	$numPages = ceil($ansCount / $pageSize);

if (!is_numeric($pageNum) || $pageNum < 1 || $pageNum > $numPages)
{
	header("LOCATION: /reports/p1");
	die();
}

if (isset($_POST["rID"]) && is_numeric($_POST["rID"]))
{
	delete_report($_POST["rID"]);
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Reports | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<style>
	.funny-boxes-extension {
		padding: 15px 20px 5px;
	}
	.funny-boxes-extension img.img-circle {
		width: 45px;
		float: left;
	}
	.funny-boxes-extension-border {
		border-top: 1px solid #ddd;
	}
	.funny-boxes-extension .funny-boxes-extension-sub {
		margin-left: 50px;
	}
	.funny-boxes-extension .funny-boxes-extension-sub .funny-boxes-extension-stats {
		float: right;
		margin-top: 5px;
	}
	.funny-boxes-extension .funny-boxes-extension-sub .funny-boxes-extension-stats .funny-boxes-extension-block {
		display: inline-block;
		text-align: center;
		padding: 5px;
		width: 80px;
	}
	.funny-boxes-extension .funny-boxes-extension-sub .funny-boxes-extension-stats .funny-boxes-extension-block .funny-boxes-extension-value {
		color: #666;
		line-height: 11px;
	}
	.funny-boxes-extension .funny-boxes-extension-sub .funny-boxes-extension-stats .funny-boxes-extension-block .funny-boxes-extension-label {
		color: #999;
	}
	.funny-boxes-extension .funny-boxes-extension-sub h2 {
		font-size: 14px;
		margin-bottom: 5px;
	}
	.funny-boxes-extension .funny-boxes-extension-sub h2 span {
		font-weight: bolder;
	}
	.funny-boxes-extension .funny-boxes-extension-sub p {
		font-size: 12px;
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
					<li class="active">Reports</li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->
		
		<!--=== Settings ===-->
		<div class="container content">
			<div class="row col-md-10 col-md-offset-1">
				<!-- Content -->
				<div class="col-md-12">
					<?php
					$reports = get_reports($pageNum, $pageSize);
					if ($reports != FALSE)
					{
						foreach ($reports as $report)
						{
							$cUser = get_user_from_uid($report["userId"]);
						?>
						<div class="funny-boxes funny-boxes-extension funny-boxes-left-<?php if ($report["type"] == 1) echo 'green'; else if ($report["type"] == 2) echo 'blue';?> bg-color-white">
							<div class="row">
								<div class="col-md-12">
									<img class="img-circle" src="/assets/img/profile-images/<?php echo $cUser["image"]; ?>">
									<form method="POST">
										<input type="hidden" name="rID" value="<?php echo $report["reportId"]; ?>">
										<button class="btn-u btn-u-xs pull-right" style="margin-top: 15px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
									</form>
									<div class="funny-boxes-extension-sub">
										<div class="funny-boxes-extension-stats hidden-xs">
											<div class="funny-boxes-extension-block">
												<div class="funny-boxes-extension-value"><span class="color-<?php if ($report["type"] == 1) echo 'green'; else if ($report["type"] == 2) echo 'blue';?>"><i class="fa fa-check-square-o" aria-hidden="true"></i></span></div>
												<div class="funny-boxes-extension-label"><span class="color-<?php if ($report["type"] == 1) echo 'green'; else if ($report["type"] == 2) echo 'blue';?>"><?php if ($report["type"] == 1) echo 'question'; else if ($report["type"] == 2) echo 'answer';?></span></div>
											</div>
											<div class="funny-boxes-extension-block">
												<div class="funny-boxes-extension-value"><?php if ($report["type"] == 1) echo number_format(count_answers_for_question($report["unquieId"])); else if ($report["type"] == 2) echo number_format(calculate_answer_vote_value($report["unquieId"]));?></div>
												<div class="funny-boxes-extension-label"><?php if ($report["type"] == 1) echo 'answers'; else if ($report["type"] == 2) echo 'votes';?></div>
											</div>
										</div>
										<h2><a target="_blank" href="<?php
										if ($report["type"] == 1)//Question
										{
											$question = get_question_from_id($report["unquieId"]);
											$cModule = get_module_by_id($question["moduleId"]);
											echo '/modules/'. str_replace(" ", "-", strtolower($cModule["name"])) . '/' . clean_text_for_url($question["question"]) . '/q' . $question["questionId"]. '/p1';
										}
										else if ($report["type"] == 2)//Answer
										{
											$cAnswer = get_answer_from_id($report["unquieId"]);
											$question = get_question_from_id($cAnswer["questionId"]);
											$cModule = get_module_by_id($question["moduleId"]);
											echo '/modules/'. str_replace(" ", "-", strtolower($cModule["name"])) . '/' . clean_text_for_url($question["question"]) . '/q' . $question["questionId"]. '/p1/a' . $cAnswer["answerId"];
										}										
										?>"><?php echo $report["content"]; ?></a></h2>
										<p>Asked by <a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a>, <?php echo time_to_ago($report["timestamp"]); ?>.</p>
									</div>
								</div>
							</div>
						</div>
						<?php
						}
					}
					else
						echo '<div class="alert alert-success fade in"><strong>Woo!</strong> There are currently no reports to be completed.</div>';
					?>
				</div>
				<?php
				$tURL = "/reports/p";
				draw_pageination(1, $numPages, $pageNum, $tURL);
				?>
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
