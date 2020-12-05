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
if (!defined("IN_FUSION")) header("Location: ../index.php");
require fusion_langdir."editprofile.php";

if (!Member()) header("Location: ".fusion_basedir."index.php");

$user_name = trim(chop(eregi_replace(" +", " ", $_POST['user_name'])));
if ($user_name == "" || $_POST['user_email'] == "") {
	$error .= LAN_440."<br>\n";
} else {
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $user_name)) {
		$error .= LAN_441."<br>\n";
	}
	if ($user_name != $userdata['user_name']) {
		$result = dbquery("SELECT user_name FROM ".$fusion_prefix."users WHERE user_name='$user_name'");
		if (dbrows($result) != 0) {
			$error = LAN_448."<br>\n";
		}
	}
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $_POST['user_email'])) {
		$error .= LAN_442."<br>\n";
	}
	if ($_POST['user_email'] != $userdata['user_email']) {
		$result = dbquery("SELECT user_email FROM ".$fusion_prefix."users WHERE user_email='".$_POST['user_email']."'");
		if (dbrows($result) != 0) {
			$error = LAN_449."<br>\n";
		}
	}
}
if ($_POST['user_newpassword'] != "") {
	if ($_POST['user_newpassword2'] != $_POST['user_newpassword']) {
		$error .= LAN_443."<br>";
	} else {
		if ($_POST['user_hash'] == $userdata['user_password']) {
			if (!preg_match("/^[0-9A-Z]+$/i", $_POST['user_newpassword'])) {
				$error .= LAN_444."<br>\n";
			}
		} else {
			$error .= LAN_450."<br>\n";
		}
	}
}
if ($_POST['user_icq']) {
	if (!preg_match("/^[0-9]+$/i", $_POST['user_icq'])) {
		$error .= LAN_445."<br>\n";
	}
}
if ($_POST['user_msn']) {
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $_POST['user_msn'])) {
		$error .= LAN_446."<br>\n";
	}
}
if ($_POST['user_yahoo']) {
	if (!preg_match("/^[_0-9A-Z]+$/i", $_POST['user_yahoo'])) {
		$error .= LAN_447."<br>\n";
	}
}
$user_location = stripinput($_POST['user_location']);
if ($_POST['user_month'] != 0 && $_POST['user_day'] != 0 && $_POST['user_year'] != 0) {
	$user_birthdate = $_POST['user_year']."-".$_POST['user_month']."-".$_POST['user_day'];
} else {
	$user_birthdate = "0000-00-00";
}
$user_web = stripinput($_POST['user_web']);
$user_signature = stripinput($_POST['user_signature']);
if ($error == "") {
	if ($userdata['user_avatar'] != "" && $user_avatar == "") {
		$set_avatar = "user_avatar='', ";
		unlink(fusion_basedir."fusion_public/avatars/".$userdata['user_avatar']);
	} else if ($userdata['user_avatar'] == "" && !empty($_FILES['user_avatar'])) {
		$newavatar = $_FILES['user_avatar'];
		if (is_uploaded_file($newavatar['tmp_name']) && $newavatar['size'] <= 20000) {
			$avatarext = strrchr($newavatar['name'],".");
			if ($avatarext == ".gif" || $avatarext == ".jpg" || $avatarext == ".png") {
				$avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
				$avatarname = $avatarname."[".$userdata['user_id']."]".$avatarext;
				$set_avatar = "user_avatar='$avatarname', ";
				move_uploaded_file($newavatar['tmp_name'], fusion_basedir."fusion_public/avatars/".$avatarname);
				chmod(fusion_basedir."fusion_public/avatars/".$avatarname,0644);
				$size = getimagesize(fusion_basedir."fusion_public/avatars/".$avatarname);
				if ($size['0'] > 100 || $size['1'] > 100) {
					unlink(fusion_basedir."fusion_public/avatars/".$avatarname);
				}
			}
		}
	}
	if ($user_newpassword != "") { $newpass = " user_password=md5('$user_newpassword'),"; } else { $newpass = ""; }
	$result = dbquery("UPDATE ".$fusion_prefix."users SET user_name='$user_name',".$newpass." user_email='".$_POST['user_email']."', user_hide_email='".$_POST['user_hide_email']."', user_location='$user_location', user_birthdate='$user_birthdate', user_icq='".$_POST['user_icq']."', user_msn='".$_POST['user_msn']."', user_yahoo='".$_POST['user_yahoo']."', user_web='$user_web', user_theme='".$_POST['user_theme']."', user_offset='".$_POST['user_offset']."', ".$set_avatar."user_sig='$user_signature' WHERE user_id='".$userdata['user_id']."'");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		header("Location: editprofile.php?update_profile=ok");
	}
}
?>