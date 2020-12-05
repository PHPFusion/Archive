<?
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
render_footer(false);

echo "</body>
</html>\n";

$result = dbquery("DELETE FROM ".$fusion_prefix."temp WHERE temp_time < '".(time()-360)."'");
$result = dbquery("DELETE FROM ".$fusion_prefix."new_users WHERE user_datestamp < '".(time()-86400)."'");

ob_end_flush();
?>