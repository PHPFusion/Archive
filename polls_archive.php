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
require "subheader.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."polls WHERE poll_ended!='0' ORDER BY poll_id DESC");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$editlist .= "<option value=\"$data[poll_id]\">".stripslashes($data[poll_title])."</option>\n";
	}
	opentable(LAN_88);
	echo "<form name=\"editform\" method=\"post\" action=\"$PHP_SELF\">
<center>
".LAN_89."<br>
<select name=\"poll_id\" class=\"textbox\" style=\"width:200px;\">
$editlist</select>
<input type=\"submit\" name=\"view\" value=\"".LAN_90."\" class=\"button\" style=\"width:60px;\">
</center>
</form>\n";
	closetable();
}
if (isset($poll_id)) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id' AND poll_ended!='0'");
	$data = dbarray($result);
	$i = 0;
	while ($i != $data[poll_optcount]) {
		$result = dbquery("SELECT count(vote_opt) FROM ".$fusion_prefix."poll_votes WHERE vote_opt='$i' AND poll_id='$data[poll_id]'");
		$numvotes = dbresult($result, 0);
		$optvotes = number_format(100/$data[poll_votes]*$numvotes);
		if ($numvotes == 1) {
			$votecount = "[1 ".LAN_83."]";
		} else {
			$votecount = "[$numvotes ".LAN_84."]";
		}
		$polloption[$i] = stripslashes($data["poll_opt_".$i]);
		$poll_archive .= "$polloption[$i]<br>
<img src=\"".fusion_themedir."images/pollbar.gif\" height=\"12\" width=\"$optvotes%\" class=\"poll\"><br>
".$optvotes."% ".$votecount."<br><br>\n";
	$i++;
	}
	tablebreak();
	opentable(LAN_91);
	echo "<table align=\"center\" width=\"200\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td>".stripslashes($data[poll_title])."
<hr>
$poll_archive
<center>
".LAN_85."$data[poll_votes]<br>
".LAN_86.strftime($settings[shortdate], $data[poll_started]+($settings[timeoffset]*3600))."<br>
".LAN_87.strftime($settings[shortdate], $data[poll_ended]+($settings[timeoffset]*3600))."
</center>
</td>
</td>
</table>\n";
	closetable();
}

echo "</td>
<td width=\"170\" valign=\"top\" class=\"srborder\">\n";
require "userinfo.php";
require "poll.php";
require "shoutbox.php";
echo "</td>
</tr>
</table>\n";

require "footer.php";
?>