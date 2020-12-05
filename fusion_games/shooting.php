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
@require "../fusion_config.php";
require "../header.php";
require "../subheader.php";
require "../side_left.php";

echo "<td valign=\"top\" class=\"bodybg\">\n";

opentable("Shooting");
?>
<div align="center">
<body onLoad="pingu.focus()" bgcolor="#ffffff">
<!--Im Film verwendete URLs-->
<a href="http://www.e-medien.com"></a>
<!--Im Film verwendeter Text-->
<object name="pingu" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="550" height="400" id="shooting" align="center">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="shooting.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="shooting.swf" quality="high" bgcolor="#000" width="550" height="400" name="shooting" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</div>
<?
closetable();

echo "</td>
<td width=\"20\" valign=\"top\" class=\"full-header\">\n";

require "../side_right.php";
require "../footer.php";
?>