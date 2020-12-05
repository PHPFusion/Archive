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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."submit_news.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if (isset($_POST['submit_news'])) {
	$sub_name = stripinput($sub_name);
	$sub_email = stripinput($sub_email);
	$subject = stripinput($subject);
	$subject = addslashes($subject);
	$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
	if (isset($_POST['line_breaks'])) {
		$news .= "\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
		if ($extendednews != "") {
			$extendednews .= "\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
		}
		$breaks = "y";
	} else {
		$news .= "\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
		if ($extendednews != "") {
			$extendednews .= "<br><br>\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
		}
		$breaks = "n";
	}
	$news = addslashes($news);
	if ($extendednews != "") {
		$extendednews = addslashes($extendednews);
	}
		
	if ($sub_name != "" && $sub_email != "" && $subject != "" && $news != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_news VALUES ('', '$sub_name', '$sub_email', '$subject', '$news', '$extendednews', '$breaks', '".time()."', '$user_ip')");
		opentable(LAN_200);
		echo "<center><br>
".LAN_210."<br>
".LAN_211."<br><br>
<a href=\"submit_news.php\">".LAN_212."</a><br><br>
<a href=\"index.php\">".LAN_213."</a><br><br>
</center>\n";
		closetable();
	} else {
		opentable(LAN_200);
		echo "<center><br>
".LAN_220."<br>
".LAN_221."<br><br>
<a href=\"submit_news.php\">".LAN_222."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (isset($_POST['preview_news'])) {
		$sub_name = stripinput($sub_name);
		$sub_email = stripinput($sub_email);
		$subject = stripinput($subject);
		$subject = stripslashes($subject);
		$news = stripslashes($news);
		$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
		if ($extendednews != "") {
			$extendednews = stripslashes($extendednews);
		}
		if (isset($_POST['line_breaks'])) {
			$preview_news = $news."\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
			$preview_news = nl2br($preview_news);
			if ($extendednews != "") {
				$preview_extnews = $extendednews."\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
				$preview_extnews = nl2br($preview_extnews);
			}
			$breaks = " checked";
		} else {
			$preview_news = $news."<br><br>\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
			if ($extendednews != "") {
				$preview_extnews = $extendednews."<br><br>\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
			}
			$breaks = "";
		}
		opentable($subject);
		echo $preview_news;
		closetable();
		if ($extendednews != "") {
			tablebreak();
			opentable($subject);
			echo $preview_extnews;
			closetable();
		}
		tablebreak();
	}
	if (!isset($_POST['preview_news'])) { $breaks = " checked"; }
	opentable(LAN_200);
	echo LAN_240."<br><br>
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>".LAN_241."</td>
<td><input type=\"textbox\" name=\"sub_name\" value=\"";
	if (isset($_POST['previewnews'])) { echo "$sub_name"; } else { echo "$userdata[user_name]"; }
	echo "\" maxlength=\"32\" class=\"textbox\" style=\"width:200px;\"></td>
	</tr>
<tr>
<td>".LAN_242."</td>
<td><input type=\"textbox\" name=\"sub_email\" value=\"";
	if (isset($_POST['previewnews'])) { echo "$sub_email"; } else { echo "$userdata[user_email]"; }
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
".LAN_247."<br><br>
<input type=\"checkbox\" name=\"line_breaks\" value=\"yes\"$breaks>".LAN_248."<br></td>
</tr>
<tr>
<td colspan=\"2\"><br><center>
<input type=\"submit\" name=\"preview_news\" value=\"".LAN_249."\" class=\"button\">
<input type=\"submit\" name=\"submit_news\" value=\"".LAN_250."\" class=\"button\"></center>
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