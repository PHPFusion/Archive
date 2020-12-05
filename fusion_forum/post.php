<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."forum/forum_post.php";

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$forum_cat_check = $data[forum_id];
	$caption = stripslashes($data[forum_name]);
}
// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$forum_id_check = $data[forum_id];
	$access = $data[forum_access];
	$caption .= ": ".stripslashes($data[forum_name]);
}
require "navigation.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";
if ($forum_cat_check != "" && $forum_id_check != "") {
	if ($userdata[user_mod] >= $access) {
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
echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>