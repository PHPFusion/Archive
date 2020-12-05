<?
require "header.php";
require "subheaderjs.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if (isset($_POST['submitlink'])) {
	$sitename = stripinput($sitename);
	$siteurl = stripinput($siteurl);
	$subname = stripinput($subname);
	$subemail = stripinput($subemail);
	if ($sitename != "" && $siteurl != "" && $subname != "" && $subemail != "") {
		$result = dbquery("INSERT INTO linksubmits VALUES ('', '$sitename', '$siteurl', '$category', '$subname', '$subemail', '$servertime', '$userip')");
		opentable("Submit Link");
		echo "<br><center>Your Link has been submitted and will verified shortly<br><br>
<a href=\"submitlink.php\">Submit another Link</a><br><br>
<a href=\"index.php\">Return to $settings[sitename] Home</a><br><br></center>\n";
		closetable();
	} else {
		opentable("Submit Link");
		echo "<br><center>You did not complete the form, please try again.<br><br></center>\n";
		closetable();
	}
} else {
	$result = dbquery("SELECT * FROM weblinks WHERE linktype='category' ORDER BY linkorder");
	while ($data = dbarray($result)) {
		$opts .= "<option>$data[linkname]</option>\n";
	}
	$opts .= "<option>other</option>\n";
	opentable("Submit Link");
	echo "Use the following form to submit a web link. Your submission will be verified by an
Administrator. $settings[sitename] reserves the right not to add links in inappropriate
material. Please do not submit a link more than once.<br><br>
<table cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr><td>Site Name:</td>
<td><input type=\"textbox\" name=\"sitename\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Site URL:</td>
<td><input type=\"textbox\" name=\"siteurl\" value=\"http://\" maxlength=\"255\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Category:</td>
<td><select name=\"category\" class=\"textbox\" style=\"width: 200px;\">
$opts</select></td></tr>
<tr><td>Your Name:</td>
<td><input type=\"textbox\" name=\"subname\" value=\"$userdata[username]\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Your Email:</td>
<td><input type=\"textbox\" name=\"subemail\" value=\"$userdata[email]\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td colspan=\"2\"><br>
<input type=\"submit\" name=\"submitlink\" value=\"Submit Link\" class=\"button\" style=\"width: 100px;\">
</td></tr>
</form>
</table>\n";
	closetable();
}
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>