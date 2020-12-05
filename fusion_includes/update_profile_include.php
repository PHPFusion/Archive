<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) � 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }
if (!iMEMBER) { header("Location:index.php"); exit; }
include FUSION_LANGUAGES.FUSION_LAN."members-profile.php";

$username = trim(chop($_POST['user_name']));
if ($username == "" || $_POST['user_email'] == "") {
	$error .= LAN_480."<br>\n";
} else {
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= LAN_481."<br>\n";
	
	if ($username != $userdata['user_name']) {
		$result = dbquery("SELECT user_name FROM ".$fusion_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error = LAN_482."<br>\n";
	}
	
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['user_email'])) $error .= LAN_483."<br>\n";
	
	if ($_POST['user_email'] != $userdata['user_email']) {
		$result = dbquery("SELECT user_email FROM ".$fusion_prefix."users WHERE user_email='".$_POST['user_email']."'");
		if (dbrows($result) != 0) $error = LAN_484."<br>\n";
	}
}

if ($_POST['user_newpassword'] != "") {
	if ($_POST['user_newpassword2'] != $_POST['user_newpassword']) {
		$error .= LAN_485."<br>";
	} else {
		if ($_POST['user_hash'] == $userdata['user_password']) {
			if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['user_newpassword'])) {
				$error .= LAN_486."<br>\n";
			}
		} else {			
			$error .= LAN_487."<br>\n";
		}
	}
}

if ($_POST['user_location']) $user_location = stripinput(trim(chop($_POST['user_location'])));
if ($_POST['user_icq']) $user_icq = stripinput(trim(chop($_POST['user_icq'])));
if ($_POST['user_msn']) $user_msn = stripinput(trim(chop($_POST['user_msn'])));
if ($_POST['user_yahoo']) $user_yahoo = stripinput(trim(chop($_POST['user_yahoo'])));
if ($_POST['user_web']) $user_web = stripinput(trim(chop($_POST['user_web'])));
if ($_POST['user_month'] != 0 && $_POST['user_day'] != 0 && $_POST['user_year'] != 0) {
	$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "0000")
	."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "00")
	."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "00");
} else {
	$user_birthdate = "0000-00-00";
}

$user_sig = stripinput($_POST['user_sig']);

if ($error == "") {
	if ($userdata['user_avatar'] == "" && !empty($_FILES['user_avatar'])) {
		$newavatar = $_FILES['user_avatar'];
		if (is_uploaded_file($newavatar['tmp_name']) && $newavatar['size'] <= 20000) {
			$avatarext = strrchr($newavatar['name'],".");
			if ($avatarext == ".gif" || $avatarext == ".jpg" || $avatarext == ".png") {
				$avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
				$avatarname = $avatarname."[".$userdata['user_id']."]".$avatarext;
				$set_avatar = "user_avatar='$avatarname', ";
				move_uploaded_file($newavatar['tmp_name'], FUSION_PUBLIC."avatars/".$avatarname);
				chmod(FUSION_BASE."fusion_public/avatars/".$avatarname,0644);
				$size = getimagesize(FUSION_PUBLIC."avatars/".$avatarname);
				if ($size['0'] > 100 || $size['1'] > 100) {
					unlink(FUSION_PUBLIC."avatars/".$avatarname);
				}
			}
		}
	}
	
	if (isset($_POST['del_avatar'])) {
		$set_avatar = "user_avatar='', ";
		unlink(FUSION_PUBLIC."avatars/".$userdata['user_avatar']);
	}
	
	if ($user_newpassword != "") { $newpass = " user_password=md5('$user_newpassword'), "; } else { $newpass = " "; }
	$result = dbquery("UPDATE ".$fusion_prefix."users SET user_name='$username',".$newpass."user_email='".$_POST['user_email']."', user_hide_email='".$_POST['user_hide_email']."', user_location='$user_location', user_birthdate='$user_birthdate', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_web='$user_web', user_theme='".$_POST['user_theme']."', user_offset='".$_POST['user_offset']."', ".$set_avatar."user_sig='$user_sig' WHERE user_id='".$userdata['user_id']."'");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		header("Location:edit_profile.php?update_profile=ok");
	}
}
?>