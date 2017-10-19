		<?php
		$active = 1;
		
		$navCourse = array("categories/", "subjects/", "modules/");
		$navUni = array("university/");
		$navAccount = array("profile/", "reports/", "settings/");
		
		$curURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		foreach ($navCourse as $cNavCourse)
		{
			if (strpos($curURL, "universitynetwork.co.uk/" . $cNavCourse) !== FALSE)
				$active = 2;
		}
		foreach ($navUni as $cNavUni)
		{
			if (strpos($curURL, "universitynetwork.co.uk/" . $cNavUni) !== FALSE)
				$active = 3;
		}
		foreach ($navAccount as $cNavAccount)
		{
			if (strpos($curURL, "universitynetwork.co.uk/" . $cNavAccount) !== FALSE)
				$active = 4;
		}
		
		?>
		<!--=== Header ===-->
		<div class="header">
			<div class="container">
				<!-- Logo -->
				<a class="logo" href="/">
					<img src="/assets/img/logo1-default.png" alt="Logo">
				</a>
				<!-- End Logo -->

				
				<!-- Topbar -->
				<div class="topbar">
					<?php 
					if (is_logged_in())
					{
					?>
					<ul class="list-inline badge-lists badge-icons pull-right" style="margin-top: 8px;">
						<li class="hidden-xs hidden-sm">
							<a href="javascript:void(0);" id="chatToggle2" style="color: #777;">
								<i class="fa fa-comments"></i>
							</a>
							<?php
							
							$msgCount = count_total_unread_messages($myUser["uid"]);
							if ($msgCount > 0)
								echo '<span class="badge badge-u rounded-x" style="right: 1px;">'.number_format($msgCount).'</span>';
							?>
						</li>
						<li>
							<a href="/profile/<?php echo clean_text_for_url_username($myUser["username"]); ?>/friends/" style="color: #777;">
								<i class="fa fa-user-plus"></i>
							</a>
							<?php
							$frCount = count_friend_requests($myUser["uid"]);
							if ($frCount > 0)
								echo '<span class="badge badge-u rounded-x" style="right: 0px;">'.number_format($frCount).'</span>';
							?>
							
						</li>
					</ul>
					<?php
					}
					?>
					<ul class="loginbar pull-right" style="margin-top: 8px; <?php  if (is_logged_in()) echo 'border-right: 1px solid #bbb; margin-right: 10px; padding-right: 10px;'; ?>">
						<?php 
						if (!is_logged_in())
						{
						?>
						<li><a href="/signup/">Sign Up</a></li>
						<li class="topbar-devider"></li>
						<li><a href="/login/">Login</a></li>
						<?php
						}
						else
						{
						?>
						<li><a href="/profile/<?php echo clean_text_for_url_username($myUser["username"]); ?>/"><?php echo $myUser["username"]; ?></a></li>
						<?php
						}
						?>
					</ul>
				</div>
				<!-- End Topbar -->
				
				
				<!-- Toggle get grouped for better mobile display -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bars"></span>
				</button>
				<!-- End Toggle -->
			</div><!--/end container-->

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse mega-menu navbar-responsive-collapse">
				<div class="container">
					<ul class="nav navbar-nav">
						<!-- Home -->
						<li<?php if ($active == 1) echo ' class="active"';?>>
							<a href="/">
								Home
							</a>
						</li>
						<!-- End Home -->

						<!-- Pages -->
						<li class="<?php if ($active == 2) echo 'active ';?>dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
								Courses
							</a>
							<ul class="dropdown-menu">
								<?php
								foreach (get_categories_alphabetically() as $category)
								{
								?>
								<!-- <?php echo $category["name"]; ?> -->
								<li class="dropdown-submenu">
									<a href="/categories/<?php echo str_replace(" ", "-", strtolower($category["name"]));?>/"><?php echo $category["name"]; ?></a>
									<?php
									$subjects = get_subjects_from_category($category["categoryId"]);
									if ($subjects !== false)
									{
									?>
									<ul class="dropdown-menu">
										<?php
										foreach ($subjects as $subject)
										{
											echo '<li><a href="/subjects/'.str_replace(" ", "-", strtolower($subject["name"])).'/">'.$subject["name"].'</a></li>';
										}
										?>
									</ul>
									<?php
									}
									?>
								</li>
								<!-- End <?php echo $category["name"]; ?> -->
								<?php
								}
								?>
							</ul>
						</li>
						<!-- End Pages -->
						
						<!-- University -->
						<li class="<?php if ($active == 3) echo 'active ';?>dropdown">
							<a href="/university/" class="dropdown-toggle" data-toggle="dropdown">
								Universities
							</a>
							<ul class="dropdown-menu">
								<?php
								foreach (get_most_popular_universities(10) as $cUni)
								{
									echo '<li><a href="/university/'.str_replace(" ", "-", strtolower($cUni["name"])).'/">'.$cUni["name"].'</a></li>';
								}
								?>
							</ul>
						</li>
						<!-- End University -->
						
						<?php 
						if (is_logged_in())
						{
						?>
						<!-- Account -->
						<li class="<?php if ($active == 4) echo 'active ';?>dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
								Account
							</a>
							<ul class="dropdown-menu">
								<!-- Profile -->
								<li>
									<a href="/profile/<?php echo clean_text_for_url_username($myUser["username"]); ?>/"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
								</li>
								<!-- End Profile -->
								<?php
								if ($myUser["usergroup"] == ADMIN || $myUser["usergroup"] == MODERATOR)
								{
								?>
								<!-- Reports -->
								<li>
									<a href="/reports/"><i class="fa fa-flag" aria-hidden="true"></i> Reports</a>
								</li>
								<!-- End Reports -->
								<?php
								}
								?>
								<!-- Settings -->
								<li>
									<a href="/settings/"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
								</li>
								<!-- End Settings -->
								<!-- Settings -->
								<li>
									<a href="/logout/"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
								</li>
								<!-- End Settings -->
							</ul>
						</li>
						<!-- End Account -->
						<?php
						}
						?>
						
						<!-- Search Block -->
						<li>
							<i class="search fa fa-search search-btn"></i>
							<div class="search-open">
								<form method="POST">
									<div class="input-group animated fadeInDown">
											<input type="text" class="form-control" name="search" placeholder="Search">
											<span class="input-group-btn">
												<button class="btn-u" type="submit">Go</button>
											</span>
									</div>
								</form>
							</div>
						</li>
						<!-- End Search Block -->
					</ul>
				</div><!--/end container-->
			</div><!--/navbar-collapse-->
		</div>
		<!--=== End Header ===-->
		<?php
		if (is_logged_in())
		{
		?>
		<style>
		.chat-list {
			position: fixed;
			left: -250px;
			top: 0;
			height: 100%;
			width: 250px;
			background: #2e3136;
			z-index: 499;
			transition: left 0.5s;
		}
		.chat-list-active {
			left: 0px;
		}
		.chat-list .chat-toggle {
			position: absolute;
			left: 250px;
			top: 30px;
			height: 40px;
			width: 40px;
			background: #2e3136;
			z-index: 500;
			border-radius: 0px 10px 10px 0px;
			cursor: pointer;
		}
		.chat-list .chat-toggle i {
			color: #fff;
			font-size: 24px;
			padding: 6px;
		}
		
		.chat-nav {
			overflow-y: scroll;
			height: 100%;
		}
		.chat-nav li {
			padding: 0;
			list-style: none;
		}
		.chat-nav li a {
			display: block;
			padding: 8px 30px 8px 15px;
			color: #666;
		}
		.chat-nav li a:hover {
			text-decoration: none;
			color: #fff;
		}
		.chat-nav .list-group-item {
			background-color: #2e3136;
			border: 0px;
		}
		.chat-nav .list-group-item:hover {
			background-color: #282b30;
			border-left: 2px solid #72c02c !important;
		}
		.chat-nav li a img {
			width: 38px;
			border-radius: 50px;
			display: inline-block;
		}
		.chat-nav li a .chat-bubble {
			width: 16px;
			height: 16px;
			border: 2px solid #2e3136;
			border-radius: 50px;
			position: absolute;
			top: 32px;
			left: 38px;
		}
		.chat-nav li a .chat-online {
			background-color: #5fb611;
		}
		.chat-nav li a .chat-away {
			background-color: #f1c40f;
		}
		.chat-nav li a .chat-offline {
			background-color: #696969;
		}
		.chat-nav li a .chat-name {
			display: inline-block;
			padding: 0px 10px;
			font-size: 13px;
			font-family: 'Poppins', sans-serif;
			font-weight: bold;
		}
		.chat-nav .chat-header:first {
			border-top: 0px;
		}
		.chat-nav .chat-header {
			text-transform: uppercase;
			font-weight: bolder;
			font-family: 'Poppins', sans-serif;
			font-size: 15px;
			margin-top: 5px;
			padding: 5px 20px 0px 20px;
			border-top: 1px solid #242629;
		}
		.chat-nav .no-border {
			border: 0px !important;
		}
		::-webkit-scrollbar {
		  width: 12px;
		  height: 12px;
		}
		::-webkit-scrollbar-button {
		  width: 0px;
		  height: 0px;
		}
		::-webkit-scrollbar-thumb {
		  background: #1b1d20;
		  border: 0px none transparent;
		  border-radius: 0px;
		}
		::-webkit-scrollbar-thumb:hover {
		  background: #1b1d20;
		}
		::-webkit-scrollbar-thumb:active {
		  background: #1b1d20;
		}
		::-webkit-scrollbar-track {
		  background: transparent;
		  border: 0px none transparent;
		  border-radius: 0px;
		}
		::-webkit-scrollbar-track:hover {
		  background: transparent;
		}
		::-webkit-scrollbar-track:active {
		  background: transparent;
		}
		::-webkit-scrollbar-corner {
		  background: transparent;
		}
		.open-chat-nav {
			list-style: none;
			position: absolute;
			left: 220px;
			top: calc(100% - 300px);
			width: 2000px;
		}
		.open-chat-nav li {
			position: static;
			width: 250px;
			height: 300px;
			border-radius: 10px 10px 0px 0px;
			display: inline-block;
		}
		.open-chat-nav li .open-chat-panel {
			background: #2e3136;
			width: 250px;
			height: 300px;
			border-radius: 10px 10px 0px 0px;
			position: absolute;
			top: 270px;
			transition: top 0.5s;
		}
		.open-chat-nav .chat-open .open-chat-panel {
			top: 0px;
		}
		.open-chat-nav li .open-chat-header {
			width: 250px;
			height: 30px;
			padding: 5px 10px;
			background: #282b30;
			border-radius: 10px 10px 0px 0px;
		}
		.open-chat-nav li .open-chat-header a {
			font-size: 13px;
			font-family: 'Poppins', sans-serif;
			font-weight: bold;
			color: #aaa;
		}
		.open-chat-nav li .open-chat-header .open-chat-icons {
			cursor: pointer;
			font-size: 16px;
			float: right;
		}
		.open-chat-nav li .open-chat-header .open-chat-icons i:hover {
			color: #fff;
		}
		.open-chat-nav li .open-chat-footer {
			width: 250px;
			position: absolute;
			top: 270px;
		}
		.open-chat-nav li .open-chat-footer input {
			width: 250px;
			height: 30px;
			background: #2e3136;
			border: 0px;
			border-top: 1px solid #242629;
			padding: 5px;
			color: #ccc;
		}
		.open-chat-nav li .open-chat-footer input:focus {
			outline: none;
		}
		.open-chat-nav li .open-chat-content {
			padding: 10px;
			color: #ccc;
			overflow-y: scroll;
			height: 250px;
		}
		.open-chat-nav li .open-chat-content .ochat-heading {
			text-align: center;
			font-family: 'Poppins', sans-serif;
			color: #999;
			font-size: 12px;
			font-weight: lighter;
		}
		.chat-name .badge {
			padding: 3px 6px;
			position: absolute;
			top: 5px;
			font-size: 12px;
			font-family: 'Poppins', sans-serif;
			text-align: center;
			font-weight: bolder;
		}
		.open-chat-nav li .open-chat-content .ochat-msg {
			border-radius: 5px;
			padding: 4px;
		}
		.open-chat-nav li .open-chat-content .ochat-me {
			margin-right: 35px;
		}
		.open-chat-nav li .open-chat-content .ochat-me .ochat-msg {
			background: #72c02c;
			color: #333;
		}
		.open-chat-nav li .open-chat-content .ochat-them {
			margin-left: 35px;
		}
		.open-chat-nav li .open-chat-content .ochat-them .ochat-msg {
			background: #696969;
		}
		.open-chat-nav li .open-chat-content .ochat-them .ochat-time {
			text-align: right;
		}
		.open-chat-nav li .open-chat-content .ochat-time {
			font-size: 10px;
			margin-top: 5px;
		}
		</style>
		<script>
		$(function() {	
			$("#chatToggle").click(function(){
				$("#chat").toggleClass("chat-list-active");
			});
			$("#chatToggle2").click(function(){
				$("#chat").toggleClass("chat-list-active");
			});
			window.setInterval(function(){
			  refreshMessages();
			}, 5000);
		});
		var openChats = 0;
		function refreshMessages()
		{
			var ocn = document.getElementById("open-chat-nav");
			for (i = 0; i < ocn.children.length; i++)
			{ 
				var cId = ocn.children[i].getAttribute('id');
				if ($("#" + cId).attr('class') == "chat-open")
				{
					getSingleMessage(cId.substring(5));
				}
			}
		}
		function getSingleMessage(id)
		{
			var tEl = document.getElementById("chat_" + id);
			$.ajax({
				url: '/chat.php',
				type: 'POST',
				dataType: 'text',
				data: { uid : id },
				success: function(data) {
					tEl.outerHTML = data;
				}
			});
		}
		function sendMessage(id)
		{
			var txt = document.getElementById("messageText_"+id).value;
			$.ajax({
				url: '/chat.php',
				type: 'POST',
				dataType: 'text',
				data: { msg : txt, uid : id },
				success: function(data) {
					document.getElementById("chat_" + id).outerHTML = data;
				}
			});
			document.getElementById("messageText_"+id).setAttribute("value", "");
			return false;
		}
		function loadChat(id)
		{
			$.ajax({
				  type: "POST",
				  url: "/chat.php",
				  data: { uid: id },
				  success: function(resultData){
					document.getElementById("open-chat-nav").innerHTML += resultData;
				  }
			});
			openChats++;
		}
		function openChat(id)
		{
			var ocn = document.getElementById("open-chat-nav");
			var isAlreadyCreated = false;
			for (i = 0; i < ocn.children.length; i++)
			{ 
				if (ocn.children[i].getAttribute('id') == "chat_" + id)
				{
					$("#chat_" + id).toggleClass("chat-open");
					if ($("#chat_" + id).attr('class') == "chat-open")
					{
						$("#minimax_" + id).removeClass("fa-square-o");
						$("#minimax_" + id).addClass("fa-minus");
					}
					else
					{
						$("#minimax_" + id).removeClass("fa-minus");
						$("#minimax_" + id).addClass("fa-square-o");
					}
					isAlreadyCreated = true;
				}
			}
			
			if (!isAlreadyCreated)
			{
				if (openChats < 5)
				{
					loadChat(id);
					var badgeElem = document.getElementById("badgeid-" + id);
					if (badgeElem != null)
						badgeElem.outerHTML = "";
				}
				else
					console.log("Maxmimum number of chats have been opened.");
			}
		}
		function closeChat(id)
		{
			var ocn = document.getElementById("open-chat-nav");
			for (i = 0; i < ocn.children.length; i++)
			{ 
				if (ocn.children[i].getAttribute('id') == "chat_" + id)
				{
					ocn.children[i].outerHTML = "";
					openChats--;
				}
			}
		}
		</script>
		<!--=== Sidebar ===-->
		<div class="chat-list hidden-xs hidden-sm" id="chat">
			<div class="chat-toggle" id="chatToggle">
				<i class="fa fa-comments" aria-hidden="true"></i>
			</div>
			<ul class="list-group chat-nav">
				<?php
				$friendReqs = get_friend_requests($myUser["uid"]);
				if (count($friendReqs) > 0)
				{
					?>
					<!-- Friend Requests -->
					<li class="chat-header no-border">Friend Requests</li>
					<?php
					foreach ($friendReqs as $friendReq)
					{
						$friendReqDetails = get_user_from_uid($friendReq["senderId"]);
					?>
					<li class="list-group-item">
						<a href="/profile/<?php echo clean_text_for_url_username($myUser["username"]); ?>/friends/">
							<img src="/assets/img/profile-images/<?php echo $friendReqDetails["image"]; ?>">
							<div class="chat-bubble chat-<?php if (is_user_online($friendReqDetails["lastOnline"])) echo 'online'; else echo 'offline'; ?>"></div>
							<div class="chat-name"><?php echo $friendReqDetails["username"]; ?></div>
							<span class="label label-success rounded-2x">View</span>
						</a>
					</li>
					<?php
					}
					?>
				<!-- End Friend Requests -->
				<?php
				}
				?>
				
				<!-- Friends -->
				<li class="chat-header<?php if (count($friendReqs) == 0) echo ' no-border';?>">Friends</li>
				<?php
				$friends = get_friends($myUser["uid"]);
				if (count($friends) == 0)
					echo '<div class="alert alert-danger fade in"><strong>Oh snap!</strong> You currently have no friends, you can add people as a friend via their profile.</div>';
				else
				{
					foreach ($friends as $friend)
					{
						if ($friend["userId1"] == $myUser["uid"])
							$friendId = $friend["userId2"];
						else
							$friendId = $friend["userId1"];
						$friendDetails = get_user_from_uid($friendId);
					?>
					<li class="list-group-item">
						<a href="javascript:void(0);" onclick="openChat('<?php echo $friendDetails["uid"]; ?>');">
							<img src="/assets/img/profile-images/<?php echo $friendDetails["image"]; ?>">
							<div class="chat-bubble chat-<?php if (is_user_online($friendDetails["lastOnline"])) echo 'online'; else echo 'offline'; ?>"></div>
							<div class="chat-name"><?php
							echo $friendDetails["username"];
							$uMsgs = count_unread_messages($myUser["uid"], $friendDetails["uid"]);
							if ($uMsgs > 0)
								echo '<span class="badge badge-u rounded" id="badgeid-' . $friendDetails["uid"] . '">'.number_format($uMsgs).'</span>';
							?></div>
						</a>
					</li>
					<?php
					}
				}
				?>
				<!-- End Friends -->
			</ul>
			<ul class="open-chat-nav" id="open-chat-nav"></ul>
		</div>
		<!--=== End Sidebar ===-->
		<?php
		}
		?>