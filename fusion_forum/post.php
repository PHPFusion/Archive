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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."forum/forum_post.php";
require "navigation.php";

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$forum_cat_check = $data['forum_id'];
	$caption = $data['forum_name'];
}
// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$result2 = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id' AND forum_cat!='0'");
	if (dbresult($result2, 0) == 1) { $forum_id_check = "ok"; } else { $forum_id_check = ""; }
	$access = $data[forum_access];
	$caption .= ": ".$data['forum_name'];
}

if ($forum_cat_check != "" && $forum_id_check != "") {
	if (Member() && UserLevel() >= $access) {
		if ($action == "newthread") {
			require "postnewthread.php";
		}
		if ($action == "reply") {
			require "postreply.php";
		}
		if ($action == "edit") {
			require "postedit.php";
		}
	}
}

echo "</td>\n";
require "../footer.php";
?>