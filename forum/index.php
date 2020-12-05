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
require fusion_langdir."forum/forum_main.php";

if (empty($lastvisited)) { $lastvisited = time(); }

require "navigation.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_200);
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
<tr>
<td colspan=\"2\" class=\"forum2\">".LAN_201."</td>
<td align=\"center\" width=\"50\" class=\"forum2\">".LAN_202."</td>
<td align=\"center\" width=\"50\" class=\"forum2\">".LAN_203."</td>
<td width=\"120\" class=\"forum2\">".LAN_204."</td>
</tr>\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		echo "<tr>\n<td colspan=\"5\" class=\"forum-caption\">".stripslashes($data[forum_name])."</td>\n</tr>\n";
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='$data[forum_id]' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				$result3 = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."posts WHERE forum_id='$data2[forum_id]' AND post_datestamp > '$lastvisited'");
        			$num = dbresult($result3, 0);
        			if ($num > 0) { $fim = "<img src=\"images/foldernew.gif\">"; } else { $fim = "<img src=\"images/folder.gif\">"; }
        			echo "<tr>
<td align=\"center\" class=\"forum2\">$fim</td>
<td class=\"forum1\"><a href=\"viewforum.php?forum_id=$data2[forum_id]&forum_cat=$data2[forum_cat]\">".stripslashes($data2[forum_name])."</a><br>
<span class=\"small\">".stripslashes($data2[forum_description])."</span></td>
<td align=\"center\" class=\"forum2\">$data2[forum_threads]</td>
<td align=\"center\" class=\"forum1\">$data2[forum_posts]</td>
<td class=\"forum2\">";
				if ($data2[forum_lastpost] == 0) {
					echo LAN_205."</td>\n</tr>\n";
				} else {
					$lastuser = explode(".", $data2[forum_lastuser]);
					echo strftime($settings[forumdate], $data2[forum_lastpost]+($settings[timeoffset]*3600))."<br>
<span class=\"small\">".LAN_206."<a href=\"../profile.php?lookup=$lastuser[0]\">$lastuser[1]</a></span></td>
</tr>\n";
				}
			}
		} else {
			echo "<tr>\n<td colspan=\"5\" class=\"forum1\">".LAN_207."</td>\n</tr>\n";
		}
	}
} else {
	echo  "<tr>\n<td colspan=\"5\" class=\"forum1\">".LAN_208."</td>\n</tr>\n";
}

echo "</table>
</td>
</tr>
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"forum\"><br>
<img src=\"images/foldernew.gif\" align=\"left\"> - ".LAN_209."<br>
<img src=\"images/folder.gif\" align=\"left\"> - ".LAN_210."</td>
</tr>
</table>\n";
closetable();

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>