<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
echo "</td><td valign=\"top\">\n";
if ($sub == "settings") {
	require "settings.php";
} else if ($sub == "sitelinks") {
	require "sitelinks.php";
} else if ($sub == "weblinks") {
	require "weblinks.php";
} else if ($sub == "downloads") {
	require "downloads.php";
} else if ($sub == "addfile") {
	require "addfile.php";
} else if ($sub == "forumadmin") {
	require "forumadmin.php";
} else if ($sub == "editforum") {
	require "editforum.php";
} else if ($sub == "shoutboxadmin") {
	require "shoutboxadmin.php";
} else if ($sub == "submissions") {
	require "submissions.php";
} else if ($sub == "addnews") {
	require "addnews.php";
} else if ($sub == "addarticle") {
	require "addarticle.php";
} else if ($sub == "addpoll") {
	require "addpoll.php";
} else if ($sub == "editnews") {
	require "editnews.php";
} else if ($sub == "editarticle") {
	require "editarticle.php";
} else if ($sub == "editpoll") {
	require "editpoll.php";
} else if ($sub == "upgrade") {
	require "upgrade.php";
}
echo "</td></tr>
</table>\n";

require "../footer.php";
?>