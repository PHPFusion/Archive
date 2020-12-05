<?
echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"padding: 0px 10px 10px 10px;\">
<tr><td>\n";
opentable("Copyright & Disclaimer");
echo "$settings[footer]\n";
closetable();
tablebreak();
echo "<div align=\"center\" class=\"small\">Powered by PHP-Fusion™ v1.33<br>
Copyright © 2003 <a href=\"http://www.digitaldominion.co.uk/\">Digital Dominion</a>,
all rights reserved.<br><br>\n";
if ($settings[counter] == 1) {
	echo "1 Unique Visit\n";
} else {
	echo "$settings[counter] Unique Visits\n";
}
echo "</div>
</td><tr>
</table>

</td><tr>
</table>

</body>
</html>\n";
?>