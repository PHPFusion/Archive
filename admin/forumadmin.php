<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage) || ($stage == "")) {
		opentable("Forum Admin");
		echo "<table align=\"center\" width=\"500\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Welcome to the Forum Admin Panel, this panel provides access to your forum structure and maintenance
tools. What would you like to do?<br>
<br>
<a href=\"$_SELF?sub=forumadmin&stage=2\">Edit Forum Layout</a><br>
Add, Edit or Remove Categories or Forums.<br>
<br>
<a href=\"$_SELF?sub=forumadmin&stage=3\">Fix Forum Counts</a><br>
Reset forum Threads & Posts Counts and Threads Replies Counts.</td></tr>
</table>\n";
		closetable();
	}
	if ($stage == 2) {
		// check if delete variable is set
		if (isset($delete)) {
			// if it is a forum, delete it and any corresponding threads & posts
			if ($t == "f") {
				$result = dbquery("DELETE FROM forums WHERE forumtype='forum' AND fid='$delete'");
				$result = dbquery("DELETE FROM threads WHERE fid='$delete'");
				$result = dbquery("DELETE FROM posts WHERE fid='$delete'");
			} else {
				// if it is a category, check if any forums are defined, if so, don't delete it
				$result = dbquery("SELECT * FROM forums WHERE forumtype='forum' AND fup='$delete'");
				if (dbrows($result) == 0) {
					$result = dbquery("DELETE FROM forums WHERE forumtype='category' AND fid='$delete'");
				}
			}
		}
		// check if submit button has been pressed
		if (isset($_POST[submit])) {
			// check if either/or both newcategory and/or newforum entries are defined
			if ($newcatname != "") {
				$result = dbquery("INSERT INTO forums VALUES('category', '', '0', '$newcatname', '$newcatorder', '', '0', '0', '0', '0')");
			}
			if ($newforumname != "") {
				$result = dbquery("SELECT * FROM forums WHERE forumtype='category'");
				if (dbrows($result) != 0) {
					$result = dbquery("INSERT INTO forums VALUES('forum', '', '$newforumcategory', '$newforumname', '$newforumorder', '', '0', '0', '0', '0')");
				}
			}
		}
		$result = dbquery("SELECT * FROM forums WHERE forumtype='category'");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) {
				$opts .= "<option value=\"$data[fid]\">$data[forumname]</option>\n";
			}
		}
		// see if any categories have been defined
		$result = dbquery("SELECT * FROM forums WHERE forumtype='category' ORDER BY forumorder ASC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$forums .= "<tr><td class=\"altleft\">$data[forumname]</td><td align=\"center\" class=\"altmid\">$data[forumorder]</td>
<td class=\"altright\"><span class=\"small\"><a href=\"$_SELF?sub=editforum&fid=$data[fid]&t=c\">Edit</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=forumadmin&stage=2&delete=$data[fid]&t=c\">Delete</a></span></td></tr>\n";
				$result2 = dbquery("SELECT * FROM forums WHERE forumtype='forum' and fup='$data[fid]' ORDER BY forumorder ASC");
				if (dbrows($result2) != 0) {
					while ($data2 = dbarray($result2)) {
						$forums .= "<tr><td>$data2[forumname]</td><td align=\"center\">$data2[forumorder]</td>
<td><span class=\"small\"><a href=\"$_SELF?sub=editforum&fid=$data2[fid]&t=f\">Edit</a></span> |
<span class=\"small\"><a href=\"$_SELF?sub=forumadmin&stage=2&delete=$data2[fid]&t=f\">Delete</a></span></td></tr>\n";
						if ($data2[forumdetails] != "") {
							$forums .= "<tr><td colspan=\"3\" class=\"small\">".stripslashes($data2[forumdetails])."</td></tr>\n";
						}
					}
				} else {
					$forums .= "<tr><td colspan=\"3\">No forums have been defined in this category</td></tr>\n";
				}
			}
		} else {
			$forums = "<tr><td colspan=\"3\">No Categories have been defined</td></tr>\n";
		}
		// show the resulting phtml
		opentable("Current Forum Layout");
		echo "<table align=\"center\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td><span class=\"alt\">Category/Forum Name</span></td>
<td width=\"80\" align=\"center\"><span class=\"alt\">Row Order</span></td>
<td width=\"90\"><span class=\"alt\">Options</span></td></tr>
$forums
</table>\n";
		closetable();
		tablebreak();
		opentable("Add New Category/Forum");
		echo "<form name=\"layoutform\" method=\"post\" action=\"$_SELF?sub=forumadmin&stage=2\">
<table align=\"center\" width=\"450\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Category:</td>
<td><input type=\"textbox\" name=\"newcatname\" class=\"textbox\" style=\"width: 150px;\"></td>
<td colspan=\"2\"><input type=\"textbox\" name=\"newcatorder\" class=\"textbox\" style=\"width: 35px;\"></td></tr>
<tr><td>Forum:</td>
<td><input type=\"textbox\" name=\"newforumname\" class=\"textbox\" style=\"width: 150px;\"></td>
<td><input type=\"textbox\" name=\"newforumorder\" class=\"textbox\" style=\"width: 35px;\"></td>
<td><select name=\"newforumcategory\" class=\"textbox\" style=\"width: 150px;\">
$opts
</select></td></tr>
</table>
<br><div align=\"center\">
<input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" style=\"width: 100px;\"></div>
</form>\n";
		closetable();
	}
	if ($stage == 3) {
		opentable("Fix Forum Counts");
		$result = dbquery("SELECT * FROM posts");
		while ($data = dbarray($result)) {
			$result2 = dbquery("SELECT * FROM threads WHERE tid='$data[tid]'");
			if (dbrows($result2) == 0) {
				$result3 = dbquery("DELETE FROM posts WHERE tid='$data[tid]'");
			}
		}
		$forums = dbquery("SELECT * FROM forums WHERE forumtype='forum'");
		while ($forumdata = dbarray($forums)) {
			$threadscount = dbquery("SELECT count(fid) FROM threads WHERE fid='$forumdata[fid]'");
			$numthreads = dbresult($threadscount, 0);
			$postscount = dbquery("SELECT count(fid) FROM posts WHERE fid='$forumdata[fid]'");
			$numposts = dbresult($postscount, 0)-$numthreads;
			if ($numposts == -1) {
				$numposts = 0;
			}
			$result = dbquery("UPDATE forums SET threads='$numthreads', posts='$numposts' WHERE fid='$forumdata[fid]'");
		}
		$threads = dbquery("SELECT * FROM threads");
		while ($threadsdata = dbarray($threads)) {
			$repliescount = dbquery("SELECT count(tid) FROM posts WHERE tid='$threadsdata[tid]'");
			$numreplies = dbresult($repliescount, 0)-1;
			if ($numreplies == -1) {
				$numreplies = 0;
			}
			$result = dbquery("UPDATE threads SET replies='$numreplies' WHERE tid='$threadsdata[tid]'");
		}	
		echo "<div align=\"center\">
<br>Forum Threads & Posts Counts Updated<br><br>
Threads Replies Counts Updated<br><br>
Unrelated Posts Removed from Database<br><br>
<a href=\"$_SELF?sub=forumadmin\">Return to the Forum Admin Panel</a><br><br>
<a href=\"index.php\">Return to Admin Home</a></div><br>\n";
		closetable();
	}
}
?>