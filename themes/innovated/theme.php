<?
// theme settings
$body_text = "#000000";
$body_bg = "#888888";
$body_margin = "10";
$table_width = "800";
$table_border = "border:1px #000000 solid;";

// horizontal bar
$header_bar = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"white-header\">
<a href=\"".fusion_basedir."index.php\" class=\"white\">Home</a> ·
<a href=\"".fusion_basedir."articles.php\" class=\"white\">Articles</a> ·
<a href=\"".fusion_basedir."downloads.php\" class=\"white\">Downloads</a> ·
<a href=\"".fusion_basedir."forum/\" class=\"white\">Forum</a> ·
<a href=\"".fusion_basedir."weblinks.php\" class=\"white\">Web Links</a> ·
<a href=\"".fusion_basedir."submitnews.php\" class=\"white\">Submit News</a>
</td>
<td align=\"right\" class=\"white-header\">
".strftime("%A, %B %d, %Y", time()+($settings[timeoffset]*3600))."
</td>
</tr>
</table>\n";

function render_news($subject, $news, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"newscapleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"38\" alt=\"\" style=\"display:block\"></td>
<td class=\"newscapmain\">$subject<br>
<span class=\"small\">".LAN_40."<a href=\"mailto:$info[1]\" class=\"white\">$info[2]</a>".LAN_41."$info[3]</span></td>
<td class=\"newscapright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"38\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">
".nl2br($news)."
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"barleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
<td align=\"right\" class=\"barmain\">";
if ($info[4] == "y") {
	echo "<a href=\"readmore.php?news_id=$info[0]\" class=\"white\">".LAN_42."</a> | ";
}
echo "<a href=\"readmore.php?news_id=$info[0]\" class=\"white\">$info[5]".LAN_43."</a> | $info[6]".LAN_44."</td>
<td class=\"barright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>\n";

}

function render_morenews($subject, $morenews, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"newscapleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"38\" alt=\"\" style=\"display:block\"></td>
<td class=\"newscapmain\">$subject<br>
<span class=\"small\">".LAN_40."<a href=\"mailto:$info[0]\" class=\"white\">$info[1]</a>".LAN_41."$info[2]</span></td>
<td class=\"newscapright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"38\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">
".nl2br($morenews)."
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"barleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
<td align=\"right\" class=\"barmain\">$info[3]".LAN_43." | $info[4]".LAN_44."</td>
<td class=\"barright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"newscapleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"38\" alt=\"\" style=\"display:block\"></td>
<td class=\"newscapmain\">$subject<br>
<span class=\"small\">".LAN_40."<a href=\"mailto:$info[1]\" class=\"white\">$info[2]</a>".LAN_41."$info[3]</span></td>
<td class=\"newscapright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"38\" alt=\"\" style=\"display:block\"></td>
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
<td class=\"barleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
<td align=\"right\" class=\"barmain\">\n";
if ($info[0] != "0") {
	echo "<a href=\"articlecomments.php?article_id=$info[0]\" class=\"white\">$info[5]".LAN_43."</a> | ";
} else {
	echo "$info[5]".LAN_43." | ";
}
echo "$info[6]".LAN_44."</td>
<td class=\"barright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"26\" alt=\"\" style=\"display:block\"></td>
<td class=\"capmain\">$title</td>
<td class=\"capright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"26\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">\n";

}

function closetable() {

echo "</td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"26\" alt=\"\" style=\"display:block\"></td>
<td class=\"capmain\">$title</td>
<td class=\"capright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"26\" alt=\"\" style=\"display:block\"></td>
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
<td class=\"barleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
<td width=\"100\" class=\"barmain\">$prev</td>
<td align=\"center\" class=\"barmain\">$current</td>
<td align=\"right\" width=\"100\" class=\"barmain\">$next</td>
<td class=\"barright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"5\" height=\"21\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>\n";

}
?>