<?
if ($userdata[mod] == "Administrator") {
	if (empty($action) || ($action == "")) {
		$shoutsperpage = 10;
		$result = dbquery("SELECT * FROM shoutbox");
		$rows = dbrows($result);
		if (!$rowstart) {
			$rowstart = 0;
		}
		$shoutbox = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
		if ($rows != 0) {
			if ($rowstart == 0) {
				$opts = "<option value=\"10\">10</option>
<option value=\"20\">20</option>
<option value=\"30\">30</option>
<option value=\"60\">60</option>
<option value=\"90\">90</option>\n";
				$shoutbox .= "<form name=\"deleteshouts\" method=\"post\" action=\"$_SELF?sub=shoutboxadmin&action=deleteshouts\">
<tr><td colspan=\"2\" align=\"center\">Delete Shouts older than <select name=\"days\" class=\"textbox\" style=\"width: 50px\">$opts</select> days<br><br>
<input type=\"submit\" name=\"deleteshouts\" value=\"Delete Shouts\" class=\"textbox\" style=\"width: 100px\"><br><hr>
</form></td></tr>\n";
			}
			$totalpages = ceil($rows / $shoutsperpage);	
			$currentpage = $rowstart / $shoutsperpage + 1;
			// check how many news items are in the database
			$result = dbquery("SELECT * FROM shoutbox ORDER BY posted DESC LIMIT $rowstart,$shoutsperpage");
			$numrows = dbrows($result);
			$i = 1;
			while ($data = dbarray($result)) {
				$message = stripslashes($data[message]);
				$message = parsesmileys($message, "../themes/$settings[theme]/images/smileys");
				$datestamp = gmdate("F d", $data[posted])." at ".gmdate("H:i", $data[posted]);
				$shoutbox .= "<tr><td colspan=\"2\"><span class=\"shoutboxname\">$data[name]</span></td></tr>
<tr><td colspan=\"2\" class=\"shoutbox\">$message</td></tr>
<tr><td><span class=\"small\"><a href=\"$_SELF?sub=shoutboxadmin&action=edit&shoutid=$data[shoutid]\">Edit</a> |
<a href=\"$_SELF?sub=shoutboxadmin&action=delete&shoutid=$data[shoutid]\">Delete</a></span></td><td class=\"shoutboxdate\">$datestamp</td></tr>\n";
				if ($i != $numrows) {
					$shoutbox .= "<tr><td colspan=\"2\" height=\"10\"></td></tr>";
				}
				$i++;
			}
		} else {
			$shoutbox .= "<tr><td><div align=\"left\">No messages have been posted.</div></td></tr>\n";
		}
		$shoutbox .= "</table>\n";
		opentable("Shoutbox Admin");
		echo $shoutbox;
		closetable();
		if ($rowstart >= $shoutsperpage) {
			$start = $rowstart - $shoutsperpage;
			$prev = "<a href=\"$_SELF?sub=shoutboxadmin&rowstart=$start\" class=\"x\">Prev</a>&nbsp;";
		}
		if ($rowstart + $shoutsperpage < $rows) {
			$start = $rowstart + $shoutsperpage;
			$next = "<a href=\"$_SELF?sub=shoutboxadmin&rowstart=$start\" class=\"x\">Next</a>&nbsp;";
		}
		if ($prev != "" || $next != "") {
			tablebreak();
			opentablex();
			echo "<table width=\"100%\" class=\"nextprev\">
<tr><td width=\"50\" class=\"small\">$prev</td>
<td align=\"center\" class=\"small\">Page $currentpage of $totalpages</td>
<td width=\"50\" align=\"right\" class=\"small\">$next</td></tr>
</table>\n";
			closetablex();
		}
	}
	if ($action == "deleteshouts") {
		$deletetime = $servertime - ($days * 86400);
		$result = dbquery("SELECT * FROM shoutbox WHERE posted < '$deletetime'");
		$numrows = dbrows($result);
		$result = dbquery("DELETE FROM shoutbox WHERE posted < '$deletetime'");
		opentable("Delete Shouts");
		echo "<div align=\"center\"><br>
$numrows shouts have been deleted<br><br>
<a href=\"$_SELF?sub=shoutboxadmin\">Return to Shoutbox Admin</a><br><br>
<a href=\"index.php\">Return to Admin Panel</a><br><br></div>\n";
		closetable();
	}
	if ($action == "edit") {
		if (isset($_POST['updateshout'])) {
			$message = htmlentities($message);
			$message = preg_replace("/^(.{255}).*$/", "$1", $message);
			$message = preg_replace("/([^\s]{80})/", "$1<br>", $message);
			$message = str_replace("&nbsp;", "", $message);
			$message = trim($message);
			if ($message != "") {
				$result = dbquery("UPDATE shoutbox SET message='$message' WHERE shoutid='$shoutid'");
			}
			opentable("Shout Updated");
			echo "<div align=\"center\"><br>
The shout has been updated<br><br>
<a href=\"$_SELF?sub=shoutboxadmin\">Return to Shoutbox Admin</a><br><br>
<a href=\"index.php\">Return to Admin Panel</a><br><br></div>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM shoutbox WHERE shoutid='$shoutid'");
			$data = dbarray($result);
			$message = stripslashes($data[message]);
			opentable("Edit Shout");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"updateform\" method=\"post\" action=\"$_SELF?sub=shoutboxadmin&action=edit&shoutid=$shoutid\">
<tr><td valign=\"top\">
<textarea name=\"message\" rows=\"3\" class=\"textbox\" style=\"width: 100%;\">$message</textarea>
</td></tr>
<tr><td><div align=\"center\"><br>
<input type=\"submit\" name=\"updateshout\" value=\"Update Shout\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
			closetable();
		}
	}
	if ($action == "delete") {
		$result = dbquery("DELETE FROM shoutbox WHERE shoutid='$shoutid'");
		opentable("Delete Shout");
		echo "<div align=\"center\"><br>
the shout has been deleted<br><br>
<a href=\"$_SELF?sub=shoutboxadmin\">Return to Shoutbox Admin</a><br><br>
<a href=\"index.php\">Return to Admin Panel</a><br><br></div>\n";
		closetable();
	}
}
?>