<?
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";
include FUSION_INFUSIONS."user_info_panel/user_info_panel.php";
openside(LAN_01);
if (iMEMBER) {
	echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN."index.php' class='side'>".LAN_150."</a><br>
<hr class='side-hr'>
<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."index.php' class='side'>".LAN_151."</a>\n";
}
closeside();
opensidex(LAN_152,"off");
if (iSUPERADMIN) {
	$panel_titles = array(
		LAN_201 => "articles",
		LAN_202 => "article_cats",
		LAN_203 => "comments",
		LAN_204 => "custom_pages",
		LAN_205 => "db_backup",
		LAN_206 => "downloads",
		LAN_207 => "download_cats",
		LAN_222 => "faq",
		LAN_208 => "forums",
		LAN_209 => "images",
		LAN_210 => "members",
		LAN_211 => "news",
		LAN_212 => "panels",
		LAN_223 => "photoalbums",
		LAN_213 => "phpinfo",
		LAN_214 => "polls",
		LAN_215 => "shoutbox",
		LAN_216 => "site_links",
		LAN_217 => "settings",
		LAN_218 => "submissions",
		LAN_219 => "weblinks",
		LAN_220 => "weblink_cats",
		LAN_221 => "upgrade",
	);
} else if (iADMIN) {
	$panel_titles = array(
		LAN_201 => "articles",
		LAN_203 => "comments",
		LAN_206 => "downloads",
		LAN_208 => "forums",
		LAN_209 => "images",
		LAN_210 => "members",
		LAN_211 => "news",
		LAN_223 => "photoalbums",
		LAN_214 => "polls",
		LAN_215 => "shoutbox",
		LAN_218 => "submissions",
		LAN_219 => "weblinks",
	);
}
foreach ($panel_titles as $key=>$panel_link) {
	echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN.$panel_link.".php' class='side'>$key</a><br>\n";
}
closesidex();
echo "</td>\n<td valign='top' class='main-bg'>\n";
?>
