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
require fusion_langdir."admin/admin_main.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

opentable(LAN_200);
if (Admin()) {
	$columns = 3;
	$counter = 0;
	$i=0;
	echo "<table align='center' width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
	foreach ($panel_titles as $key=>$panel_link) {
		if ($counter != 0) {
			if ($counter % $columns == 0) echo "</tr>\n<tr>\n";
		}
		echo "<td valign='top'>· <a href='".$panel_link.".php'>$key</a></td>\n";
		$counter++;
	}
	echo "</tr>\n</table>\n";
}
closetable();
tablebreak();
opentable(LAN_250);
echo "<table align='center' width='100%'>
<tr>
<td width='50%' class='small'>\n";
$result = dbquery("SELECT count(user_id) FROM ".$fusion_prefix."users");
echo "<span class='alt'>".LAN_251."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(user_id) FROM ".$fusion_prefix."users WHERE user_ban > '0'");
echo "<span class='alt'>".LAN_252."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(subnews_id) FROM ".$fusion_prefix."submitted_news");
echo "<span class='alt'>".LAN_253."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(sub_article_id) FROM ".$fusion_prefix."submitted_articles");
echo "<span class='alt'>".LAN_254."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(sublink_id) FROM ".$fusion_prefix."submitted_links");
echo "<span class='alt'>".LAN_255."</span> ".dbresult($result, 0)."
</td>
<td valign='top' width='50%' class='small'>\n";
$result = dbquery("SELECT count(comment_id) FROM ".$fusion_prefix."comments");
echo "<span class='alt'>".LAN_256."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
echo "<span class='alt'>".LAN_257."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(post_id) FROM ".$fusion_prefix."posts");
echo "<span class='alt'>".LAN_258."</span> ".dbresult($result, 0)."
</td>
</tr>
</table>\n";
closetable();

echo "</td>\n";
require "../footer.php";
?>