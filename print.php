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
include FUSION_LANGUAGES.FUSION_LAN."print.php";

echo "<html>
<head>
<title>".$settings['sitename']."</title>
<style type=\"text/css\">
body { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }
hr { height:1px;color:#ccc; }
.small { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:11px; }
.small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:11px;color:#666; }
</style>
</head>
<body>\n";
if ($type == "A") {
	$res = dbquery(
		"SELECT ta.*, user_id, user_name FROM ".$fusion_prefix."articles ta
		LEFT JOIN ".$fusion_prefix."users tu ON ta.article_name=tu.user_id
		WHERE article_id='$item_id'"
	);
	if (dbrows($res) != 0) {
		$data = dbarray($res);
		$article = str_replace("<--PAGEBREAK-->", "", stripslashes($data['article_article']));
		if ($data['article_breaks'] == "y") $article = nl2br($article);
		echo "<b>".$data['article_subject']."</b><br>
<span class='small'>".LAN_400.$data['user_name'].LAN_401.ucfirst(showdate("longdate", $data['article_datestamp']))."</span>
<hr>
$article\n";
	}
} elseif ($type = "N") {
	$res = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$fusion_prefix."news tn
		LEFT JOIN ".$fusion_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$item_id'"
	);
	if (dbrows($res) != 0) {
		$data = dbarray($res);
		$news = stripslashes($data['news_news']);
		if ($data['news_breaks'] == "y") $news = nl2br($news);
		if ($data['news_extended']) {
			$news_extended = stripslashes($data['news_extended']);
			if ($data['news_breaks'] == "y") $news_extended = nl2br($news_extended);
		}
		echo "<b>".$data['news_subject']."</b><br>
<span class='small'>".LAN_400.$data['user_name'].LAN_401.ucfirst(showdate("longdate", $data['news_datestamp']))."</span>
<hr>
$news\n";
		if ($news_extended) {
			echo "<hr>\n<b>".LAN_402."</b>\n<hr>\n$news_extended\n";
		}
	}
}
echo "</body>
</html>\n";
?>