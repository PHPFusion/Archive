<?
/*--------------------------------------------+
| PHP-Fusion v6 - Content Management System   |
|---------------------------------------------|
| author: Nick Jones (Digitanium) � 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
/*--------------------------------------------+
|      Fusion 6 Theme for PHP-Fusion v6       |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock � 2005 |
| web: http://phpfusion.org                   |
| email: webmaster@phpfusion.org              |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/

// theme settings
$body_text = "#000000";
$body_bg = "#EFEFEF";
$theme_width = "100%";
$theme_width_l = "165";
$theme_width_r = "165";

function render_header($header_content) {

global $theme_width,$settings;

	echo "<table style='background-image:url(".THEME."images/center.gif)' align='center' border='0' cellpadding='0' cellspacing='0' width='$theme_width'>";
	echo "<tr><td width='21' height='82'><img src='".THEME."images/header_left.gif' width='21' height='82'></td>";
	// Start banner code
	echo "<td width='100%' height='82'>$header_content</td>";
	// End banner code
	echo "<td width='21' height='82'><img src='".THEME."images/header_right.gif' width='21' height='82'></td></tr></table>\n";
	echo "<img src='".THEME."images/pixel.gif' height='5'>";
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='$theme_width'>";
	echo "<tr><td width='42' height='29'><img src='".THEME."images/nav_left.gif' width='42' height='29'></td>";
	echo "<td width='75%' height='29' style='background-image:url(".THEME."images/nav_center.gif)'>";

$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_visibility<='".iUSER."' AND link_position>='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	$i = 1;
	while($data = dbarray($result)) {
		if ($data['link_url']!="---") {
			$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
			if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
				echo "<a href='".$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
			} else {
				echo "<a href='".BASEDIR.$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
			}
		}
		if ($i != dbrows($result)) { echo " �\n"; } else { echo "\n"; } $i++;
	}
}
	echo "</td>";

	echo "<td align='right' width='25%' height='29' style='background-image:url(".THEME."images/nav_center.gif)'>";
	echo "".ucwords(showdate($settings['subheaderdate'], time()))."</td>";
	echo "<td width='42' height='29'><img src='".THEME."images/nav_right.gif' width='42' height='29'></td></tr></table>";
	echo "<table align='center' cellspacing='0' cellpadding='0' width='$theme_width'>\n<tr>\n";
}

function render_footer($license=false) {

global $theme_width,$locale,$settings;

	echo "</tr>\n</table>\n";
	echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0'><tr>";
	echo "<td class='header'>".stripslashes($settings['footer'])."</td></tr></table>\n";
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='$theme_width'>";
	echo "<tr><td width='42' height='29'><img src='".THEME."images/nav_left.gif' width='42' height='29'></td>";
	echo "<td align='left' width='38%' height='29' style='background-image:url(".THEME."images/nav_center.gif)'>";

		if ($license == false) {
	echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'><img src='".THEME."images/fusion.gif' alt='PHP-Fusion' border='0' style='vertical-align:top;'></a> v".$settings['version']." &copy; 2003-2005";
	}
	echo "</td>";
	echo "<td align='center' width='25%' style='background-image:url(".THEME."images/nav_center.gif)'>Fusion 6 by: <a target='_blank' href='http://phpfusion.org'><img src='".THEME."images/fthemes.gif' style='vertical-align:middle;'></a>";
	echo "</td>";
	echo "<td align='right' width='37%' style='background-image:url(".THEME."images/nav_center.gif)'>";
	echo "<strong>".$settings['counter']." </strong>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td>";
	echo "<td width='42'><img src='".THEME."images/nav_right.gif' width='42' height='29'></td></tr></table>";
}

function render_news($subject, $news, $info) {

global $locale;
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$subject</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>";
	echo "<td class='main-body'><div style='width:100%;vertical-align:top;'>$news</div>";
	echo "<div style='margin-top:5px'>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='infobar'><img src='".THEME."images/bullet.gif'> ";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['news_date'])."</td>";
	echo "<td align='right' class='infobar2'>";
	echo "".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> <b>�</b>\n" : "")."";
	if ($info['news_allow_comments']) 
	echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> <b>�</b> ";
	echo "".$info['news_reads'].$locale['044']." <b>-</b> ";
	echo "<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>";
	echo "</td></tr></table></div></td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'>";
	echo "</td></tr><tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "</tr></table><br>\n";
}

function render_article($subject, $article, $info) {
	
global $locale;

	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$subject</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>";
	echo "<td class='main-body'><div style='width:100%;vertical-align:top;'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>";
	echo "<div style='margin-top:5px'>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='infobar'><img src='".THEME."images/bullet.gif'> ";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['article_date'])."</td>";
	echo "<td align='right' class='infobar2'>";
	if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." <b>�</b> ";
	echo "".$info['article_reads'].$locale['044']." <b>-</b> ";
	echo "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>";
	echo "</td></tr></table></div></td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>";
	echo "</tr><tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "</tr></table><br>\n";
}

function opentable($title) {

	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td class='panel-left'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$title</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>";
	echo "<td class='main-body'>\n";
}

function closetable() {

	echo "</td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "</tr></table><br>\n";
}

function openside($title) {
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$title</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-body'>\n";
}

function closeside() {

	echo "</td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "</tr></table><br>\n";
}

function opensidex($title,$open="on") {

	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "<td class='panel-main'>$title</td>";
	echo "<td align='right' class='panel-main'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='11' height='28' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-body'>";
	echo "<div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

	echo "</div>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='7' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='7' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='7' height='7' alt='' style='display:block'></td>";
	echo "</tr></table><br>\n";
}

function tablebreak() {

	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='5'>";
	echo "</td></tr></table>\n";
}
?>