<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");
$erNum = 0;

if (!is_logged_in())
{
	header("LOCATION: /");
	die();
}

if (isset($_FILES["profilePic"]) && $_FILES["profilePic"]["error"] != 4)
{
	if ($_FILES["profilePic"]["error"] != 0)
	{
		switch ($_FILES["profilePic"]["error"])
		{
			case 1:
			case 2:
			$erMsg[$erNum] = "Image exceeds maximum size. Try again with a smaller image.";
			break;
			case 3:
			$erMsg[$erNum] = "Image was only partially uploaded, please try again.";
			break;
			case 7:
			$erMsg[$erNum] = "Out of non volatile memory, please try again later.";
			break;
			default:
			$erMsg[$erNum] = "An error occured uploading your image, please try again.";
			break;
		}
		$erNum++;
	}
	else
	{
		$imageDetails = getimagesize($_FILES["profilePic"]["tmp_name"]);
		if ($imageDetails == FALSE)
		{
			$erMsg[$erNum] = "Submitted file was not an image, please try again.";
			$erNum++;
		}
		else
		{
			if ($imageDetails[0] != 100 || $imageDetails[1] != 100)
			{
				$erMsg[$erNum] = "Image must have a width of 100px and a height of 100px, please try again.";
				$erNum++;
			}
			if ($imageDetails[2] != 1 && $imageDetails[2] != 2 && $imageDetails[2] != 3)
			{
				$erMsg[$erNum] = "Image must be either GIF, JPEG or PNG, please try again.";
				$erNum++;
			}
			if ($_FILES["profilePic"]["size"] > 50000)
			{
				$erMsg[$erNum] = "Image must be less than 50KB in size, please try again.";
				$erNum++;
			}
			
			if ($erNum == 0)
			{
				$reqDirectory = $_SERVER['DOCUMENT_ROOT'] . "/assets/img/profile-images/";
				$reqExtension = ".gif";
				switch ($imageDetails[2])
				{
					case 2:
					$reqExtension = ".jpg";
					break;
					case 3:
					$reqExtension = ".png";
					break;
				}
				
				do
				{
					$reqName = generateRandomString(20);
				}
				while(file_exists($reqDirectory . $reqName . $reqExtension));
				
				if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $reqDirectory . $reqName . $reqExtension))
				{
					if ($myUser["image"] != "default.jpg")
						unlink($reqDirectory . $myUser["image"]);
					update_user_field($myUser["uid"], "image", $reqName . $reqExtension);
					$gdMsg = "Settings Saved";
				}
				else
				{
					$erMsg[$erNum] = "An error occured uploading your image, please try again.";
					$erNum++;
				}
			}
		}
	}	
}
if (isset($_POST["university"]))
{
	$university = $_POST["university"];
	$uniExist = does_university_exist($university) || $university == 0;
	
	if (!$uniExist)
	{
		$erMsg[$erNum] = "The selected University does not exist.";
		$erNum++;
	}
	else
	{
		update_user_field($myUser["uid"], "uniId", $university);
		$gdMsg = "Settings Saved";
	}
	$myUser = get_my_user();
}
if (isset($_POST["course"]))
{
	$course = $_POST["course"];
	$courseExist = does_subject_exist($course) || $course == 0;
	
	if (!$courseExist)
	{
		$erMsg[$erNum] = "The selected Course does not exist.";
		$erNum++;
	}
	else
	{
		update_user_field($myUser["uid"], "subjectId", $course);
		$gdMsg = "Settings Saved";
	}
	$myUser = get_my_user();
}
if (isset($_POST["bio"]))
{
	$bio = htmlspecialchars($_POST["bio"]);
	$bioLength = check_string_length($bio, 4, 500);
	if ($bioLength < 0)
	{
		$erMsg[$erNum] = "Bio needs to be atleast 4 characters long.";
		$erNum++;
	}
	else if ($bioLength > 0)
	{
		$erMsg[$erNum] = "Bio needs to be less than or equal to 500 characters long.";
		$erNum++;
	}
	else
	{
		update_user_field($myUser["uid"], "bio", $bio);
		$gdMsg = "Settings Saved";
	}
	$myUser = get_my_user();
}
$deleteSocials = false;
foreach (get_all_social() as $cSocial)
{
	$socialSmallName = str_replace(" ", "-", strtolower($cSocial["name"]));
	if (isset($_POST[$socialSmallName]) && !empty($_POST[$socialSmallName]))
		$deleteSocials = true;
}
if ($deleteSocials == true)
	delete_social_for_user($myUser["uid"]);
foreach (get_all_social() as $cSocial)
{
	$socialSmallName = str_replace(" ", "-", strtolower($cSocial["name"]));
	if (isset($_POST[$socialSmallName]) && !empty($_POST[$socialSmallName]))
	{
		$jSocial = htmlspecialchars($_POST[$socialSmallName]);
		$socLength = check_string_length($jSocial, 3, 75);
		if ($socLength < 0)
		{
			$erMsg[$erNum] = $cSocial["name"] . " needs to be atleast 3 characters long.";
			$erNum++;
		}
		else if ($socLength > 0)
		{
			$erMsg[$erNum] = $cSocial["name"] . " needs to be less than or equal to 75 characters long.";
			$erNum++;
		}
		else
		{
			add_social_for_user($myUser["uid"], $cSocial["socialId"], $jSocial);
			$gdMsg = "Settings Saved";
		}
		$myUser = get_my_user();
	}
?>
<?php
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Profile Settings | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
	<style>
	.sidebar-nav-v1 > li.active, .sidebar-nav-v1 > li.active:hover {
		border-left: 2px solid #72c02c !important;
		background: #fff;
	}
	.sidebar-nav-v1 > li.active > a {
		color: #555;
	}
	.select2-container--default .select2-selection--single {
		border-radius: 0px;
		border-color: #ccc;
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
					<li><a href="/profile/<?php echo clean_text_for_url_username($myUser["username"]); ?>/">Profile</a></li>
					<li class="active">Settings</li>
				</ul>
			</div>
		</div>
		<!--=== End Breadcrumbs ===-->
		
		<!--=== Settings ===-->
		<div class="container content">
			<div class="row col-md-10 col-md-offset-1">
				<!-- Content -->
				<div class="col-md-3">
					<ul class="list-group sidebar-nav-v1" id="sidebar-nav">
						<!-- Profile -->
						<li class="list-group-item active"><a href="/settings/">Profile</a></li>
						<!-- End Profile -->
						
						<!-- Account -->
						<li class="list-group-item"><a href="/settings/account/">Account</a></li>
						<!-- End Account -->
					</ul>
				</div>
				<div class="col-md-9">
					<form method="POST" class="sky-form"  enctype="multipart/form-data">
						<header>Public profile</header>
						<fieldset>
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
							if (isset($gdMsg))
							{
								?>
								<div class="alert alert-success fade in alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>Success!</strong> <?php echo $gdMsg; ?>
								</div>
								<?php
							}
							?>
							
							<section>
								<label class="label">Profile Picture</label>
								<label for="file" class="input input-file">
									<div class="button"><input type="file" id="file" name="profilePic" accept="image/*" onchange="this.parentNode.nextSibling.value = this.value">Browse new picture</div><input type="text" readonly="">
								</label>
								<div class="note"><strong>Note:</strong> Image size must be 100px by 100px, and be less than 50KB in size and must be either a GIF, JPG or PNG.</div>
							</section>
							
							<section>
								<label class="label">University</label>
								<div class="input-group margin-bottom-20">
									<span class="input-group-addon"><i class="fa fa-university"></i></span>
									<select class="form-control" name="university">
										<option value="0">None / Other</option>
										<?php
										foreach (get_countries() as $cCountry)
										{
											echo '<optgroup label="'.$cCountry["name"].'">';
											foreach (get_universities_in_country($cCountry["countryId"]) as $cUni)
											{
												echo '<option value="'.$cUni["uniId"].'"';
												if ($myUser["uniId"] == $cUni["uniId"])
													echo ' selected';
												echo '>'.$cUni["name"].'</option>';
											}
											echo '</optgroup>';
										}
										?>
									</select>
								</div>
							</section>
						
							<section>
								<label class="label">Course</label>
								<div class="input-group margin-bottom-20">
									<span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span>
									<select class="form-control" name="course">
										<option value="0">Other</option>
										<?php
										foreach (get_categories_alphabetically() as $cCategory)
										{
											echo '<optgroup label="'.$cCategory["name"].'">';
											foreach (get_subjects_from_category($cCategory["categoryId"]) as $cSubject)
											{
												echo '<option value="'.$cSubject["subjectId"].'"';
												if ($myUser["subjectId"] == $cSubject["subjectId"])
													echo ' selected';
												echo '>'.$cSubject["name"].'</option>';
											}
											echo '</optgroup>';
										}
										?>
									</select>
								</div>
							</section>
							
							<section>
								<label class="label">Bio</label>
								<label class="textarea">
									<textarea rows="4" placeholder="Tell a little about yourself..." maxlength="500" name="bio"><?php if (isset($_POST["bio"])) echo htmlspecialchars($_POST["bio"]); else if ($myUser["bio"] != NULL) echo $myUser["bio"];?></textarea>
								</label>
								<div class="note"><strong>Note:</strong> Allows use of {b}{/b}, {i}{/i}, {u}{/u}</div>
							</section>
							
							<?php
							$i = 0;
							foreach (get_all_social() as $cSocial)
							{
								$tcSocial = get_user_social_links_for_id($myUser["uid"], $cSocial["socialId"]);
								
								if ($i % 2 == 0)
									echo '<div class="row">';
							?>
							<section class="col col-6">
								<label class="label"><?php echo $cSocial["name"];?></label>
								<label class="input">
									<i class="icon-append fa fa-<?php echo $cSocial["icon"];?>"></i>
									<input type="text" name="<?php echo str_replace(" ", "-", strtolower($cSocial["name"]));?>" maxlength="75" value="<?php if (isset($_POST[str_replace(" ", "-", strtolower($cSocial["name"]))])) echo htmlspecialchars($_POST[str_replace(" ", "-", strtolower($cSocial["name"]))]); else if ($tcSocial != false) echo $tcSocial["name"]; ?>">
								</label>
							</section>
							<?php
								if ($i % 2 == 1)
									echo '</div>';
							$i++;
							}
							?>							
						</fieldset>
						<footer>
							<button type="submit" class="btn-u">Update Profile</button>
						</footer>
					</form>
				</div>
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
	<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
	<!-- JS Page Level -->
	<script type="text/javascript" src="/assets/js/app.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			App.init();
			$('[data-toggle="tooltip"]').tooltip();
			$('select').select2();
		});
	</script>
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/plugins/placeholder-IE-fixes.js"></script>
	<![endif]-->
</body>
</html>
