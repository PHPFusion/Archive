<?
// theme settings
$body_text = "#dddddd";
$body_bg = "#008800";
$body_margin = "0";
$table_width = "100%";
$table_border = "border:0px #000 solid;";

// horizontal bar
$header_bar = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"white-header\">
<a href=\"".fusion_basedir."index.php\">Home</a> |
<a href=\"".fusion_basedir."articles.php\">Articles</a> |
<a href=\"".fusion_basedir."downloads.php\">Downloads</a> |
<a href=\"".fusion_basedir."fusion_forum/\">Forum</a> |
<a href=\"".fusion_basedir."weblinks.php\">Web Links</a> |
<a href=\"".fusion_basedir."submit_news.php\">Submit News</a> |
<a href=\"".fusion_basedir."submit_link.php\">Submit Link</a>
</td>
<td align=\"right\" class=\"white-header\">
".ucwords(strftime("%A, %B %d, %Y", time()+($settings[timeoffset]*3600)))."</td>
</tr>
</table>\n";

function render_news($subject, $news, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
<td class=\"capmain\">$subject</td>
<td class=\"capright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">
$news
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"news-footer\">".LAN_40."<a href=\"mailto:$info[1]\">$info[2]</a>".LAN_41."$info[3]</td>
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

function render_morenews($subject, $news, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
<td class=\"capmain\">$subject</td>
<td class=\"capright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">
$news
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"news-footer\">".LAN_40."<a href=\"mailto:$info[0]\">$info[1]</a>".LAN_41."$info[2]</td>
<td align=\"right\" class=\"news-footer\">$info[3]".LAN_43."</a> | $info[4]".LAN_44."</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
<td class=\"capmain\">$subject</td>
<td class=\"capright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\">\n";
if ($info[4] == "y") { echo nl2br($article); } else { echo $article; }
echo "</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"news-footer\">".LAN_40."<a href=\"mailto:$info[1]\">$info[2]</a>".LAN_41."$info[3]</td>
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

// open the table
function opentable($title) {
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"capleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
<td class=\"capmain\">$title</td>
<td class=\"capright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"8\" height=\"20\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td class=\"body\">\n";
}
// close the table
function closetable() {
	echo "</td>
</tr>
</table>\n";
}

function openside($title) {
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"scapmain\">$title</td>
</tr>
<td class=\"sbody\">\n";
}

function closeside() {
	echo "</td>
</tr>
</table>\n";
}

// table functions
function tablebreak() {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td height=\"8\"></td></tr>
</table>\n";
}

// previous-next bar
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