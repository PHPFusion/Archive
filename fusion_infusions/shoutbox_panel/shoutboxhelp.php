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
@require_once "../../fusion_config.php";
require_once "../../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_BASE."side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."shoutboxhelp.php";

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
<td><img src='".FUSION_IMAGES."smiley/smile.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/wink.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/frown.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/sad.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/shock.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/pfft.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/cool.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/grin.gif'></td>
<td><img src='".FUSION_IMAGES."smiley/angry.gif'></td>
</tr>
</table>
<br>
".LAN_404."\n";
closetable();

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>