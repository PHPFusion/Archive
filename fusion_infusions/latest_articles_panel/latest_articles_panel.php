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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

openside(LAN_23);
$result = dbquery("SELECT * FROM ".$fusion_prefix."articles ORDER BY article_datestamp DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['article_subject'], 23);
		echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."readarticle.php?article_id=".$data['article_id']."' title='".$data['article_subject']."' class='side'>$itemsubject</a><br>\n";
	}
} else {
	echo "<center>".LAN_04."</center>\n";
}
closeside();
?>