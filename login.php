<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Login");
echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"loginform\" method=\"post\" action=\"index.php\">
<tr><td colspan=\"2\">Login to $settings[sitename] by entering your
details below.<br><br></td></tr>
<tr class=\"content\"><td width=\"90\">Username:</td>
<td><input type=\"textbox\" name=\"username\" class=\"textbox\" maxlength=\"255\" style=\"width: 170px;\"></td></tr>
<tr class=\"content\"><td width=\"90\">Password:</td>
<td><input type=\"password\" name=\"password\" class=\"textbox\" maxlength=\"255\" style=\"width: 170px;\"></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"login\" value=\"Login\" class=\"button\" style=\"width: 100px;\"></div></td></tr>
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