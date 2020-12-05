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
echo "<td width='170' valign='top' class='slborder'>\n";
@openside("$userdata[user_name]");
if (Member()) {
	$result = dbquery("SELECT count(message_id) FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id]' AND message_read='0'");
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
	$i=0;
	$panel_titles = array(
		LAN_201, // Articles
		LAN_202, // Article Categories
		LAN_203, // Comments Management
		LAN_204, // Custom Pages
		LAN_205, // Database Backup
		LAN_206, // Downloads
		LAN_207, // Download Categories
		LAN_208, // Forum Management
		LAN_209, // Image Management
		LAN_210, // Member Management
		LAN_211, // News
		LAN_212, // Panel Management
		LAN_213, // PHP Info
		LAN_214, // Poll Management
		LAN_215, // Shoutbox Management
		LAN_216, // Site Links
		LAN_217, // Site Settings
		LAN_218, // Submissions
		LAN_219, // Weblinks
		LAN_220, // Weblink Categories
		LAN_221  // Upgrade
	);
	$panel_links = array(
		"articles",
		"article_cats",
		"comments",
		"custom_pages",
		"db_backup",
		"downloads", 
		"download_cats",
		"forums",
		"images",
		"members",
		"news",
		"panels",
		"phpinfo",
		"polls",
		"shoutbox", 
		"site_links",
		"settings",
		"submissions",
		"weblinks",
		"weblink_cats",
		"upgrade"
	);
} else if (Admin()) {
	$i=0;
	$panel_titles = array(
		LAN_201, // Articles
		LAN_203, // Comments Management
		LAN_206, // Downloads
		LAN_208, // Forum Management
		LAN_209, // Image Management
		LAN_210, // Member Management
		LAN_211, // News
		LAN_214, // Poll Management
		LAN_215, // Shoutbox Management
		LAN_218, // Submissions
		LAN_219, // Weblinks
	);
	$panel_links = array(
		"articles",
		"comments",
		"downloads",
		"forums",
		"images",
		"members",
		"news",
		"polls",
		"shoutbox", 
		"submissions",
		"weblinks"
	);
}
while ($panel_titles[$i] != "") {
	echo "· <a href='".$panel_links[$i].".php'>$panel_titles[$i]</a><br />\n";
	$i++;
}
closesidex();
echo "</td>\n<td valign='top' class='bodybg'>\n";
?>
