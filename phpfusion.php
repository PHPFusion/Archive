<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("PHP-Fusion™ Overview");
echo "PHP-Fusion™ is an All-in-One Community Website Package. It provides an easy to install PHP/Mysql powered
website, packed with every feature you would expect to have. It requires PHP 4.1.2 (or later), MySQL,
sufficient webspace, and FTP access (for upload/installation).

<p><span class=\"alt\">Key Features:</span><br>
· Easy to use Administration Panel.<br>
· Membership orientated web community.<br>
· Simple News and Article publishing system.<br>
· Comments facility for both News & Articles.<br>
· Member Polls.<br>
· Shoutbox.<br>
· Customisable site navigation panel.<br>
· Customisable Web Links Page.<br>
· Private Message facility.<br>
· Members List.<br>
· Integrated threaded Discussion Forums.<br>
· Recent Threads list shows 5 most recently posted/updated Forum Threads.<br>
· Hottest Threads list shows 5 most popular Forum Threads.<br>
· Latest Articles 5 most recently posted/updated Articles.<br>
· Users Online displays the number of visitors/members active in the last 5 minutes.<br>
· Hit counter tracks the number of unique web visitors.<br>
· Ships with a variety of simple themes, new themes will be made available.<br>
· Weblink and News submission feature for visitors & members.

<p>As you can see, PHP-Fusion™ offers an abundance of features. I have made every effort to ensure that
everything works, however, with anything new problems can crop up, if you encounter any glitches
or problems please report it in the support forum. Please allow sufficient time for a response.

<p>PHP-Fusion™ is free to use for non-profit and charity organisations, you may customise it to any degree
you wish. However, all I request is that you retain the bottom most foot notice. PHP-Fusion™ is copyright
material, You are not permitted to redistribute it in any way, shape or form for profit or for free.

<p><a href=\"downloads.php?catid=38\">PHP-Fusion™ v1.32</a> is available
for Download Now.<br>
View the <a href=\"updatelog.txt\">PHP-Fusion™ Update Log</a>.\n";
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>