<?
// theme settings
$body_text = "#000000";
$body_bg = "#888888";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {

global $theme_width,$settings;
echo "<script type='text/javascript' src='".FUSION_INCLUDES."link.js'></script>";
echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0' style='border:0px #444 solid'>
<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='header'>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td>$header_content</td>
</tr>
</table>
</td>
</tr>
</table>\n";

echo "<table width='100%' height='80' cellspacing='0' cellpadding='0' align='center' border='0'>
<tr>
<td class='header2' width='162' align='center'><img src='http://gryfpp.freelinuxhost.com/fusion_images/monster1.gif' border='0' width='63' height='69'></td>
<td class='header3'><center>
<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/
shockwave/cabs/flash/swflash.cab#version=4,0,2,0' width='100%' height='37'>
<PARAM name='movie' value='http://gryfpp.freelinuxhost.com/fusion_images/Bounce3.swf'>
<PARAM name='quality' value='high'>
<PARAM name='wmode' value='transparent'>
<PARAM name='menu' value='true'>
<EMBED src='http://gryfpp.freelinuxhost.com/fusion_images/Bounce3.swf' wmode='transparent' quality='high' menu='true' pluginspage='http://www.macromedia.com/shockwave/
download/index.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' width='100%' height='37'>
</EMBED>
</OBJECT>
</center>
</td>
<td class='header4' width='162' align='center'><img src='http://gryfpp.freelinuxhost.com/fusion_images/monster1.gif' border='0' width='63' height='69'></td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>\n";

}

function render_footer($license=false) {

global $theme_width,$settings;

echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='footer'>".stripslashes($settings['footer'])."</td>
</tr>
</table>
</td>
</tr>
</table>
<table align='center' width='$theme_width' cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' class='footer2'>
".$settings['counter']." ".($settings['counter'] == 1 ? LAN_140."<br><br>\n" : LAN_141."<br><br>\n");
if ($license == false) {
	echo "Powered by <a href='http://www.php-fusion.co.uk' class='white'>PHP-Fusion</a> v".
	sprintf("%.2f", $settings['version']/100)." C 2003-2004<br><br>\n";
}
echo "</td>
</tr>
</table>\n";

}

function render_news($subject, $news, $info) {

global $settings;
	
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td class='caption'>$subject</td>
</tr>
<tr>
<td class='main-body'>
$news
</td>
</tr>
<tr>
<td>
 <table width='100%' cellspacing='0' cellpadding='0' border='0'>
 <tr>
 <td class='infobar' width='50%' align='left'>
".LAN_40."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".LAN_41.showdate("longdate", $info['news_date'])."
</td>
<td class='infobar' align='right'>
".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".LAN_42."</a> ·\n" : "")."
<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].LAN_43."</a> ·
".$info['news_reads'].LAN_44."
<a href='print.php?type=N&item_id=".$info['news_id']."'><img src='".FUSION_THEME."images/printer.gif' alt='".LAN_45."' border='0' style='vertical-align:middle;'></a>
</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {

global $settings;
	
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td class='caption'>$subject</td>
</tr>
<tr>
<td class='main-body'>\n";
echo ($info['article_breaks'] == "y" ? nl2br($article) : $article)."
</td>
</tr>
<tr>
<td class='infobar'>
".LAN_40."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".LAN_41.showdate("longdate", $info['article_date'])."<br>
<a href='articlecomments.php?article_id=".$info['article_id']."'>".$info['article_comments'].LAN_43."</a> ·
".$info['article_reads'].LAN_44."
<a href='print.php?type=A&item_id=".$info['article_id']."'><img src='".FUSION_THEME."images/printer.gif' alt='".LAN_45."' border='0' style='vertical-align:middle;'></a>
</td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td class='caption'>$title</td>
</tr>
<tr>
<td class='main-body'>\n";

}

function closetable() {

echo "</td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='side-caption'>$title</td>
</tr>
<tr>
<td class='side-body'>\n";

}

function closeside() {

echo "</td>
</tr>
</table>\n";
tablebreak();

}

function opensidex($title,$open="on") {

$box_img = ($open=="on" ? "off" : "on");
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='side-caption'>$title</td>
<td class='side-caption' align='right'>
<img onclick=\"javascript:flipBox('$title')\" name='b_$title' alt='$box_img' border='0' src='".FUSION_THEME."images/panel_$box_img.gif'>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class='side-body'>
<div id='box_$title'".($open=="off" ? "style='display:none'" : "").">\n";

}

function closesidex() {

echo "</div>
</td>
</tr>
</table>\n";
tablebreak();

}

function tablebreak() {

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr><td height='10'></td></tr>
</table>\n";

}
?>
