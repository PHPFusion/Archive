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
require fusion_langdir."editprofile.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_200);
echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF\" enctype=\"multipart/form-data\">\n";
if (isset($_POST['update_profile'])) {
	if ($error == "") {
		echo "<tr>
<td colspan=\"2\">".LAN_201."<br><br>
</td>
</tr>\n";
	} else {
		echo "<tr>
<td colspan=\"2\">".LAN_202."<br><br>
$error<br></td>
</tr>\n";
	}
}
echo "<tr>
<td>".LAN_210."</td>
<td><input type=\"textbox\" name=\"username\" value=\"$userdata[user_name]\" maxlength=\"30\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<td>".LAN_211."</td>
<td><input type=\"textbox\" name=\"newpassword\" value=\"\" maxlength=\"20\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_212."</td>
<td><input type=\"textbox\" name=\"newpassword2\" value=\"\" maxlength=\"20\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_213."</td>
<td><input type=\"textbox\" name=\"email\" value=\"$userdata[user_email]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_214."</td>\n";
if ($userdata[user_hide_email] == "1") { $yes = " checked"; $no = ""; } else { $yes = ""; $no = " checked"; } 
echo "<td><input type=\"radio\" name=\"hide_email\" value=\"1\"$yes>".LAN_215."<input type=\"radio\" name=\"hide_email\" value=\"2\"$no>".LAN_216."</td>
</tr>
<tr>
<td>".LAN_217."</td>
<td><input type=\"textbox\" name=\"location\" value=\"$userdata[user_location]\" maxlength=\"50\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_218."</td>
<td><input type=\"textbox\" name=\"icq\" value=\"$userdata[user_icq]\" maxlength=\"15\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_219."</td>
<td><input type=\"textbox\" name=\"msn\" value=\"$userdata[user_msn]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_220."</td>
<td><input type=\"textbox\" name=\"yahoo\" value=\"$userdata[user_yahoo]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_221."</td>
<td><input type=\"textbox\" name=\"web\" value=\"$userdata[user_web]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<td>".LAN_222."</td>
<td>";
if ($userdata[user_avatar] == "") {
	echo "<input type=\"file\" name=\"avatar\" enctype=\"multipart/form-data\" class=\"textbox\" style=\"width:200px;\"><br>
<span class=\"small2\">".LAN_223."</span>";
} else {
	echo "<input type=\"textbox\" name=\"avatar\" value=\"$userdata[user_avatar]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"><br>
<span class=\"small2\">".LAN_224."</span>";
}
echo "</td>
</tr>
<tr>
<td valign=\"top\" class=\"content\">".LAN_225."</td>
<td><textarea name=\"signature\" rows=\"5\" class=\"textbox\" style=\"width:295px\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$userdata[user_sig]</textarea><br>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('b');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('i');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('u');\">
<input type=\"button\" value=\"url\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('url');\">
<input type=\"button\" value=\"mail\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('mail');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('img');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('center');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('small');\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br>
<input type=\"submit\" name=\"update_profile\" value=\"".LAN_226."\" class=\"button\"></td>
</tr>
</form>
</table>\n";
closetable();
echo "<script language=\"JavaScript\">
var editBody = document.editform.signature;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[/\" + wrap + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][/\" + wrap + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";

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