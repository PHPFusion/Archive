<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Blank Page");
echo "<span class=\"alt\">What is PHP-Fusion?</span><br>
PHP-Fusion is content management system which provides an easy to install community website. It
includes a variety of integrated features including:<br>
<br>
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

Blank Page
\n";
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>