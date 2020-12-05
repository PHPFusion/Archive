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
require fusion_langdir."admin/admin_main.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

opentable("Upgrade");
if (SuperAdmin()) {
	echo "<center>There is no upgrade available for this installation of PHP-Fusion.</center>\n";
}
closetable();

echo "</td>\n";
require "../footer.php";
?>