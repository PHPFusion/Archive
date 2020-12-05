<?
require "header.php";
require "subheader.php";

if (empty($page)) {
	$page = 0;
	$result = dbquery("UPDATE articles SET reads=reads+1 WHERE aid='$aid'");
}

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
$result = dbquery("SELECT * FROM articles WHERE aid='$aid'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$subject = stripslashes($data[subject]);
	$filename = $data[filename];
	$file = fOpen("articles/$filename","rb");
	$article = fRead($file, fileSize("articles/$filename"));
	fClose($file);
	if ($data[breaks] == "y") { $article = nl2br($article); }
	$postdate = gmdate("F d Y", $data[posted]);
	$posttime = gmdate("H:i", $data[posted]);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article) - 1;
	opentablex();
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td colspan=\"2\" class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">$data[postname]</a> on $postdate at $posttime</span></td></tr>
<tr><td colspan=\"2\" class=\"newsbody\">$article[$page]</td></tr>
<tr><td class=\"newsfoot\"><a href=\"articlecomments.php?aid=$data[aid]\" class=\"x\">$data[comments] Comments</a> | $data[reads] Reads</td></tr>
</table>\n";
	closetablex();
	if (count($article) > 1) {
		tablebreak();
		if ($page > 0) {
			$prevpage = $page - 1;
			$prev = "<a href=\"$PHP_SELF?aid=$aid&page=$prevpage\" class=\"x\">Prev</a>";
		}
		if ($page < $pagecount) {
			$nextpage = $page + 1;
			$next = "<a href=\"$PHP_SELF?aid=$aid&page=$nextpage\" class=\"x\">Next</a>";
		}
		$currentpage = $page + 1;
		opentablex();
		echo "<table width=\"100%\" class=\"nextprev\">
<tr><td width=\"50\" class=\"small\">$prev</td>
<td align=\"center\" class=\"small\">Page $currentpage of ".count($article)."</td>
<td width=\"50\" align=\"right\" class=\"small\">$next</td></tr>
</table>\n";
		closetablex();
	}
}
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>