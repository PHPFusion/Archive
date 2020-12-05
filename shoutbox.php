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
openside(LAN_100);
if ($settings[guestposts] == "1") {
	echo "<form name=\"chatform\" method=\"post\" action=\"$PHP_SELF\">
".LAN_101."<br>
<input type=\"textbox\" name=\"shoutname\" value=\"$userdata[user_name]\" class=\"textbox\" maxlength=\"32\" style=\"width:100%;\"><br>
".LAN_102."<br>
<textarea name=\"shout_message\" rows=\"3\" class=\"textbox\" style=\"width:100%;\"></textarea><br>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td>
<input type=\"submit\" name=\"post_shout\" value=\"".LAN_103."\" class=\"button\" style=\"width:80px;\"></td>
<td align=\"right\"><a href=\"shoutboxhelp.php\" class=\"slink\">".LAN_104."</a></td></tr>
</table>
</form><br>\n";
} else {
	if ($userdata[user_name] != "") {
		echo "<form name=\"shoutform\" method=\"post\" action=\"$PHP_SELF\">
".LAN_102."<br>
<textarea name=\"shout_message\" rows=\"3\" class=\"textbox\" style=\"width: 100%;\"></textarea><br>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td>
<input type=\"submit\" name=\"post_shout\" value=\"".LAN_103."\" class=\"button\" style=\"width: 80px;\"></td>
<td align=\"right\"><a href=\"shoutboxhelp.php\" class=\"slink\">".LAN_104."</a></td></tr>
</table>
</form><br>\n";
	} else {
		echo "<center>".LAN_105."</center><br>\n";
	}
}

$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
$rows = dbresult($result, 0);
$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox ORDER BY shout_datestamp DESC LIMIT 0,".$settings[numofshouts]);
if (dbrows($result) != 0) {
	$i = 1;
	while ($data = dbarray($result)) {
		$shout_message = stripslashes($data[shout_message]);
		$shout_message = parsesmileys($shout_message);
		$postee = explode(".", $data[shout_name]);
$shoutbox .= "<div class=\"shoutboxname\">";
		if ($postee[0] != 0) {
			echo "<a href=\"profile.php?lookup=$postee[0]\" class=\"slink\">$postee[1]</a>";
		} else {
			echo "$postee[1]";
		}
		echo "</div>
<div align=\"left\" class=\"shoutbox\">$shout_message</div>
<div class=\"shoutboxdate\">".strftime($settings[shortdate], $data[shout_datestamp]+($settings[timeoffset]*3600))."</div>\n";
	}
	if ($rows > 10) {
		echo "<center><br>
[<a href=\"shoutbox_archive.php\" class=\"slink\">".LAN_106."</a>]</center>\n";
	}
} else {
	echo "<div align=\"left\">".LAN_107."</div>\n";
}
closeside();
?>