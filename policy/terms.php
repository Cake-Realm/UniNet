<?php
include_once("../assets/php/config.php");
include_once("../assets/php/functions.php");
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title>Terms of Service | University Network</title>

	<?php include_once("../assets/php/header.php"); ?>
	<!-- CSS Page Style -->
	<style>
	.select2-container--default .select2-selection--single {
		border-radius: 0px;
		border-color: #ccc;
	}
	</style>
</head>

<body>
	<div class="wrapper">
		<?php include_once("../assets/php/navbar.php"); ?>

		<!--=== Content Part ===-->
		<div class="container content">
				<div class="row-fluid privacy">
				<p class="lead"><em>Terms of Service</em></p>
				<p>Please read these Terms and Conditions carefully before using the <a href="http://www.universitynetwork.co.uk">http://www.universitynetwork.co.uk</a> website, operated by Team Project407. These Terms are applicable to users, visitors and/or anyone using the website.</p>
				<p>By accessing University Network, you agree to follow these Terms of Service; any disagreement or failure to follow these Terms means your denial to the website.</p><br />
				
				<h4>Content</h4>
				<p>Our Service allows you to post information onto University Network. This can range from: non-copyrighted information, videos, links, shared data, etc. You are responsible for the uploading and deletion of these posts.</p><br />

				<h4>Links to Other Web Sites</h4>
				<p>Our website may contain links to websites or services that are not owned, created or controlled by Team Project407.</p>
				<p>Our team has no control or responsibility for the privacy, methods and/or content these services may offer. By using these links, you agree Team Project407 does not hold responsibility (direct or indirect) over the risks and damages that may occur.</p><br />

				<h4><em>Changes</em></h4>
				<p>We have the right to change these Terms at any time. If a change is made, we will notify all users at least 10 days prior to taking effect. What we consider a change is at our discretion, as well as when we will make a change.</p><br />
				
				<h4>Contact Us</h4>
				<p>If you have any questions about these Terms, please contact us.</p><br />
			</div><!--/row-fluid-->
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
