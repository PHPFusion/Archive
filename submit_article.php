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
require fusion_langdir."submit_article.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if (isset($_POST['submit_article'])) {
	$sub_name = stripinput($sub_name);
	$sub_email = stripinput($sub_email);
	$subject = stripinput($subject);
	$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
	$description = addslashes($description);
	if (isset($_POST['line_breaks'])) {
		$article .= "\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
		$breaks = "y";
	} else {
		$article .= "<br><br>\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
		$breaks = "n";
	}
	$article = addslashes($article);
	if ($sub_name != "" && $sub_email != "" && $subject != "" && $article != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_articles VALUES ('', '$sub_name', '$sub_email', '$sub_cat', '$subject', '$description', '$article', '$breaks', '".time()."', '$user_ip')");
		opentable(LAN_200);
		echo "<center><br>
".LAN_210."<br>
".LAN_211."<br><br>
<a href=\"submit_article.php\">".LAN_212."</a><br><br>
<a href=\"index.php\">".LAN_213."</a><br><br>
</center>\n";
		closetable();
	} else {
		opentable(LAN_200);
		echo "<center><br>
".LAN_220."<br>
".LAN_221."<br><br>
<a href=\"submit_article.php\">".LAN_222."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (isset($_POST['preview_article'])) {
		$sub_name = stripinput($sub_name);
		$sub_email = stripinput($sub_email);
		$subject = stripinput($subject);
		$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
		$description = stripslashes($description);
		$article = stripslashes($article);
		if (isset($_POST['line_breaks'])) {
			$preview_article = $article."\n\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
			$preview_article = nl2br($preview_article);
			$breaks = " checked";
		} else {
			$preview_article = $article."<br><br>\n<span class=\"small\">".LAN_230."$sub_name".LAN_231."$postdate</small>";
			$breaks = "";
		}
		opentable($subject);
		echo $description;
		closetable();
		tablebreak();
		opentable($subject);
		echo $preview_article;
		closetable();
		tablebreak();
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($_POST['preview_article'])) {
				if ($sub_cat == $data[article_cat_id]) { $sel = " selected"; } else { $sel = ""; }
			}
			$catlist .= "<option value=\"$data[article_cat_id]\"$sel>".stripslashes($data[article_cat_name])."</option>\n";
		}
	}
	opentable(LAN_200);
	echo LAN_240."<br><br>
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>".LAN_241."</td>
<td><input type=\"textbox\" name=\"sub_name\" value=\"";
	if (isset($_POST['preview_article'])) { echo "$sub_name"; } else { echo "$userdata[user_name]"; }
	echo "\" maxlength=\"32\" class=\"textbox\" style=\"width:200px;\"></td>
	</tr>
<tr>
<td>".LAN_242."</td>
<td><input type=\"textbox\" name=\"sub_email\" value=\"";
	if (isset($_POST['preview_article'])) { echo "$sub_email"; } else { echo "$userdata[user_email]"; }
	echo "\" maxlength=\"64\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"100\">".LAN_243."</td>
<td><select name=\"sub_cat\" class=\"textbox\" style=\"width:200px;\">
$catlist</select></td>
</tr>
<tr>
<td>".LAN_244."</td>
<td><input type=\"textbox\" name=\"subject\" value=\"$subject\" maxlength=\"64\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td valign=\"top\">".LAN_245."</td>
<td><textarea class=\"textbox\" name=\"description\" rows=\"5\" style=\"width:300px;\">$description</textarea></td>
</tr>
<tr>
<td valign=\"top\">".LAN_246."</td>
<td><textarea class=\"textbox\" name=\"article\" rows=\"8\" style=\"width:300px;\">$article</textarea><br><br>
".LAN_247."<br><br>
<input type=\"checkbox\" name=\"line_breaks\" value=\"yes\"$breaks>".LAN_248."<br></td>
</tr>
<tr>
<td colspan=\"2\"><br><center>
<input type=\"submit\" name=\"preview_article\" value=\"".LAN_249."\" class=\"button\">
<input type=\"submit\" name=\"submit_article\" value=\"".LAN_250."\" class=\"button\"></center>
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