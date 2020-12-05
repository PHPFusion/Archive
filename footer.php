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
echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
<tr>
<td class=\"white-header\">
$settings[footer]
</td>
</tr>
</table>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
<tr>
<td align=\"center\" class=\"full-header\">
<br>Powered by PHP-Fusion X3 [RV3] © 2003-2004 <a href=\"http://www.digitaldominion.co.uk\" class=\"foot\">Digital Dominion</a><br><br>\n";
if ($settings[counter] == 1) {
	echo "1 ".LAN_120."<br><br>\n";
} else {
	echo "$settings[counter] ".LAN_121."<br><br>\n";
}
echo "</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>\n";
?>