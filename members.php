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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."members-profile.php";

opentable(LAN_400);
if (iMEMBER) {
	if (!isset($sortby)) $sortby = "all";
	$orderby = ($sortby == "all" ? "" : " WHERE user_name LIKE '$sortby%'");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users".$orderby."");
	$rows = dbrows($result);
	if (!$rowstart) $rowstart = 0;
	if ($rows != 0) {
		echo "<table align='center' width='100%' cellspacing='0'>
<tr>
<td class='tbl2'><span>".LAN_401."</td>
<td align='right' class='tbl2'>".LAN_402."</td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users".$orderby." ORDER BY user_mod DESC, user_name LIMIT $rowstart,20");
		while ($data = dbarray($result)) {
			if ($data['user_id'] != $userdata['user_id']) {
				echo "<td>\n<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
			} else {
				echo "<tr>\n<td>".$data['user_name']."</td>\n";
			}
			echo "<td align='right'>".getmodlevel($data['user_mod'])."</td>\n</tr>";
		}
		echo "</table>\n";
	} else {
		echo "<center><br>\n".LAN_403."$sortby<br><br>\n</center>\n";
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr><table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='$PHP_SELF?sortby=all'>".LAN_404."</a></td>";
	for ($i=0;$search[$i]!="";$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='$PHP_SELF?sortby=".$search[$i]."'>".$search[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='$PHP_SELF?sortby=all'>".LAN_404."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
	echo "</tr>\n</table>\n";
} else {
	echo "<center><br>\n".LAN_02."<br><br>\n</center>\n";
}
closetable();
echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,20,$rows,3,"$PHP_SELF?sortby=$sortby&")."\n</div>\n";

include "side_right.php";
include "footer.php";
?>