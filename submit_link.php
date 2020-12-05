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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."submit_link.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if (isset($_POST['submit_link'])) {
	$link_sitename = stripinput($link_sitename);
	$link_url = stripinput($link_url);
	$link_name = stripinput($link_name);
	$link_email = stripinput($link_email);
	if ($link_sitename != "" && $link_url != "" && $link_name != "" && $link_email != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_links VALUES ('', '$link_sitename', '$link_description', '$link_url', '$link_category', '$link_name', '$link_email', '".time()."', '$user_ip')");
		opentable(LAN_200);
		echo "<center><br>
".LAN_210."<br>
".LAN_211."<br><br>
<a href=\"submit_link.php\">".LAN_212."</a><br><br>
<a href=\"index.php\">".LAN_213."</a><br><br>
</center>\n";
		closetable();
	} else {
		opentable(LAN_200);
		echo "<center><br>
".LAN_220."<br>
".LAN_221."<br><br>
<a href=\"submit_link.php\">".LAN_222."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
	while ($data = dbarray($result)) {
		$opts .= "<option>".stripslashes($data[weblink_cat_name])."</option>\n";
	}
	$opts .= "<option>Other</option>\n";
	opentable(LAN_200);
	echo LAN_230."<br><br>
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>".LAN_231."</td>
<td><input type=\"textbox\" name=\"link_sitename\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_232."</td>
<td><input type=\"textbox\" name=\"link_description\" maxlength=\"200\" class=\"textbox\" style=\"width:300px;\"></td>
</tr>
<tr>
<td>".LAN_233."</td>
<td><input type=\"textbox\" name=\"link_url\" value=\"http://\" maxlength=\"200\" class=\"textbox\" style=\"width:300px;\"></td>
</tr>
<tr>
<td>".LAN_234."</td>
<td><select name=\"link_category\" class=\"textbox\" style=\"width:200px;\">
$opts</select></td>
</tr>
<tr>
<td>".LAN_235."</td>
<td><input type=\"textbox\" name=\"link_name\" value=\"$userdata[user_name]\" maxlength=\"30\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_236."</td>
<td><input type=\"textbox\" name=\"link_email\" value=\"$userdata[user_email]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br>
<input type=\"submit\" name=\"submit_link\" value=\"".LAN_237."\" class=\"button\">
</td>
</tr>
</form>
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