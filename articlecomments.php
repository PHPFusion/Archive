<?
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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_INCLUDES."comments_include.php";
include FUSION_INCLUDES."ratings_include.php";

if (!$rowstart) $rowstart = 0;

$result = dbquery(
	"SELECT ta.*, tu.user_id,user_name, COUNT(comment_item_id) AS article_comments
	FROM ".$fusion_prefix."articles ta
	LEFT JOIN ".$fusion_prefix."users tu ON ta.article_name=tu.user_id
	LEFT JOIN ".$fusion_prefix."comments ON ta.article_id=comment_item_id AND comment_type='A'
	WHERE article_id='$article_id' GROUP BY article_id"
);
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data['article_article']);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article);
	$article_subject = $data['article_subject'];
	$article_info = array(
		"article_id" => $data['article_id'],
		"user_id" => $data['user_id'],
		"user_name" => $data['user_name'],
		"article_date" => $data['article_datestamp'],
		"article_breaks" => $data['article_breaks'],
		"article_comments" => $data['article_comments'],
		"article_reads" => $data['article_reads']
	);
	render_article($article_subject, $article[$rowstart], $article_info);
	if (count($article) > 1) {
		$rows = $pagecount;
		echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,1,$rows,3,"$PHP_SELF?article_id=$article_id&page=$nextpage&")."</div>\n";
	}
	showcomments("A","articles","article_id",$article_id,"$PHP_SELF?article_id=$article_id");
	showratings("A",$article_id,"$PHP_SELF?article_id=$article_id");
}

include "side_right.php";
include "footer.php";
?>