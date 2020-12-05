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
require "header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."forum/forum_post.php";

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$caption = stripslashes($data[forum_name]);
}
// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$caption .= ": ".stripslashes($data[forum_name]);
}
require "navigation.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

// Start a new Thread
if ($action == "newthread") {
	require "postnewthread.php";
}
// Reply to Post
if ($action == "reply") {
	require "postreply.php";
}
// Edit Post
if ($action == "edit") {
	require "postedit.php";
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>