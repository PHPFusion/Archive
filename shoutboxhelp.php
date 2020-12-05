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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."shoutboxhelp.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_200);
echo LAN_201."<br><br>

<table align=\"center\">
<tr>
<td width=\"80\">".LAN_202."</td>
<td>:)</td><td>;)</td><td>:|</td><td>:(</td><td>:o</td><td>:p</td><td>b)</td><td>:D</td><td>:@</td>
</tr>
<tr>
<td>".LAN_203."</td>
<td><img src=\"".fusion_basedir."fusion_images/smiley/smile.gif\"></td><td><img src=\"".fusion_basedir."fusion_images/smiley/wink.gif\"></td>
<td><img src=\"".fusion_basedir."fusion_images/smiley/frown.gif\"></td><td><img src=\"".fusion_basedir."fusion_images/smiley/sad.gif\"></td>
<td><img src=\"".fusion_basedir."fusion_images/smiley/shock.gif\"></td><td><img src=\"".fusion_basedir."fusion_images/smiley/pfft.gif\"></td>
<td><img src=\"".fusion_basedir."fusion_images/smiley/cool.gif\"></td><td><img src=\"".fusion_basedir."fusion_images/smiley/grin.gif\"></td>
<td><img src=\"".fusion_basedir."fusion_images/smiley/angry.gif\"></td>
</tr>
</table>

<br>
".LAN_204."\n";
closetable();

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