<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";

if (!checkrights("U")) fallback("../index.php");

opentable("Upgrade");
if (str_replace(".","",$settings['version']) < "60111") {
	if (!isset($_POST['stage'])) {
		echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='upgrade.php'>
<tr>
<td>
<center>
A minor database upgrade is available for this installation of PHP-Fusion.<br>
Simply click Upgrade to update your system.<br><br>
<input type='hidden' name='stage' value='2'>
<input type='submit' name='upgrade' value='Upgrade' class='button'>
</center>
</td>
</tr>
</form>
</table>\n";
	}
	if (isset($_POST['stage']) && $_POST['stage'] == 2) {
		if (isset($_POST['upgrade'])) {
			$result = dbquery("UPDATE ".$db_prefix."settings SET version='6.01.11'");
			echo "<center><br>\nDatabase upgrade complete.<br><br>\n</center>\n";
		}
	}
} else {
	echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
}
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>