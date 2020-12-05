<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
echo "</td><td valign=\"top\">\n";
opentable("PHP-Fusion™ Admin");
echo "Welcome to the PHP-Fusion™ Admin Panel. This panel is used to tailor your
website to suit your requirements by providing an array of easy to understand pages. You
can access this area only when you are logged in, so nobody other than you can access this
area.<br><br>

You should complete the fields on the Site Settings page. Once you have done that you can
begin to add the content, a poll and set up the forums. If you run into difficulty or
encounter a problem please email me at <a href=\"mailto:digitanium@digitaldominion.co.uk\">
digitanium@digitaldominion.co.uk</a> OR visit the official PHP-Fusion™ Forum over at
<a href=\"http://www.digitaldominion.co.uk\">www.digitaldominion.co.uk</a>.\n";
closetable();
echo "</td></tr>
</table>\n";

require "../footer.php";
?>