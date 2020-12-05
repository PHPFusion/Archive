<?
// theme settings
$body_text = "#aaaaaa";
$body_bg = "#415873";
$body_margin = "10";
$table_width = "100%";
$table_border = "border:1px #000 solid;";

// horizontal bar
$header_bar = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"white-header\">
<a href=\"".fusion_basedir."index.php\" class=\"white\">Home</a> |
<a href=\"".fusion_basedir."articles.php\" class=\"white\">Articles</a> |
<a href=\"".fusion_basedir."downloads.php\" class=\"white\">Downloads</a> |
<a href=\"".fusion_basedir."fusion_forum/\" class=\"white\">Forum</a> |
<a href=\"".fusion_basedir."weblinks.php\" class=\"white\">Web Links</a> |
<a href=\"".fusion_basedir."submit_news.php\" class=\"white\">Submit News</a> |
<a href=\"".fusion_basedir."submit_link.php\" class=\"white\">Submit Link</a>
</td>
<td align=\"right\" class=\"white-header\">
".ucwords(strftime("%A, %B %d, %Y", time()+($settings[timeoffset]*3600)))."</td>
</tr>
</table>\n";

function render_news($subject, $news, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capmain\">$subject<br>
<span class=\"small\">".LAN_40."<a href=\"mailto:$info[1]\" class=\"white\">$info[2]</a>".LAN_41."$info[3]</span></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">
$news
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"right\" class=\"news-footer\">\n";
if ($info[4] == "y") {
	echo "<a href=\"readmore.php?news_id=$info[0]\">".LAN_42."</a> | ";
}
echo "<a href=\"readmore.php?news_id=$info[0]\">$info[5]".LAN_43."</a> | $info[6]".LAN_44."
</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_morenews($subject, $morenews, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capmain\">$subject<br>
<span class=\"small\">".LAN_40."<a href=\"mailto:$info[0]\" class=\"white\">$info[1]</a>".LAN_41."$info[2]</span></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">
$morenews
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"right\" class=\"news-footer\">$info[3]".LAN_43." | $info[4]".LAN_44."</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capmain\">$subject<br>
<span class=\"small\">".LAN_40."<a href=\"mailto:$info[1]\" class=\"white\">$info[2]</a>".LAN_41."$info[3]</span></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">\n";
if ($info[4] == "y") { echo nl2br($article); } else { echo $article; }
echo "</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"right\" class=\"news-footer\">\n";
if ($info[0] != "0") {
	echo "<a href=\"articlecomments.php?article_id=$info[0]\">$info[5]".LAN_43."</a> | ";
} else {
	echo "$info[5]".LAN_43." | ";
}
echo "$info[6]".LAN_44."</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capmain\">$title</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">\n";

}

function closetable() {

echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"scapmain\">$title</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"sbody\">\n";

}

function closeside() {

echo "</td>
</tr>
</table>\n";

}

function tablebreak() {

echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td height=\"8\"></td></tr>
</table>\n";

}

function prevnextbar($prev,$current,$next) {

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td width=\"100\" class=\"barmain\">$prev</td>
<td align=\"center\" class=\"barmain\">$current</td>
<td align=\"right\" width=\"100\" class=\"barmain\">$next</td>
</tr>
</table>\n";

}
?>