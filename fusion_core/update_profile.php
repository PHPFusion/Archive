<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
require fusion_langdir."editprofile.php";

$user_name = trim(chop(str_replace("&nbsp;", "", $user_name)));
if ($user_name == "" || $user_email == "") {
	$error .= LAN_440."<br>\n";
} else {
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $user_name)) {
		$error .= LAN_441."<br>\n";
	}
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $user_email)) {
		$error .= LAN_442."<br>\n";
	}
}
if ($user_newpassword != "") {
	if ($user_newpassword2 != $user_newpassword) {
		$error .= LAN_443."<br>";
	} else {
		if (!preg_match("/^[0-9A-Z]+$/i", $user_newpassword)) {
			$error .= LAN_444."<br>\n";
		}
	}
}
if ($user_icq) {
	if (!preg_match("/^[0-9]+$/i", $user_icq)) {
		$error .= LAN_445."<br>\n";
	}
}
if ($user_msn) {
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $user_msn)) {
		$error .= LAN_446."<br>\n";
	}
}
if ($user_yahoo) {
	if (!preg_match("/^[_0-9A-Z]+$/i", $user_yahoo)) {
		$error .= LAN_447."<br>\n";
	}
}
$user_location = stripinput($user_location);
$user_web = stripinput($user_web);
$user_signature = stripinput($user_signature);
if ($user_name != "") {
	if ($user_name != $userdata[user_name]) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$user_name'");
		if (dbrows($result) != 0) {
			$error = LAN_448."<br>\n";
		}
	}
}
if ($error == "") {
	if ($userdata[user_avatar] != "" && $user_avatar == "") {
		$set_avatar = "user_avatar='', ";
		unlink(fusion_basedir."fusion_public/avatars/".$userdata[user_avatar]);
	} else if ($userdata[user_avatar] == "" && !empty($_FILES['user_avatar'])) {
		$newavatar = $_FILES['user_avatar'];
		if (is_uploaded_file($newavatar[tmp_name]) && $newavatar[size] <= 20000) {
			$avatarext = strrchr($newavatar[name],".");
			if ($avatarext == ".gif" || $avatarext == ".jpg" || $avatarext == ".png") {
				$avatarname = substr($newavatar[name], 0, strrpos($newavatar[name], "."));
				$avatarname = $avatarname."[".$userdata[user_id]."]".$avatarext;
				$set_avatar = "user_avatar='$avatarname', ";
				move_uploaded_file($newavatar[tmp_name], fusion_basedir."fusion_public/avatars/".$avatarname);
				chmod(fusion_basedir."fusion_public/avatars/".$avatarname,0644);
				$size = getimagesize(fusion_basedir."fusion_public/avatars/".$avatarname);
				if ($size[0] > 100 || $size[1] > 100) {
					unlink(fusion_basedir."fusion_public/avatars/".$avatarname);
				}
			}
		}
	}
	if ($user_name != $userdata[user_name]) {
		$result = dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_name='$userdata[user_id].$user_name' WHERE shout_name='$userdata[user_id].$userdata[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."comments SET comment_name='$userdata[user_id].$user_name' WHERE comment_name='$userdata[user_id].$userdata[user_name]'");
		if ($userdata[user_mod] > "2") {
			if ($user_email != $userdata[user_email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$user_name', news_email='$user_email' WHERE news_name='$userdata[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$user_name', article_email='$user_email' WHERE article_name='$userdata[user_name]'");
			} else {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$user_name' WHERE news_name='$userdata[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$user_name' WHERE article_name='$userdata[user_name]'");
			}
		}
		if ($user_newpassword != "") { $newpass = " user_password=md5('$user_newpassword'),"; } else { $newpass = ""; }
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_name='$user_name',$newpass user_email='$user_email', user_hide_email='$user_hide_email', user_location='$user_location', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_web='$user_web', user_theme='$user_theme', user_offset='$user_offset', ".$set_avatar."user_sig='$user_signature' WHERE user_id='$userdata[user_id]'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$userdata[user_id]'");
		if (dbrows($result) != 0) {
			$userdata = dbarray($result);
			header("Location:editprofile.php?update_profile=ok");
		}
	} else {
		if ($userdata[user_mod] > "2") {
			if ($user_email != $userdata[user_email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_email='$user_email' WHERE news_email='$userdata[user_email]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_email='$user_email' WHERE article_email='$userdata[user_email]'");
			}
		}
		if ($user_newpassword != "") { $newpass = "user_password=md5('$user_newpassword'),"; } else { $newpass = ""; }
		$result = dbquery("UPDATE ".$fusion_prefix."users SET $newpass user_email='$user_email', user_hide_email='$user_hide_email', user_location='$user_location', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_web='$user_web', user_theme='$user_theme', user_offset='$user_offset', ".$set_avatar."user_sig='$user_signature' WHERE user_id='$userdata[user_id]'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$userdata[user_id]'");
		if (dbrows($result) != 0) {
			$userdata = dbarray($result);
			header("Location:editprofile.php?update_profile=ok");
		}
	}
}
?>