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

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";
opentable("Upgrade");
if ($userdata[user_mod] > "2") {
	if ($settings[version] < 304) {
		echo "<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"upgradeform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>
A minor database is available for this installation of PHP-Fusion.<br>
It is recommended that you back-up your database prior to completing this process.<br><br>
The following changes will be applied:<br><br>
· post_smileys column will be inserted into the posts database table.<br>
· version number will be set to 304.<br><br>
<center>
<input type=\"hidden\" name=\"stage\" value=\"2\">
<input type=\"submit\" name=\"upgrade\" value=\"Upgrade\" class=\"button\" style=\"width:100px\">
</center>
</td>
</tr>
</form>
</table>\n";
	} else {
		echo "<center>There is no upgrade available for this installation of PHP-Fusion.</center>\n";
	}
	if ($_POST['stage'] == 2) {
		if (isset($_POST['upgrade'])) {
			$result = dbquery("ALTER TABLE ".$fusion_prefix."posts ADD post_smileys char(1) default 'y' NOT NULL AFTER post_showsig");
			$result = dbquery("UPDATE ".$fusion_prefix."settings SET version='304'");
			echo "Upgrade Complete\n";
		}
	}
}
closetable();

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>