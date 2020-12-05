<?
require "header.php";
require "subheaderjs.php";

// Get the Forum Category name
$result = dbquery("SELECT * FROM forums WHERE forumtype='category' and fid='$fup'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$caption = $data[forumname];
}
// Get the Forum name
$result = dbquery("SELECT * FROM forums WHERE forumtype='forum' and fid='$fid'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$caption .= ": ".$data[forumname];
}
echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
echo "</td><td valign=\"top\">\n";
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
echo "</td></tr>
</table>\n";

require "../footer.php";
?>