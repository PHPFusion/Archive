<?
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."submit.php";

if (!iMEMBER) { header("Location:index.php"); exit; }

echo "<script language='JavaScript'>
function validateLink(frm) {
	if (frm.link_name.value==\"\" || frm.link_name.value==\"\" || frm.link_description.value==\"\") {
		alert(\"".LAN_550."\"); return false;
	}
}
function validateNews(frm) {
	if (frm.news_subject.value==\"\" || frm.news_body.value==\"\") {
		alert(\"".LAN_550."\"); return false;
	}
}
function validateArticle(frm) {
	if (frm.article_subject.value==\"\" || frm.article_description.value==\"\" || frm.article_body.value==\"\") {
		alert(\"".LAN_550."\");
		return false;
	}
}
</script>\n";

if ($stype == "l") {
	if (isset($_POST['submit_link'])) {
		if ($_POST['link_name'] != "" && $_POST['link_url'] != "" && $_POST['link_description'] != "") {
			$submit_info['link_category'] = stripinput($_POST['link_category']);
			$submit_info['link_name'] = stripinput($_POST['link_name']);
			$submit_info['link_url'] = stripinput($_POST['link_url']);
			$submit_info['link_description'] = stripinput($_POST['link_description']);
			$sql = dbquery("INSERT INTO ".$fusion_prefix."submissions VALUES('', 'l', '".$userdata['user_id']."', '".time()."', '".serialize($submit_info)."')");
			opentable(LAN_400);
			echo "<center><br>\n".LAN_410."<br><br>
<a href='submit.php?stype=l'>".LAN_411."</a><br><br>
<a href='index.php'>".LAN_412."</a><br><br>\n</center>\n";
			closetable();
		}
	} else {
		opentable(LAN_400);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result)) {
			while ($data = dbarray($result)) $opts .= "<option>".$data['weblink_cat_name']."</option>\n";
			echo LAN_420."<br><br>
<form name='submit_form' method='post' action='$PHP_SELF?stype=l' onSubmit='return validateLink(this);'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_421."</td>
<td><select name='link_category' class='textbox'>
$opts</select></td>
</tr>
<tr>
<td>".LAN_422."</td>
<td><input type='text' name='link_name' maxlength='100' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td>".LAN_423."</td>
<td><input type='text' name='link_url' value='http://' maxlength='200' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td>".LAN_424."</td>
<td><input type='text' name='link_description' maxlength='200' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='submit_link' value='".LAN_425."' class='button'>
</td>
</tr>
</table>
</form>\n";
		} else {
			echo "<center><br>\n".LAN_551."<br><br>\n</center>\n";
		}
		closetable();
	}
} elseif ($stype == "n") {
	if (isset($_POST['submit_news'])) {
		if ($_POST['news_subject'] != "" && $_POST['news_body'] != "") {
			$submit_info['news_subject'] = stripinput($_POST['news_subject']);
			$submit_info['news_body'] = descript($_POST['news_body']);
			$submit_info['news_breaks'] = (isset($_POST['line_breaks']) ? "y" : "n");
			$sql = dbquery("INSERT INTO ".$fusion_prefix."submissions VALUES('', 'n', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			opentable(LAN_450);
			echo "<center><br>\n".LAN_460."<br><br>
<a href='submit.php?stype=n'>".LAN_461."</a><br><br>
<a href='index.php'>".LAN_412."</a><br><br>\n</center>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview_news'])) {
			$news_subject = stripinput($_POST['news_subject']);
			$news_body = descript(stripslashes($_POST['news_body']));
			$breaks = (isset($_POST['line_breaks']) ? " checked" : "");
			opentable($news_subject);
			echo (isset($_POST['line_breaks']) ? nl2br($news_body) : $news_body);
			closetable();
			tablebreak();
		}
		if (!isset($_POST['preview_news'])) $breaks = " checked";
		opentable(LAN_450);
		echo LAN_470."<br><br>
<form name='submit_form' method='post' action='$PHP_SELF?stype=n' onSubmit='return validateNews(this);'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_471."</td>
<td><input type='text' name='news_subject' value='$news_subject' maxlength='64' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_472."</td>
<td><textarea class='textbox' name='news_body' rows='8' style='width:300px;'>$news_body</textarea></td>
</tr>
<tr>
<td colspan='2'><br><center>
<input type='checkbox' name='line_breaks' value='yes'$breaks>".LAN_473."<br><br>
<input type='submit' name='preview_news' value='".LAN_474."' class='button'>
<input type='submit' name='submit_news' value='".LAN_475."' class='button'></center>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
} elseif ($stype == "a") {
	if (isset($_POST['submit_article'])) {
		if ($_POST['article_subject'] != "" && $_POST['article_body'] != "") {
			$submit_info['article_category'] = $_POST['article_category'];
			$submit_info['article_subject'] = stripinput($_POST['article_subject']);
			$submit_info['article_description'] = descript($_POST['article_description']);
			$submit_info['article_body'] = descript($_POST['article_body']);
			$submit_info['article_breaks'] = (isset($_POST['line_breaks']) ? "y" : "n");
			$sql = dbquery("INSERT INTO ".$fusion_prefix."submissions VALUES('', 'a', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			opentable(LAN_500);
			echo "<center><br>\n".LAN_510."<br><br>
<a href='submit.php?stype=a'>".LAN_511."</a><br><br>
<a href='index.php'>".LAN_412."</a><br><br>\n</center>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview_article'])) {
			$article_subject = stripinput($_POST['article_subject']);
			$article_description = descript(stripslashes($_POST['article_description']));
			$article_body = descript(stripslashes($_POST['article_body']));
			$breaks = (isset($_POST['line_breaks']) ? " checked" : "");
			opentable($article_subject);
			echo (isset($_POST['line_breaks']) ? nl2br($article_body) : $article_body);
			closetable();
			tablebreak();
		}
		opentable(LAN_500);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name DESC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				if (isset($_POST['preview_article'])) $sel = ($article_category == $data['article_cat_id'] ? " selected" : "");
				$cat_list .= "<option value='".$data['article_cat_id']."'$sel>".$data['article_cat_name']."</option>\n";
			}
			if (!isset($_POST['preview_article'])) $breaks = " checked";
			echo LAN_520."<br><br>
<form name='submit_form' method='post' action='$PHP_SELF?stype=a' onSubmit='return validateArticle(this);'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='100'>".LAN_521."</td>
<td><select name='article_category' class='textbox'>
$cat_list</select></td>
</tr>
<tr>
<td>".LAN_522."</td>
<td><input type='text' name='article_subject' value='$article_subject' maxlength='64' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_523."</td>
<td><textarea class='textbox' name='article_description' rows='3' style='width:300px;'>$article_description</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_524."</td>
<td><textarea class='textbox' name='article_body' rows='8' style='width:300px;'>$article_body</textarea></td>
</tr>
<tr>
<td colspan='2'><br><center>
<input type='checkbox' name='line_breaks' value='yes'$breaks>".LAN_525."<br><br>
<input type='submit' name='preview_article' value='".LAN_526."' class='button'>
<input type='submit' name='submit_article' value='".LAN_527."' class='button'></center>
</td>
</tr>
</table>
</form>\n";
		} else {
			echo "<center><br>\n".LAN_551."<br><br>\n</center>\n";
		}
		closetable();
	}
} else {
	header("Location:index.php");
}

include "side_right.php";
include "footer.php";
?>