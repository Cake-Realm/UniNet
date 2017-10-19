<?php
$domain = "universitynetwork.co.uk";
$actual_link = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if (strpos($actual_link,'www.' . $domain) !== false) 
	header( 'Location: https://' . $domain . $_SERVER["REQUEST_URI"]);

if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "http")
	header( 'Location: https://' . $domain . $_SERVER["REQUEST_URI"]);

if (isset($_POST["search"]))
{
	header("LOCATION: /search/p1/".clean_text_for_url2($_POST["search"]));
	die();
}
if (is_logged_in())
{
	$myUser = get_my_user();
	update_user_field($myUser["uid"], "lastOnline", time());
	$myUser = get_my_user();
}
else if (isset($_COOKIE["m3kvyJS7q2GFyb89"]))//Remember Me Login
{
	$cookieHash = $_COOKIE["m3kvyJS7q2GFyb89"];	
	if (does_remember_me_hash_exist($cookieHash))
	{
		$cUser = get_user_from_remember_me_hash($cookieHash);
		create_new_remember_me($cUser);
		$_SESSION["uid"] = $cUser["uid"];
		$myUser = get_my_user();
	}
	else
		setcookie("m3kvyJS7q2GFyb89", "", time()-3600, "/");
}

function get_chat_messages($userId1, $userId2)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `messages` WHERE (`senderId`=:userId1 AND `recieverId`=:userId2) OR (`senderId`=:userId2 AND `recieverId`=:userId1) ORDER BY `timestamp` ASC LIMIT 50"); 
	$query->execute(array(":userId1"=>$userId1, ":userId2"=>$userId2));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function post_message($senderId, $recieverId, $msg)
{
	global $conn;
	
	$query = $conn->prepare("SELECT `content`, `timestamp` FROM `messages` WHERE (`senderId`=:userId1 AND `recieverId`=:userId2) OR (`senderId`=:userId2 AND `recieverId`=:userId1) ORDER BY `timestamp` DESC LIMIT 1"); 
	$query->execute(array(":userId1"=>$senderId, ":userId2"=>$recieverId));
	$cRow = $query->fetch();
	if (time() - $cRow["timestamp"] > 3 || $cRow["content"] != $msg)
	{
		$query = $conn->prepare("INSERT INTO `messages` (`senderId`, `recieverId`, `content`, `timestamp`) VALUES (:senderId, :recieverId, :content, :timestamp)"); 
		$query->execute(array(":senderId"=>$senderId, ":recieverId"=>$recieverId, ":content"=>$msg, ":timestamp"=>time()));
	}	
}
function mark_messages_as_read($recieverId, $senderId)
{
	global $conn;
	$query = $conn->prepare("UPDATE `messages` SET `msgRead`='1' WHERE `senderId`=:senderId AND `recieverId`=:recieverId AND `msgRead`='0'"); 
	$query->execute(array(":senderId"=>$senderId, ":recieverId"=>$recieverId));
}
function count_total_unread_messages($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `messages` WHERE `recieverId`=:userId AND `msgRead`='0'"); 
	$query->execute(array(":userId"=>$userId));
	return $query->fetch()["count"];
}
function count_unread_messages($recieverId, $senderId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `messages` WHERE `senderId`=:senderId AND `recieverId`=:recieverId AND `msgRead`='0'"); 
	$query->execute(array(":senderId"=>$senderId, ":recieverId"=>$recieverId));
	return $query->fetch()["count"];
}
function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
function parse_to_html_simple($inp)
{
	$inp = parase_to_html_single($inp, "b");
	$inp = parase_to_html_single($inp, "u");
	$inp = parase_to_html_single($inp, "i");
	return $inp;
}
function parse_to_html($inp)
{
	$inp = parase_to_html_single($inp, "b");
	$inp = parase_to_html_single($inp, "u");
	$inp = parase_to_html_single($inp, "i");
	$inp = parase_to_html_single($inp, "code");
	
	
	$inp = str_replace("{subtitle}",'<div class="subtitle"><h4>', $inp);
	$inp = str_replace("{/subtitle}",'</h4></div>', $inp);
	return $inp;
}
function parase_to_html_single($msg, $replace)
{
	$msg = str_replace("{" . $replace . "}","<" . $replace . ">", $msg);
	$msg = str_replace("{/" . $replace . "}","</" . $replace . ">", $msg);
	return $msg;
}
function get_reports($pageNum, $pageSize)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `report` WHERE `deleted`='0' ORDER BY `timestamp` ASC LIMIT $pageSize OFFSET " . (($pageNum - 1) * $pageSize)); 
	$query->execute();
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function delete_report($reportId)
{
	global $conn;
	$query = $conn->prepare("UPDATE `report` SET `deleted`='1' WHERE `reportId`=:rid"); 
	$query->execute(array(":rid"=>$reportId));
}
function count_reports()
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `report`"); 
	$query->execute();
	return $query->fetch()["count"];
}
function get_friends($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `friend` WHERE `userId1`=:userId1 || `userId2`=:userId2"); 
	$query->execute(array(":userId1"=>$userId, ":userId2"=>$userId));
	return $query->fetchAll();
}
function are_friends($userId1, $userId2)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `friend` WHERE (`userId1`=:userId1 AND `userId2`=:userId2) OR (`userId1`=:userId2 AND `userId2`=:userId1)"); 
	$query->execute(array(":userId1"=>$userId1, ":userId2"=>$userId2));
	return $query->fetch()["count"] == 1;
}
function add_friendship($userId1, $userId2)
{
	global $conn;
	$query = $conn->prepare("INSERT INTO `friend` (`userId1`, `userId2`, `timestamp`) VALUES (:userId1, :userId2, :timestamp)");
	$query->execute(array(
		":userId1" => $userId1,
		":userId2" => $userId2,
		":timestamp" => time(),
	));
}
function remove_friendship($userId1, $userId2)
{
	global $conn;
	$query = $conn->prepare("DELETE FROM `friend` WHERE (`userId1`=:userId1 AND `userId2`=:userId2) OR (`userId1`=:userId2 AND `userId2`=:userId1)");
	$query->execute(array(
		":userId1" => $userId1,
		":userId2" => $userId2
	));
}
function is_request_active($sender, $reciever)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `friendRequest` WHERE `senderId`=:userId1 AND `recieverId`=:userId2"); 
	$query->execute(array(":userId1"=>$sender, ":userId2"=>$reciever));
	return $query->fetch()["count"] == 1;
}
function request_friend($sender, $reciever)
{
	if (is_request_active($reciever, $sender))
	{
		remove_request_friend($reciever, $sender);
		add_friendship($sender, $reciever);
	}
	else
	{
		global $conn;
		$query = $conn->prepare("INSERT INTO `friendRequest` (`senderId`, `recieverId`, `timestamp`) VALUES (:userId1, :userId2, :timestamp)");
		$query->execute(array(
			":userId1" => $sender,
			":userId2" => $reciever,
			":timestamp" => time(),
		));
	}
}
function remove_request_friend($sender, $reciever)
{
	global $conn;
	$query = $conn->prepare("DELETE FROM `friendRequest` WHERE (`senderId`=:userId1 AND `recieverId`=:userId2) OR (`senderId`=:userId2 AND `recieverId`=:userId1)");
	$query->execute(array(
		":userId1" => $sender,
		":userId2" => $reciever
	));
}
function count_friend_requests($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `friendRequest` WHERE `recieverId`=:userId"); 
	$query->execute(array(":userId"=>$userId));
	return $query->fetch()["count"];
}
function get_friend_requests($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `friendRequest` WHERE `recieverId`=:userId"); 
	$query->execute(array(":userId"=>$userId));
	return $query->fetchAll();
}
function login($username, $password, $remember)
{
	$cUser = get_user_from_username($username);
	if ($cUser == false)
	{
		if (!isset($_SESSION["loginAttempts"]))
			$_SESSION["loginAttempts"] = 1;
		else
			$_SESSION["loginAttempts"]++;
		
		if ($_SESSION["loginAttempts"] == 5)
			$_SESSION["loginTimer"] = time() + 900;
		
		//Username doesn't exist
		return 1;
	}
	
	if ($cUser["password"] != crypt($password, $cUser["password"]))
	{
		if (!isset($_SESSION["loginAttempts"]))
			$_SESSION["loginAttempts"] = 1;
		else
			$_SESSION["loginAttempts"]++;
		
		if ($_SESSION["loginAttempts"] == 5)
			$_SESSION["loginTimer"] = time() + 900;
		
		//Password is incorrect
		return 2;
	}
	
	if (is_account_email_verified($cUser["uid"]))
	{
		if ($remember)
			create_new_remember_me($cUser);
		$_SESSION["uid"] = $cUser["uid"];
		return 0;
	}
	else
	{
		return 3;
	}
}
function create_new_remember_me($cUser)
{
	setcookie("m3kvyJS7q2GFyb89", "", time()-3600, "/");
	do
	{
		$token = uniqid($cUser["username"], true);
		$salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
		$tokenHash = crypt($token, '$2y$12$' . $salt);
	}
	while(does_remember_me_hash_exist($tokenHash));
	update_user_field($cUser["uid"], "rememberMeHash", $tokenHash);
	setcookie("m3kvyJS7q2GFyb89", $tokenHash, strval(time() + 604800), "/");
}
function get_user_from_remember_me_hash($hash)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `rememberMeHash`=:hash"); 
	$query->execute(array(":hash"=>$hash));
	return $query->fetch();
}
function does_remember_me_hash_exist($hash)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `rememberMeHash`=:hash"); 
	$query->execute(array(":hash"=>$hash));
	return $query->rowCount() > 0;
}
function is_logged_in()
{
	return isset($_SESSION["uid"]);
}
function is_user_online($lastOnline)
{
	return (time() - $lastOnline) <= 300;
}
function get_my_user()
{
	if (is_logged_in())
		return get_user_from_uid($_SESSION["uid"]);
	else
		return false;
}
function time_to_ago($epoch)
{
	$remSecs = time() - $epoch;
	
	if ($remSecs < 60)
		return $remSecs . " sec ago";
	if ($remSecs < 3600)
	{
		$remSecs = floor($remSecs / 60);
		if ($remSecs == 1)
			return $remSecs . " min ago";
		else
			return $remSecs . " mins ago";
	}
	if ($remSecs < 86400)
	{
		$remSecs = floor($remSecs / 3600);
		if ($remSecs == 1)
			return $remSecs . " hour ago";
		else
			return $remSecs . " hours ago";
	}
	if ($remSecs < 604800)
	{
		$remSecs = floor($remSecs / 86400);
		if ($remSecs == 1)
			return $remSecs . " day ago";
		else
			return $remSecs . " days ago";
	}
	if ($remSecs < 2628000)
	{
		$remSecs = floor($remSecs / 604800);
		if ($remSecs == 1)
			return $remSecs . " week ago";
		else
			return $remSecs . " weeks ago";
	}
	if ($remSecs < 31535965)
	{
		$remSecs = floor($remSecs / 2628000);
		if ($remSecs == 1)
			return $remSecs . " month ago";
		else
			return $remSecs . " months ago";
	}
	else
	{
		$remSecs = floor($remSecs / 31535965);
		if ($remSecs == 1)
			return $remSecs . " year ago";
		else
			return $remSecs . " years ago";
	}
}
function clean_text_for_url($inp)
{
	$inp = substr($inp, 0, 80);
	$inp = str_replace(" ", "-", strtolower($inp));
	return preg_replace('/[^A-Za-z0-9\-]/', '', $inp);
}
function clean_text_for_url2($inp)
{
	$inp = preg_replace('/[^A-Za-z0-9 ]/', '', $inp);
	return str_replace(" ", "-", strtolower($inp));
}
function clean_text_for_url3($inp)
{
	$inp = preg_replace('/[^A-Za-z0-9\-]/', '', $inp);
	return str_replace(" ", "-", strtolower($inp));
}
function clean_text_for_url_username($inp)
{
	return strtolower($inp);
}
function share_social_links_this_page($outer = true)
{
	share_social_links("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $outer);
}
function share_social_links($url, $outer)
{
	$encodedURL = urlencode(htmlspecialchars($url));
	if ($outer)
		echo '<ul class="footer-socials list-inline text-right">';
	?>
		<li>
			<a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $encodedURL;?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facebook">
				<i class="fa fa-facebook"></i>
			</a>
		</li>
		<li>
			<a href="http://twitter.com/share?url=<?php echo $encodedURL;?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Twitter">
				<i class="fa fa-twitter"></i>
			</a>
		</li>
		<li>
			<a href="https://plus.google.com/share?url=<?php echo $encodedURL;?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Google Plus">
				<i class="fa fa-google-plus"></i>
			</a>
		</li>
		<li>
			<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $encodedURL;?>&title=&summary=&source=" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Linkedin">
				<i class="fa fa-linkedin"></i>
			</a>
		</li>
	<?php
	if ($outer)
		echo '</ul>';
}
function search($searchCrit, $pageNum, $pageSize)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `question` WHERE `question` LIKE :sc1 OR `text` LIKE :sc2 ORDER BY `views` DESC LIMIT $pageSize OFFSET " . (($pageNum - 1) * $pageSize));
	$query->execute(array(":sc1"=>"%".$searchCrit."%", ":sc2"=>"%".$searchCrit."%"));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function count_items_in_search($searchCrit)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `question` WHERE `question` LIKE :sc1 OR `text` LIKE :sc2");
	$query->execute(array(":sc1"=>"%".$searchCrit."%", ":sc2"=>"%".$searchCrit."%"));
	return $query->rowCount();
}
function get_user_from_uid($uid)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `uid`=:uid"); 
	$query->execute(array(":uid"=>$uid));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_user_from_username($username)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `username`=:username"); 
	$query->execute(array(":username"=>$username));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_user_from_email_code($emailCode)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `emailCode`=:emailCode"); 
	$query->execute(array(":emailCode"=>$emailCode));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_user_from_reset_code($resetCode)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `resetCode`=:resetCode"); 
	$query->execute(array(":resetCode"=>$resetCode));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_categories_alphabetically()
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `category` ORDER BY `name` ASC"); 
	$query->execute();
	return $query->fetchAll();
}
function get_categories_popularity($amt)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `category` ORDER BY `name` ASC LIMIT $amt"); 
	$query->execute();
	return $query->fetchAll();
}
function get_subjects_from_category($categoryId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `subject` WHERE `categoryId`=:categoryId ORDER BY `name` ASC"); 
	$query->execute(array(":categoryId"=>$categoryId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function get_subject_by_name($name)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `subject` WHERE `name`=:name"); 
	$query->execute(array(":name"=>$name));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_category_by_name($name)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `category` WHERE `name`=:name"); 
	$query->execute(array(":name"=>$name));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_module_by_name($name)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `module` WHERE `name`=:name"); 
	$query->execute(array(":name"=>$name));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_module_by_id($moduleId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `module` WHERE `moduleId`=:moduleId"); 
	$query->execute(array(":moduleId"=>$moduleId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_modules_from_subject($subjectId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `module` WHERE `subjectId`=:subjectId ORDER BY `name` ASC"); 
	$query->execute(array(":subjectId"=>$subjectId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function get_questions_from_module($moduleId, $pageNum, $pageSize)
{
	if (is_numeric($pageNum))
	{
		global $conn;
		$query = $conn->prepare("SELECT * FROM `question` WHERE `moduleId`=:moduleId AND `deleted`='0' AND `moderation`='0' ORDER BY `timestamp` DESC LIMIT $pageSize OFFSET " . (($pageNum - 1) * $pageSize)); 
		$query->execute(array(":moduleId"=>$moduleId));
		if ($query->rowCount() == 0)
			return false;
		else
			return $query->fetchAll();
	}
	else
		return false;
}
function get_recent_questions_from_user($uid)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `question` WHERE `userId`=:userId AND `deleted`='0' AND `moderation`='0' ORDER BY `timestamp` DESC LIMIT 5"); 
	$query->execute(array(":userId"=>$uid));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function get_recent_answers_from_user($uid)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `answer` WHERE `userId`=:userId AND `deleted`='0' ORDER BY `timestamp` DESC LIMIT 5"); 
	$query->execute(array(":userId"=>$uid));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function get_category_from_id($categoryId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `category` WHERE `categoryId`=:categoryId"); 
	$query->execute(array(":categoryId"=>$categoryId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_subject_from_id($subjectId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `subject` WHERE `subjectId`=:subjectId"); 
	$query->execute(array(":subjectId"=>$subjectId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_question_from_id($questionId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `question` WHERE `questionId`=:questionId"); 
	$query->execute(array(":questionId"=>$questionId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_answer_from_id($answerId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `answer` WHERE `answerId`=:answerId"); 
	$query->execute(array(":answerId"=>$answerId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_answers_for_question($questionId, $pageNum, $pageSize, $correctAnswer)
{
	if (is_numeric($pageNum))
	{
		if ($correctAnswer == NULL)
			$correctAnswer = -1;
		
		global $conn;
		$query = $conn->prepare("SELECT * FROM `answer` WHERE `questionId`=:questionId AND `answerId` != :correctAnswer AND `deleted`='0' ORDER BY `timestamp` ASC LIMIT $pageSize OFFSET " . (($pageNum - 1) * $pageSize)); 
		$query->execute(array(":questionId"=>$questionId, ":correctAnswer"=>$correctAnswer));
		if ($query->rowCount() == 0)
			return false;
		else
			return $query->fetchAll();
	}
	else
		return false;
}
function count_answers_for_question($questionId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `answer` WHERE `questionId`=:questionId");  $query->execute(array(":questionId" => $questionId));
	return $query->fetch()["count"];
}
function get_latest_questions($quantity)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `question` ORDER BY `timestamp` DESC LIMIT $quantity"); 
	$query->execute();
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function get_homepage_stats()
{
	global $conn;
	$retVal = array(0, 0, 0, 0);
	
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `question`");  $query->execute();
	$retVal[0] = $query->fetch()["count"];
	
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `answer`");  $query->execute();
	$retVal[1] = $query->fetch()["count"];
	
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `users`");  $query->execute();
	$retVal[2] = $query->fetch()["count"];
	
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `module`");  $query->execute();
	$retVal[3] = $query->fetch()["count"];
	
	return $retVal;
}
function count_subjects_in_category($categoryId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `subject` WHERE `categoryId`=:categoryId");  $query->execute(array(":categoryId" => $categoryId));
	return $query->fetch()["count"];
}
function count_modules_in_subject($subjectId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `module` WHERE `subjectId`=:subjectId");  $query->execute(array(":subjectId" => $subjectId));
	return $query->fetch()["count"];
}
function count_questions_in_module($moduleId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `question` WHERE `moduleId`=:moduleId AND `deleted`='0' AND `moderation`='0'");  $query->execute(array(":moduleId" => $moduleId));
	return $query->fetch()["count"];
}
function count_answers_in_question($questionId, $ignoreCorrect)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `answer` WHERE `questionId`=:questionId");  $query->execute(array(":questionId" => $questionId));
	$cCount = $query->fetch()["count"];
	if ($ignoreCorrect)
	{
		$query = $conn->prepare("SELECT `answerId` FROM `question` WHERE `questionId`=:questionId");  $query->execute(array(":questionId" => $questionId));
		if ($query->fetch()["answerId"] != NULL)
			$cCount--;
	}
	return $cCount;
}
function count_user_social_links($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `socialUserLookup` WHERE `userId`=:userId");  $query->execute(array(":userId" => $userId));
	return $query->fetch()["count"];
}
function get_user_social_links($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT `socialId`, `name` FROM `socialUserLookup` WHERE `userId`=:userId");  $query->execute(array(":userId" => $userId));
	return $query->fetchAll();
}
function get_user_social_links_for_id($userId, $socialId)
{
	global $conn;
	$query = $conn->prepare("SELECT `socialId`, `name` FROM `socialUserLookup` WHERE `userId`=:userId AND `socialId`=:socialId");  $query->execute(array(":userId" => $userId, ":socialId" => $socialId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_social_info_from_id($socialId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `social` WHERE `socialId`=:socialId");  $query->execute(array(":socialId" => $socialId));
	return $query->fetch();
}
function get_all_social()
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `social` ORDER BY `socialId` DESC");  $query->execute();
	return $query->fetchAll();
}
function get_countries()
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `countries` ORDER BY `name` ASC");  $query->execute();
	return $query->fetchAll();
}
function get_most_popular_universities($limit)
{
	if ($limit != 0)
	{
		$tLimit = "LIMIT " . $limit;
	}
	else
		$tLimit = "";
	
	global $conn;
	$query = $conn->prepare("SELECT `name` FROM `universities` WHERE `uniId` IN (SELECT `uniId` FROM `users` GROUP BY `uniId` ORDER BY COUNT(`uniId`) DESC) $tLimit");  $query->execute();
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function get_universities_in_country($countryId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `universities` WHERE `countryId`=:countryId");  $query->execute(array(":countryId"=>$countryId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function check_string_length($str, $lower, $upper)
{
	if (strlen($str) < $lower)
		return -1;
	else if (strlen($str) > $upper)
		return 1;
	else
		return 0;
}
function does_username_exist($username)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `users` WHERE `username`=:username");  $query->execute(array(":username" => $username));
	return $query->fetch()["count"] > 0;
}
function does_email_exist($email)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `users` WHERE `email`=:email");  $query->execute(array(":email" => $email));
	return $query->fetch()["count"] > 0;
}
function does_university_exist($uniId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `universities` WHERE `uniId`=:uniId");  $query->execute(array(":uniId" => $uniId));
	return $query->fetch()["count"] > 0;
}
function does_subject_exist($subjectId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `subject` WHERE `subjectId`=:subjectId");  $query->execute(array(":subjectId" => $subjectId));
	return $query->fetch()["count"] > 0;
}
function get_university_from_id($uniId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `universities` WHERE `uniId`=:uniId"); 
	$query->execute(array(":uniId"=>$uniId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function register($username, $email, $password, $university, $course) 
{
	$salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
	$password = crypt($password, '$2y$12$' . $salt);
	
	$emailCode = sprintf("%06d", mt_rand(1, 999999999999));
	
	do
	{
		$resetCode = sprintf("%06d", mt_rand(1, 999999999999));
	}
	while (get_user_from_reset_code($resetCode) !== FALSE);
	
	global $conn;
	$query = $conn->prepare("INSERT INTO `users` (`username`, `password`, `email`, `emailCode`, `resetCode`, `uniId`, `subjectId`, `register`, `lastOnline`) VALUES (:username, :password, :email, :emailCode, :resetCode, :uniId, :subjectId, :register, :lastonline)");
	$query->execute(array(
		":username" => $username,
		":password" => $password,
		":email" => $email,
		":emailCode" => $emailCode,
		":resetCode" => $resetCode,
		":uniId" => $university,
		":subjectId" => $course,
		":register" => time(),
		":lastonline" => time(),
	));
	
	mail($email, "Verify your University Network account.", "Hello " . $username . ", your verification code is " . $emailCode . ". You can also verify your account by clicking the following link; http://universitynetwork.co.uk/signup/verify/" . $emailCode, "From: no-reply@unvierstynetwork.co.uk");
}
function is_account_email_verified($uid)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `users` WHERE `uid`=:uid AND `emailVerified`='1'");  $query->execute(array(":uid" => $uid));
	return $query->fetch()["count"] > 0;
}
function does_email_code_exist($emailCode)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `users` WHERE `emailCode`=:emailCode AND `emailVerified`='0'");  $query->execute(array(":emailCode" => $emailCode));
	return $query->fetch()["count"] > 0;
}
function update_user_field($uid, $field, $value)
{
	global $conn;
	$query = $conn->prepare("UPDATE `users` SET $field=:value WHERE `uid`=:uid");
	$query->execute(array(":uid" => $uid, ":value" => $value));
}
function update_user_field_by_email($email, $field, $value)
{
	global $conn;
	$query = $conn->prepare("UPDATE `users` SET $field=:value WHERE `email`=:email");
	$query->execute(array(":email" => $email, ":value" => $value));
}
function draw_pageination($from, $to, $current, $url, $urlEnd = "")
{
	?>
	<!--Pagination-->
	<div class="text-center">
		<ul class="pagination">
			<li<?php if ($current == $from) echo ' class="disabled"'; ?>><a href="<?php if ($current == $from) echo 'javascript:void(0)'; else echo $url . ($current - 1) . $urlEnd; ?>">«</a></li>
			<?php
			for ($i = $from; $i <= $to; $i++)
			{
				echo '<li';
				if ($i == $current)
					echo ' class="active"';
				echo '><a href="';
				if ($i == $current)
					echo 'javascript:void(0)';
				else
					echo $url . $i . $urlEnd;
				echo '">'.$i.'</a></li>';
			}
			?>
			<li<?php if ($current == $to) echo ' class="disabled"'; ?>><a href="<?php if ($current == $to) echo 'javascript:void(0)'; else echo $url . ($current + 1) . $urlEnd; ?>">»</a></li>
		</ul>
	</div>
	<!--End Pagination-->
	<?php
}
function count_answer_down_vote($answerId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `answerVotes` WHERE `answerId`=:answerId AND `isUp` = '0'");  $query->execute(array(":answerId" => $answerId));
	return $query->fetch()["count"];
}
function count_answer_up_vote($answerId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `answerVotes` WHERE `answerId`=:answerId AND `isUp` = '1'");  $query->execute(array(":answerId" => $answerId));
	return $query->fetch()["count"];
}
function calculate_answer_vote_value($answerId)
{
	return count_answer_up_vote($answerId) - count_answer_down_vote($answerId);
}
function get_answer_vote_status_by_me($answerId)
{
	if (!is_logged_in())
		return 0;
	global $myUser;
	global $conn;
	$query = $conn->prepare("SELECT `isUp` FROM `answerVotes` WHERE `answerId`=:answerId AND `userId`=:userId");  $query->execute(array(":answerId" => $answerId, ":userId" => $myUser["uid"]));
	if ($query->rowCount() == 0)
		return 0;
	else if ($query->fetch()["isUp"] == '1')
		return 1;
	else
		return -1;
}
function create_vote($answerId, $vote)
{
	global $myUser;
	global $conn;
	$query = $conn->prepare("INSERT INTO `answerVotes` (`answerId`, `userId`, `isUp`, `timestamp`) VALUES (:answerId, :userId, :isUp, :time)");
	$query->execute(array(
		":answerId" => $answerId,
		":userId" => $myUser["uid"],
		":isUp" => $vote,
		":time" => time()
	));
}
function update_vote($answerId, $vote)
{
	global $myUser;
	global $conn;
	$query = $conn->prepare("UPDATE `answerVotes` SET `isUp`=:value, `timestamp`=:time WHERE `answerId`=:answerId AND `userId`=:userId");
	$query->execute(array(":answerId" => $answerId, ":userId" => $myUser["uid"], ":value" => $vote, ":time" => time()));
}
function does_answer_id_exist($answerId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `answer` WHERE `answerId`=:answerId");  $query->execute(array(":answerId" => $answerId));
	return $query->fetch()["count"] > 0;
}
function get_awards($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `awardLookup` WHERE `userId`=:userId");  $query->execute(array(":userId"=>$userId));
	return $query->fetchAll();
}
function get_award_from_id($awardId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `awards` WHERE `awardId`=:awardId"); 
	$query->execute(array(":awardId"=>$awardId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function js_escape($inp)
{
	return str_replace("'", "\\x27", $inp);
}
/*
 * Checks given text for specific keywords such as module codes,
 * and returns true if one or more is found.
 *
 * @param $text The text to be analysed
 * @return bool If the text picked up keywords and requires checking by a moderator.
 */
function input_moderation_analysis($text)
{
	$text = strtolower($text);
	$blacklist = array(
		"specification",
		"assignment",
		"assessment",
		"logbook",
		"collusion",
		"deadline",
		"unmoderated",
		"moderated",
	);
	foreach ($blacklist as $cWord)
	{
		if (strpos($text, $cWord) !== FALSE)
			return true;
	}
	return false;
}
function get_reputation_history_for_user($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `reputation` WHERE `userId`=:userId ORDER BY `timestamp` DESC LIMIT 5"); 
	$query->execute(array(":userId"=>$userId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
}
function post_question($subject, $content, $moduleId)
{
	$content = strip_banned_words($content);
	$subject = strip_banned_words($subject);
	global $myUser;
	global $conn;
	$query = $conn->prepare("INSERT INTO `question` (`userId`, `moduleId`, `question`, `text`, `timestamp`, `moderation`) VALUES (:uid, :moduleId, :subject, :content, :timestamp, :moderation)");
	$query->execute(array(
		":uid" => $myUser["uid"],
		":moduleId" => $moduleId,
		":subject" => $subject,
		":content" => $content,
		":timestamp" => time(),
		":moderation" => input_moderation_analysis($subject) || input_moderation_analysis($content)
	));	
	$qID = $conn->lastInsertId();
	
	$cQuestion = get_question_from_id($qID);
	$cModule = get_module_by_id($cQuestion["moduleId"]);
	
	add_reputation(25, "Asked the following question <a href='/modules/".str_replace(" ", "-", strtolower($cModule["name"]))."/".clean_text_for_url($cQuestion["question"])."/q".$qID."/p1'>".$cQuestion["question"]."</a>");
	
	header("LOCATION: /modules/".str_replace(" ", "-", strtolower(get_module_by_id($moduleId)["name"]))."/".clean_text_for_url($subject)."/q".$qID."/p1");
}
function update_question_field($questionId, $field, $value)
{
	global $conn;
	$query = $conn->prepare("UPDATE `question` SET $field=:value WHERE `questionId`=:questionId");
	$query->execute(array(":questionId" => $questionId, ":value" => $value));
}
function post_report($type, $id, $text)
{
	$text = strip_banned_words($text);
	if ($type == "question")
		$typeNum = 1;
	else
		$typeNum = 2;
	
	global $myUser;
	global $conn;
	$query = $conn->prepare("INSERT INTO `report` (`userId`, `unquieId`, `type`, `content`, `timestamp`) VALUES (:uid, :unquieId, :type, :content, :timestamp)");
	$query->execute(array(
		":uid" => $myUser["uid"],
		":unquieId" => $id,
		":type" => $typeNum,
		":content" => $text,
		":timestamp" => time()
	));	
}
function count_users_at_university($uniId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `users` WHERE `uniId`=:uniId");  $query->execute(array(":uniId" => $uniId));
	return $query->fetch()["count"];
}
function get_university_by_name($name)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `universities` WHERE `name`=:name"); 
	$query->execute(array(":name"=>$name));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetch();
}
function get_user_subjects_in_university($uniId)
{
	global $conn;
	$query = $conn->prepare("SELECT DISTINCT `subjectId` FROM `users` WHERE `uniId`=:uniId AND `emailVerified`='1'"); 
	$query->execute(array(":uniId"=>$uniId));
	if ($query->rowCount() == 0)
		return false;
	else
		return $query->fetchAll();
	
}
function get_users_from_university_in_subject($uniId, $subjectId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `users` WHERE `uniId`=:uniId AND `subjectId`=:subjectId AND `emailVerified`='1' ORDER BY `username` ASC");  $query->execute(array(":uniId" => $uniId, ":subjectId" => $subjectId));
	return $query->fetchAll();
}
function post_answer($content, $questionId)
{
	$content = strip_banned_words($content);
	global $myUser;
	global $conn;
	$query = $conn->prepare("INSERT INTO `answer` (`userId`, `questionId`, `text`, `timestamp`) VALUES (:uid, :questionId, :content, :timestamp)");
	$query->execute(array(
		":uid" => $myUser["uid"],
		":questionId" => $questionId,
		":content" => $content,
		":timestamp" => time()
	));
	
	$cQuestion = get_question_from_id($questionId);
	$cModule = get_module_by_id($cQuestion["moduleId"]);
	
	add_reputation(10, "Commented an answer on the question <a href='/modules/".str_replace(" ", "-", strtolower($cModule["name"]))."/".clean_text_for_url($cQuestion["question"])."/q".$questionId."/p1'>".$cQuestion["question"]."</a>");
}
function add_reputation($amt, $reason)
{
	global $myUser;	
	global $conn;
	$query = $conn->prepare("INSERT INTO `reputation` (`userId`, `amount`, `reason`, `timestamp`) VALUES (:uid, :amt, :reason, :timestamp)");
	$query->execute(array(
		":uid" => $myUser["uid"],
		":amt" => $amt,
		":reason" => $reason,
		":timestamp" => time()
	));
	award_check(5, $myUser["uid"]);
	award_check(6, $myUser["uid"]);
}
function add_reputation_user($userId, $amt, $reason)
{
	global $conn;
	$query = $conn->prepare("INSERT INTO `reputation` (`userId`, `amount`, `reason`, `timestamp`) VALUES (:uid, :amt, :reason, :timestamp)");
	$query->execute(array(
		":uid" => $userId,
		":amt" => $amt,
		":reason" => $reason,
		":timestamp" => time()
	));
	award_check(5, $userId);
	award_check(6, $userId);
}
function get_reputation($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT SUM(`amount`) as `amount` FROM `reputation` WHERE `userId`=:userId");  $query->execute(array(":userId" => $userId));
	$amt = $query->fetch()["amount"];
	
	if ($amt == NULL)
		return 0;
	else
		return $amt;
}
function mark_accepted_answer($questionId, $answerId)
{
	update_question_field($questionId, "answerId", $answerId);
	$tAnswer = get_answer_from_id($answerId);
	
	$cQuestion = get_question_from_id($tAnswer["questionId"]);
	$cModule = get_module_by_id($cQuestion["moduleId"]);
	
	add_reputation_user($tAnswer["userId"], 50, "Had a answer marked as the accepted answer for the question <a href='/modules/".str_replace(" ", "-", strtolower($cModule["name"]))."/".clean_text_for_url($cQuestion["question"])."/q".$tAnswer["questionId"]."/p1'>".$cQuestion["question"]."</a>");
	award_check(1, $tAnswer["userId"]);
	award_check(2, $tAnswer["userId"]);
	award_check(3, $tAnswer["userId"]);
	award_check(4, $tAnswer["userId"]);
}
function count_accepted_answers($userId)
{
	global $conn;
	$query = $conn->prepare("SELECT COUNT(*) as `count` FROM `question` INNER JOIN `answer` ON question.answerId = answer.answerId WHERE answer.userId = :userId");  $query->execute(array(":userId" => $userId));
	return $query->fetch()["count"];
}
function does_user_have_award($awdId, $userId)
{
	global $conn;
	$query = $conn->prepare("SELECT * FROM `awardLookup` WHERE `userId` = :userId");  $query->execute(array(":userId" => $userId));
	return $query->rowCount() > 0;
}
function unlock_award($awdId, $userId)
{
	global $conn;
	if (!does_user_have_award($awdId, $userId))
	{
		$query = $conn->prepare("INSERT INTO `awardLookup` (`userId`, `awardId`, `timestamp`) VALUES (:uid, :awardId, :timestamp)");
		$query->execute(array(
			":uid" => $userId,
			":awardId" => $awdId,
			":timestamp" => time()
		));
	}
}
function award_check($awdId, $userId)
{
	$cAward = get_award_from_id($awdId);
	
	switch ($awdId)
	{
		case 1:
		if (count_accepted_answers($userId) >= 10)
		{
			unlock_award($awdId, $userId);
			add_reputation_user($userId, 150, "Unlocked the achievement " . $cAward["name"]);
		}
		break;
		case 2:
		if (count_accepted_answers($userId) >=25)
		{
			unlock_award($awdId, $userId);
			add_reputation_user($userId, 200, "Unlocked the achievement " . $cAward["name"]);
		}
		break;
		case 3:
		if (count_accepted_answers($userId) >= 50)
		{
			unlock_award($awdId, $userId);
			add_reputation_user($userId, 250, "Unlocked the achievement " . $cAward["name"]);
		}
		break;
		case 4:
		if (count_accepted_answers($userId) >= 100)
		{
			unlock_award($awdId, $userId);
			add_reputation_user($userId, 500, "Unlocked the achievement " . $cAward["name"]);
		}
		break;
		case 5:
		if (get_reputation($userId) >= 1000)
		{
			unlock_award($awdId, $userId);
			add_reputation_user($userId, 250, "Unlocked the achievement " . $cAward["name"]);
		}
		break;
		case 6:
		if (get_reputation($userId) >= 2500)
		{
			unlock_award($awdId, $userId);
			add_reputation_user($userId, 500, "Unlocked the achievement " . $cAward["name"]);
		}
		break;
	}
}
function get_ban_list()
{
	$banList = array(
		"fuck",
		"bitch",
		"pussy",
		"motherfucker",
		"wanker",
		"dickhead",
		"cunt",
		"knobhead",
		"arsehole",
		"bastard",
		"bellend",
		"plonker",
		"tosser",
		"twat",
		"nigga",
		"shit",
		"whore",
		"paki",
	);
	return $banList;
}
function is_banned_word($inp)
{
	foreach (get_ban_list() as $bWord)
	{
		if (strpos($inp, $bWord) !== FALSE)
		{
			return true;
		}
	}
	return false;
}
function strip_banned_words($inp)
{
	foreach (get_ban_list() as $bWord)
	{
		$replace = $bWord[0];
		for ($i = 0; $i < strlen($bWord) - 1; $i++)
			$replace .= "*";
		$inp = str_replace($bWord, $replace, $inp);
	}
	return $inp;
}
function delete_answer($answerId)
{
	global $conn;
	
	$cAnswer = get_answer_from_id($answerId);
	
	$query = $conn->prepare("UPDATE `answer` SET `deleted`='1' WHERE `answerId`=:answerId");
	$query->execute(array(":answerId" => $answerId));
	
	add_reputation_user($cAnswer["userId"], -10, "Had an answer removed by a moderator.");
	
	
	$query = $conn->prepare("SELECT `userId` FROM `question` WHERE `answerId`=:answerId");
	$query->execute(array(":answerId" => $answerId));
	if ($query->rowCount() > 0)
	{
		$deductUser = $query->fetch()["userId"];
		
		add_reputation_user($deductUser, -50, "Had an accepted answer removed by a moderator.");
		
		$query = $conn->prepare("UPDATE `question` SET `answerId`=NULL WHERE `answerId`=:answerId");
		$query->execute(array(":answerId" => $answerId));
	}
}
function delete_question($questionId)
{
	global $conn;
	
	$cQuestion = get_question_from_id($questionId);
	
	$query = $conn->prepare("UPDATE `question` SET `deleted`='1' WHERE `questionId`=:questionId");
	$query->execute(array(":questionId" => $questionId));
	
	add_reputation_user($cQuestion["userId"], -25, "Had a question removed by a moderator.");
}
function add_social_for_user($uid, $socialId, $name)
{
	global $conn;
	$query = $conn->prepare("INSERT INTO `socialUserLookup` (`userId`, `socialId`, `name`) VALUES (:uid, :socialid, :name)");
	$query->execute(array(
		":uid" => $uid,
		":socialid" => $socialId,
		":name" => $name
	));
}
function delete_social_for_user($uid)
{
	global $conn;
	$query = $conn->prepare("DELETE FROM `socialUserLookup` WHERE `userId`=:uid");
	$query->execute(array(
		":uid" => $uid
	));
}
?>