<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Shoutbox Help");
echo "The $settings[sitename] Shoubox allows visitors and members to post short messages. HTML is stripped
since poorly formatted code could cause the site layout to break. The maximum length messsage allowed is
255 characters, anything exceeding the maximum will be chopped. Please <b>do not</b>:<br>
<br>
&middot;&nbsp;<span class=\"alt2\">Flame, Abuse or Attack other visitors or Members</span><br>
&middot;&nbsp;<span class=\"alt2\">Flood or Spam by posting multiple messages</span><br>
&middot;&nbsp;<span class=\"alt2\">Use unnacceptable or abusive language</span><br>
<br>
You may advertise your website, or any other website as long is it does not contain any explicit or copyright
infringing material. You must provide both a name and message or your post will be rejected. The following
smileys are also available to add emotions to your post:<br><br>
<table align=\"center\">
<tr><td width=\"80\">Code</td><td>:)</td><td>;)</td><td>:|</td><td>:(</td><td>:o</td><td>:p</td><td>(c)</td>
<td>(l)</td><td>(y)</td><td>(n)</td></tr>
<tr><td>Smiley</td><td><img src=\"themes/$settings[theme]/images/smileys/smile.gif\"></td><td><img src=\"themes/$settings[theme]/images/smileys/wink.gif\"></td>
<td><img src=\"themes/$settings[theme]/images/smileys/none.gif\"></td><td><img src=\"themes/$settings[theme]/images/smileys/sad.gif\"></td>
<td><img src=\"themes/$settings[theme]/images/smileys/eek.gif\"></td><td><img src=\"themes/$settings[theme]/images/smileys/pfft.gif\"></td>
<td><img src=\"themes/$settings[theme]/images/smileys/cool.gif\"></td><td><img src=\"themes/$settings[theme]/images/smileys/laugh.gif\"></td>
<td><img src=\"themes/$settings[theme]/images/smileys/yes.gif\"></td><td><img src=\"themes/$settings[theme]/images/smileys/no.gif\"></td></tr>
</table>
<br>
$settings[sitename] reserves the right to remove any posts which infringe the afformentioned rules. Any
questions should be mailed to <a href=\"mailto:$settings[siteemail]\">$settings[siteemail]</a>.\n";
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>