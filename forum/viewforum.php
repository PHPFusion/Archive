<?
require "header.php";
require "subheader.php";

if ($userdata[username] != "") {
	$result = dbquery("UPDATE users SET lastvisit='$servertime' WHERE userid='$userdata[userid]'");
}
if (empty($lastvisited)) { $lastvisited = $servertime; }

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
	$caption .= " · $data[forumname]";
}
// See if any Threads have been added
$result = dbquery("SELECT * FROM threads WHERE fid='$fid' AND sticky='y' ORDER BY lastpost DESC");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
			$result3 = dbquery("SELECT count(tid) FROM posts WHERE tid='$data[tid]' and posted > '$lastvisited'");
        		$num = dbresult($result3, 0);
			if ($data[locked] == "y") {
				$table .= "<tr><td align=\"center\" width=\"25\" class=\"forumf1\"><img src=\"images/folderlock.gif\"></td>";
			} else  {
	        		if ($num > 19) { $folder = "<img src=\"images/folderhot.gif\">";
	        		} else if ($num > 0) { $folder = "<img src=\"images/foldernew.gif\">";
	        		} else { $folder = "<img src=\"images/folder.gif\">"; }
				$table .= "<tr><td align=\"center\" width=\"25\" class=\"forumf1\">$folder</td>";
			}
			// Get the Last User's name
			$result2 = dbquery("SELECT * FROM users WHERE userid='$data[lastuser]'");
			$data2 = dbarray($result2);
			$lastpost = gmdate("m.d.Y", $data[lastpost])." at ".gmdate("H:i", $data[lastpost])."<br>
<span class=\"small\">by <a href=\"../profile.php?lookup=$data2[userid]\">$data2[username]</a></span>";
			// Get the Author's Name
			$result2 = dbquery("SELECT * FROM users WHERE userid='$data[author]'");
			$data2 = dbarray($result2);
			$author = "<a href=\"../profile.php?lookup=$data2[userid]\">$data2[username]</a>";
			// build the html code
			$table .= "<td class=\"forumf2\"><img src=\"images/stickythread.gif\">
<a href=\"viewthread.php?fid=$fid&fup=$fup&tid=$data[tid]\">".stripslashes($data[subject])."</a></td>
<td width=\"100\" class=\"forumf2\">$author</td>
<td align=\"center\" width=\"50\" class=\"forumf2\">$data[views]</td>
<td align=\"center\" width=\"50\" class=\"forumf2\">$data[replies]</td>
<td width=\"170\" class=\"forumf2\">$lastpost</td></tr>\n";
	}
}

// since we can expect the forum to fill up, we need to paginate the threads. Note that
// I have chosen not to paginate any sticky threads, because we want them visible on all
// pages.

// number of threads to display per page
$threadsperpage = 20;
// run a sql query to count the actual number of non-sticky threads
$result = dbquery("SELECT * FROM threads WHERE fid='$fid' AND sticky='n'");
$rows = dbrows($result);
// if $rowstart has not been defined, set it to 0
if (!$rowstart) {
	$rowstart = 0;
}
if ($rows != 0) {
	$totalpages = ceil($rows / $threadsperpage);	
	$currentpage = $rowstart / $threadsperpage + 1;
	// retrieve the threads from the database
	$result = dbquery("SELECT * FROM threads WHERE fid='$fid' AND sticky='n' ORDER BY lastpost DESC LIMIT $rowstart,$threadsperpage");
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
			$result3 = dbquery("SELECT count(tid) FROM posts WHERE tid='$data[tid]' and posted > '$lastvisited'");
        		$num = dbresult($result3, 0);
			if ($data[locked] == "y") {
				$table .= "<tr><td align=\"center\" width=\"25\" class=\"forumf1\"><img src=\"images/folderlock.gif\"></td>";
			} else  {
	        		if ($num > 19) { $folder = "<img src=\"images/folderhot.gif\">";
	        		} else if ($num > 0) { $folder = "<img src=\"images/foldernew.gif\">";
	        		} else { $folder = "<img src=\"images/folder.gif\">"; }
				$table .= "<tr><td align=\"center\" width=\"25\" class=\"forumf1\">$folder</td>";
			}
			// Get the Last User's name
			$result2 = dbquery("SELECT * FROM users WHERE userid='$data[lastuser]'");
			$data2 = dbarray($result2);
			$lastpost = gmdate("m.d.Y", $data[lastpost])." at ".gmdate("H:i", $data[lastpost])."<br>
<span class=\"small\">by <a href=\"../profile.php?lookup=$data2[userid]\">$data2[username]</a></span>";
			// Get the Author's Name
			$result2 = dbquery("SELECT * FROM users WHERE userid='$data[author]'");
			$data2 = dbarray($result2);
			$author = "<a href=\"../profile.php?lookup=$data2[userid]\">$data2[username]</a>";
			// build the html code
			$table .= "<td class=\"forumf2\">
<a href=\"viewthread.php?fid=$fid&fup=$fup&tid=$data[tid]\">".stripslashes($data[subject])."</a></td>
<td width=\"100\" class=\"forumf2\">$author</td>
<td align=\"center\" width=\"50\" class=\"forumf2\">$data[views]</td>
<td align=\"center\" width=\"50\" class=\"forumf2\">$data[replies]</td>
<td width=\"170\" class=\"forumf2\">$lastpost</td></tr>\n";
	}
}
if ($table == "") {
	$table .= "<tr><td colspan=\"6\" class=\"forumf1\">No Threads have been started</td></tr>\n";
}
echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
echo "</td><td valign=\"top\">\n";
if ($userdata[username] != "") {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"right\"><a href=\"post.php?action=newthread&fid=$fid&fup=$fup\"><img src=\"images/newthread.gif\" border=\"0\"></a></td></tr>
</table>\n";
	tablebreak();
}
opentable("<a href=\"index.php\" class=\"x\">Discussion Forum</a> · $caption");
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td width=\"20\" class=\"forumh1\">&nbsp</td><td class=\"forumh2\">Subject</td>
<td width=\"100\" class=\"forumh2\">Author</td>
<td align=\"center\" width=\"50\" class=\"forumh2\">Views</td>
<td align=\"center\" width=\"50\" class=\"forumh2\">Replies</td>
<td width=\"170\" class=\"forumh2\">Last Post</td></tr>
$table
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"forum\"><br>
<img src=\"images/foldernew.gif\"> - Open Thread with new posts since last visit (<img src=\"images/folderhot.gif\"> - 20 or more)<br>
<img src=\"images/folder.gif\"> - Open Thread with no new posts since last visit<br>
<img src=\"images/folderlock.gif\"> - Locked Thread</td></tr>
</table>\n";
closetable();
tablebreak();
if ($userdata[username] != "") {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"right\"><a href=\"post.php?action=newthread&fid=$fid&fup=$fup\"><img src=\"images/newthread.gif\" border=\"0\"></a></td></tr>
</table>\n";
}
if ($rows != 0) {
	if ($rowstart >= $threadsperpage) {
		$start = $rowstart - $threadsperpage;
		$prev = "<a href=\"$PHP_SELF?rowstart=$start&fid=$fid&fup=$fup\">Prev</a>";
	}
	if ($rowstart + $threadsperpage < $rows) {
		$start = $rowstart + $threadsperpage;
		$next = "<a href=\"$PHP_SELF?rowstart=$start&fid=$fid&fup=$fup\">Next</a>";
	}
	if ($prev != "" || $next != "") {
		echo "<table width=\"100%\">
<tr><td width=\"50\" class=\"small\">$prev</td>
<td align=\"center\" class=\"small\">page $currentpage of $totalpages</td>
<td width=\"50\" align=\"right\" class=\"small\">$next</td></tr>
</table>\n";
	}
}
echo "</td></tr>
</table>\n";

require "../footer.php";
?>