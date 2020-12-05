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
require "header.php";
require fusion_basedir."subheader.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";
opentable("Upgrade");
if ($userdata[user_mod] > "2") {
	echo "<center><br>
There is no database upgrade available
</center><br>\n";
}
closetable();
echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>