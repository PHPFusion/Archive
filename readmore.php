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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."comments.php";
require "side_left.php";

if (isset($_POST['post_comment'])) {
	if (dbrows(dbquery("SELECT news_id FROM ".$fusion_prefix."news WHERE news_id='$news_id'"))==0) {
		header("Location: ".fusion_basedir."index.php");
		exit;
	}
	if (Member()) {
		$comment_name = $userdata['user_id'];
	} elseif ($settings['guestposts'] == "1") {
		$comment_name = stripinput($_POST['comment_name']);
		if (is_numeric($comment_name)) $comment_name="";
	}
	$comment_message = stripinput($_POST['comment_message']);
	if ($comment_name != "" && $comment_message != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."comments VALUES('', '$news_id', 'N', '$comment_name', '$comment_message', '".time()."', '$user_ip')");
		$result = dbquery("UPDATE ".$fusion_prefix."news SET news_comments=news_comments+1 WHERE news_id='$news_id'");
	}
	header("Location: ".$PHP_SELF."?news_id=$news_id");
} else {
	$result = dbquery("UPDATE ".$fusion_prefix."news SET news_reads=news_reads+1 WHERE news_id='$news_id'");
}
$result = dbquery(
	"SELECT tn.*, user_id, user_name FROM ".$fusion_prefix."news tn
	LEFT JOIN ".$fusion_prefix."users tu ON tn.news_name=tu.user_id
	WHERE news_id='$news_id'"
);
$data = dbarray($result);
$news_subject = $data['news_subject'];
if ($data['news_extended']) {
	$news_news = stripslashes($data['news_extended']);
} else {
	$news_news = stripslashes($data['news_news']);
}
if ($data[news_breaks] == "y") { $news_news = nl2br($news_news); }
$news_date = strftime($settings['longdate'], $data['news_datestamp']+($settings['timeoffset']*3600));
$news_info = array($data['news_id'], $data['user_id'], $data['user_name'], $news_date, "n", $data['news_comments'], $data['news_reads']);
render_news($news_subject, $news_news, $news_info);
$comment_type = "N"; $comment_item_id = "$news_id"; $comment_link = "$PHP_SELF?news_id=$news_id";
require fusion_basedir."fusion_core/comments_panel.php";

require "side_right.php";
require "footer.php";
?>