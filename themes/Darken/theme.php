<?
// theme settings
$body_text = "#888888";
$body_bg = "#000000";
$theme_width = "90%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {

global $theme_width,$settings;

echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0'>
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
<td class='white-header'>\n";
$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	$i = 0;
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_url']!="---") {
				if ($i != 0) { echo " &middot;\n"; } else { echo "\n"; }
				$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo "<a href='".$data['link_url']."'".$link_target.">".$data['link_name']."</a>";
				} else {
					echo "<a href='".BASEDIR.$data['link_url']."'".$link_target.">".$data['link_name']."</a>";
				}
			}
			$i++;
		}
	}
}
echo ($i == 0 ? "&nbsp;" : "")."</td><td align='right' class='white-header'>".ucwords(showdate("%A, %B %d, %Y", time()))."</td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>\n";

}

function render_footer($license=false) {

global $theme_width,$settings,$locale;

echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='white-header'>".stripslashes($settings['footer'])."</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' class='full-header'><br>
".$settings['counter']." ".($settings['counter'] == 1 ? $locale['140']."<br><br>\n" : $locale['141']."<br><br>\n");
if ($license == false) {
	echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'><img src='".THEME."images/fusion.gif' alt='PHP-Fusion' title='PHP-Fusion' style='vertical-align:middle;border:0px;'></a> v".$settings['version']." &copy; 2003-2005<br><br>\n";
}
echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_news($subject, $news, $info) {

global $locale;

echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject</td>
</tr>
<tr>
<td class='main-body'>
$news
</td>
</tr>
<tr>
<td align='right' class='news-footer'><img src='".THEME."images/bullet.gif'>
".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".$locale['041'].showdate("longdate", $info['news_date'])." &middot
".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> &middot\n" : "");
if ($info['news_allow_comments']) echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> &middot\n";
echo $info['news_reads'].$locale['044']."
<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>
</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {

global $locale;

echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject</td>
</tr>
<tr>
<td class='main-body'>
".($info['article_breaks'] == "y" ? nl2br($article) : $article)."
</td>
</tr>
<tr>
<td class='news-footer'><img src='".THEME."images/bullet.gif'>
".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".$locale['041'].showdate("longdate", $info['article_date'])." &middot\n";
if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." &middot\n";
echo $info['article_reads'].$locale['044']."
<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>
</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$title</td>
</tr>
<tr>
<td class='main-body'>\n";

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
	
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
</tr>
<tr>
<td class='side-body'>\n";

}

function closeside() {

echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";
tablebreak();

}

function opensidex($title,$open="on") {

$boxname = str_replace(" ", "", $title);
$box_img = $open == "on" ? "off" : "on";
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
<td class='scapmain' align='right'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>
</tr>
<tr>
<td colspan='2' class='side-body'>
<div id='box_$boxname'".($open=="off"?" style='display:none'":"").">\n";

}

function closesidex() {

echo "</div>
</td>
</tr>
</table>
</td>
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