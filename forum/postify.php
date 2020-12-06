<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/post.php";

if (!FUSION_QUERY || !isset($forum_id) || !isNum($forum_id)) fallback("index.php");
if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");

if ($error == 0) { $error = ""; }
else if ($error == 1) { $error = $locale['440']; }
else if ($error == 2) { $error = $locale['441']; }
else if ($error == 3) { $error = $locale['450']; }

if ($post == "new") {
	opentable($locale['401']);
	echo "<div align='center'>\n";
	if ($error) {
		echo "<br>$error<br><br>\n";
	} else {
		echo "<br>".$locale['442']."<br><br>\n";
	}
	echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id'>".$locale['447']."</a> |
<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br></div>\n";
	closetable();
} else if ($post == "reply") {
	opentable($locale['403']);
	echo "<center><br>\n";
	if ($error) {
		echo "$error<br><br>\n";
	} else {
		echo $locale['443']."<br><br>\n";
	}
	$rstart = ($rstart > 0 ? "&rowstart=".$rstart : "");
	echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id".$rstart."#".$newpost_id."'>".$locale['447']."</a> |
<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>
</center>\n";
	closetable();
} else if ($post == "edit") {
	opentable($locale['409']);
	echo "<center><br>\n";
	if ($error) {
		echo "$error<br><br>\n";
	} else {
		echo $locale['446']."<br><br>\n";
	}
	$rstart = ($rstart > 0 ? "&rowstart=".$rstart : "");
	echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id".$rstart."#".$post_id."'>".$locale['447']."</a> |
<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>
</center>\n";
		closetable();
}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>