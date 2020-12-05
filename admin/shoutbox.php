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
require fusion_langdir."admin/admin_shoutbox.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	if ($action == "deleteshouts") {
		$deletetime = time() - ($days * 86400);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox WHERE shout_datestamp < '$deletetime'");
		$numrows = dbrows($result);
		$result = dbquery("DELETE FROM ".$fusion_prefix."shoutbox WHERE shout_datestamp < '$deletetime'");
		opentable(LAN_200);
		echo "<center><br>
$numrows ".LAN_201."<br><br>
<a href=\"shoutbox.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		closetable();
	} else if ($action == "delete") {
		opentable(LAN_204);
		$result = dbquery("DELETE FROM ".$fusion_prefix."shoutbox WHERE shout_id='$shout_id'");
		echo "<center><br>
".LAN_205."<br><br>
<a href=\"shoutbox.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['saveshout'])) {
			if ($action == "edit") {
				$shout_message = stripinput($shout_message);
				$shout_message = addslashes($shout_message);
				$result = dbquery("UPDATE ".$fusion_prefix."shoutbox SET shout_message='$shout_message' WHERE shout_id='$shout_id'");
				unset($action, $shout_message);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox WHERE shout_id='$shout_id'");
			$data = dbarray($result);
			$shout_message = stripslashes($data[shout_message]);
			opentable(LAN_220);
			echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?action=edit&shout_id=$data[shout_id]\">
<tr>
<td>".LAN_221."</td>
</tr>
<tr>
<td><textarea name=\"shout_message\" rows=\"3\" class=\"textbox\" style=\"width:200px;\">$shout_message</textarea></td>
</tr>
<tr>
<td align=\"center\"><input type=\"submit\" name=\"saveshout\" value=\"".LAN_222."\" class=\"button\" style=\"width: 100px;\"></td>
</tr>
</form>
</table>\n";
			closetable();
			tablebreak();
		}
		$shoutsperpage = 10;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox");
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
				$shoutbox .= "<form name=\"deleteform\" method=\"post\" action=\"$PHP_SELF?action=deleteshouts\">
<tr>
<td colspan=\"2\" align=\"center\">".LAN_230."
<select name=\"days\" class=\"textbox\" style=\"width: 50px\">$opts</select>".LAN_231."<br><br>
<input type=\"submit\" name=\"deleteshouts\" value=\"".LAN_232."\" class=\"button\" style=\"width:100px\"><br><hr>
</td>
</tr>
</form>
\n";
			}
			$totalpages = ceil($rows / $shoutsperpage);	
			$currentpage = $rowstart / $shoutsperpage + 1;
			// check how many news items are in the database
			$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox ORDER BY shout_datestamp DESC LIMIT $rowstart,$shoutsperpage");
			$numrows = dbrows($result);
			$i = 1;
			while ($data = dbarray($result)) {
				$message = stripslashes($data[shout_message]);
				$message = parsesmileys($message);
				$postee = explode(".", $data[shout_name]);
				if ($postee[0] != 0) {
					$shoutname = "<a href=\"../profile.php?lookup=$postee[0]\">$postee[1]</a>";
				} else {
					$shoutname = "$postee[1]";
				}
				$shoutbox .= "<tr><td colspan=\"2\">$shoutname</td></tr>
<tr><td colspan=\"2\">$message</td></tr>
<tr><td><span class=\"small\"><a href=\"$PHP_SELF?action=edit&shout_id=$data[shout_id]\">".LAN_241."</a> |
<a href=\"$PHP_SELF?action=delete&shout_id=$data[shout_id]\">".LAN_242."</a></span></td>
<td align=\"right\" class=\"small\">".strftime($settings[shortdate], $data[shout_datestamp]+($settings[timeoffset]*3600))."</td></tr>\n";
				if ($i != $numrows) {
					$shoutbox .= "<tr><td colspan=\"2\" height=\"10\"></td></tr>";
				}
				$i++;
			}
		} else {
			$shoutbox .= "<tr><td><div align=\"left\">".LAN_243."</div></td></tr>\n";
		}
		$shoutbox .= "</table>\n";
		opentable(LAN_240);
		echo $shoutbox;
		closetable();
		if ($rowstart >= $shoutsperpage) {
			$start = $rowstart - $shoutsperpage;
			$prev = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_50."</a>&nbsp;";
		}
		if ($rowstart + $shoutsperpage < $rows) {
			$start = $rowstart + $shoutsperpage;
			$next = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_51."</a>&nbsp;";
		}
		if ($prev != "" || $next != "") {
			$current = LAN_52.$currentpage.LAN_53.$totalpages;
			tablebreak();
			prevnextbar($prev,$current,$next);
		}
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>