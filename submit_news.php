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
require fusion_langdir."submit_news.php";
require "side_left.php";

if (isset($_POST['submit_news'])) {
	$sub_name = stripinput($sub_name);
	$sub_email = stripinput($sub_email);
	$subject = stripinput($subject);
	$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
	if (isset($_POST['line_breaks'])) {
		$news .= "\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
		if ($extendednews != "") {
			$extendednews .= "\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
		}
		$breaks = "y";
	} else {
		$news .= "\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
		if ($extendednews != "") {
			$extendednews .= "<br /><br />\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
		}
		$breaks = "n";
	}
	$news = encscript($news);
	$news = addslashes($news);
	if ($extendednews != "") {
		$extendednews = encscript($extendednews);
		$extendednews = addslashes($extendednews);
	}
		
	if ($sub_name != "" && $sub_email != "" && $subject != "" && $news != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_news VALUES ('', '$sub_name', '$sub_email', '$subject', '$news', '$extendednews', '$breaks', '".time()."', '$user_ip')");
		opentable(LAN_400);
		echo "<center><br />
".LAN_410."<br />
".LAN_411."<br /><br />
<a href='submit_news.php'>".LAN_412."</a><br /><br />
<a href='index.php'>".LAN_413."</a><br /><br />
</center>\n";
		closetable();
	} else {
		opentable(LAN_400);
		echo "<center><br />
".LAN_420."<br />
".LAN_421."<br /><br />
<a href='submit_news.php'>".LAN_422."</a><br /><br />
</center>\n";
		closetable();
	}
} else {
	if (isset($_POST['preview_news'])) {
		$sub_name = stripinput($sub_name);
		$sub_email = stripinput($sub_email);
		$subject = stripinput($subject);
		$news = encscript($news);
		$news = stripslashes($news);
		$postdate = strftime($settings[longdate], time()+($settings[timeoffset]*3600));
		if ($extendednews != "") {
			$extendednews = encscript($extendednews);
			$extendednews = stripslashes($extendednews);
		}
		if (isset($_POST['line_breaks'])) {
			$preview_news = $news."\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
			$preview_news = nl2br($preview_news);
			if ($extendednews != "") {
				$preview_extnews = $extendednews."\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
				$preview_extnews = nl2br($preview_extnews);
			}
			$breaks = " checked";
		} else {
			$preview_news = $news."<br /><br />\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
			if ($extendednews != "") {
				$preview_extnews = $extendednews."<br /><br />\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
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
	opentable(LAN_400);
	echo LAN_440."<br /><br />
<form name='userform' method='post' action='$PHP_SELF'>
<table align='center' cellspacing='0' cellpadding='0' class='body'>
<tr>
<td>".LAN_441."</td>
<td><input type='text' name='sub_name' value='";
	if (isset($_POST['previewnews'])) { echo "$sub_name"; } else { echo "$userdata[user_name]"; }
	echo "' maxlength='32' class='textbox' style='width:200px;'></td>
	</tr>
<tr>
<td>".LAN_442."</td>
<td><input type='text' name='sub_email' value='";
	if (isset($_POST['previewnews'])) { echo "$sub_email"; } else { echo "$userdata[user_email]"; }
	echo "' maxlength='64' class='textbox' style='width:200px;'></td>
</tr>
<tr><td>".LAN_443."</td>
<td><input type='text' name='subject' value='$subject' maxlength='64' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_444."</td>
<td><textarea class='textbox' name='news' rows='5' style='width:300px;'>$news</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_445."<br /><span class='small'>".LAN_446."</span></td>
<td><textarea class='textbox' name='extendednews' rows='8' style='width:300px;'>$extendednews</textarea><br /><br />
".LAN_447."<br /><br />
<input type='checkbox' name='line_breaks' value='yes'$breaks>".LAN_448."<br /></td>
</tr>
<tr>
<td colspan='2'><br /><center>
<input type='submit' name='preview_news' value='".LAN_449."' class='button'>
<input type='submit' name='submit_news' value='".LAN_450."' class='button'></center>
</td>
</tr>
</table>
</form>\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>