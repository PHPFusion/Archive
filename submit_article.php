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
require fusion_langdir."submit_article.php";
require "side_left.php";

if (isset($_POST['submit_article'])) {
	$sub_name = stripinput($_POST['sub_name']);
	$sub_email = stripinput($_POST['sub_email']);
	$subject = stripinput($_POST['subject']);
	$postdate = strftime($settings['longdate'], time()+($settings['timeoffset']*3600));
	$description = encscript($_POST['description']);
	$description = addslashes($description);
	if (isset($_POST['line_breaks'])) {
		$article .= "\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
		$breaks = "y";
	} else {
		$article .= "<br><br>\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
		$breaks = "n";
	}
	$article = encscript($article);
	$article = addslashes($article);
	if ($sub_name != "" && $sub_email != "" && $subject != "" && $article != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."submitted_articles VALUES ('', '$sub_name', '$sub_email', '$sub_cat', '$subject', '$description', '$article', '$breaks', '".time()."', '$user_ip')");
		opentable(LAN_400);
		echo "<center><br />
".LAN_410."<br />
".LAN_411."<br /><br />
<a href='submit_article.php'>".LAN_412."</a><br /><br />
<a href='index.php'>".LAN_413."</a><br /><br />
</center>\n";
		closetable();
	} else {
		opentable(LAN_400);
		echo "<center><br />
".LAN_420."<br />
".LAN_421."<br /><br />
<a href='submit_article.php'>".LAN_422."</a><br /><br />
</center>\n";
		closetable();
	}
} else {
	if (isset($_POST['preview_article'])) {
		$sub_name = stripinput($_POST['sub_name']);
		$sub_email = stripinput($_POST['sub_email']);
		$subject = stripinput($_POST['subject']);
		$postdate = strftime($settings['longdate'], time()+($settings['timeoffset']*3600));
		$description = encscript($_POST['description']);
		$description = stripslashes($description);
		$article = encscript($article);
		$article = stripslashes($article);
		if (isset($_POST['line_breaks'])) {
			$preview_article = $article."\n\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
			$preview_article = nl2br($preview_article);
			$breaks = " checked";
		} else {
			$preview_article = $article."<br /><br />\n<span class='small'>".LAN_430."$sub_name".LAN_431."$postdate</span>";
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
				if ($sub_cat == $data['article_cat_id']) { $sel = " selected"; } else { $sel = ""; }
			}
			$catlist .= "<option value='$data[article_cat_id]'$sel>".$data['article_cat_name']."</option>\n";
		}
	}
	opentable(LAN_400);
	echo LAN_440."<br /><br />
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<form name='userform' method='post' action='$PHP_SELF'>
<tr>
<td>".LAN_441."</td>
<td><input type='text' name='sub_name' value='";
	if (isset($_POST['preview_article'])) { echo "$sub_name"; } else { echo $userdata['user_name']; }
	echo "' maxlength='32' class='textbox' style='width:200px;'></td>
	</tr>
<tr>
<td>".LAN_442."</td>
<td><input type='text' name='sub_email' value='";
	if (isset($_POST['preview_article'])) { echo "$sub_email"; } else { echo $userdata['user_email']; }
	echo "' maxlength='64' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='100'>".LAN_443."</td>
<td><select name='sub_cat' class='textbox' style='width:200px;'>
$catlist</select></td>
</tr>
<tr>
<td>".LAN_444."</td>
<td><input type='text' name='subject' value='$subject' maxlength='64' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_445."</td>
<td><textarea class='textbox' name='description' rows='5' style='width:300px;'>$description</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_446."</td>
<td><textarea class='textbox' name='article' rows='8' style='width:300px;'>$article</textarea><br /><br />
".LAN_447."<br /><br />
<input type='checkbox' name='line_breaks' value='yes'$breaks>".LAN_448."<br /></td>
</tr>
<tr>
<td colspan='2'><br /><center>
<input type='submit' name='preview_article' value='".LAN_449."' class='button'>
<input type='submit' name='submit_article' value='".LAN_450."' class='button'></center>
</td>
</tr>
</table>
</form>\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>