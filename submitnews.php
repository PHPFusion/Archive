<?
require "header.php";
require "subheaderjs.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if (isset($_POST['submitnews'])) {
	$subname = stripinput($subname);
	$subemail = stripinput($subemail);
	$subject = stripinput($subject);
	$subject = addslashes($subject);
	$postdate = gmdate("F d Y", $servertime);
	$posttime = gmdate("H:i", $servertime);
	$news .= "\n\n<span class=\"small\">Submitted by $subname on $postdate at $posttime</small>";
	$news = addslashes($news);
	if ($extendednews != "") {
		$extendednews .= "\n\n<span class=\"small\">Submitted by $subname on $postdate at $posttime</small>";
		$extendednews = addslashes($extendednews);
	}
	if ($subname != "" && $subemail != "" && $subject != "" && $news != "") {
		$result = dbquery("INSERT INTO newssubmits VALUES ('', '$subname', '$subemail', '$subject', '$news', '$extendednews', '$servertime', '$userip')");
		opentable("Submit News");
		echo "<br><center>Your News has been submitted and will verified shortly<br><br>
<a href=\"submitnews.php\">Submit more News</a><br><br>
<a href=\"index.php\">Return to $settings[sitename] Home</a><br><br></center>\n";
		closetable();
	} else {
		opentable("Submit News");
		echo "<br><center>You did not complete the form, please try again.<br><br></center>\n";
		closetable();
	}
} else {
	if (isset($_POST['previewnews'])) {
		$subname = stripinput($subname);
		$subemail = stripinput($subemail);
		$subject = stripinput($subject);
		$subject = stripslashes($subject);
		$news = stripslashes($news);
		$postdate = gmdate("F d Y", $servertime);
		$posttime = gmdate("H:i", $servertime);
		$prevnews = $news."\n\n<span class=\"small\">Submitted by $subname on $postdate at $posttime</small>";
		$prevnews = nl2br($prevnews);
		if ($extendednews != "") {
			$extendednews = stripslashes($extendednews);
			$prevextnews = $extendednews."\n\n<span class=\"small\">Submitted by $subname on $postdate at $posttime</small>";
			$prevextnews = nl2br($prevextnews);
		}
		opentablex();
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject</td></tr>
<tr><td class=\"newsbody\">$prevnews</td></tr>
</table>\n";
		closetablex();
		if ($extendednews != "") {
			tablebreak();
			opentablex();
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject</td></tr>
<tr><td class=\"newsbody\">$prevextnews</td></tr>
</table>\n";
			closetablex();
		}
		tablebreak();
	}
	opentable("Submit News");
	echo "Use the following form to submit News. Your submission will be reviewed by an
Administrator. $settings[sitename] reserves the right to ammend or edit any submission. News
should be applicable to the content of this site. Unsuitable submissions will be deleted.<br><br>
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr><td>Your Name:</td>
<td><input type=\"textbox\" name=\"subname\" value=\"";
	if (isset($_POST['previewnews'])) { echo "$subname"; } else { echo "$userdata[username]"; }
	echo "\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Your Email:</td>
<td><input type=\"textbox\" name=\"subemail\" value=\"";
	if (isset($_POST['previewnews'])) { echo "$subemail"; } else { echo "$userdata[email]"; }
	echo "\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td>Subject:</td>
<td><input type=\"textbox\" name=\"subject\" value=\"$subject\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td valign=\"top\">News:</td>
<td><textarea class=\"textbox\" name=\"news\" rows=\"5\" style=\"width: 300px;\">$news</textarea></td></tr>
<tr><td valign=\"top\">Extended News:<br><span class=\"small\">(optional)</span></td>
<td><textarea class=\"textbox\" name=\"extendednews\" rows=\"8\" style=\"width: 300px;\">$extendednews</textarea><br><br>
HTML is allowed in both News fields.<br>
Line Breaks are added automatically.</td></tr>
<tr><td colspan=\"2\"><br><center>
<input type=\"submit\" name=\"previewnews\" value=\"Preview News\" class=\"button\" style=\"width: 100px;\">
<input type=\"submit\" name=\"submitnews\" value=\"Submit News\" class=\"button\" style=\"width: 100px;\"></center>
</td></tr>
</form>
</table>\n";
	closetable();
}
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>