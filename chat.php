<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/assets/php/config.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/assets/php/functions.php");
if (is_logged_in())
{
	if (isset($_POST["uid"]) && is_numeric($_POST["uid"]))
	{
		$cUser = get_user_from_uid($_POST["uid"]);
		if ($cUser != false && $cUser["uid"] != $myUser["uid"] && are_friends($cUser["uid"], $myUser["uid"]))
		{
			if (isset($_POST["msg"]))
			{
				$msg = htmlspecialchars($_POST["msg"]);
				$msgLen = check_string_length($msg, 1, 254);
				if ($msgLen == 0)
				{
					post_message($myUser["uid"], $cUser["uid"], $msg);
				}
			}
	?>
	<li id="chat_<?php echo $cUser["uid"]; ?>" class="chat-open">
		<div class="open-chat-panel">
			<div class="open-chat-header">
				<a href="/profile/<?php echo clean_text_for_url_username($cUser["username"]); ?>/"><?php echo $cUser["username"]; ?></a>
				<div class="open-chat-icons">
					<i onclick="openChat('<?php echo $cUser["uid"]; ?>');" id="minimax_<?php echo $cUser["uid"]; ?>" class="fa fa-minus" aria-hidden="true" style="font-size: 14px;"></i>
					<i onclick="closeChat('<?php echo $cUser["uid"]; ?>');" class="fa fa-times" aria-hidden="true"></i>
				</div>
			</div>
			<div class="open-chat-content">
				<?php
				$messages = get_chat_messages($myUser["uid"], $cUser["uid"]);
				mark_messages_as_read($myUser["uid"], $cUser["uid"]);
				if ($messages == false)
					echo '<div class="ochat-heading">Send a message to say hello!</div>';
				else
				{
					foreach ($messages as $message)
					{
						$isMyMessage = false;
						if ($message["senderId"] == $myUser["uid"])
							$isMyMessage = true;
						?>
						<div class="ochat-<?php if ($isMyMessage) echo 'me'; else echo 'them'; ?>">
							<div class="ochat-time"><?php echo time_to_ago($message["timestamp"]); ?></div>
							<div class="ochat-msg">
								<?php echo $message["content"]; ?>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<div class="open-chat-footer">
				<form method="POST" onsubmit="return sendMessage(<?php echo $cUser["uid"]; ?>);">
					<input type="text" maxlength="254" id="messageText_<?php echo $cUser["uid"]; ?>" placeholder="Type a message...">
					<input type="submit" style="display: none;">
				</form>
			</div>
		</div>
	</li>
	<?php
		}
	}
}
?>