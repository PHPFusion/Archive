<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."custom_pages.php";

require fusion_basedir."navigation.php";
require fusion_basedir."sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	opentable($data[page_title]);
	require fusion_basedir."fusion_pages/".$data[page_id];
} else {
	opentable(LAN_200);
	echo "<center><br />
".LAN_201."
<br /><br /></center>\n";
}
closetable();

echo "</td>
<td width=\"170\" valign=\"top\" class=\"srborder\">\n";
require fusion_basedir."userinfo.php";
require fusion_basedir."poll.php";
require fusion_basedir."shoutbox.php";
echo "</td>
</tr>
</table>\n";

require fusion_basedir."footer.php";
?>