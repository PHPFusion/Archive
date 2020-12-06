<?
/*--------------------------------------------+
| PHP-Fusion v6 - Content Management System   |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
/*--------------------------------------------+
|     The Journal Theme for PHP-Fusion v6     |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock © 2005 |
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
$body_bg = "#E5E5E5";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {
global $theme_width,$settings;

	echo "<table class='forumline' align='center' width='$theme_width' cellspacing='0' cellpadding='0'>";
	echo "<tr><td><table align='center' width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr><td><table align='center' width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr><td style='background-color:#E7E7E7;padding:5px;'>";
	// Start banner code
	echo "<table width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr><td>$header_content</td>";
	echo "</tr></table></tr></td></table>\n";
	// End banner code
	echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>";
	echo "<td class='sub-header'>\n";

$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	$i = 0;
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_url']!="---") {
				if ($i != 0) { echo " <img src='".THEME."images/bullet.gif'> \n"; } else { echo "\n"; }
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
	echo ($i == 0 ? "&nbsp;" : "")."</td>";
	echo "<td align='right' class='sub-header'>".ucwords(showdate($settings['subheaderdate'], time()))."";
	echo "</td></tr></table>\n";
	echo "<table width='100%' cellpadding='4' bgcolor='#F8F8F8' cellspacing='0' border='0'>";
}

function render_footer($license=false) {
global $theme_width,$locale,$settings;

	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%'><tr>";
	echo "<td>".stripslashes($settings['footer'])."</td>";
	echo "</tr></table></td></tr></table><br>";
	echo "<table cellSpacing='0' cellPadding='2' width='100%' border='0'>";
	echo "<tr><td width='32%' class='footer' align='left'><div align='left'>";
	if ($license == false) {
		echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'><img src='".THEME."images/fusion.gif' alt='PHP-Fusion' title='PHP-Fusion' border='0' style='vertical-align:middle;'></a> v".$settings['version']." &copy; 2003-2005";
	}
	echo "</div></td>";
	echo "<td width='35%' class='footer' align='center'>";

	echo "The Journal by: <a target='_blank' href='http://phpfusion.org/'><img src='".THEME."images/fthemes.gif' style='vertical-align:middle;'></a></td>";
	echo "<td width='33%' class='footer' align='right'>";
	echo "<font class='clock'><b>".$settings['counter']." </b></font>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td></tr></table>";
}

function render_news($subject, $news, $info) {

global $locale;

	echo "<table class='news-border' border='0' cellspacing='2' width='100%' cellpadding='1'><tr>";
	echo "<td class='table-cellpic'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>$news</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['news_date'])." </td>";
	echo "<td height='24' align='right' class='news-footer'>";
	echo "".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> ·\n" : "")."";
	if ($info['news_allow_comments'])
	echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> · ";
	echo "".$info['news_reads'].$locale['044']." ";
	echo "<a href='print.php?type=N&item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' border='0' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {

global $locale;

	echo "<table class='news-border' border='0' cellspacing='2' width='100%' cellpadding='1'><tr>";
	echo "<td class='table-cellpic'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['article_date'])."</td>";
	echo "<td height='24' align='right' class='news-footer'>";
	if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." · ";
	echo "".$info['article_reads'].$locale['044']." ";
	echo "<a href='print.php?type=A&item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' border='0' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {

	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
	echo "<tr><td width='100%'><table class='panel-border' cellSpacing='1' cellPadding='1' width='100%' border='0'>";
	echo "<tr><th width='100%'><div class='table-cellpic' align='left'>$title</div></th></tr>";
	echo "<tr><td class='main-body' width='100%' bgColor='#EFEFEF'>";
}

// Close table end
function closetable() {

	echo "</td></tr></table></td></tr></table>";
}

function openside($title) {

	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
	echo "<tr><td width='100%'><table class='panel-border' cellSpacing='1' cellPadding='1' width='100%' border='0'>";
	echo "<tr><th width='100%'><div class='panel-cellpic' align='center'>$title</div></th></tr>";
	echo "<tr><td class='side-body' width='100%' bgColor='#EFEFEF'>";
}

function closeside() {

	echo "</td></tr></table></td></tr></table>";
	tablebreak();
}

function opensidex($title,$open="on") {

if($open=="on"){$box_img="off";}else{$box_img="on";}

	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
	echo "<tr><td width='100%'><table class='panel-border' cellSpacing='1' cellPadding='1' width='100%' border='0'>";
	echo "<tr><th width='100%'>";
	echo "<div class='panel-cellpic' align='center'><img align='right' onclick=\"javascript:flipBox('$title')\" name='b_$title' border='0' src='".THEME."images/panel_$box_img.gif'>$title</div>";
	echo "</th></tr>";
	echo "<tr><td class='side-body' width='100%' bgColor='#EFEFEF'>";
	echo "<div id='box_$title'"; if($open=="off"){ echo "style='display:none'"; } echo ">\n";
}

function closesidex() {

	echo "</td></tr></table></td></tr></table>";
	tablebreak();
}

// Table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='8'></td></tr></table>\n";
}
?>