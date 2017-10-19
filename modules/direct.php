<?php
$pageSize = 5;
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

/*
Checks the module, question and page parameters are provided to the current page.
If it is not provided, redirect to homepage, otherwise convert
it from URL format to database format and check it exists in the
database. If it doesn't exist, redirect to homepage.
*/
if (!isset($_GET["moduleName"]) || empty($_GET["moduleName"]) || !isset($_GET["questionId"]) || empty($_GET["questionId"]) || !isset($_GET["page"]) || empty($_GET["page"]))
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
	if ($tModule == false)
	{
		header("LOCATION: /");
		die();
	}
	
	$tQuestion = get_question_from_id($_GET["questionId"]);
	
	if (isset($_GET["selectAnswer"]) && is_numeric($_GET["selectAnswer"]))
	{
		$selAnswer = get_answer_from_id($_GET["selectAnswer"]);
		if ($selAnswer == false || $selAnswer["questionId"] != $tQuestion["questionId"])
		{
			$selAnswer = null;
			unset($selAnswer);
		}
	}
	
	$pageNum = $_GET["page"];
	$ansCount = count_answers_in_question($tQuestion["questionId"], true);
	if ($ansCount == 0)
		$numPages = 1;
	else
		$numPages = ceil($ansCount / $pageSize);
	
	if ($tQuestion == false || $tQuestion["moduleId"] != $tModule["moduleId"] || !is_numeric($pageNum) || $pageNum < 1 || $pageNum > $numPages)
	{
		header("LOCATION: /");
		die();
	}
	
	if ($tQuestion["deleted"] == 1)
	{
		header("LOCATION: /");
		die();
	}
	
	if ($tQuestion["moderation"] == 1 && $tQuestion["userId"] != $myUser["uid"])
	{
		header("LOCATION: /");
		die();
	}
	
	update_question_field($tQuestion["questionId"], "views", $tQuestion["views"] + 1);
	
	$tSubject = get_subject_from_id($tModule["subjectId"]);
	$tCategory = get_category_from_id($tSubject["categoryId"]);
	
	$cUser = get_user_from_uid($tQuestion["userId"]);
	$userRep = get_reputation($cUser["uid"]);
}

if (isset($_POST["aId"], $_POST["qVote"]) && is_numeric($_POST["aId"]) && is_numeric($_POST["qVote"]) && ($_POST["qVote"] == 1 || $_POST["qVote"] == 0))
{
	if (is_logged_in())
	{
		if (does_answer_id_exist($_POST["aId"]))
		{
			$vtSt = get_answer_vote_status_by_me($_POST["aId"]);
			
			if ($vtSt == 0)
				create_vote($_POST["aId"], $_POST["qVote"]);
			else
				update_vote($_POST["aId"], $_POST["qVote"]);
		}
	}
	else
	{
		header("LOCATION: /login/");
		die();
	}
}

if (isset($_POST["type"], $_POST["id"], $_POST["text"]) && is_logged_in())
{
	/*
	Maybe add a spam check to make sure this IP hasn't created too
	many reports in x time
	*/
	
	$text = htmlspecialchars($_POST["text"]);
	$type = htmlspecialchars($_POST["type"]);
	$id = $_POST["id"];
	
	$txtLengthCheck = check_string_length($text, 0, 200);
	
	if (strcmp($type, "question") == 0)
		$idCorrect = get_question_from_id($id) !== FALSE;
	else if (strcmp($type, "answer") == 0)
		$idCorrect = get_answer_from_id($id) !== FALSE;
	
	if (isset($idCorrect) && $idCorrect && $txtLengthCheck == 0)
	{
		post_report($type, $id, $text);
	}
}

if (isset($_POST["answercontent"]) && is_logged_in())
{
	/*
	Maybe add a spam check to make sure this IP hasn't created too
	many answers recently
	*/
	
	$erNum = 0;

	$answercontent = htmlspecialchars($_POST["answercontent"]);
	$contentLength = check_string_length($answercontent, 5, 5000);

	if ($contentLength < 0)
	{
		$erMsg[$erNum] = "Answer needs to be atleast 5 characters long.";
		$erNum++;
	}
	if ($contentLength > 0)
	{
		$erMsg[$erNum] = "Answer needs to be less than or equal to 5000 characters long.";
		$erNum++;
	}
	
	if ($erNum == 0)
		post_answer($answercontent, $tQuestion["questionId"]);
}

if (isset($_POST["accepted"]) && is_logged_in() && $tQuestion["answerId"] == null && $tQuestion["userId"] == $myUser["uid"])
{
	$tAnswer = get_answer_from_id($_POST["accepted"]);
	if ($tAnswer !== false)
	{
		mark_accepted_answer($tQuestion["questionId"], $tAnswer["answerId"]);
		$tQuestion = get_question_from_id($_GET["questionId"]);
		$cUser = get_user_from_uid($tQuestion["userId"]);
	}
}

if (isset($_POST["remove"]) && is_logged_in() && ($myUser["usergroup"] == ADMIN || $myUser["usergroup"] == MODERATOR))
{
	$tAnswer = get_answer_from_id($_POST["remove"]);
	if ($tAnswer !== false)
	{
		delete_answer($tAnswer["answerId"]);
		$tQuestion = get_question_from_id($_GET["questionId"]);
	}
}
if (isset($_POST["removeQ"]) && is_logged_in() && ($myUser["usergroup"] == ADMIN || $myUser["usergroup"] == MODERATOR))
{
	$fQuestion = get_question_from_id($_POST["removeQ"]);
	if ($fQuestion !== false)
	{
		delete_question($fQuestion["questionId"]);
		header("LOCATION: /modules/" . str_replace(" ", "-", strtolower($tModule["name"])) . "/p1");
		die();
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?php echo $tQuestion["question"]; ?> | University Network</title>
	<meta property="og:title"              content="<?php echo $tQuestion["question"]; ?>" />
	<meta property="og:description"        content="<?php echo $tQuestion["text"]; ?>" />

	<?php include_once("../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/profile.css">
	<style>
	.subtitle {
		display: block;
		margin: 10px 0 10px 0;
		border-bottom: 1px dotted #e4e9f0;
	}
	.subtitle h4 {
		margin: 0 0 -2px 0;
		padding-bottom: 2px;
		display: inline-block;
		border-bottom: 2px solid #72c02c;
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
					<li><a href="/modules/<?php echo str_replace(" ", "-", strtolower($tModule["name"]));?>/p1"><?php echo $tModule["name"]; ?></a></li>
					<li class="active" data-toggle="tooltip" data-placement="left" title="<?php echo $tQuestion["question"]; ?>"><?php if(strlen($tQuestion["question"]) > 25) echo substr($tQuestion["question"], 0, 22) . "..."; else echo $tQuestion["question"]; ?></li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->
		
		<!--=== Content Part ===-->
		<div class="container content profile">
			<div class="row category">
				<!-- Info Blocks -->
				<div class="col-md-12">
					<div class="headline"><h2><?php echo $tQuestion["question"]; ?></h2><?php
					if ($myUser["usergroup"] == ADMIN || $myUser["usergroup"] == MODERATOR)
					{
						echo '<form method="POST" style="margin-bottom: 5px; display: inline; padding-right: 5px; float: right;"><input type="hidden" name="removeQ" value="'.$tQuestion["questionId"].'"><button class="btn-u btn-u-red btn-u-xs"><i class="fa fa-trash"></i> Delete Question</button></form>';
					}?></div>
					<?php
					if ($tQuestion["moderation"] == 1)
					{
					?>
					<div class="alert alert-warning fade in alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Warning!</strong> This question is being reviewed by moderators. It is currently not publicly visible and only visible to you. If the moderator denies this question then it will automatically be deleted, otherwise it will be visible to everyone.
					</div>
					<?php
					}
					?>
					<div class="row">
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4" style="border-right: 1px solid #eee;">
							<a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/">
								<img class="img-circle img-responsive profile-img margin-bottom-20" src="/assets/img/profile-images/<?php echo $cUser["image"]; ?>" style="max-width: 100px; margin: 0 auto" width="100%" height="100%" alt="">
							</a>

							<h3 class="heading-xs" style="text-align: center;"><strong><a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a></strong></h3>
							
							<h3 class="heading-xs" style="margin-top: 0px;">Reputation <span class="pull-right"><?php echo number_format($userRep); ?></span></h3>
							<div class="progress progress-u progress-xxs">
							<?php
							if ($userRep < 0)
								$neg = true;
							?>
								<div style="width: <?php echo (abs($userRep) % 1000) / 10; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo (abs($userRep) % 1000) / 10; ?>" role="progressbar" class="progress-bar progress-bar-<?php if (isset($neg)) echo 'red'; else echo 'u'; ?>">
								</div>
							</div>
						</div>
						<div class="col-lg-10 col-md-9 col-sm-9 col-xs-8">
							<p><?php echo parse_to_html(nl2br($tQuestion["text"])); ?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="margin-top: -20px;">
							<ul class="footer-socials list-inline text-right">
								<?php share_social_links_this_page(false);
								if (is_logged_in())
								{
								?>
								<li class="report-icon">
									<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="updateReport('question', '<?php echo $tQuestion["questionId"]; ?>')">
										<i class="fa fa-flag" data-toggle="tooltip" data-placement="top" title="Report" data-original-title="Report"></i>
									</a>
								</li>
								<?php
								}
								?>
							</ul>
							<hr style="margin: 15px 0px;">
						</div>
					</div>
					<?php
					if (isset($selAnswer) && $selAnswer != null)
					{
					?>
					<div class="alert alert-warning fade in">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Notice!</strong> You are viewing a single answer for this question.<a class="pull-right btn-u btn-u-xs" style="margin-right: 5px;" href="/modules/<?php echo str_replace(" ", "-", strtolower($tModule["name"])); ?>/<?php echo clean_text_for_url($tQuestion["question"]);?>/q<?php echo $tQuestion["questionId"]; ?>/p1"><i class="fa fa-comment"></i> View all the comments</a>
					</div>
					<?php
					}
					?>
					<div class="row">
						<div class="col-md-12">
							<?php
							if ($tQuestion["answerId"] != null)
							{
								$tQAnswerShow = true;
								if (isset($selAnswer) && $selAnswer != null)
								{
									$tQAnswerShow = false;
									if ($selAnswer["answerId"] == $tQuestion["answerId"])
										$tQAnswerShow = true;
								}
								
								if ($tQAnswerShow)
								{
								$approvedAnswer = get_answer_from_id($tQuestion["answerId"]);
								$aaUser = get_user_from_uid($approvedAnswer["userId"]);
								$voteStatus = get_answer_vote_status_by_me($tQuestion["answerId"]);
							?>
							<div class="media media-v2 media-tick-main" style="border: 1px solid #72c02c;">
								<div class="pull-left media-voting">
									<div class="media-voting-main">
										<form method="POST" id="vote-form-a-<?php echo $approvedAnswer["answerId"]; ?>">
											<input type="hidden" name="qVote" value="1">
											<input type="hidden" name="aId" value="<?php echo $approvedAnswer["answerId"]; ?>">
											<i onclick="document.getElementById('vote-form-a-<?php echo $approvedAnswer["answerId"]; ?>').submit();" class="fa fa-chevron-up <?php if ($voteStatus == 1) echo 'media-voted-up'; else echo 'media-voting-up';?>" aria-hidden="true"></i>
										</form>
										<span class="media-voting-value"><?php echo number_format(calculate_answer_vote_value($approvedAnswer["answerId"])); ?></span>
										<form method="POST" id="vote-form-b-<?php echo $approvedAnswer["answerId"]; ?>">
											<input type="hidden" name="qVote" value="0">
											<input type="hidden" name="aId" value="<?php echo $approvedAnswer["answerId"]; ?>">
											<i onclick="document.getElementById('vote-form-b-<?php echo $approvedAnswer["answerId"]; ?>').submit();" class="fa fa-chevron-down <?php if ($voteStatus == -1) echo 'media-voted-down'; else echo 'media-voting-down';?>" aria-hidden="true"></i>
										</form>
									</div>
									<a href="/profile/<?php echo clean_text_for_url_username($aaUser["username"]); ?>/">
										<img class="media-object rounded-x" src="/assets/img/profile-images/<?php echo $aaUser["image"]; ?>" alt="">
									</a>
								</div>
								<div class="media-body">
									<h4 class="media-heading">
										<strong><a href="/profile/<?php echo clean_text_for_url_username($aaUser["username"]); ?>/"><?php echo $aaUser["username"]; ?></a></strong>
										<small><?php
										if ($myUser["usergroup"] == ADMIN || $myUser["usergroup"] == MODERATOR)
										{
											echo '<form method="POST" style="margin-bottom: 5px; display: inline; padding-right: 5px;"><input type="hidden" name="remove" value="'.$approvedAnswer["answerId"].'"><button class="btn-u btn-u-red btn-u-xs"><i class="fa fa-trash"></i> Delete</button></form>';
										}
										echo time_to_ago($approvedAnswer["timestamp"]);
										?></small>
									</h4>
									<i class="fa fa-check media-tick" aria-hidden="true"></i>
									<p><?php echo parse_to_html(nl2br($approvedAnswer["text"])); ?></p>
								</div>
								<?php
								if (is_logged_in())
								{
								?>
								<ul class="footer-socials list-inline text-right no-margin">
									<li>
										<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="updateReport('answer', '<?php echo $approvedAnswer["answerId"]; ?>')">
											<i class="fa fa-flag" data-toggle="tooltip" data-placement="top" title="" data-original-title="Report"></i>
										</a>
									</li>
								</ul>
								<?php
								}
								?>
							</div>
							<?php
								}
							}
							$answers = get_answers_for_question($tQuestion["questionId"], $pageNum, $pageSize, $tQuestion["answerId"]);
							if ($answers != false)
							{
								foreach ($answers as $answer)
								{
									if ($answer["answerId"] != $tQuestion["answerId"])
									{
										$answerShow = true;
										if (isset($selAnswer) && $selAnswer != null)
										{
											$answerShow = false;
											if ($selAnswer["answerId"] == $answer["answerId"])
												$answerShow = true;
										}
										if ($answerShow)
										{
										$tUser = get_user_from_uid($answer["userId"]);
										$voteStatus = get_answer_vote_status_by_me($answer["answerId"]);
									?>
									<div class="media media-v2 media-tick-main">
										<div class="pull-left media-voting">
											<div class="media-voting-main">											
												<form method="POST" id="vote-form-a-<?php echo $answer["answerId"]; ?>">
													<input type="hidden" name="qVote" value="1">
													<input type="hidden" name="aId" value="<?php echo $answer["answerId"]; ?>">
													<i onclick="document.getElementById('vote-form-a-<?php echo $answer["answerId"]; ?>').submit();" class="fa fa-chevron-up <?php if ($voteStatus == 1) echo 'media-voted-up'; else echo 'media-voting-up';?>" aria-hidden="true"></i>
												</form>
												<span class="media-voting-value"><?php echo number_format(calculate_answer_vote_value($answer["answerId"])); ?></span>
												<form method="POST" id="vote-form-b-<?php echo $answer["answerId"]; ?>">
													<input type="hidden" name="qVote" value="0">
													<input type="hidden" name="aId" value="<?php echo $answer["answerId"]; ?>">
													<i onclick="document.getElementById('vote-form-b-<?php echo $answer["answerId"]; ?>').submit();" class="fa fa-chevron-down <?php if ($voteStatus == -1) echo 'media-voted-down'; else echo 'media-voting-down';?>" aria-hidden="true"></i>
												</form>
											</div>
											<a href="/profile/<?php echo clean_text_for_url_username($tUser["username"]); ?>/">
												<img class="media-object rounded-x" src="/assets/img/profile-images/<?php echo $tUser["image"]; ?>" alt="">
											</a>
										</div>
										<div class="media-body">
											<h4 class="media-heading">
												<strong><a href="/profile/<?php echo clean_text_for_url_username($tUser["username"]); ?>/"><?php echo $tUser["username"]; ?></a></strong>
												<small><?php
												if ($myUser["usergroup"] == ADMIN || $myUser["usergroup"] == MODERATOR)
												{
													echo '<form method="POST" style="margin-bottom: 5px; display: inline; padding-right: 5px;"><input type="hidden" name="remove" value="'.$answer["answerId"].'"><button class="btn-u btn-u-red btn-u-xs"><i class="fa fa-trash"></i> Delete</button></form>';
												}
												if ($tQuestion["answerId"] == null && $tQuestion["userId"] == $myUser["uid"])
												{
													echo '<form method="POST" style="margin-bottom: 5px; display: inline;"><input type="hidden" name="accepted" value="'.$answer["answerId"].'"><button class="btn-u btn-u-xs"><i class="fa fa-check"></i> Mark as accepted answer</button></form>';
												}
												echo '<p class="text-right">'.time_to_ago($answer["timestamp"]).'</p>';
												?></small>
											</h4>
											<p><?php echo parse_to_html(nl2br($answer["text"])); ?></p>
										</div>
										<?php
										if (is_logged_in())
										{
										?>
										<ul class="footer-socials list-inline text-right no-margin">
											<li>
												<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="updateReport('answer', '<?php echo $answer["answerId"]; ?>')">
													<i class="fa fa-flag" data-toggle="tooltip" data-placement="top" title="" data-original-title="Report"></i>
												</a>
											</li>
										</ul>
										<?php
										}
										?>
									</div>
									<?php
										}
									}
								}
							}
							?>
						</div>
					</div>
					<hr style="margin: 15px 0px;">
					<div class="row">
						<div class="col-md-12">
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
							<?php if (!is_logged_in()) echo '<p>You must be <a href="/login/">login</a> to post an answer.</p>';?>
							<form class="sky-form" method="POST">
								<label class="textarea">
									<button class="answer-button btn btn-default" type="submit"<?php if (!is_logged_in()) echo ' disabled';?>><i class="fa fa-reply"></i> Send Reply</button>
									<i class="icon-prepend fa fa-comment"></i>
									<textarea rows="5" name="answercontent" maxlength="5000" placeholder="Enter your answer..." style="padding-right: 120px;"<?php if (!is_logged_in()) echo ' disabled';?>></textarea>
								</label>
								<div class="note"><strong>Note:</strong> Allows use of {b}<b>Bold</b>{/b}, {i}<i>Italics</i>{/i}, {u}<u>Underline</u>{/u}, {subtitle}<span style="border-bottom: 2px solid #72c02c;">Subtitle</span>{/subtitle}, {code}<code>Code</code>{/code}</div>
							</form>
						</div>
					</div>
				</div>
				<!-- End Info Blocks -->
				
				<?php
				$tURL = "/modules/" . str_replace(" ", "-", strtolower($tModule["name"])) . "/" . clean_text_for_url($tQuestion["question"]) . "/q" .  $tQuestion["questionId"] . "/p";
				draw_pageination(1, $numPages, $pageNum, $tURL);
				?>
			</div>
		</div><!--/container-->
		<!--=== End Content Part ===-->
		<?php
		if (is_logged_in())
		{
		?>
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="POST" id="reportForm">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel4">Report</h4>
						</div>
						<div id="reportBody" class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="type" id="reportType" value="">
									<input type="hidden" name="id" id="reportValue" value="">
									<textarea class="form-control" rows="5" name="text" placeholder="Describe why you are reporting this question/answer..." maxlength="200"></textarea>
									<hr style="margin: 15px 0px;">
									<div class="alert alert-info fade in alert-dismissable">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<strong>Note:</strong> This function is to be used to report spam, advertising messages, and problematic (harassment, fighting, or rude) posts, anything that breaks the rules. Any false reports will be result in an penalty for wasting a moderator's time.
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Close</button>
							<button type="submit" id="reportButton" class="btn-u btn-u-primary">Send Report</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
		}
		include_once("../assets/php/footer.php"); ?>
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
			
			$('#reportForm').on('submit', function(e) {
				e.preventDefault();
				$.ajax({
					url : $(this).attr('action') || window.location.pathname,
					type: "POST",
					data: $(this).serialize(),
					success: function (data) {
						document.getElementById("reportButton").style.display = "none";
						document.getElementById("reportBody").innerHTML = '<div class="alert alert-success fade in alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Success!</strong> The report has been sent.</div>';
					},
					error: function (jXHR, textStatus, errorThrown) {
						alert("Failed to send report.");
					}
				});
			});
		});
		
		function updateReport(type, value)
		{
			document.getElementById("reportType").value = type;
			document.getElementById("reportValue").value = value;
		}
	</script>
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/plugins/placeholder-IE-fixes.js"></script>
	<![endif]-->
</body>
</html>
