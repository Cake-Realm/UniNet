<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");

/*
Checks the name parameters are provided to the current page.
If it is not provided, redirect to homepage
*/
if (!isset($_GET["name"]) || empty($_GET["name"]))
{
	header("LOCATION: /");
	die();
}
else
{
	//From username or url depending
	$cUser = get_user_from_username($_GET["name"]);
	
	if ($cUser == false || !is_account_email_verified($cUser["uid"]))
	{
		header("LOCATION: /");
		die();
	}
	
	$userUni = get_university_from_id($cUser["uniId"]);
}

if (isset($_POST["addfriend"]))
{
	if (is_logged_in() && $myUser["uid"] != $cUser["uid"] && !are_friends($myUser["uid"], $cUser["uid"]) && !is_request_active($myUser["uid"], $cUser["uid"]))
		request_friend($myUser["uid"], $cUser["uid"]);
}
else if (isset($_POST["removefriendrequest"]))
{
	if (is_logged_in() && $myUser["uid"] != $cUser["uid"] && !are_friends($myUser["uid"], $cUser["uid"]) && is_request_active($myUser["uid"], $cUser["uid"]))
		remove_request_friend($myUser["uid"], $cUser["uid"]);
}
else if (isset($_POST["removefriend"]))
{
	if (is_logged_in() && $myUser["uid"] != $cUser["uid"] && are_friends($myUser["uid"], $cUser["uid"]))
		remove_friendship($myUser["uid"], $cUser["uid"]);
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?php echo $cUser["username"]; ?> | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<link rel="stylesheet" href="/assets/css/pages/profile.css">
	
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
	.award {
		text-align: center;
	}
	.award .award-upper-block {
		padding-bottom: 10px;
	}
	.award .award-upper-block img {
		width: 64px;
		height: 64px;
	}
	.award .award-upper-block h3 {
		font-size: 17px;
		font-weight: bold;
		color: #777;
		margin: 0px;
	}
	.award .award-upper-block p {
		font-size: 13px;
		color: #888;
	}
	.award .award-list .award-lower-block {
		height: 90px;
		color: #888;
		font-size: 12px;
	}
	.award .award-list .award-lower-block img {
		width: 48px;
		height: 48px;
	}
	.funny-boxes-top-blue {
		border-top: solid 2px #3498db;
	}
	.funny-boxes-top-green {
		border-top: solid 2px #72c02c;
	}
	.funny-boxes h2 a {
		color: #72c02c;
	}
	.funny-boxes-left-red {
		border-left: solid 2px #e74c3c;
	}
	.profile-main {
		display: inline-block;
		width: 100%;
		margin-bottom: 15px;
	}
	@media (min-width: 992px) {
		.profile-main-md {
			width: calc(100% - 100px);
		}
	}
	.availability-bubble {
		width: 18px;
		height: 18px;
		border: 2px solid #fff;
		border-radius: 50px;
		position: absolute;
		top: 76px;
		left: 73px;
	}
	.availability-online {
		background-color: #5fb611;
	}
	.availability-offline {
		background-color: #696969;
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
					<li><a href="/university/">Universities</a></li>
					<li><a href="/university/<?php echo str_replace(" ", "-", strtolower($userUni["name"])); ?>/"><?php echo $userUni["name"]; ?></a></li>
					<li class="active"><?php echo $cUser["username"]; ?></li>
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
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-profile">
									<div class="panel-body">
										<?php 
										$userSub = get_subject_from_id($cUser["subjectId"]);
										$userRep = get_reputation($cUser["uid"]);
										?>
										<div class="col-md-12">
											<div class="profile-bio">
												<img class="hidden-xs hidden-sm img-circle img-responsive profile-img margin-bottom-20" style="max-height: 80px; margin: 0 auto; display: inline-block; margin: -60px 15px 0px 0px;" src="/assets/img/profile-images/<?php echo $cUser["image"]; ?>" alt="">
												<div class="availability-bubble availability-<?php if (is_user_online($cUser["lastOnline"])) echo 'online'; else echo 'offline'; ?>"></div>
												<div class="profile-main profile-main-md">
													<h2><?php echo $cUser["username"]; ?></h2>
													<div class="pull-right">
														<?php
														if (is_logged_in() && $myUser["uid"] != $cUser["uid"] && !are_friends($myUser["uid"], $cUser["uid"]))
														{
															if (is_request_active($myUser["uid"], $cUser["uid"]))
															{
																?>
																<span>
																	<form method="POST">
																		<input type="hidden" name="removefriendrequest" value="1">
																		<button class="btn-u btn-u-xs btn-u-default"><i class="fa fa-close"></i> Friend request sent</button>
																	</form>
																</span>
																<?php
															}
															else
															{
																?>
																<span>
																	<form method="POST">
																		<input type="hidden" name="addfriend" value="1">
																		<button type="submit" class="btn-u btn-u-xs"><i class="fa fa-user-plus"></i> Add as a friend</button>
																	</form>
																</span>
																<?php
															}
														}
														if (is_logged_in() && $myUser["uid"] != $cUser["uid"] && are_friends($myUser["uid"], $cUser["uid"]))
														{
															?>
															<span>
																<form method="POST">
																	<input type="hidden" name="removefriend" value="1">
																	<button type="submit" class="btn-u btn-u-xs btn-u-red"><i class="fa fa-close"></i> Remove as friend</button>
																</form>
															</span>
															<?php
														}
														?>
														<span class="hidden-xs"><strong>Reputation:</strong> <?php echo number_format($userRep); ?></span>
													</div>
													<span><strong>Course:</strong> <?php if ($userSub == false) echo 'Other'; else echo '<a href="/subjects/' . str_replace(" ", "-", strtolower($userSub["name"])) . '/">'.$userSub["name"].'</a>'; ?></span>
													<span><strong>University:</strong> <?php if ($userUni == false) echo 'Other'; else echo '<a href="/university/' . str_replace(" ", "-", strtolower($userUni["name"])) .'/">' . $userUni["name"] . '</a>'; ?></span>
												</div>
												<div>
													<div class="hidden-xs progress progress-u progress-xxs">
													<?php
													if ($userRep < 0)
														$neg = true;
													?>
														<div style="width: <?php echo (abs($userRep) % 1000) / 10; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo (abs($userRep) % 1000) / 10; ?>" role="progressbar" class="progress-bar progress-bar-<?php if (isset($neg)) echo 'red'; else echo 'u'; ?>">
														</div>
													</div>
													<p><?php if ($cUser["bio"] == NULL) echo 'This user currently does not have a bio.'; else echo parse_to_html_simple(strip_banned_words($cUser["bio"])); ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!--/end row-->
						
						<?php
						$socialCount = count_user_social_links($cUser["uid"]);
						$awards = get_awards($cUser["uid"]);
						$friends = get_friends($cUser["uid"]);
						
						if ($socialCount > 0 || count($awards) > 0 || count($friends) > 0)
						{
						?>
						<hr style="margin: 15px 0px;">
						<div class="row">
							<?php
							if ($socialCount > 0 || count($friends) > 0)
							{
							?>
							<div class="<?php if (count($awards) > 0) echo 'col-md-7'; else echo 'col-md-12';?>">
								<?php
								if ($socialCount > 0)
								{
								?>
								<div class="panel panel-profile">
									<div class="panel-heading overflow-h">
										<h2 class="panel-title heading-sm">Social Links<span class="pull-right"><i class="fa fa-comments"></i></span></h2>
									</div>
									<div class="panel-body">
										<ul class="list-unstyled social-contacts-v2">
											<?php
											foreach (get_user_social_links($cUser["uid"]) as $cSLink)
											{
												$cSocial = get_social_info_from_id($cSLink["socialId"]);
												echo '<li><i class="rounded-x '.$cSocial["shortIcon"].' fa fa-'.$cSocial["icon"].'"></i> <a href="'.$cSocial["url"].$cSLink["name"].$cSocial["urlEnd"].'">'.$cSLink["name"].'</a></li>';
											}
											?>
										</ul>
									</div>
								</div>
								<?php
								}
								if (count($friends) > 0)
								{
								?>
								<div class="panel panel-profile" style="margin-top: 10px;">
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
											<a class="btn-u btn-u-xs btn-u-dark" style="border-radius: 0px 0px 10px 10px; padding: 5px; width: 100%; max-width: 100px;"  href="/profile/<?php echo clean_text_for_url_username($friendDetails["username"]); ?>/">
												<div><?php echo $friendDetails["username"]; ?></div>
											</a>
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
							<?php
							}
							if (count($awards) > 0)
							{
							?>
							<div class="<?php if ($socialCount == 0) echo 'col-md-12'; else echo 'col-md-5';?>">
								<div class="panel panel-profile">
									<div class="panel-heading overflow-h">
										<h2 class="panel-title heading-sm">Awards<span class="pull-right"><i class="fa fa-trophy"></i></span></h2>
									</div>
									<div class="panel-body award">
										<div class="col-md-10 col-md-offset-1 award-upper-block">
											<img id="award-image" src="" alt="" border="0" title="">
											<h3 id="award-title"></h3>
											<p id="award-subtext"></p>
										</div>
										<div class="col-md-1"></div>
										<div class="award-list">
										<?php
										foreach ($awards as $awardID)
										{
											$award = get_award_from_id($awardID["awardId"]);
											?>
											<div class="col-lg-3 col-md-4 col-sm-3 col-xs-6 award-lower-block" onmouseover="<?php echo "do_award('".js_escape($award["name"])."','".$award["info"]."','".$award["image"]."');";?>">
												<img src="/assets/img/awards/awd<?php echo $award["image"]; ?>.png" alt="<?php echo $award["name"]; ?>" border="0" title="<?php echo $award["name"]; ?>">
												<div><?php echo $award["name"]; ?></div>
											</div>
											<?php
										}
										?>
										</div>
									</div>
								</div>
							</div>
							<?php
							}
							?>
						</div><!--/end row-->
						<?php
						}
						?>
						<hr style="margin: 15px 0px;">
						<?php
						$recentReputation = get_reputation_history_for_user($cUser["uid"]);
						if ($recentReputation != false)
						{
						?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-profile">
									<div class="panel-heading overflow-h">
										<h2 class="panel-title heading-sm">Recent Reputation<span class="pull-right"><i class="fa fa-star"></i></span></h2>
									</div>
									<div class="panel-body">
										<?php
										foreach ($recentReputation as $cReputation)
										{
											$fUser = get_user_from_uid($cReputation["userId"]);
											$isPositive = $cReputation["amount"] >= 0;
										?>
										<div class="funny-boxes funny-boxes-extension funny-boxes-left-<?php if ($isPositive) echo 'green'; else echo 'red';?> bg-color-white">
											<div class="row">
												<div class="col-md-12">
													<div class="funny-boxes-extension-sub" style="margin: 0 !important;">
														<div class="funny-boxes-extension-stats">
															<div class="funny-boxes-extension-block">
																<div class="funny-boxes-extension-value"><?php if ($isPositive) echo '+'; echo number_format($cReputation["amount"]);?></div>
																<div class="funny-boxes-extension-label">Reputation</div>
															</div>
														</div>
														<h2><?php echo $cReputation["reason"]; ?></h2>
														<p>Earned <?php echo time_to_ago($cReputation["timestamp"]); ?>.</p>
													</div>
												</div>
											</div>
										</div>
										<?php
										}
										?>
									</div>
								</div>
							</div>
						</div><!--/end row-->
						<hr style="margin: 15px 0px;">
						<?php
						}
						
						
						$questions = get_recent_questions_from_user($cUser["uid"]);
						if ($questions != false)
						{
						?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-profile">
									<div class="panel-heading overflow-h">
										<h2 class="panel-title heading-sm">Recently Asked Questions<span class="pull-right"><i class="fa fa-question-circle"></i></span></h2>
									</div>
									<div class="panel-body">
										<?php
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
														<p>Asked by <a href=""><?php echo $cUser["username"]; ?></a>, <?php echo time_to_ago($question["timestamp"]); ?>.</p>
													</div>
												</div>
											</div>
										</div>
										<?php
										}
										?>
									</div>
								</div>
							</div>
						</div><!--/end row-->
						<hr style="margin: 15px 0px;">
						<?php
						}
						
						$answers = get_recent_answers_from_user($cUser["uid"]);
						if ($answers != false)
						{
						?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-profile">
									<div class="panel-heading overflow-h">
										<h2 class="panel-title heading-sm">Recently Answered Questions<span class="pull-right"><i class="fa fa-commenting"></i></span></h2>
									</div>
									<div class="panel-body">
										<?php
										foreach ($answers as $answer)
										{
											$cQuestion = get_question_from_id($answer["questionId"]);
											$cModule = get_module_by_id($cQuestion["moduleId"]);
											$isAnswered = $cQuestion["answerId"] != NULL;
											$isMyAnswerAccepted = $cQuestion["answerId"] == $answer["answerId"];
											
											$answerUser = get_user_from_uid($answer["userId"]);
											$questionUser = get_user_from_uid($cQuestion["userId"]);
											
											$voteStatus = get_answer_vote_status_by_me($answer["answerId"]);
										?>
										<div class="funny-boxes funny-boxes-extension funny-boxes-left-<?php if ($isAnswered) echo 'green'; else echo 'blue';?> bg-color-white">
											<div class="row">
												<div class="col-md-12">
													<img class="img-circle" src="/assets/img/profile-images/<?php echo $questionUser["image"]; ?>">
													<div class="funny-boxes-extension-sub">
														<div class="funny-boxes-extension-stats hidden-xs">
															<div class="funny-boxes-extension-block">
																<div class="funny-boxes-extension-value"><span class="color-<?php if ($isAnswered) echo 'light-green'; else echo 'blue';?>"><i class="fa fa-<?php if ($isAnswered) echo 'check-';?>square-o" aria-hidden="true"></i></span></div>
																<div class="funny-boxes-extension-label"><span class="color-<?php if ($isAnswered) echo 'light-green'; else echo 'blue';?>"><?php if ($isAnswered) echo 'answered'; else echo 'unanswered';?></span></div>
															</div>
															<div class="funny-boxes-extension-block">
																<div class="funny-boxes-extension-value"><?php echo number_format(count_answers_for_question($cQuestion["questionId"])); ?></div>
																<div class="funny-boxes-extension-label">answers</div>
															</div>
															<div class="funny-boxes-extension-block">
																<div class="funny-boxes-extension-value"><?php echo number_format($cQuestion["views"]);?></div>
																<div class="funny-boxes-extension-label">views</div>
															</div>
														</div>
														<h2><a href="/modules/<?php echo str_replace(" ", "-", strtolower($cModule["name"])); ?>/<?php echo clean_text_for_url($cQuestion["question"]);?>/q<?php echo $cQuestion["questionId"]; ?>/p1"><?php echo $cQuestion["question"];?></a></h2>
														<p>Asked by <a href=""><?php echo $questionUser["username"]; ?></a>, <?php echo time_to_ago($cQuestion["timestamp"]); ?>.</p>
													</div>
												</div>
											</div>
										</div>
										
										<div class="funny-boxes-top-<?php if ($isMyAnswerAccepted) echo 'green'; else echo 'blue';?>" style="width: 52px;"></div>
										<div class="funny-boxes funny-boxes-extension funny-boxes-left-<?php if ($isMyAnswerAccepted) echo 'green'; else echo 'blue';?> bg-color-white" style="margin-left: 50px;">
											<div class="row">
												<div class="col-md-12">
													<div class="pull-left media-voting">
														<div class="media-voting-main" style="font-size: 14px; width: 35px;">
															<i style="cursor:not-allowed;" class="fa fa-chevron-up <?php if ($voteStatus == 1) echo 'media-voted-up'; else echo 'media-voting-up';?>" aria-hidden="true"></i>
															<span class="media-voting-value" style="cursor:not-allowed; line-height: 14px;"><?php echo number_format(calculate_answer_vote_value($answer["answerId"])); ?></span>
															<i style="cursor:not-allowed;" class="fa fa-chevron-down <?php if ($voteStatus == -1) echo 'media-voted-down'; else echo 'media-voting-down';?>" aria-hidden="true"></i>
														</div>
													</div>
													<img class="img-circle" src="/assets/img/profile-images/<?php echo $answerUser["image"]; ?>" style="margin: 0px 10px 5px 0px;">
													<div class="funny-boxes-extension-sub">
														<h2><?php echo parse_to_html($answer["text"]);?></h2>
														<p>Answered by <a href=""><?php echo $answerUser["username"]; ?></a>, <?php echo time_to_ago($answer["timestamp"]); ?>.</p>
													</div>
												</div>
											</div>
										</div>
										<?php
										}
										?>
									</div>
								</div>
							</div>
						</div><!--/end row-->
						<hr style="margin: 15px 0px;">
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
			
			<?php
			$Caward = get_award_from_id($awards[0]["awardId"]);
			echo 'do_award("'.htmlspecialchars($Caward["name"]).'","'.htmlspecialchars($Caward["info"]).'","'.htmlspecialchars($Caward["image"]).'");';?>
		});
		
		function do_award(title, sub, image)
		{
			document.getElementById("award-image").src = "/assets/img/awards/awd"+image+".png";
			document.getElementById("award-image").title = title;
			document.getElementById("award-image").alt = title;
			document.getElementById("award-title").innerText = title;
			document.getElementById("award-subtext").innerText = sub;
		}
	</script>
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/plugins/placeholder-IE-fixes.js"></script>
	<![endif]-->
</body>
</html>
