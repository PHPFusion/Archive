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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."shoutboxhelp.php";
require "side_left.php";

opentable(LAN_400);
echo LAN_401."<br><br>
<table align='center'>
<tr>
<td width='80'>".LAN_402."</td>
<td>:)</td>
<td>;)</td>
<td>:|</td>
<td>:(</td>
<td>:o</td>
<td>:p</td>
<td>b)</td>
<td>:D</td>
<td>:@</td>
</tr>
<tr>
<td>".LAN_403."</td>
<td><img src='".fusion_basedir."fusion_images/smiley/smile.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/wink.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/frown.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/sad.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/shock.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/pfft.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/cool.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/grin.gif'></td>
<td><img src='".fusion_basedir."fusion_images/smiley/angry.gif'></td>
</tr>
</table>
<br>
".LAN_404."\n";
closetable();

require "side_right.php";
require "footer.php";
?>