<?
// theme settings
$body_text = "#cccccc";
$body_bg = "#485f5c";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

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
<td class='white-header'>
<a href='".fusion_basedir."index.php'>Home</a> |
<a href='".fusion_basedir."articles.php'>Articles</a> |
<a href='".fusion_basedir."downloads.php'>Downloads</a> |
<a href='".fusion_basedir."fusion_forum/'>Forum</a> |
<a href='".fusion_basedir."weblinks.php'>Web Links</a> |
<a href='".fusion_basedir."submit_news.php'>Submit News</a> |
<a href='".fusion_basedir."submit_link.php'>Submit Link</a>
</td>
<td align='right' class='white-header'>";
echo ucwords(strftime("%A, %B %d, %Y", time()+($GLOBALS['settings']['timeoffset']*3600)));
echo "</td>
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
<td class='capleft'><img src='".fusion_themedir."images/blank.gif' width='2' height='20' alt='' style='display:block'></td>
<td class='capmain'>$subject</td>
<td class='capright'><img src='".fusion_themedir."images/blank.gif' width='2' height='20' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='body'>
$news
</td>
</tr>
</table>
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
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capleft'><img src='".fusion_themedir."images/blank.gif' width='8' height='20' alt='' style='display:block'></td>
<td class='capmain'>$subject</td>
<td class='capright'><img src='".fusion_themedir."images/blank.gif' width='8' height='20' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='body'>\n";
if ($info[4] == "y") { echo nl2br($article); } else { echo $article; }
echo "</td>
</tr>
</table>
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
</td>
</tr>
</table>\n";

}

// open the table
function opentable($title) {
	echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capleft'><img src='".fusion_themedir."images/blank.gif' width='2' height='20' alt='' style='display:block'></td>
<td class='capmain'>$title</td>
<td class='capright'><img src='".fusion_themedir."images/blank.gif' width='2' height='20' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td class='body'>\n";
}
// close the table
function closetable() {
	echo "</td>
</tr>
</table>\n";
}

function openside($title) {
	echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
</tr>
<td class='sbody'>\n";
}

function closeside() {
	echo "</td>
</tr>
</table>\n";
}

function opensidex($title,$open="on") {

if($open=="on"){$box_img="off";}else{$box_img="on";}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
<td class='scapmain' align='right'>
<img onclick=\"javascript:flipBox('$title')\" name='b_$title' border='0' src='".fusion_themedir."images/panel_$box_img.gif'>
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='sbody'>
<div id='box_$title'"; if($open=="off"){ echo "style='display:none'"; } echo ">\n";

}

function closesidex() {

echo "</div>
</td>
</tr>
</table>\n";

}

// table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr><td height='8'></td></tr>
</table>\n";
}
?>