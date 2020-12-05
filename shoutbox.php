<?
if (isset($_POST['shout'])) {
	if ($settings[visitorshoutbox] == "yes") {
		$shoutname = htmlentities($shoutname);
		$shoutname = str_replace("&nbsp;", "", $shoutname);
		$shoutname = trim($shoutname);
	} else {
		$shoutname = $userdata[username];
	}
	$message = htmlentities($message);
	$message = preg_replace("/^(.{255}).*$/", "$1", $message);
	$message = preg_replace("/([^\s]{80})/", "$1<br>", $message);
	$message = str_replace("&nbsp;", "", $message);
	$message = trim($message);
	if ($shoutname != "" && $message != "") {
		$result = dbquery("INSERT INTO shoutbox VALUES ('', '$shoutname', '$message', '$servertime', '$userip')");
	}
}

if ($settings[visitorshoutbox] == "yes") {
	$shoutbox = "<form name=\"chatform\" method=\"post\" action=\"$PHP_SELF\">
Name:<br>
<input type=\"textbox\" name=\"shoutname\" value=\"$userdata[username]\" class=\"textbox\" maxlength=\"32\" style=\"width: 100%;\"><br>
Message:<br>
<textarea name=\"message\" rows=\"3\" class=\"textbox\" style=\"width: 100%;\"></textarea><br>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td>
<input type=\"submit\" name=\"shout\" value=\"Shout\" class=\"button\" style=\"width: 80px;\"></td>
<td align=\"right\"><font style=\"font-size: 7pt;\"><a href=\"shoutboxhelp.php\">Help</a></font></td></tr>
</table>
</form><br>\n";
} else {
	if ($userdata[username] != "") {
		$shoutbox = "<form name=\"chatform\" method=\"post\" action=\"$PHP_SELF\">
Name:<br>
<font class=\"alt2\">$userdata[username]</font><br>
Message:<br>
<textarea name=\"message\" rows=\"3\" class=\"textbox\" style=\"width: 100%;\"></textarea><br>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td>
<input type=\"submit\" name=\"shout\" value=\"Shout\" class=\"button\" style=\"width: 80px;\"></td>
<td align=\"right\"><font style=\"font-size: 7pt;\">&middot;&nbsp;<a href=\"shoutboxhelp.php\">Help</a>&nbsp;&middot;</font></td></tr>
</table>
</form><br>\n";
	} else {
		$shoutbox = "<center><font class=\"alt2\">You Must <a href=\"login.php\">Login</a> to post messages here</font></center><br>\n";
	}
}


$result = dbquery("SELECT * FROM shoutbox ORDER BY posted DESC LIMIT 0,10");
$rows = dbrows($result);
if ($rows != 0) {
	$i = 1;
	while ($data = dbarray($result)) {
		$message = stripslashes($data[message]);
		$message = parsesmileys($message, "themes/$settings[theme]/images/smileys");
		$datestamp = gmdate("F d", $data[posted])." at ".gmdate("H:i", $data[posted]);
$shoutbox .= "<div align=\"left\" class=\"shoutboxname\"><b>$data[name]</b></div>
<div align=\"left\" class=\"shoutbox\">$message</div>
<div class=\"shoutboxdate\">$datestamp</div>\n";
	}
} else {
	$shoutbox .= "<div align=\"left\">no messages have been posted.</div>\n";
}
opentables("Shoutbox");
echo "$shoutbox";
closetable();
tablebar();
?>