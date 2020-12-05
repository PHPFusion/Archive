<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require fusion_langdir."editprofile.php";

$username = trim(chop(str_replace("&nbsp;", "", $username)));
if ($username == "" || $email == "") {
	$error .= LAN_240."<br>\n";
} else {
	if (!preg_match("/^[-0-9A-Z@\s]+$/i", $username)) {
		$error .= LAN_241."<br>\n";
	}
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $email)) {
		$error .= LAN_242."<br>\n";
	}
}
if ($newpassword != "") {
	if ($newpassword2 != $newpassword) {
		$error .= LAN_243."<br>";
	} else {
		if (!preg_match("/^[0-9A-Z]+$/i", $newpassword)) {
			$error .= LAN_244."<br>\n";
		}
	}
}
if ($icq) {
	if (!preg_match("/^[0-9]+$/i", $icq)) {
		$error .= LAN_245."<br>\n";
	}
}
if ($msn) {
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $msn)) {
		$error .= LAN_246."<br>\n";
	}
}
if ($yahoo) {
	if (!preg_match("/^[_0-9A-Z]+$/i", $yahoo)) {
		$error .= LAN_247."<br>\n";
	}
}
$location = stripinput($location);
$web = stripinput($web);
$signature = stripinput($signature);
if ($username != "") {
	if ($username != $userdata[user_name]) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) {
			$error = LAN_248."<br>\n";
		}
	}
}
if ($error == "") {
	if ($userdata[user_avatar] != "" && $avatar == "") {
		$user_avatar = "user_avatar='', ";
		unlink(fusion_basedir."avatars/".$userdata[user_avatar]);
	} else if ($userdata[user_avatar] == "" && !empty($_FILES['avatar'])) {
		$newavatar = $_FILES['avatar'];
		if (is_uploaded_file($newavatar[tmp_name]) && $newavatar[size] <= 20000) {
			$avatarext = strrchr($newavatar[name],".");
			if ($avatarext == ".gif" || $avatarext == ".jpg" || $avatarext == ".png") {
				$avatarname = substr($newavatar[name], 0, strrpos($newavatar[name], "."));
				$avatarname = $avatarname."[".$userdata[user_id]."]".$avatarext;
				$user_avatar = "user_avatar='$avatarname', ";
				move_uploaded_file($newavatar[tmp_name], fusion_basedir."avatars/".$avatarname);
				$size = getimagesize(fusion_basedir."avatars/".$avatarname);
				if ($size[0] > 100 || $size[1] > 100) {
					unlink(fusion_basedir."avatars/".$avatarname);
				}
			}
		}
	}
	if ($username != $userdata[user_name]) {
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_lastuser='$userdata[user_id].$username' WHERE forum_lastuser='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_author='$userdata[user_id].$username' WHERE thread_author='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_lastuser='$userdata[user_id].$username' WHERE thread_lastuser='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_author='$userdata[user_id].$username' WHERE post_author='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_edituser='$userdata[user_id].$username' WHERE post_edituser='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."poll_votes SET vote_user='$userdata[user_id].$username' WHERE vote_user='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_to='$userdata[user_id].$username' WHERE message_to='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_from='$userdata[user_id].$username' WHERE message_from='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_name='$userdata[user_id].$username' WHERE shout_name='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."comments SET comment_name='$userdata[user_id].$username' WHERE comment_name='$userdata[user_id].$userdata[user_name]'");
		if ($userdata[user_mod] > "1") {
			if ($email != $userdata[user_email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$username', news_email='$email' WHERE news_name='$userdata[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$username', article_email='$email' WHERE article_name='$userdata[user_name]'");
			} else {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$username' WHERE news_name='$userdata[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$username' WHERE article_name='$userdata[user_name]'");
			}
		}
		if ($newpassword != "") { $newpass = " user_password=md5('$newpassword'),"; } else { $newpass = ""; }
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_name='$username',$newpass user_email='$email', user_hide_email='$hide_email', user_location='$location', user_icq='$icq', user_msn='$msn', user_yahoo='$yahoo', user_web='$web', ".$user_avatar."user_sig='$signature' WHERE user_id='$userdata[user_id]'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$userdata[user_id]'");
		if (dbrows($result) != 0) {
			$userdata = dbarray($result);
		}
	} else {
		if ($userdata[user_mod] > "1") {
			if ($email != $userdata[email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_email='$email' WHERE news_email='$userdata[user_email]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_email='$email' WHERE article_email='$userdata[user_email]'");
			}
		}
		if ($newpassword != "") { $newpass = "user_password=md5('$newpassword'),"; } else { $newpass = ""; }
		$result = dbquery("UPDATE ".$fusion_prefix."users SET $newpass user_email='$email', user_hide_email='$hide_email', user_location='$location', user_icq='$icq', user_msn='$msn', user_yahoo='$yahoo', user_web='$web', ".$user_avatar."user_sig='$signature' WHERE user_id='$userdata[user_id]'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$userdata[user_id]'");
		if (dbrows($result) != 0) {
			$userdata = dbarray($result);
		}
	}
}
?>