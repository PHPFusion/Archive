<?
if ($userdata[mod] == "Administrator") {
	if (empty($stage)) {
		$i = 1;
		while ($i != 15) {
			$i++;
			$options .= "<option>$i</option>\n";
		}
		opentable("Add Poll");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"addpollform\" method=\"post\" action=\"$_SELF?sub=addpoll&stage=2\">
<tr><td colspan=\"2\">Adding a new Poll closes any current running Poll. Previous Polls are stored
in the database and can be deleted using the appropriate panel option.<br><br></td></tr>
<tr><td>Number of Poll Options:</td>
<td width=\"225\"><select name=\"options\" class=\"textbox\" style=\"width: 50px;\">
$options</select></td></tr>
<tr><td colspan=\"2\"><div align=\"center\"><br>
<input type=\"submit\" name=\"addoptions\" value=\"Add Options\" class=\"button\" style=\"width: 100px;\">
</div></td></tr>
</form>
</table>\n";
closetable();
	}
	if ($stage == 2) {
		$i = 0;
		while ($i != $options) {
			$opts .= "<tr><td><input type=\"textbox\" name=\"option$i\" class=\"textbox\" style=\"width: 200px;\"></td></tr>\n";
			$i++;
		}
		opentable("Add Poll");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"addpollform\" method=\"post\" action=\"$_SELF?sub=addpoll&stage=3\">
<tr><td>Poll Question:</td></tr>
<tr><td><input type=\"textbox\" name=\"title\" class=\"textbox\" style=\"width: 350px;\"></td></tr>
<tr><td>Poll Options:</td></tr>
$opts
<tr><td><div align=\"center\"><br>
<input type=\"hidden\" name=\"options\" value=\"$options\">
<input type=\"submit\" name=\"preview\" value=\"Preview Poll\" class=\"button\" style=\"width: 100px;\">
</div></td></tr>
</form>
</table>\n";
closetable();
}
if ($stage == 3) {
		$i = 0;
		while ($i != $options) {
			$opt = "option".$i;
			$opt = stripslashes($$opt);
			$opts .= "<input type=\"radio\" name=\"option\" value=\"$i\"> 
<span>$opt</span><br>\n";
			$i++;
			if ($i != $options) { $opttext .= $opt."|"; } else { $opttext .= $opt; }
		}
		opentable("Add Poll");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"addpollform\" method=\"post\" action=\"$_SELF?sub=addpoll&stage=4\">
<tr><td>This is a Preview of your new Poll, if you are satisfied with this
you can start this Poll by clicking Save Poll. Note that any current Poll
will be closed.<br><br></td></tr>
<tr><td><span class=\"small\">
$title<br><br>
$opts</span></td></tr>
<tr><td><div align=\"center\"><br>
<input type=\"hidden\" name=\"title\" value=\"$title\">
<input type=\"hidden\" name=\"options\" value=\"$opttext\">
<input type=\"submit\" name=\"savepoll\" value=\"Save Poll\" class=\"button\" style=\"width: 80px;\">
</div></td></tr>
</form>
</table>\n";
closetable();
	}
	if ($stage == 4) {
		$result = dbquery("UPDATE polls SET ended='$servertime' WHERE ended='0'");
		$result = dbquery("INSERT INTO polls VALUES('', '$title', '$options', '0', '$servertime', '0')");
		$options = explode("|", $options);
		$optioncount = count($options);
		$i = 0;
		while ($i != $optioncount) {
			$poll .= "<input type=\"radio\" name=\"polloption\" value=\"$i\">".$options[$i]."<br>\n";
			$i++;
		}
		opentable("Add Poll");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Poll Saved<br><br></td></tr>
<tr><td><span class=\"small\">
$title<br><br>
$poll</span><br></td></tr>
<tr><td>Your new Poll is now open. you can edit or close it at
any time by going to the Edit Poll Admin Panel.</td></tr>
<tr><td><div align=\"center\"><br>
<a href=\"index.php\">Return to Admin Index</a>
</div></td></tr>
</form>
</table>\n";
closetable();
	}
}
?>