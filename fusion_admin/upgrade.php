<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once "navigation.php";

if (!checkrights("O")) { header("Location:../index.php"); exit; }

opentable("Upgrade");
echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
closetable();

echo "</td>
</tr>
</table>\n";

require_once FUSION_BASE."footer.php";
?>