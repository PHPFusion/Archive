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
|       Aztec Theme for PHP-Fusion v6         |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock � 2005 |
| web: http://phpfusion.org                   |
| email: webmaster@phpfusion.org              |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/

/************************/
/* Theme Settings		*/
/************************/

$body_text = "#000000";
$body_bg = "#505050";
$theme_width = "100%";
$theme_width_l = "160";
$theme_width_r = "160";

function render_header($header_content) {

include LOCALE.LOCALESET."forum/main.php";

global $theme_width,$settings;
	
	echo "<table cellpadding='0' cellspacing='0' width='$theme_width' border='0' align='center' bgcolor='#EFEFEF'>";
	echo "<tr><td bgcolor='#EFEFEF'>";
	echo "<img height='16' alt='' hspace='0' src='".THEME."images/corner-top-left.gif' width='17' align='left'>";
	// Start banner code
	echo "$header_content</td>";
	// End banner code
	echo "<td bgcolor='#EFEFEF'><IMG src='".THEME."images/pixel.gif' width='1' height='1' alt='' border='0' hspace='0'></td>";
	echo "<td bgcolor='#EFEFEF' align='center'>";

	// Search script //
	echo "<form name='search' method='post' action='".BASEDIR."search.php?stype=n'><span class='side-small'><b>News Search: </b></span>";
	echo "<input type='textbox' value='Enter search here...' name='stext' class='textbox' style='width:130px' onBlur=\"if(this.value=='') this.value='Enter search here...';\" onFocus=\"if(this.value=='Enter search here...') this.value='';\"> ";
	echo "<input type='submit' name='search' value='".$locale['550']."' class='button'></form></td>";
	// Search script end //

	echo "<td bgcolor='#EFEFEF' valign='top'><img height='17' alt='' hspace='0' src='".THEME."images/corner-top-right.gif' width='17' align='right'>";
	echo "</td></tr></table>";
	echo "<table cellpadding='0' cellspacing='0' width='$theme_width' border='0' align='center'><tr>";
	echo "<td bgcolor='#000000' colspan='4'><IMG src='".THEME."images/pixel.gif' width='1' height=1 alt='' border='0' hspace='0'>";
	echo "</td></tr>";
	echo "<tr valign='middle' bgcolor='#dedebb'><td class='white-header'>\n";

	$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
	if (dbrows($result) != 0) {
	$i = 0;
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_url']!="---") {
				if ($i != 0) { echo " �\n"; } else { echo "\n"; }
				$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo "<a href='".$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
				} else {
					echo "<a href='".BASEDIR.$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
				}
			}
			$i++;
		}
	}
}
	echo ($i == 0 ? "&nbsp;" : "")." </td>";
	echo "<td align='right' class='white-header'>".ucwords(showdate($settings['subheaderdate'], time()))."</td></tr>";
	echo "<tr><td bgcolor='#000000' colspan='4'><IMG src='".THEME."images/pixel.gif' width='1' height='1' alt='' border='0' hspace='0'>";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr valign='top'>";
	echo "<td valign='middle' align='right'>";
	echo "<table width='$theme_width' cellpadding='4' bgcolor='#EFEFEF' cellspacing='0' border='0' align='center'>";
}

function render_footer($license=false) {
global $theme_width,$locale,$settings;

	echo "</tr>\n</table>\n";
	echo "<table width='$theme_width' cellpadding='0' cellspacing='0' border='0' bgcolor='#EFEFEF' align='center'>";
	echo "<tr valign='top'><td align='center' height='17'>";
	echo "<img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-left.gif' width='17' align='left'>";
	echo "<img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-right.gif' width='17' align='right'>";
	echo "</td></tr></table><font color='#DDDDDD'>".stripslashes($settings['footer'])."</font>";
	tablebreak();
	echo "<table width='$theme_width' cellpadding='0' cellspacing='0' border='0' bgcolor='#EFEFEF' align='center'>";
	echo "<tr valign='middle'>";
	echo "<td><img height='17' alt='' hspace='0' src='".THEME."images/corner-top-left.gif' width='17' align='left'></td>";
	echo "<td class='footer' align='center' width='100%'>";
	echo "<strong>".$settings['counter']." </strong>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td>";
	echo "<td><img height='17' alt='' hspace='0' src='".THEME."images/corner-top-right.gif' width='17' align='right'></td></tr>";
	echo "<tr valign='middle' align='center'><td class='footer' width='100%' colspan='3'>";
	echo "</td></tr>";
	echo "<tr><td><img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-left.gif' width='17' align='left'></td>";
	echo "<td align='center' width='100%'>";
	if ($license == false) {
		echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'><img src='".THEME."images/fusion.gif' alt='PHP-Fusion' title='PHP-Fusion' border='0' style='vertical-align:middle;'></a> v".$settings['version']." &copy; 2003-2005";
	}
	echo " - Aztec Theme by: <a target='_blank' href='http://phpfusion.org'><img src='".THEME."images/fthemes.gif' style='vertical-align:top;'></a></td>";
	echo "<td><img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-right.gif' width='17' align='right'>";
	echo "</td></tr></table>";
}

function render_news($subject, $news, $info) {

global $locale;
	
	echo "<table border='0' class='border1' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>$news</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['news_date'])." </td>";
	echo "<td height='20' align='right' class='news-footer'>";
	echo "".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> �\n" : "")."";
	if ($info['news_allow_comments']) 
	echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> � ";
	echo "".$info['news_reads'].$locale['044']." ";
	echo "<a href='print.php?type=N&item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt= '".$locale['045']."' border='0' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {

global $locale;
	
	echo "<table border='0' class='border1' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['article_date'])."</td>";
	echo "<td height='20' align='right' class='news-footer'>";
	if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." � ";
	echo "".$info['article_reads'].$locale['044']." ";
	echo "<a href='print.php?type=A&item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' border='0' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {

	echo "<table border='0' style='border: 1px solid #000000' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header' height='20' width='100%'>$title</td></tr>";
	echo "<tr><td class='main-body' width='100%'>";
}

// Close table end
function closetable() {
	echo "</td></tr></table>\n";
}

function openside($title) {

	echo "<table border='0' style='border: 1px solid #000000' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header' height='20' width='100%'>$title</td></tr>";
	echo "<tr><td class='side-body' width='100%'>";
}

function closeside() {
	echo "</td></tr></table>";
	tablebreak();
}

function opensidex($title,$open="on") {
$box_img = ($open=="on" ? "off" : "on");

	echo "<table border='0' style='border: 1px solid #000000' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header' height='20' width='100%'>";
	echo "<img align='right'onclick=\"javascript:flipBox('$title')\" name='b_$title' alt='$box_img' border='0' src='".THEME."images/panel_$box_img.gif'>$title";
	echo "</td></tr>";
	echo "<tr><td class='side-body' width='100%'>";
	echo "<div id='box_$title'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

	echo "</div></td></tr></table>";
	tablebreak();
}

// Table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='8'></td></tr></table>\n";
}
?>