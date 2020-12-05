<?php
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
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_ADMIN."navigation.php";

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_submissions.php";

if (!checkrights("M")) { header("Location:../index.php"); exit; }

if (!isset($stage)) $stage = "";
$links = ""; $news = ""; $articles = "";

if ($stage == "" || $stage == "1") {
	if (isset($delete)) {
		opentable(LAN_400);
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$delete'");
		echo "<br><div align='center'>".LAN_401."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."submissions WHERE submit_type='l' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$links .= "<tr><td>".$submit_criteria['link_name']."</td>
<td align='right' width='100'><span class='small'><a href='".FUSION_SELF."?stage=2&t=l&submit_id=".$data['submit_id']."'>".LAN_417."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".LAN_418."</a></span></td></tr>";
			}
		} else {
			$links = "<tr><td colspan='2'>".LAN_414."</td></tr>";
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."submissions WHERE submit_type='n' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$news .= "<tr><td>".$submit_criteria['news_subject']."</td>
<td align='right' width='100'><span class='small'><a href='".FUSION_SELF."?stage=2&t=n&submit_id=".$data['submit_id']."'>".LAN_417."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".LAN_418."</a></span></td></tr>";
			}
		} else {
			$news = "<tr><td colspan='2'>".LAN_415."</td></tr>";
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."submissions WHERE submit_type='a' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$articles .= "<tr><td>".$submit_criteria['article_subject']."</td>
<td align='right' width='100'><span class='small'><a href='".FUSION_SELF."?stage=2&t=a&submit_id=".$data['submit_id']."'>".LAN_417."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".LAN_418."</a></span></td></tr>";
			}
		} else {
			$articles = "<tr><td colspan='2'>".LAN_416."</td></tr>";
		}
		opentable(LAN_410);
		echo "<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>
<tr><td colspan='2' class='tbl2'>".LAN_411."</td></tr>
$links
<tr><td colspan='2' class='tbl2'>".LAN_412."</td></tr>
$news
<tr><td colspan='2' class='tbl2'>".LAN_413."</td></tr>
$articles
</table>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "l") {
	if (isset($_POST['add'])) {
		$link_name = stripinput($_POST['link_name']);
		$link_url = stripinput($_POST['link_url']);
		$link_description = stripinput($_POST['link_description']);
		$result = dbquery("INSERT INTO ".$fusion_prefix."weblinks VALUES('', '$link_name', '$link_description', '$link_url', '".$_POST['link_category']."', '".time()."', '0')");
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$submit_id'");
		opentable(LAN_430);
		echo "<br><div align='center'>".LAN_431."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable(LAN_432);
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".LAN_433."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else {
		$opts = "";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) $opts .= "<option value='".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</option>\n";
		} else {
			$opts .= "<option value='0'>".LAN_434."</option>\n";
		}
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$fusion_prefix."submissions ts
			LEFT JOIN ".$fusion_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$posted = showdate("longdate", $data['submit_datestamp']);
		opentable(LAN_440);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?stage=2&t=l&submit_id=$submit_id'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td style='text-align:center;'>".LAN_441."<a href='".FUSION_BASE."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>".LAN_442."$posted</td>
</tr>
<tr>
<td style='text-align:center;'><a href='".$submit_criteria['link_url']."' target='_blank'>".$submit_criteria['link_name']."</a> - ".$submit_criteria['link_url']."</td>
</tr>
<tr>
<td style='text-align:center;'><span class='alt'>".LAN_443."</span> ".$submit_criteria['link_category']."</td>
</tr>
</table>
<table align='center'>
<tr>
<td>".LAN_443."</td>
<td><select name='link_category' class='textbox'>
$opts</select></td>
</tr>
<tr>
<td>".LAN_444."</td>
<td><input type='text' name='link_name' value='".$submit_criteria['link_name']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".LAN_445."</td>
<td><input type='text' name='link_url' value='".$submit_criteria['link_url']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".LAN_446."</td>
<td><input type='text' name='link_description' value='".$submit_criteria['link_description']."' class='textbox' style='width:300px'></td>
</tr>
</table>
<center><br>
".LAN_447."<br>
<input type='submit' name='add' value='".LAN_448."' class='button'>
<input type='submit' name='delete' value='".LAN_449."' class='button'></center>
</form>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "n") {
	if (isset($_POST['publish'])) {
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$fusion_prefix."submissions ts
			LEFT JOIN ".$fusion_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$news_body = addslash($submit_criteria['news_body']);
		$result = dbquery("INSERT INTO ".$fusion_prefix."news VALUES('', '".$submit_criteria['news_subject']."', '$news_body', '', '".$submit_criteria['news_breaks']."', '".$data['user_id']."', '".time()."', '0', '0', '0', '0')");
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$submit_id'");
		opentable(LAN_490);
		echo "<br><div align='center'>".LAN_491."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable(LAN_492);
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".LAN_493."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else {	
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$fusion_prefix."submissions ts
			LEFT JOIN ".$fusion_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$subject = $submit_criteria['news_subject'];
		$news = stripslash($submit_criteria['news_body']);
		opentable($subject);
		echo ($submit_criteria['news_breaks'] == "y" ? nl2br($news) : $news);
		closetable();
		tablebreak();
		opentable(LAN_500);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?sub=submissions&stage=2&t=n&submit_id=$submit_id'>
<center>
".LAN_501."<a href='".FUSION_BASE."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br><br>
".LAN_502."<br>
<input type='submit' name='publish' value='".LAN_503."' class='button'>
<input type='submit' name='delete' value='".LAN_504."' class='button'>
</center>
</form>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "a") {
	if (isset($_POST['publish'])) {
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$fusion_prefix."submissions ts
			LEFT JOIN ".$fusion_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$description = addslash($submit_criteria['article_description']);
		$article = addslash($submit_criteria['article_body']);
		$result = dbquery("INSERT INTO ".$fusion_prefix."articles VALUES('', '".$submit_criteria['article_category']."', '".$submit_criteria['article_subject']."', '$description', '$article', '".$submit_criteria['article_breaks']."', '".$data['user_id']."', '".time()."', '0')");
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$submit_id'");
		opentable(LAN_530);
		echo "<br><div align='center'>".LAN_531."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable(LAN_532);
		$result = dbquery("DELETE FROM ".$fusion_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".LAN_533."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
		closetable();
	} else {	
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$fusion_prefix."submissions ts
			LEFT JOIN ".$fusion_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$subject = $submit_criteria['article_subject'];
		$description = stripslash($submit_criteria['article_description']);
		$article = stripslash($submit_criteria['article_body']);
		opentable($subject);
		echo $description;
		closetable();
		tablebreak();
		opentable($subject);
		echo ($submit_criteria['article_breaks'] == "y" ? nl2br($article) : $article);
		closetable();
		tablebreak();
		opentable(LAN_540);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?sub=submissions&stage=2&t=a&submit_id=$submit_id'>
<center>
".LAN_541."<a href='".FUSION_BASE."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br><br>
".LAN_542."<br>
<input type='submit' name='publish' value='".LAN_543."' class='button'>
<input type='submit' name='delete' value='".LAN_544."' class='button'>
</center>
</form>\n";
		closetable();
	}
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>