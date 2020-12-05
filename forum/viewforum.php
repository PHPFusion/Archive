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
require fusion_langdir."forum/forum_main.php";

if (empty($lastvisited)) { $lastvisited = time(); }

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
$data = dbarray($result);
$caption = stripslashes($data[forum_name]);

// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
$data = dbarray($result);
$caption .= " | <a href=\"viewforum.php?forum_id=$data[forum_id]&forum_cat=$data[forum_cat]\">".stripslashes($data[forum_name])."</a>";

require "navigation.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_250);
echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"smallalt\">
<a href=\"index.php\">$settings[sitename]</a> |
$caption</td>\n";
if ($userdata[user_name] != "") {
	echo "<td align=\"right\">
<a href=\"post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat\"><img src=\"images/newthread.gif\" border=\"0\"></a>
</td>\n";
}
echo "</tr>
</table>\n";

$threadsperpage = 20;
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='n'");
$rows = dbrows($result);
if (!$rowstart) $rowstart = 0;
if ($rows != 0) {
	$totalpages = ceil($rows / $threadsperpage);	
	$currentpage = $rowstart / $threadsperpage + 1;
	if ($rowstart >= $threadsperpage) {
		$start = $rowstart - $threadsperpage;
		$prev = "<a href=\"$PHP_SELF?rowstart=$start&forum_id=$forum_id&forum_cat=$forum_cat\" class=\"white\">".LAN_50."</a>";
	}
	if ($rowstart + $threadsperpage < $rows) {
		$start = $rowstart + $threadsperpage;
		$next = "<a href=\"$PHP_SELF?rowstart=$start&forum_id=$forum_id&forum_cat=$forum_cat\" class=\"white\">".LAN_51."</a>";
	}
	if ($prev != "" || $next != "") {
		$current = LAN_52.$currentpage.LAN_53.$totalpages;
		prevnextbar($prev,$current,$next);
	}
}
	
echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\">
<tr>
<td width=\"20\" class=\"forum2\">&nbsp</td>
<td class=\"forum2\">".LAN_251."</td>
<td width=\"100\" class=\"forum2\">".LAN_252."</td>
<td align=\"center\" width=\"50\" class=\"forum2\">".LAN_253."</td>
<td align=\"center\" width=\"50\" class=\"forum2\">".LAN_254."</td>
<td width=\"120\" class=\"forum2\">".LAN_204."</td>
</tr>\n";

if ($rowstart == 0) {	
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='y' ORDER BY thread_lastpost DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$result3 = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='$data[thread_id]' and post_datestamp > '$lastvisited'");
        		$num = dbresult($result3, 0);
			if ($data[thread_locked] == "y") {
				echo "<tr>\n<td align=\"center\" width=\"25\" class=\"forum2\"><img src=\"images/folderlock.gif\"></td>";
			} else  {
	        		if ($num > 0) {
	        			$folder = "<img src=\"images/foldernew.gif\">";
	        		} else if ($num > 19) {
        				$folder = "<img src=\"images/folderhot.gif\">";
	        		} else {
	        			$folder = "<img src=\"images/folder.gif\">";
	        		}
				echo "<tr><td align=\"center\" width=\"25\" class=\"forum2\">$folder</td>";
			}
			$thread_lastuser = explode(".", $data[thread_lastuser]);
			$thread_author = explode(".", $data[thread_author]);
			echo "<td class=\"forum1\"><img src=\"images/stickythread.gif\">
<a href=\"viewthread.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]\">".$data[thread_subject]."</a></td>
<td class=\"forum2\"><a href=\"../profile.php?lookup=$thread_author[0]\">$thread_author[1]</a></td>
<td align=\"center\" class=\"forum1\">$data[thread_views]</td>
<td align=\"center\" class=\"forum2\">$data[thread_replies]</td>
<td class=\"forum1\">".strftime($settings[forumdate], $data[thread_lastpost]+($settings[timeoffset]*3600))."<br>
<span class=\"small\">".LAN_206."<a href=\"../profile.php?lookup=$thread_lastuser[0]\">$thread_lastuser[1]</a></span></td>
</tr>\n";
		}
	} else {
		$threadcount = 0;
	}
}

if ($rows != 0) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='n' ORDER BY thread_lastpost DESC LIMIT $rowstart,$threadsperpage");
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$result3 = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='$data[thread_id]' and post_datestamp > '$lastvisited'");
        	$num = dbresult($result3, 0);
		if ($data[thread_locked] == "y") {
			echo "<tr>
<td align=\"center\" width=\"25\" class=\"forum2\"><img src=\"images/folderlock.gif\"></td>";
		} else  {
        		if ($num > 0) {
        			$folder = "<img src=\"images/foldernew.gif\">";
        		} else if ($num > 19) {
        			$folder = "<img src=\"images/folderhot.gif\">";
        		} else {
        			$folder = "<img src=\"images/folder.gif\">";
        		}
			echo "<tr>
<td align=\"center\" width=\"25\" class=\"forum2\">$folder</td>";
		}
		$thread_lastuser = explode(".", $data[thread_lastuser]);
		$thread_author = explode(".", $data[thread_author]);
		echo "<td class=\"forum1\">
<a href=\"viewthread.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]\">".$data[thread_subject]."</a></td>
<td class=\"forum2\"><a href=\"../profile.php?lookup=$thread_author[0]\">$thread_author[1]</a></td>
<td align=\"center\" class=\"forum1\">$data[thread_views]</td>
<td align=\"center\" class=\"forum2\">$data[thread_replies]</td>
<td class=\"forum1\">".strftime($settings[forumdate], $data[thread_lastpost]+($settings[timeoffset]*3600))."<br>
<span class=\"small\">".LAN_206."<a href=\"../profile.php?lookup=$thread_lastuser[0]\">$thread_lastuser[1]</a></span></td>
</tr>\n";
	}
} else {
	if ($threadcount == 0) {
		echo "<tr>\n<td colspan=\"6\" class=\"forum1\">".LAN_255."</td>\n</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";
if ($rows != 0) {
	if ($prev != "" || $next != "") {
		$current = LAN_52.$currentpage.LAN_53.$totalpages;
		prevnextbar($prev,$current,$next);
	}
}
if ($userdata[user_name] != "") {
	tablebreak();
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td align=\"right\">
<a href=\"post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat\"><img src=\"images/newthread.gif\" border=\"0\"></a></td>
</tr>
</table>\n";
}
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td class=\"forum1\">
<img src=\"images/foldernew.gif\"> - ".LAN_256."(<img src=\"images/folderhot.gif\"> - ".LAN_257.")<br>
<img src=\"images/folder.gif\"> - ".LAN_258."<br>
<img src=\"images/folderlock.gif\"> - ".LAN_259."</td>
</tr>
</table>\n";
closetable();

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>