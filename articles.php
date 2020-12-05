<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Articles");
echo "Welcome to my Articles section, here I aim to deliver
useful, up to date Articles covering a wide range of PC related subjects. This section
will be limited for some time whilst I continue to develop PHP-Fusion™.<br><br>
Article Archive:<br><br>\n";
$result = dbquery("SELECT * FROM articles ORDER BY posted DESC");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$postdate = gmdate("F d Y", $data[posted]);
		$posttime = gmdate("H:i", $data[posted]);
		$subject = stripslashes($data[subject]);
		$articles .= "<a href=\"readarticle.php?aid=$data[aid]\">$subject</a>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td align=\"left\" class=\"small\">Posted by <a href=\"mailto:$data[postemail]\">$data[postname]</a> on $postdate at $posttime</td>
<td align=\"right\" class=\"small\"><a href=\"articlecomments.php?aid=$data[aid]\">$data[comments] comments</a></td></tr>
</table>\n";
	}
}
echo $articles;
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>