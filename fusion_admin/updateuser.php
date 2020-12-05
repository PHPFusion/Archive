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
require fusion_langdir."admin/admin_members.php";

$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
$data = dbarray($result);
if ($user_id == 1) {
	$error .= LAN_250."<br>\n";
}
$username = trim(chop(str_replace("&nbsp;", "", $username)));
if ($username == "" || $email == "") {
	$error .= LAN_251."<br>\n";
}
if (!preg_match("/^[-0-9A-Z@\s]+$/i", $username)) {
	$error .= LAN_252."<br>\n";
}
if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $email)) {
	$error .= LAN_253."<br>\n";
}
if ($icq) {
	if (!preg_match("/^[0-9]+$/i", $icq)) {
		$error .= LAN_256."<br>\n";
	}
}
if ($msn) {
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $msn)) {
		$error .= LAN_257."<br>\n";
	}
}
if ($yahoo) {
	if (!preg_match("/^[_0-9A-Z]+$/i", $yahoo)) {
		$error .= LAN_258."<br>\n";
	}
}
$signature = stripinput($signature);
if ($username != "") {
	if ($username != $data[user_name]) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) {
			$error .= LAN_259."<br>\n";
		}
	}
}
if ($error == "") {
	if ($username != $data[user_name]) {
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_lastuser='$data[user_id].$username' WHERE forum_lastuser='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_author='$data[user_id].$username' WHERE thread_author='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_lastuser='$data[user_id].$username' WHERE thread_lastuser='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_author='$data[user_id].$username' WHERE post_author='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_edituser='$data[user_id].$username' WHERE post_edituser='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."poll_votes SET vote_user='$data[user_id].$username' WHERE vote_user='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_to='$data[user_id].$username' WHERE message_to='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_from='$data[user_id].$username' WHERE message_from='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_name='$data[user_id].$username' WHERE shout_name='$data[user_id].$data[user_name]'");
		$result = dbquery("UPDATE ".$fusion_prefix."comments SET comment_name='$data[user_id].$username' WHERE comment_name='$data[user_id].$data[user_name]'");
		if ($data[user_mod] > "1") {
			if ($email != $data[user_email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$username', news_email='$email' WHERE news_name='$data[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$username', article_email='$email' WHERE article_name='$data[user_name]'");
			} else {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_name='$username' WHERE news_name='$data[user_name]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_name='$username' WHERE article_name='$data[user_name]'");
			}
		}
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_name='$username', user_email='$email', user_hide_email='$hide_email', user_location='$location', user_icq='$icq', user_msn='$msn', user_yahoo='$yahoo', user_web='$web', user_sig='$signature', user_mod='$modlevel' WHERE user_id='$user_id'");
	} else {
		if ($data[user_mod] > "1") {
			if ($email != $data[email]) {
				$result = dbquery("UPDATE ".$fusion_prefix."news SET news_email='$email' WHERE news_email='$data[user_email]'");
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_email='$email' WHERE article_email='$data[user_email]'");
			}
		}
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_email='$email', user_hide_email='$hide_email', user_location='$location', user_icq='$icq', user_msn='$msn', user_yahoo='$yahoo', user_web='$web', user_sig='$signature', user_mod='$modlevel' WHERE user_id='$user_id'");
	}
}
?>