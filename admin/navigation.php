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
openside(LAN_01);
if ($userdata[user_name] != "") {
	echo "· <a href=\"index.php\" class=\"slink\">".LAN_130."</a><br>
<hr class=\"shr\">
· <a href=\"../index.php\" class=\"slink\">".LAN_131."</a>";
}
closeside();
?>
