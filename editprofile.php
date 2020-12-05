<?
require "header.php";
require "subheaderjs.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Edit Profile");
echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr><td>Username:<font color=\"red\">*&nbsp</font></td>
<td><input type=\"textbox\" name=\"username\" value=\"$userdata[username]\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Email Address:<font color=\"red\">*&nbsp</font></td>
<td><input type=\"textbox\" name=\"email\" value=\"$userdata[email]\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Location:&nbsp</td>
<td><input type=\"textbox\" name=\"location\" value=\"$userdata[location]\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>ICQ Number:&nbsp</td>
<td><input type=\"textbox\" name=\"icq\" value=\"$userdata[icq]\" maxlength=\"12\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>MSN Address:&nbsp</td>
<td><input type=\"textbox\" name=\"msn\" value=\"$userdata[msn]\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Yahoo ID:&nbsp</td>
<td><input type=\"textbox\" name=\"yahoo\" value=\"$userdata[yahoo]\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Web URL: <span class=\"small\">include http:// prefix</span>&nbsp</td>
<td><input type=\"textbox\" name=\"web\" value=\"$userdata[web]\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td valign=\"top\" class=\"content\">Signature:&nbsp;</td>
<td><textarea name=\"signature\" rows=\"4\" class=\"textbox\" style=\"width: 369px\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">$userdata[sig]</textarea><br>
<input type=\"button\" value=\"Bold\" class=\"button\" style=\"width: 35px;\" onClick=\"AddTags('[b]', '[/b]');\">
<input type=\"button\" value=\"Italic\" class=\"button\" style=\"width: 40px;\" onClick=\"AddTags('[i]', '[/i]');\">
<input type=\"button\" value=\"Underline\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[u]', '[/u]');\">
<input type=\"button\" value=\"Small\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[small]', '[/small]');\">
<input type=\"button\" value=\"Center\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('[center]', '[/center]');\">
<input type=\"button\" value=\"Hyperlink\" class=\"button\" style=\"width: 60px;\" onClick=\"AddTags('[url]', '[/url]');\">
<input type=\"button\" value=\"Image\" class=\"button\" style=\"width: 45px;\" onClick=\"AddTags('[img]', '[/img]');\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"updateprofile\" value=\"Update Profile\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>