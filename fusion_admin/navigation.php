<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
echo "<td width='$theme_width_l' valign='top' class='slborder'>\n";
@openside("$userdata[user_name]");
if (Member()) {
	$result = dbquery("SELECT count(message_id) FROM ".$fusion_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_read='0'");
echo "· <a href=\"".fusion_basedir."editprofile.php\" class=\"slink\">".LAN_60."</a><br>
· <a href=\"".fusion_basedir."messages.php\" class=\"slink\">".LAN_61."</a> [".dbresult($result, 0)."]<br>
· <a href=\"".fusion_basedir."members.php\" class=\"slink\">".LAN_62."</a><br>\n";
	if (Admin()) {
		echo "· <a href=\"".fusion_basedir."fusion_admin/index.php\" class=\"slink\">".LAN_63."</a><br>\n";
	}
	echo "· <a href=\"".fusion_basedir."index.php?logout=yes\" class=\"slink\">".LAN_64."</a>\n";
}
@closeside();
@openside(LAN_01);
if (Member()) {
	echo "· <a href=\"index.php\" class=\"slink\">".LAN_130."</a><br>
<hr class=\"shr\">
· <a href=\"../index.php\" class=\"slink\">".LAN_131."</a>\n";
}
@closeside();
@opensidex(LAN_132,"off");
if (SuperAdmin()) {
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
} else if (Admin()) {
	$panel_titles = array(
		LAN_201 => "articles",
		LAN_203 => "comments",
		LAN_206 => "downloads",
		LAN_208 => "forums",
		LAN_209 => "images",
		LAN_210 => "members",
		LAN_211 => "news",
		LAN_223 => "photoalbum",
		LAN_214 => "polls",
		LAN_215 => "shoutbox",
		LAN_218 => "submissions",
		LAN_219 => "weblinks",
	);
}
foreach ($panel_titles as $key=>$panel_link) {
	echo "· <a href='".$panel_link.".php' class='slink'>$key</a><br>\n";
}
closesidex();
echo "</td>\n<td valign='top' class='bodybg'>\n";
?>
