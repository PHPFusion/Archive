<?
/*
-------------------------------------------------------
	PHP Fusion X3
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
require fusion_langdir."admin/admin_main.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_140);
echo "<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>\n";

if ($userdata[user_mod] == "3") {
	
echo "<td width=\"33%\" class=\"small\">
· <a href=\"news.php\">".LAN_141."</a><br><br>
· <a href=\"article_cats.php\">".LAN_142."</a><br><br>
· <a href=\"articles.php\">".LAN_143."</a><br><br>
· <a href=\"polls.php\">".LAN_144."</a><br><br>
· <a href=\"sitelinks.php\">".LAN_145."</a><br><br>
· <a href=\"forums.php\">".LAN_146."</a>
</td>
<td width=\"33%\" class=\"small\">
· <a href=\"download_cats.php\">".LAN_147."</a><br><br>
· <a href=\"downloads.php\">".LAN_148."</a><br><br>
· <a href=\"weblink_cats.php\">".LAN_149."</a><br><br>
· <a href=\"weblinks.php\">".LAN_150."</a><br><br>
· <a href=\"shoutbox.php\">".LAN_151."</a><br><br>
· <a href=\"submissions.php\">".LAN_152."</a>
</td>
<td valign=\"top\" width=\"33%\" class=\"small\">
· <a href=\"members.php\">".LAN_153."</a><br><br>
· <a href=\"uploads.php\">".LAN_154."</a><br><br>
· <a href=\"settings.php\">".LAN_155."</a><br><br>
· <a href=\"upgrade.php\">".LAN_156."</a><br><br>
· <a href=\"phpinfo.php\">".LAN_157."</a>
</td>\n";

} else if ($userdata[user_mod] == "2") {
	
echo "<td width=\"33%\" class=\"small\">
· <a href=\"news.php\">".LAN_141."</a><br><br>
· <a href=\"articles.php\">".LAN_143."</a><br><br>
· <a href=\"polls.php\">".LAN_144."</a><br><br>
· <a href=\"forums.php\">".LAN_146."</a>
</td>
<td width=\"33%\" class=\"small\">
· <a href=\"downloads.php\">".LAN_148."</a><br><br>
· <a href=\"weblinks.php\">".LAN_150."</a><br><br>
· <a href=\"shoutbox.php\">".LAN_151."</a><br><br>
· <a href=\"submissions.php\">".LAN_152."</a>
</td>
<td valign=\"top\" width=\"33%\" class=\"small\">
· <a href=\"members.php\">".LAN_153."</a><br><br>
</td>\n";

}
echo "</tr>
</table>\n";
closetable();
tablebreak();
opentable(LAN_170);
echo "<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"50%\" class=\"small\">\n";
$result = dbquery("SELECT count(user_id) FROM ".$fusion_prefix."users");
echo "<span class=\"alt\">".LAN_171."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(user_id) FROM ".$fusion_prefix."users WHERE user_ban > '0'");
echo "<span class=\"alt\">".LAN_172."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(subnews_id) FROM ".$fusion_prefix."submitted_news");
echo "<span class=\"alt\">".LAN_173."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(sublink_id) FROM ".$fusion_prefix."submitted_links");
echo "<span class=\"alt\">".LAN_174."</span> ".dbresult($result, 0)."
</td>
<td valign=\"top\" width=\"50%\" class=\"small\">\n";
$result = dbquery("SELECT count(comment_id) FROM ".$fusion_prefix."comments");
echo "<span class=\"alt\">".LAN_175."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
echo "<span class=\"alt\">".LAN_176."</span> ".dbresult($result, 0)."<br>\n";
$result = dbquery("SELECT count(post_id) FROM ".$fusion_prefix."posts");
echo "<span class=\"alt\">".LAN_177."</span> ".dbresult($result, 0)."
</td>
</tr>
</table>";
closetable();

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>