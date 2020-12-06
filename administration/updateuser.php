<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { header("location: ../index.php"); exit; }
if (!checkrights("M")) fallback("../index.php");
if (!isset($user_id) || !isNum($user_id)) fallback(FUSION_SELF);

$error = ""; $set_avatar = "";
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$user_id'");
$data2 = dbarray($result);
if ($user_id == 1) $error .= $locale['450']."<br>\n";

$user_name = trim(eregi_replace(" +", " ", $_POST['user_name']));

if ($user_name == "" || $_POST['user_email'] == "") {
	$error .= $locale['451']."<br>\n";
} else {
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $user_name)) $error .= $locale['452']."<br>\n";
	
	if ($user_name != $data2['user_name']) {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$user_name'");
		if (dbrows($result) != 0) {
			$error .= $locale['453']."<br>\n";
		}
	}
	
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['user_email'])) $error .= $locale['454']."<br>\n";
	
	if ($_POST['user_email'] != $data2['user_email']) {
		$result = dbquery("SELECT user_email FROM ".$db_prefix."users WHERE user_email='".$_POST['user_email']."'");
		if (dbrows($result) != 0) {
			$error = $locale['455']."<br>\n";
		}
	}
}

if ($_POST['user_newpassword'] != "") {
	if ($_POST['user_newpassword2'] != $_POST['user_newpassword']) {
		$error .= $locale['456']."<br>";
	} else {
		if ($_POST['user_hash'] == $data2['user_password']) {
			if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['user_newpassword'])) {
				$error .= $locale['457']."<br>\n";
			}
		} else {			
			$error .= $locale['458']."<br>\n";
		}
	}
}

$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
$user_location = isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "";
if ($_POST['user_month'] != 0 && $_POST['user_day'] != 0 && $_POST['user_year'] != 0) {
	$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "0000")
	."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "00")
	."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "00");
} else {
	$user_birthdate = "0000-00-00";
}
$user_aim = isset($_POST['user_aim']) ? stripinput(trim($_POST['user_aim'])) : "";
$user_icq = isset($_POST['user_icq']) ? stripinput(trim($_POST['user_icq'])) : "";
$user_msn = isset($_POST['user_msn']) ? stripinput(trim($_POST['user_msn'])) : "";
$user_yahoo = isset($_POST['user_yahoo']) ? stripinput(trim($_POST['user_yahoo'])) : "";
$user_web = isset($_POST['user_web']) ? stripinput(trim($_POST['user_web'])) : "";
$user_theme = stripinput($_POST['user_theme']);
$user_offset = is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0";
$user_sig = isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "";

if ($error == "") {
	if ($data2['user_avatar'] == "" && !empty($_FILES['user_avatar'])) {
		$newavatar = $_FILES['user_avatar'];
		if (is_uploaded_file($newavatar['tmp_name']) && $newavatar['size'] <= 20000) {
			$avatarext = strrchr($newavatar['name'],".");
			if ($avatarext == ".gif" || $avatarext == ".jpg" || $avatarext == ".png") {
				$avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
				$avatarname = $avatarname."[".$user_id."]".$avatarext;
				$set_avatar = "user_avatar='$avatarname', ";
				move_uploaded_file($newavatar['tmp_name'], IMAGES."avatars/".$avatarname);
				chmod(IMAGES."/avatars/".$avatarname,0644);
				$size = getimagesize(IMAGES."avatars/".$avatarname);
				if ($size['0'] > 100 || $size['1'] > 100) {
					unlink(IMAGES."avatars/".$avatarname);
				}
			}
		}
	}

	if (isset($_POST['del_avatar'])) {
		$set_avatar = "user_avatar='', ";
		unlink(IMAGES."avatars/".$data2['user_avatar']);
	}
	
	if ($user_newpassword != "") { $newpass = " user_password=md5('$user_newpassword'), "; } else { $newpass = " "; }
	$result = dbquery("UPDATE ".$db_prefix."users SET user_name='$user_name',".$newpass."user_email='".$_POST['user_email']."', user_hide_email='$user_hide_email', user_location='$user_location', user_birthdate='$user_birthdate', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_web='$user_web', user_theme='$user_theme', user_offset='$user_offset', ".$set_avatar."user_sig='$user_sig' WHERE user_id='$user_id'");
}
?>