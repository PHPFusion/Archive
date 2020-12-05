<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
echo "</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='white-header'>
$settings[footer]
</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' class='full-header'><br>\n";
if ($settings[counter] == 1) {
	echo "1 ".LAN_120."<br><br>\n";
} else {
	echo "$settings[counter] ".LAN_121."<br><br>\n";
}
echo "Powered by <a href='http://www.php-fusion.co.uk' class='foot'>PHP-Fusion</a> v".sprintf("%.2f", $settings[version]/100)."
© 2003-2004 <a href='mailto:nick@php-fusion.co.uk' class='foot'>Nick Jones</a><br><br>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>\n";

$result = dbquery("DELETE FROM ".$fusion_prefix."temp WHERE temp_time < '".(time()-300)."'");

ob_end_flush();
?>