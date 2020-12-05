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
require "side_left.php";

if (!$rowstart) {
	$rowstart = 0; 
	$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_reads=article_reads+1 WHERE article_id='$article_id'");
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data[article_article]);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article);
	$article_subject = stripslashes($data[article_subject]);
	$article_date = strftime($settings[longdate], $data[article_datestamp]+($settings[timeoffset]*3600));
	$article_info = array($data[article_id], $data[article_email], $data[article_name], $article_date,
	$data[article_breaks], $data[article_comments], $data[article_reads]);
	render_article($article_subject, $article[$rowstart], $article_info);
	if (count($article) > 1) {
		$itemsperpage = 1;
		$rows = $pagecount;
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?article_id=$article_id&page=$nextpage&")."
</div>\n";
	}
}

require "side_right.php";
require "footer.php";
?>