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
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads ORDER BY thread_lastpost DESC LIMIT 0,$settings[numofthreads]");
if (dbrows($result) != 0) {
	opentable(LAN_31);
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"";
	if ($table_width == "100%") { echo " style=\"text-indent:2px\""; }
	echo ">\n<tr>\n";
	if ($table_width == "100%") {
		echo "<td><b>".LAN_32."</b></td>\n";
	}
	echo "<td><b>".LAN_33."</b></td>
<td align=\"center\"><b>".LAN_34."</b></td>
<td align=\"center\"><b>".LAN_35."</b></td>
<td align=\"right\"><b>".LAN_36."</b></td>
</tr>\n";
	while ($data = dbarray($result)) {
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$data[forum_id]'");
		$data2 = dbarray($result2);
		$thread_subject = stripslashes($data[thread_subject]);
		if (strlen($thread_subject) > 30) {
			$thread_subject = substr($thread_subject, 0, 27)."...";
		}
		$lastuser = explode(".", $data[thread_lastuser]);
		echo "<tr>\n";
		if ($table_width == "100%") {
			echo "<td>".stripslashes($data2[forum_name])."</td>
<td><a href=\"forum/viewthread.php?forum_id=$data2[forum_id]&thread_id=$data[thread_id]\">$thread_subject</a></td>\n";
		} else {
			echo "<td><a href=\"forum/viewthread.php?forum_id=$data2[forum_id]&thread_id=$data[thread_id]\">$thread_subject</a><br>
<span class=\"small\">[".stripslashes($data2[forum_name])."]</span></td>\n";
		}
		echo "<td align=\"center\">$data[thread_views]</td>
<td align=\"center\">$data[thread_replies]</td>
<td align=\"right\"><a href=\"profile.php?lookup=$lastuser[0]\">$lastuser[1]</a><br>
".strftime($settings[forumdate], $data[thread_lastpost]+($settings[timeoffset]*3600))."</td>
</tr>\n";
	}
	echo "</table>\n";
	closetable();
	tablebreak();
}
?>