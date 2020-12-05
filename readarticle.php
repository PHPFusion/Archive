<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@require_once "fusion_config.php";
require_once "fusion_core.php";
require_once "subheader.php";
require_once "side_left.php";

if (!isset($rowstart)) {
	$rowstart = 0; 
	$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_reads=article_reads+1 WHERE article_id='$article_id'");
}
$result = dbquery(
	"SELECT ta.*, tu.user_id,user_name FROM ".$fusion_prefix."articles ta
	LEFT JOIN ".$fusion_prefix."users tu ON ta.article_name=tu.user_id
	WHERE article_id='$article_id' GROUP BY article_id"
);
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data['article_article']);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article);
	$article_subject = stripslashes($data['article_subject']);
	$article_info = array(
		"article_id" => $data['article_id'],
		"user_id" => $data['user_id'],
		"user_name" => $data['user_name'],
		"article_date" => $data['article_datestamp'],
		"article_breaks" => $data['article_breaks'],
		"article_comments" => dbcount("(comment_id)", "comments", "comment_type='A' AND comment_item_id='".$data['article_id']."'"),
		"article_reads" => $data['article_reads']
	);
	render_article($article_subject, $article[$rowstart], $article_info);
	if (count($article) > 1) {
		$rows = $pagecount;
		echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,1,$rows,3,FUSION_SELF."?article_id=$article_id&")."\n</div>\n";
	}
}

require_once "side_right.php";
require_once "footer.php";
?>