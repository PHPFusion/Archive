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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable("Upgrade");
if ($userdata[user_mod] > "2") {
	echo "<center>There is no upgrade available for this installation of PHP-Fusion.</center>\n";
}
closetable();

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>