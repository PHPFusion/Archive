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
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads ORDER BY thread_lastpost DESC LIMIT 0,$settings[numofthreads]");
if (dbrows($result) != 0) {
	opentable(LAN_31);
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td><b>".LAN_32."</b></td>
<td align=\"center\" width=\"50\"><b>".LAN_33."</b></td>
<td align=\"center\" width=\"50\"><b>".LAN_34."</b></td>
<td align=\"right\"><b>".LAN_35."</b></td>
</tr>\n";
	while ($data = dbarray($result)) {
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$data[forum_id]'");
		$data2 = dbarray($result2);
		$thread_subject = stripslashes($data[thread_subject]);
		if (strlen($thread_subject) > 30) {
			$thread_subject = substr($thread_subject, 0, 27)."...";
		}
		$lastuser = explode(".", $data[thread_lastuser]);
		echo "<tr>
<td><a href=\"forum/viewthread.php?forum_id=$data2[forum_id]&thread_id=$data[thread_id]\">$thread_subject</a><br>
<span class=\"small\">[".stripslashes($data2[forum_name])."]</span></td>
<td align=\"center\">$data[thread_views]</td>
<td align=\"center\">$data[thread_replies]</td>
<td align=\"right\"><a href=\"profile.php?lookup=$lastuser[0]\">$lastuser[1]</a><br>
<span class=\"small\">".strftime($settings[forumdate], $data[thread_lastpost]+($settings[timeoffset]*3600))."</span></td>
</tr>\n";
	}
	echo "</table>\n";
	closetable();
	tablebreak();
}
?>