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
require "side_left.php";

echo "<td valign=\"top\" class=\"bodybg\">\n";

opentable("Digital Dominion IRC Chat");
echo "<table align=\"center\">
<tr>
<td>
<applet archive=\"http://www.freejavachat.com/java/cr.zip\" codebase=\"http://www.freejavachat.com/java/\"
name=cr code=\"ConferenceRoom.class\" width=575 height=400> 
<param name=channel value=#digital-dominion> 
<param name=showbuttonpanel value=false>
<param name=bg value=ffffef>
<param name=fg value=000000>
<param name=roomswidth value=0>
<param name=lurk value=true>
<param name=simple value=false>
<param name=restricted value=false>
<param name=showjoins value=true>
<param name=showserverwindow value=true>
<param name=nicklock value=false>
<param name=playsounds value=false>
<param name=onlyshowchat value=false>
<param name=showcolorpanel value=true>
<param name=floatnewwindows value=true>
<param name=timestamp value=false>
<param name=listtime value=0>
<param name=guicolors1 value=\"youColor=245375;operColor=755324;voicecolor=247553;userscolor=000000\">
<param name=guicolors2 value=\"inputcolor=ffffff;inputtextColor=000000;sessioncolor=ddddcd;systemcolor=aa0000\">
<param name=guicolors3 value=\"titleColor=eeeede;titletextColor=000000;sessiontextColor=000000\">
<param name=guicolors4 value=\"joinColor=00aa00;partColor=000000;talkcolor=000000\">
<param name=nick value=\"$userdata[user_name]\"> 
<center>This application requires Java VM suport.<br>
This server also available via IRC at:<br>
<a href=\"irc://irc.ircstorm.net:6667/\">irc://irc.ircstorm.net:6667/</a></center>
</applet></td>
</tr>
</table>\n";
closetable();

echo "</td>\n";

require "side_right.php";
require "footer.php";
?>