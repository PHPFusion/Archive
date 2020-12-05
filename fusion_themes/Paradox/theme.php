<?
// theme settings
$body_text = "#000000";
$body_bg = "#c2c9c9";
$theme_width = "100%";
$theme_width_l = "190";
$theme_width_r = "190";

function render_header($header_content) {

echo "<table align='center' width='".$GLOBALS['theme_width']."' cellspacing='0' cellpadding='0'>
<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='full-header' style='padding:5px;'>
$header_content
</td></tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='bar-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='28' alt='' style='display:block'></td>
<td class='bar-main'>
<a href='".fusion_basedir."index.php' class='white'>Home</a> |
<a href='".fusion_basedir."articles.php' class='white'>Articles</a> |
<a href='".fusion_basedir."downloads.php' class='white'>Downloads</a> |
<a href='".fusion_basedir."fusion_forum/' class='white'>Forum</a> |
<a href='".fusion_basedir."weblinks.php' class='white'>Web Links</a> |
<a href='".fusion_basedir."submit_news.php' class='white'>Submit News</a> |
<a href='".fusion_basedir."submit_link.php' class='white'>Submit Link</a>
</td>
<td align='right' class='bar-main'>";
echo ucwords(strftime("%A, %B %d, %Y", time()+($GLOBALS['settings']['timeoffset']*3600)));
echo "</td>
<td class='bar-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='28' alt='' style='display:block'></td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>\n";

}

function render_footer($license=false) {

echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='white-header'>
".stripslashes($GLOBALS['settings']['footer'])."
</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' class='full-header'><br>
".$GLOBALS['settings']['counter']." ";
if ($GLOBALS['settings']['counter'] == 1) { echo LAN_120."<br><br>\n"; } else { echo LAN_121."<br><br>\n"; }
if ($license == false) {
	echo "Powered by <a href='http://www.php-fusion.co.uk' class='foot'>PHP-Fusion</a> v".
	sprintf("%.2f", $GLOBALS['settings']['version']/100)." © 2003-2004<br><br>\n";
}
echo "</td>\n</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_news($subject, $news, $info) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$subject</td>
<td class='b-top-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='body'>
$news
<div style='margin-top:5px;'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='news-footer'>".LAN_40."<a href='profile.php?lookup=$info[1]'>$info[2]</a>".LAN_41."$info[3]</td>
<td align='right' class='news-footer'>\n";
if ($info[4] == "y") {
	echo "<a href='readmore.php?news_id=$info[0]'>".LAN_42."</a> |\n";
}
echo "<a href='readmore.php?news_id=$info[0]'>$info[5]".LAN_43."</a> | $info[6]".LAN_44." |
<a href='printer.php?type=N&item_id=$info[0]'>".LAN_45."</a>
</td>
</tr>
</table>
</div>
</td>
<td class='b-right'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".fusion_themedir."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$subject</td>
<td class='b-top-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='body'>\n";
if ($info[4] == "y") { echo nl2br($article); } else { echo $article; }
echo "<div style='margin-top:5px;'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='news-footer'>".LAN_40."<a href='profile.php?lookup=$info[1]'>$info[2]</a>".LAN_41."$info[3]</td>
<td align='right' class='news-footer'>\n";
if ($info[0] != "0") {
	echo "<a href='articlecomments.php?article_id=$info[0]'>$info[5]".LAN_43."</a> |\n";
} else {
	echo "$info[5]".LAN_43." | ";
}
echo "$info[6]".LAN_44." | <a href='printer.php?type=A&item_id=$info[0]'>".LAN_45."</a>
</td>
</tr>
</table>
</div>
</td>
<td class='b-right'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".fusion_themedir."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$title</td>
<td class='b-top-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='body'>\n";

}

function closetable() {

echo "</td>
<td class='b-right'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".fusion_themedir."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$title</td>
<td class='b-top-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='sbody'>\n";

}

function closeside() {

echo "</td>
<td class='b-right'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".fusion_themedir."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";
tablebreak();

}

function opensidex($title,$open="on") {

if($open=="on"){$box_img="off";}else{$box_img="on";}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$title</td>
<td class='b-top-main' align='right'><img onclick=\"javascript:flipBox('$title')\" name='b_$title' border='0' src='".fusion_themedir."images/panel_$box_img.gif'></td>
<td class='b-top-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='sbody'>
<div id='box_$title'"; if($open=="off"){ echo "style='display:none'"; } echo ">\n";

}

function closesidex() {

echo "</div>
</td>
<td class='b-right'><img src='".fusion_themedir."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".fusion_themedir."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".fusion_themedir."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".fusion_themedir."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";
tablebreak();

}

function tablebreak() {

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr><td height='8'></td></tr>
</table>\n";

}
?>