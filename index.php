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
require "header.php";
require "subheader.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($rowstart < 1) {
	if ($settings[siteintro] != "") {
		opentable(LAN_30);
		echo stripslashes($settings[siteintro])."\n";
		closetable();
		tablebreak();
	}
	if ($settings[forumpanels] == "H" || $settings[forumpanels] == "B") {
		require "forumlist.php";
	}
}
require "news.php";

echo "</td>
<td width=\"170\" valign=\"top\" class=\"srborder\">\n";
require "userinfo.php";
require "poll.php";
require "shoutbox.php";
echo "</td>
</tr>
</table>\n";

require "footer.php";
?>