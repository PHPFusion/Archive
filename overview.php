<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Digital Dominion Overview");
echo "Digital Dominion is the official PHP-Fusion™ development site and homepage of Nick Jones. This
site is also a community where PC users can air their views, share knowledge or request help
with any PC related problem. You can also keep up to date on what's new such as drivers,
games, software and hardware. I aim to provide regular news on the more interesting and
innovative side of the PC world.

<p>By becoming a member of Digital Dominion you will have access to lots of extra features.
You will be able to vote on regular polls, post and reply to forum threads, and also
have access to the new private message system.

<p>What else is there? Soon I will be starting a series of tutorials for those wanting
to learn more about advanced HTML and PHP. My first tutorial is due very soon and will
help you to setup your development server on your PC. After that you can expect to see
regular tutorials ranging from basic to advanced levels of difficulty.

<p>I'm also keen to get feedback from our visitors, if you have any constructive comments,
suggestions or comments, please mail me at <a href=\"mailto:admin@digitaldominion.co.uk\">
admin@digitaldominion.co.uk</a>. I look forward to hearing from you.\n";
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>