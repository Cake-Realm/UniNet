<?php
$pageSize = 10;
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

/*
Checks the module parameter is provided to the current page.
If it is not provided, redirect to homepage, otherwise convert
it from URL format to database format and check it exists in the
database. If it doesn't exist, redirect to homepage.
*/
if (!isset($_GET["moduleName"]) || empty($_GET["moduleName"]) || !isset($_GET["page"]) || empty($_GET["page"]))
{
	header("LOCATION: /");
	die();
}
else
{
	if ($_GET["moduleName"][strlen($_GET["moduleName"]) - 1] == "/")
		$_GET["moduleName"] = substr($_GET["moduleName"], 0, -1);
	
	$_GET["moduleName"] = str_replace("-", " ", $_GET["moduleName"]);
	$tModule = get_module_by_name($_GET["moduleName"]);
	
	$pageNum = $_GET["page"];
	
	$ansCount = count_questions_in_module($tModule["moduleId"]);
	if ($ansCount == 0)
		$numPages = 1;
	else
		$numPages = ceil($ansCount / $pageSize);
		
	if ($tModule == false || !is_numeric($pageNum) || $pageNum < 1 || $pageNum > $numPages)
	{
		header("LOCATION: /");
		die();
	}
	
	$tSubject = get_subject_from_id($tModule["subjectId"]);
	$tCategory = get_category_from_id($tSubject["categoryId"]);
	
	if (is_logged_in() && isset($_POST["subject"], $_POST["content"]))
	{
		/*
		Maybe add a spam check to make sure this IP hasn't created too
		many questions recently
		*/
		
		$erNum = 0;
	
		$subject = htmlspecialchars($_POST["subject"]);
		$content = htmlspecialchars($_POST["content"]);
		
		$subjectLength = check_string_length($subject, 10, 100);
		$contentLength = check_string_length($content, 10, 500);
		
		if ($subjectLength < 0)
		{
			$erMsg[$erNum] = "Question Title needs to be atleast 10 characters long.";
			$erNum++;
		}
		if ($subjectLength > 0)
		{
			$erMsg[$erNum] = "Question Title needs to be less than or equal to 100 characters long.";
			$erNum++;
		}
		if ($contentLength < 0)
		{
			$erMsg[$erNum] = "Question Content needs to be atleast 10 characters long.";
			$erNum++;
		}
		if ($contentLength > 0)
		{
			$erMsg[$erNum] = "Question Content needs to be less than or equal to 500 characters long.";
			$erNum++;
		}
		
		if ($erNum == 0)
			post_question($subject, $content, $tModule["moduleId"]);
	}
}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?php echo $tModule["name"]; ?> | University Network</title>

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
	.question-panel .panel-title span {
		font-size: 24px;
		float: right;
	}
	.question-panel .question-form textarea {
		margin-top: 10px;
	}
	.question-panel .question-form .col-md-10 {
		padding-right: 0px;
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
					<li><a href="/categories/<?php echo str_replace(" ", "-", strtolower($tCategory["name"]));?>/"><?php echo $tCategory["name"]; ?></a></li>
					<li><a href="/subjects/<?php echo str_replace(" ", "-", strtolower($tSubject["name"]));?>/"><?php echo $tSubject["name"]; ?></a></li>
					<li class="active"><?php echo $tModule["name"]; ?></li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->

		<!--=== Content Part ===-->
		<div class="container content">
			<div class="row category">
				<!-- Info Blocks -->
				<div class="col-md-12">
					<div class="headline"><h2><?php echo $tModule["name"]; ?></h2></div>
					<p><?php echo $tModule["description"]; ?></p>
					<hr style="margin: 15px 0;">
					<?php
					if (is_logged_in())
					{
					?>
					<div class="panel-group acc-v1" id="accordion-1">
						<div class="panel panel-default question-panel">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="accordion-toggle<?php if (!isset($erMsg)) echo ' collapsed';?>" data-toggle="collapse" data-parent="#accordion-1" href="#collapse-One" aria-expanded="<?php if (isset($erMsg)) echo 'true'; else echo 'false'?>">
										Ask a question<span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
									</a>
								</h4>
							</div>
							<div id="collapse-One" class="panel-collapse collapse<?php if (isset($erMsg)) echo ' in';?>" aria-expanded="<?php if (isset($erMsg)) echo 'true'; else echo 'false'?>">
								<div class="panel-body">
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
									<form class="question-form" method="POST">
										<div class="row">
											<div class="col-md-10 col-sm-8 col-xs-6">
												<input type="text" class="form-control" name="subject" placeholder="What's your question?" maxlength="100"<?php if (isset($_POST["subject"])) echo ' value="'.htmlspecialchars($_POST["subject"]).'"';?> required>
											</div>
											<div class="col-md-2 col-sm-4 col-xs-6">
												<button type="submit" class="btn-u btn-block">Post question</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<textarea class="form-control" rows="5" name="content" placeholder="Describe your question in more detail..." maxlength="500" required><?php if (isset($_POST["content"])) echo htmlspecialchars($_POST["content"]);?></textarea>
											</div>
										</div>
										<div style="margin-top: 6px; padding: 0 1px; font-size: 11px; line-height: 15px; color: #999;"><strong>Note:</strong> Allows use of {b}<b>Bold</b>{/b}, {i}<i>Italics</i>{/i}, {u}<u>Underline</u>{/u}, {subtitle}<span style="border-bottom: 2px solid #72c02c;">Subtitle</span>{/subtitle}, {code}<code>Code</code>{/code}</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					else
					{
					?>
					<div class="panel panel-default question-panel">
						<div class="panel-heading">
							<h4 class="panel-title">
								You must <a href="/signup/">sign up</a> or <a href="/login/">log in</a> to post a question.<span class="hidden-xs"><i class="fa fa-link" aria-hidden="true"></i></span>
							</h4>
						</div>
					</div>
					<?php
					}
					?>
					<hr style="margin: 15px 0;">
					
					<?php
					$questions = get_questions_from_module($tModule["moduleId"], $pageNum, $pageSize);
					if ($questions == false)
						echo '<div class="alert alert-danger fade in"><strong>Oh snap!</strong> There are currently no questions asked for this module.</div>';
					else
					{
						foreach ($questions as $question)
						{
							$cModule = get_module_by_id($question["moduleId"]);
							$isAnswered = $question["answerId"] != NULL;
							
							$cUser = get_user_from_uid($question["userId"]);
						?>
						<div class="funny-boxes funny-boxes-extension funny-boxes-left-<?php if ($isAnswered) echo 'green'; else echo 'blue';?> bg-color-white">
							<div class="row">
								<div class="col-md-12">
									<img class="img-circle" src="/assets/img/profile-images/<?php echo $cUser["image"]; ?>">
									<div class="funny-boxes-extension-sub">
										<div class="funny-boxes-extension-stats hidden-xs">
											<div class="funny-boxes-extension-block">
												<div class="funny-boxes-extension-value"><span class="color-<?php if ($isAnswered) echo 'light-green'; else echo 'blue';?>"><i class="fa fa-<?php if ($isAnswered) echo 'check-';?>square-o" aria-hidden="true"></i></span></div>
												<div class="funny-boxes-extension-label"><span class="color-<?php if ($isAnswered) echo 'light-green'; else echo 'blue';?>"><?php if ($isAnswered) echo 'answered'; else echo 'unanswered';?></span></div>
											</div>
											<div class="funny-boxes-extension-block">
												<div class="funny-boxes-extension-value"><?php echo number_format(count_answers_for_question($question["questionId"])); ?></div>
												<div class="funny-boxes-extension-label">answers</div>
											</div>
											<div class="funny-boxes-extension-block">
												<div class="funny-boxes-extension-value"><?php echo number_format($question["views"]);?></div>
												<div class="funny-boxes-extension-label">views</div>
											</div>
										</div>
										<h2><a href="/modules/<?php echo str_replace(" ", "-", strtolower($cModule["name"])); ?>/<?php echo clean_text_for_url($question["question"]);?>/q<?php echo $question["questionId"]; ?>/p1"><?php echo $question["question"];?></a></h2>
										<p>Asked by <a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a>, <?php echo time_to_ago($question["timestamp"]); ?>.</p>
									</div>
								</div>
							</div>
						</div>
						<?php
						}
					}
					?>
				</div>
				<!-- End Info Blocks -->
				
				<?php
				$tURL = "/modules/" . str_replace(" ", "-", strtolower($tModule["name"])) . "/p";
				draw_pageination(1, $numPages, $pageNum, $tURL);
				?>
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
