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
@require "fusion_config.php";
require "header.php";
require "subheader.php";

if (empty($page)) {
	$page = 0;
	$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_reads=article_reads+1 WHERE article_id='$article_id'");
}

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data[article_article]);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article) - 1;
	$article_subject = stripslashes($data[article_subject]);
	$article_date = strftime($settings[longdate], $data[article_datestamp]+($settings[timeoffset]*3600));
	$article_info = array($data[article_id], $data[article_email], $data[article_name], $article_date,
	$data[article_breaks], $data[article_comments], $data[article_reads]);
	render_article($article_subject, $article[$page], $article_info);
	if (count($article) > 1) {
		tablebreak();
		if ($page > 0) {
			$prevpage = $page - 1;
			$prev = "<a href=\"$PHP_SELF?article_id=$article_id&page=$prevpage\" class=\"white\">".LAN_50."</a>";
		}
		if ($page < $pagecount) {
			$nextpage = $page + 1;
			$next = "<a href=\"$PHP_SELF?article_id=$article_id&page=$nextpage\" class=\"white\">".LAN_51."</a>";
		}
		$currentpage = $page + 1;
		$current = LAN_52.$currentpage.LAN_53.count($article);
		prevnextbar($prev,$current,$next);
	}
}

echo "</td>
<td width=\"170\" valign=\"top\" class=\"srborder\">\n";
require "userinfo.php";
require "poll.php";
require "shoutbox.php";
echo "</td>
</tr>
</table>\n";

require "footer.php";
?>