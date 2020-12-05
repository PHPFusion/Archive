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
require "header.php";
require "subheader.php";
require fusion_langdir."submitnews.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if (isset($_POST['submitnews'])) {
	$subname = stripinput($subname);
	$subemail = stripinput($subemail);
	$subject = stripinput($subject);
	$subject = addslashes($subject);
	$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
	$news .= "\n\n<span class=\"small\">".LAN_230."$subname".LAN_231."$postdate</small>";
	$news = addslashes($news);
	if ($extendednews != "") {
		$extendednews .= "\n\n<span class=\"small\">".LAN_230."$subname".LAN_231."$postdate</small>";
		$extendednews = addslashes($extendednews);
	}
	if ($subname != "" && $subemail != "" && $subject != "" && $news != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_news VALUES ('', '$subname', '$subemail', '$subject', '$news', '$extendednews', '".time()."', '$userip')");
		opentable(LAN_200);
		echo "<center><br>
".LAN_210."<br>
".LAN_211."<br><br>
<a href=\"submitnews.php\">".LAN_212."</a><br><br>
<a href=\"index.php\">".LAN_213."</a><br><br>
</center>\n";
		closetable();
	} else {
		opentable(LAN_200);
		echo "<center><br>
".LAN_220."<br>
".LAN_221."<br><br>
<a href=\"submitnews.php\">".LAN_222."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (isset($_POST['previewnews'])) {
		$subname = stripinput($subname);
		$subemail = stripinput($subemail);
		$subject = stripinput($subject);
		$subject = stripslashes($subject);
		$news = stripslashes($news);
		$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
		$prevnews = $news."\n\n<span class=\"small\">".LAN_230."$subname".LAN_231."$postdate</small>";
		$prevnews = nl2br($prevnews);
		if ($extendednews != "") {
			$extendednews = stripslashes($extendednews);
			$prevextnews = $extendednews."\n\n<span class=\"small\">".LAN_230."$subname".LAN_231."$postdate</small>";
			$prevextnews = nl2br($prevextnews);
		}
		opentable($subject);
		echo $prevnews;
		closetable();
		if ($extendednews != "") {
			tablebreak();
			opentable($subject);
			echo $prevextnews;
			closetable();
		}
		tablebreak();
	}
	opentable(LAN_200);
	echo LAN_240."<br><br>
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>".LAN_241."</td>
<td><input type=\"textbox\" name=\"subname\" value=\"";
	if (isset($_POST['previewnews'])) { echo "$subname"; } else { echo "$userdata[user_name]"; }
	echo "\" maxlength=\"32\" class=\"textbox\" style=\"width:200px;\"></td>
	</tr>
<tr>
<td>".LAN_242."</td>
<td><input type=\"textbox\" name=\"subemail\" value=\"";
	if (isset($_POST['previewnews'])) { echo "$subemail"; } else { echo "$userdata[user_email]"; }
	echo "\" maxlength=\"64\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr><td>".LAN_243."</td>
<td><input type=\"textbox\" name=\"subject\" value=\"$subject\" maxlength=\"64\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td valign=\"top\">".LAN_244."</td>
<td><textarea class=\"textbox\" name=\"news\" rows=\"5\" style=\"width:300px;\">$news</textarea></td>
</tr>
<tr>
<td valign=\"top\">".LAN_245."<br><span class=\"small\">".LAN_246."</span></td>
<td><textarea class=\"textbox\" name=\"extendednews\" rows=\"8\" style=\"width:300px;\">$extendednews</textarea><br><br>
".LAN_247."<br>
".LAN_248."</td>
</tr>
<tr>
<td colspan=\"2\"><br><center>
<input type=\"submit\" name=\"previewnews\" value=\"".LAN_249."\" class=\"button\" style=\"width:100px;\">
<input type=\"submit\" name=\"submitnews\" value=\"".LAN_250."\" class=\"button\" style=\"width:100px;\"></center>
</td>
</tr>
</form>
</table>\n";
	closetable();
}

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