<?
// theme settings
$body_text = "#000000";
$body_bg = "#c2c9c9";
$theme_width = "100%";
$theme_width_l = "190";
$theme_width_r = "190";

function render_header($header_content) {
	
global $theme_width,$settings;

echo "<table align='center' width='".$theme_width."' cellspacing='0' cellpadding='0'>
<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='full-header' style='padding:5px;'>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td>$header_content</td>
</tr>
</table>
</td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>
<td class='bar-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='28' alt='' style='display:block'></td>
<td class='bar-main'>\n";
$result = dbquery("SELECT * FROM ".FUSION_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	$i = 0;
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_url']!="---") {
				if ($i != 0) { echo " ·\n"; } else { echo "\n"; }
				$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo "<a href='".$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
				} else {
					echo "<a href='".FUSION_BASE.$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
				}
			}
			$i++;
		}
	}
}
echo ($i == 0 ? "&nbsp;" : "")."</td><td align='right' class='bar-main'>".ucwords(showdate("%A, %B %d, %Y", time()))."</td>
<td class='bar-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='28' alt='' style='display:block'></td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>\n";

}

function render_footer($license=false) {

global $theme_width,$settings;

echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='white-header'>".stripslashes($settings['footer'])."</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' class='full-header'><br>
".$settings['counter']." ".($settings['counter'] == 1 ? LAN_140."<br><br>\n" : LAN_141."<br><br>\n");
if ($license == false) {
	echo "Powered by <a href='http://www.php-fusion.co.uk' class='foot'>PHP-Fusion</a> v".
	sprintf("%.2f", $settings['version']/100)." © 2003-2005<br><br>\n";
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
<td class='b-top-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$subject</td>
<td class='b-top-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='main-body'>
$news
<div style='margin-top:5px;'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='news-footer'><img src='".FUSION_THEME."images/bullet.gif'>
".LAN_40."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".LAN_41.showdate("longdate", $info['news_date'])."</td>
<td align='right' class='news-footer'>\n";
if ($info['news_ext'] == "y") echo "<a href='news.php?readmore=".$info['news_id']."'>".LAN_42."</a> ·\n";
echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].LAN_43."</a> ·
".$info['news_reads'].LAN_44."
<a href='print.php?type=N&item_id=".$info['news_id']."'><img src='".FUSION_THEME."images/printer.gif' alt='".LAN_45."' border='0' style='vertical-align:middle;'></a>
</td>
</tr>
</table>
</div>
</td>
<td class='b-right'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".FUSION_THEME."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$subject</td>
<td class='b-top-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='main-body'>
".($info['article_breaks'] == "y" ? nl2br($article) : $article)."
<div style='margin-top:5px;'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='news-footer'>
".LAN_40."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".LAN_41.showdate("longdate", $info['article_date'])."</td>
<td align='right' class='news-footer'>
<a href='articlecomments.php?article_id=".$info['article_id']."'>".$info['article_comments'].LAN_43."</a> ·
".$info['article_reads'].LAN_44."
<a href='print.php?type=A&item_id=".$info['article_id']."'><img src='".FUSION_THEME."images/printer.gif' alt='".LAN_45."' border='0' style='vertical-align:middle;'></a>
</td>
</tr>
</table>
</div>
</td>
<td class='b-right'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".FUSION_THEME."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$title</td>
<td class='b-top-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='main-body'>\n";

}

function closetable() {

echo "</td>
<td class='b-right'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".FUSION_THEME."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$title</td>
<td class='b-top-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='side-body'>\n";

}

function closeside() {

echo "</td>
<td class='b-right'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".FUSION_THEME."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
</tr>
</table>\n";
tablebreak();

}

function opensidex($title,$open="on") {

if($open=="on"){$box_img="off";}else{$box_img="on";}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-top-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='27' alt='' style='display:block'></td>
<td class='b-top-main'>$title</td>
<td class='b-top-main' align='right'><img onclick=\"javascript:flipBox('$title')\" name='b_$title' border='0' src='".FUSION_THEME."images/panel_$box_img.gif'></td>
<td class='b-top-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='27' alt='' style='display:block'></td>
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='b-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
<td class='side-body'>
<div id='box_$title'"; if($open=="off"){ echo "style='display:none'"; } echo ">\n";

}

function closesidex() {

echo "</div>
</td>
<td class='b-right'><img src='".FUSION_THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='b-bottom-left'><img src='".FUSION_THEME."images/blank.gif' width='7' height='10' alt='' style='display:block'></td>
<td class='b-bottom-main'><img src='".FUSION_THEME."images/blank.gif' width='1' height='10' alt='' style='display:block'></td>
<td class='b-bottom-right'><img src='".FUSION_THEME."images/blank.gif' width='9' height='10' alt='' style='display:block'></td>
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