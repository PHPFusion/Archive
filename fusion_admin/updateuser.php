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
require fusion_langdir."admin/admin_members.php";

if (Admin()) {

$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
$data2 = dbarray($result);
if ($user_id == 1) {
	$error .= LAN_450."<br>\n";
}
$user_name = trim(chop(str_replace("&nbsp;", "", $_POST['user_name'])));
if ($user_name == "" || $_POST['user_email'] == "") {
	$error .= LAN_451."<br>\n";
}
if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $user_name)) {
	$error .= LAN_452."<br>\n";
}
if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $_POST['user_email'])) {
	$error .= LAN_453."<br>\n";
}
if ($_POST['user_icq']) {
	if (!preg_match("/^[0-9]+$/i", $_POST['user_icq'])) {
		$error .= LAN_456."<br>\n";
	}
}
if ($_POST['user_msn']) {
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $_POST['user_msn'])) {
		$error .= LAN_457."<br>\n";
	}
}
if ($_POST['user_yahoo']) {
	if (!preg_match("/^[_0-9A-Z]+$/i", $_POST['user_yahoo'])) {
		$error .= LAN_458."<br>\n";
	}
}
$user_location = stripinput($_POST['user_location']);
$user_web = stripinput($_POST['user_web']);
$user_signature = stripinput($_POST['user_signature']);
if ($user_name != "") {
	if ($user_name != $data2[user_name]) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$user_name'");
		if (dbrows($result) != 0) {
			$error .= LAN_459."<br>\n";
		}
	}
}
if ($error == "") {
	if ($data2[user_avatar] != "" && $user_avatar == "") {
		$set_avatar = "user_avatar='', ";
		unlink(fusion_basedir."fusion_public/avatars/".$data2[user_avatar]);
	}
	if ($user_name != $data2[user_name]) {
		$result = dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_name='$data2[user_id].$user_name' WHERE shout_name='$data2[user_id].$data2[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."comments SET comment_name='$data2[user_id].$user_name' WHERE comment_name='$data2[user_id].$data2[user_name]'");
		if ($data2[user_mod] > "2") {
			if ($_POST['email'] != $data2[user_email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$user_name', news_email='".$_POST['user_email']."' WHERE news_name='$data2[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$user_name', article_email='".$_POST['user_email']."' WHERE article_name='$data2[user_name]'");
			} else {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$user_name' WHERE news_name='$data2[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$user_name' WHERE article_name='$data2[user_name]'");
			}
		}
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_name='$user_name', user_email='".$_POST['user_email']."', user_hide_email='".$_POST['user_hide_email']."', user_location='$user_location', user_icq='".$_POST['user_icq']."', user_msn='".$_POST['user_msn']."', user_yahoo='".$_POST['user_yahoo']."', user_web='$user_web', user_theme='".$_POST['user_theme']."', user_offset='".$_POST['user_offset']."', ".$set_avatar."user_sig='$user_signature', user_mod='".$_POST['user_mod']."' WHERE user_id='$user_id'");
	} else {
		if ($data2[user_mod] > "2") {
			if ($email != $data2[email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_email='".$_POST['user_email']."' WHERE news_email='$data2[user_email]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_email='".$_POST['user_email']."' WHERE article_email='$data2[user_email]'");
			}
		}
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_email='".$_POST['user_email']."', user_hide_email='".$_POST['user_hide_email']."', user_location='$user_location', user_icq='".$_POST['user_icq']."', user_msn='".$_POST['user_msn']."', user_yahoo='".$_POST['user_yahoo']."', user_web='$user_web', user_theme='".$_POST['user_theme']."', user_offset='".$_POST['user_offset']."', ".$set_avatar."user_sig='$user_signature', user_mod='".$_POST['user_mod']."' WHERE user_id='$user_id'");
	}
}

}
?>