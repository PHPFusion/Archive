<?
require "header.php";
require "subheader.php";

if (empty($fup) || ($fup == "")) {
	$result = dbquery("SELECT * FROM forums WHERE fid='$fid'");
	$data = dbarray($result);
	$fup = $data[fup];
}

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
	$caption .= " · <a href=\"viewforum.php?fid=$data[fid]&fup=$data[fup]\" class=\"x\">$data[forumname]</a>";
}
// Update the View count in the parent Thread
$result = dbquery("UPDATE threads SET views=views+1 WHERE tid='$tid'");
// Get the Thread Subject
$result = dbquery("SELECT * FROM threads WHERE tid='$tid'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$locked = $data[locked];
}
// we can expect to have alot of posts in our thread, so we will paginate
// our posts into 20 per page, same method as we used for the threads.

// number of threads to display per page
$postsperpage = 20;
// run a sql query to count the actual number of posts
$result = dbquery("SELECT * FROM posts WHERE tid='$tid'");
$rows = dbrows($result);
// if $rowstart has not been defined, set it to 0
if (!$rowstart) {
	$rowstart = 0;
}
if ($rows != 0) {
	$totalpages = ceil($rows / $postsperpage);	
	$currentpage = $rowstart / $postsperpage + 1;
	// retrieve the posts from the database
	$result = dbquery("SELECT * FROM posts WHERE tid='$tid' ORDER BY posted LIMIT $rowstart,$postsperpage");
	$numrows = dbrows($result);
	$i = 0;
	while ($data = dbarray($result)) {
		$result2 = dbquery("SELECT * FROM users WHERE userid='$data[author]'");
		$data2 = dbarray($result2);
		$i++;
		$subject = stripslashes($data[subject]);
		$message = stripslashes($data[message]);
		if ($data[showsig] == "y") {
			$message = $message."\n\n".$data2[sig];
		}
		$message = parseubb($message);
		$message = nl2br($message);
		if ($data[edittime] != "0") {
			if ($data[author] != $data[edituser]) {
				$result3 = dbquery("SELECT * FROM users WHERE userid='$data[edituser]'");
				$data3 = dbarray($result3);
				$edituser = "<a href=\"../profile.php?lookup=$data2[userid]\">$data3[username]</a>";
				$edittime = gmdate("m.d.Y", $data[edittime])." at ".gmdate("H:i", $data[edittime]);
			} else {
				$edituser = "<a href=\"../profile.php?lookup=$data2[userid]\">$data2[username]</a>";
				$edittime = gmdate("m.d.Y", $data[edittime])." at ".gmdate("H:i", $data[edittime]);
			}
		}
		// build the html code
		$posted = gmdate("m.d.Y", $data[posted])." at ".gmdate("H:i", $data[posted]);
		$threads .= "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td width=\"100\" class=\"forumt1\">Author</td>
<td class=\"forumt2\">
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td>$subject - $posted</td><td align=\"right\">\n";
		if ($userdata[userid] == $data[author] || $userdata[mod] == "Administrator" || $userdata[mod] == "Moderator") {
			if ($locked != "y") {
				$threads .= "<a href=\"post.php?action=edit&fid=$fid&fup=$fup&tid=$data[tid]&pid=$data[pid]\"><img src=\"images/edit.gif\" border=\"0\"></a>";
			}
		}
		$threads .= "</td></tr>
</table>
</td></tr>
<tr><td valign=\"top\"width=\"145\" class=\"forumf1\"><a href=\"../profile.php?lookup=$data2[userid]\">$data2[username]</a><br>
$data2[mod]<br><br>
Posts: $data2[posts]<br>
Location: $data2[location]<br>
Joined: ".gmdate("m.d.Y", $data2[joined])."</td>
<td class=\"forumf2\" valign=\"top\">$message<br><hr>\n";
		if ($data[edittime] != "0") {
			$threads .= "Edited by: $edituser - $edittime <br><hr>\n";
		}
		if ($data2[icq]) {
			$threads .= "<a href=\"http://web.icq.com/wwp?Uin=$data2[icq]\" target=\"_blank\"><img src=\"images/icq.gif\" alt=\"$data2[icq]\" border=\"0\"></a> ";
		}
		if ($data2[msn]) {
			$threads .= "<a href=\"mailto:$data2[msn]\"><img src=\"images/msn.gif\" alt=\"$data2[msn]\" border=\"0\"></a> ";
		}
		if ($data2[yahoo]) {
			$threads .= "<a href=\"http://uk.profiles.yahoo.com/$data2[yahoo]\"><img src=\"images/yahoo.gif\" alt=\"$data2[yahoo]\" border=\"0\"></a> ";
		}
		if ($data2[web]) {
			$threads .= "<a href=\"$data2[web]\"><img src=\"images/web.gif\" alt=\"$data2[web]\" border=\"0\"></a> ";
		}
		$threads .= "<a href=\"../sendmessage.php?user=$data2[userid]\"><img src=\"images/pm.gif\" border=\"0\"></a>
</td></tr>
</table>\n";
		if ($i != $numrows) {
			$threads .= "<br>\n";
		}
	}
}
echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
echo "</td><td valign=\"top\">\n";
if ($userdata[username] != "") {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"right\" style=\"font-size: 6px;\">\n";
	if ($locked != "y") {
		echo "<a href=\"post.php?action=reply&fid=$fid&fup=$fup&tid=$tid\"><img src=\"images/reply.gif\" border=\"0\"></a>";
	}
	echo "&nbsp;&nbsp;<a href=\"post.php?action=newthread&fid=$fid&fup=$fup\"><img src=\"images/newthread.gif\" border=\"0\"></a></td></tr>
</table>\n";
	tablebreak();
}
opentable("<a href=\"index.php\" class=\"x\">Discussion Forum</a> · $caption");
echo $threads;
closetable();
tablebreak();
if ($userdata[username] != "") {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"right\" style=\"font-size: 6px;\">\n";
	if ($locked != "y") {
		echo "<a href=\"post.php?action=reply&fid=$fid&fup=$fup&tid=$tid\"><img src=\"images/reply.gif\" border=\"0\"></a>";
	}
	echo "&nbsp;&nbsp;<a href=\"post.php?action=newthread&fid=$fid&fup=$fup\"><img src=\"images/newthread.gif\" border=\"0\"></a></td></tr>\n";
echo "</table>";
}
if ($rows != 0) {
	if ($rowstart >= $postsperpage) {
		$start = $rowstart - $postsperpage;
		$prev = "<a href=\"$PHP_SELF?rowstart=$start&fid=$fid&fup=$fup&tid=$tid\">Prev</a>";
	}
	if ($rowstart + $postsperpage < $rows) {
		$start = $rowstart + $postsperpage;
		$next = "<a href=\"$PHP_SELF?rowstart=$start&fid=$fid&fup=$fup&tid=$tid\">Next</a>";
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