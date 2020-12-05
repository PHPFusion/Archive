<?
// theme settings
$body_text = "#ffe28b";
$body_bg = "#222222";
$body_margin = "10";
$table_width = "800";
$table_border = "border:1px #666699 solid;";

// horizontal bar
$header_bar = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"white-header\">
<a href=\"".fusion_basedir."index.php\">Home</a> |
<a href=\"".fusion_basedir."articles.php\">Articles</a> |
<a href=\"".fusion_basedir."downloads.php\">Downloads</a> |
<a href=\"".fusion_basedir."forum/\">Forum</a> |
<a href=\"".fusion_basedir."weblinks.php\">Web Links</a> |
<a href=\"".fusion_basedir."submitnews.php\">Submit News</a>
</td>
<td align=\"right\" class=\"white-header\">\n";
$themedate = strftime("%A, %B %d, %Y", time()+($settings[timeoffset]*3600));
$themedate = str_replace($e_days, $f_days, $themedate);
$themedate = str_replace($e_months, $f_months, $themedate);
$header_bar .= $themedate."
</td>
</tr>
</table>\n";

function render_news($subject, $news, $info) {
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\"><span class=\"capmain\">$subject</span>
<hr>
".nl2br($news)."
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
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\"><span class=\"capmain\">$subject</span>
<hr>
".nl2br($news)."
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
	
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"body\"><span class=\"capmain\">$subject</span>
<hr>\n";
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
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"border\">
<tr>
<td class=\"body\"><span class=\"capmain\">$title</span>
<hr>\n";
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
<td class=\"barleft\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"2\" height=\"20\" alt=\"\" style=\"display:block\"></td>
<td width=\"100\" class=\"barmain\">$prev&nbsp;</td>
<td align=\"center\" class=\"barmain\">$current</td>
<td align=\"right\" width=\"100\" class=\"barmain\">&nbsp;$next</td>
<td class=\"barright\"><img src=\"".fusion_themedir."images/blank.gif\" width=\"2\" height=\"20\" alt=\"\" style=\"display:block\"></td>
</tr>
</table>\n";
}
?>