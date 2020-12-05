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

if (!$rowstart) $rowstart = 0;
if (isset($_POST['post_comment'])) {
	if (dbrows(dbquery("SELECT article_id FROM ".$fusion_prefix."articles WHERE article_id='$article_id'"))==0) {
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
		$result = dbquery("INSERT INTO ".$fusion_prefix."comments VALUES('', '$article_id', 'A', '$comment_name', '$comment_message', '".time()."', '$user_ip')");
		$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_comments=article_comments+1 WHERE article_id='$article_id'");
	}
	header("Location: ".$PHP_SELF."?article_id=$article_id");
}
$result = dbquery(
	"SELECT ta.*, user_id, user_name FROM ".$fusion_prefix."articles ta
	LEFT JOIN ".$fusion_prefix."users tu ON ta.article_name=tu.user_id
	WHERE article_id='$article_id'"
);
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data['article_article']);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article);
	$article_subject = $data['article_subject'];
	$article_date = strftime($settings['longdate'], $data['article_datestamp']+($settings['timeoffset']*3600));
	$article_info = array($data['article_id'], $data['user_id'], $data['user_name'], $article_date,
	$data['article_breaks'], $data['article_comments'], $data['article_reads']);
	render_article($article_subject, $article[$rowstart], $article_info);
	if (count($article) > 1) {
		$itemsperpage = 1;
		$rows = $pagecount;
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?article_id=$article_id&page=$nextpage&")."
</div>\n";
	}
}
$comment_type = "A"; $comment_item_id = "$article_id"; $comment_link = "$PHP_SELF?article_id=$article_id";
require fusion_basedir."fusion_core/comments_panel.php";

require "side_right.php";
require "footer.php";
?>