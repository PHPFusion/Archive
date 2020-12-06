<?
// theme settings
$body_text = "#000000";
$body_bg = "#666666";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {

global $theme_width,$settings;

echo "<table align='center' cellspacing='0' cellpadding='0' width='$theme_width'>
<tr>
<td class='tbl2-top-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl2-top'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl2-top-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='header'>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td>$header_content</td>
</tr>
</table>
</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td style='background-color:#ccc;padding:0px 5px 5px 5px;'>
<table width='100%' cellspacing='0' cellpadding='0' style='border:#aaa 1px solid;'>
<tr>
<td class='sub-header'>\n";
$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	$i = 0;
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_url']!="---") {
				if ($i != 0) { echo " |\n"; } else { echo "\n"; }
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
echo ($i == 0 ? "&nbsp;" : "")."</td>\n<td align='right' class='sub-header'>".ucwords(showdate("%A, %B %d, %Y", time()))."</td>
</tr>
</table>
</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>\n";

}

function render_footer($license=false) {

global $theme_width,$settings,$locale;

echo "</tr>\n</table>\n";

echo "<table align='center' cellspacing='0' cellpadding='0' width='$theme_width'>
<tr>
<td class='tbl2-top-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl2-top'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl2-top-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='header'>".stripslashes($settings['footer'])."</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";

echo "<table align='center' cellpadding='0' cellspacing='0' width='$theme_width'>
<tr>
<td align='center' class='footer'>
".$settings['counter']." ".($settings['counter'] == 1 ? $locale['140']."<br><br>\n" : $locale['141']."<br><br>\n");
if ($license == false) {
	echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'><img src='".THEME."images/fusion.gif' alt='PHP-Fusion' title='PHP-Fusion' border='0' style='vertical-align:middle;'></a> v".$settings['version']." &copy; 2003-2005<br><br>\n";
}
echo "</td>
</tr>
</table>\n";

}

function render_news($subject, $news, $info) {

global $locale;

echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-top-left'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
<td width='100%' class='tbl-top'>$subject</td>
<td class='tbl-top-right'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='main-body'>
<div style='width:100%;vertical-align:top;'>$news</div>
<div style='margin-top:5px'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='infobar'><img src='".THEME."images/bullet.gif'>
".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".$locale['041'].showdate("longdate", $info['news_date'])."
</td>
<td align='right' class='infobar'>
".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> &middot\n" : "");
if ($info['news_allow_comments']) echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> &middot\n";
echo $info['news_reads'].$locale['044']."
<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>
</td>
</tr>
</table>
</div>
</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {

global $locale;
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-top-left'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
<td width='100%' class='tbl-top'>$subject</td>
<td class='tbl-top-right'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='main-body'>
<div style='width:100%;vertical-align:top;'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>
<div style='margin-top:5px'>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='infobar'><img src='".THEME."images/bullet.gif'>
".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
".$locale['041'].showdate("longdate", $info['article_date'])."
</td>
<td align='right' class='infobar'>\n";
if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." ·\n";
echo $info['article_reads'].$locale['044']."
<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>
</td>
</tr>
</table>
</div>
</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='5' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='5' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='5' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-top-left'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
<td width='100%' class='tbl-top'>$title</td>
<td class='tbl-top-right'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='main-body'>\n";

}

function closetable() {

echo "</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-top-left'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
<td width='100%' class='tbl-top'>$title</td>
<td class='tbl-top-right'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='side-body'>\n";

}

function closeside() {

echo "</td>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";
tablebreak();

}

function opensidex($title,$open="on") {

$boxname = str_replace(" ", "", $title);
$box_img = $open == "on" ? "off" : "on";
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-top-left'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
<td class='tbl-top'>$title</td>
<td align='right' class='tbl-top'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>
<td class='tbl-top-right'><img src='".THEME."images/blank.gif' width='6' height='23' alt='' style='display:block'></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
<td class='side-body'>
<div id='box_$boxname'".($open=="off" ? "style=' display:none'" : "").">\n";

}

function closesidex() {

echo "</div>
<td class='tbl-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>
</tr>
<tr>
<td class='tbl-bot-left'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
<td class='tbl-bot'><img src='".THEME."images/blank.gif' width='1' height='6' alt='' style='display:block'></td>
<td class='tbl-bot-right'><img src='".THEME."images/blank.gif' width='6' height='6' alt='' style='display:block'></td>
</tr>
</table>\n";
tablebreak();

}

function tablebreak() {

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr><td height='5'></td></tr>
</table>\n";

}
?>