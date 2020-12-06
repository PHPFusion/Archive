<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/submissions.php";

if (!checkrights("SU")) fallback("../index.php");
if (isset($submit_id) && !isNum($submit_id)) fallback(FUSION_SELF);
if (!isset($stage)) $stage = "";
$links = ""; $news = ""; $articles = "";

if ($stage == "" || $stage == "1") {
	if (isset($delete)) {
		opentable($locale['400']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$delete'");
		echo "<br><div align='center'>".$locale['401']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {
		$result = dbquery("SELECT * FROM ".$db_prefix."submissions WHERE submit_type='l' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$links .= "<tr><td class='tbl'>".$submit_criteria['link_name']."</td>
<td align='right' width='100' class='tbl'><span class='small'><a href='".FUSION_SELF."?stage=2&t=l&submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".$locale['418']."</a></span></td></tr>";
			}
		} else {
			$links = "<tr><td colspan='2' class='tbl'>".$locale['414']."</td></tr>";
		}
		$result = dbquery("SELECT * FROM ".$db_prefix."submissions WHERE submit_type='n' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$news .= "<tr><td class='tbl'>".$submit_criteria['news_subject']."</td>
<td align='right' width='100' class='tbl'><span class='small'><a href='".FUSION_SELF."?stage=2&t=n&submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".$locale['418']."</a></span></td></tr>";
			}
		} else {
			$news = "<tr><td colspan='2' class='tbl'>".$locale['415']."</td></tr>";
		}
		$result = dbquery("SELECT * FROM ".$db_prefix."submissions WHERE submit_type='a' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$articles .= "<tr><td class='tbl'>".$submit_criteria['article_subject']."</td>
<td align='right' width='100' class='tbl'><span class='small'><a href='".FUSION_SELF."?stage=2&t=a&submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".$locale['418']."</a></span></td></tr>";
			}
		} else {
			$articles = "<tr><td colspan='2' class='tbl'>".$locale['416']."</td></tr>";
		}
		opentable($locale['410']);
		echo "<table align='center' width='400' cellspacing='0' cellpadding='0'>
<tr><td colspan='2' class='tbl2'>".$locale['411']."</td></tr>
$links
<tr><td colspan='2' class='tbl2'>".$locale['412']."</td></tr>
$news
<tr><td colspan='2' class='tbl2'>".$locale['413']."</td></tr>
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
		$result = dbquery("INSERT INTO ".$db_prefix."weblinks VALUES('', '$link_name', '$link_description', '$link_url', '".$_POST['link_category']."', '".time()."', '0')");
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		opentable($locale['430']);
		echo "<br><div align='center'>".$locale['431']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable($locale['432']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".$locale['433']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {
		$opts = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) $opts .= "<option value='".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</option>\n";
		} else {
			$opts .= "<option value='0'>".$locale['434']."</option>\n";
		}
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$posted = showdate("longdate", $data['submit_datestamp']);
		opentable($locale['440']);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?stage=2&t=l&submit_id=$submit_id'>
<table align='center' cellspacing='0' cellpadding='0'>
<tr>
<td style='text-align:center;' class='tbl'>".$locale['441']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>".$locale['442']."$posted</td>
</tr>
<tr>
<td style='text-align:center;' class='tbl'><a href='".$submit_criteria['link_url']."' target='_blank'>".$submit_criteria['link_name']."</a> - ".$submit_criteria['link_url']."</td>
</tr>
<tr>
<td style='text-align:center;' class='tbl'><span class='alt'>".$locale['443']."</span> ".$submit_criteria['link_category']."</td>
</tr>
</table>
<table align='center'>
<tr>
<td>".$locale['443']."</td>
<td><select name='link_category' class='textbox'>
$opts</select></td>
</tr>
<tr>
<td>".$locale['444']."</td>
<td><input type='text' name='link_name' value='".$submit_criteria['link_name']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".$locale['445']."</td>
<td><input type='text' name='link_url' value='".$submit_criteria['link_url']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".$locale['446']."</td>
<td><input type='text' name='link_description' value='".$submit_criteria['link_description']."' class='textbox' style='width:300px'></td>
</tr>
</table>
<center><br>
".$locale['447']."<br>
<input type='submit' name='add' value='".$locale['448']."' class='button'>
<input type='submit' name='delete' value='".$locale['449']."' class='button'></center>
</form>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "n") {
	if (isset($_POST['publish'])) {
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$news_body = addslash($submit_criteria['news_body']);
		$result = dbquery("INSERT INTO ".$db_prefix."news VALUES('', '".$submit_criteria['news_subject']."', '$news_body', '', '".$submit_criteria['news_breaks']."', '".$data['user_id']."', '".time()."', '0', '0', '0', '0', '1', '1')");
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		opentable($locale['490']);
		echo "<br><div align='center'>".$locale['491']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable($locale['492']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".$locale['493']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {	
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$subject = $submit_criteria['news_subject'];
		$news = stripslash($submit_criteria['news_body']);
		opentable($subject);
		echo ($submit_criteria['news_breaks'] == "y" ? nl2br($news) : $news);
		closetable();
		tablebreak();
		opentable($locale['500']);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?sub=submissions&stage=2&t=n&submit_id=$submit_id'>
<center>
".$locale['501']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br><br>
".$locale['502']."<br>
<input type='submit' name='publish' value='".$locale['503']."' class='button'>
<input type='submit' name='delete' value='".$locale['504']."' class='button'>
</center>
</form>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "a") {
	if (isset($_POST['publish'])) {
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$description = addslash($submit_criteria['article_description']);
		$article = addslash($submit_criteria['article_body']);
		$result = dbquery("INSERT INTO ".$db_prefix."articles VALUES('', '".$submit_criteria['article_category']."', '".$submit_criteria['article_subject']."', '$description', '$article', '".$submit_criteria['article_breaks']."', '".$data['user_id']."', '".time()."', '0', '1', '1')");
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		opentable($locale['530']);
		echo "<br><div align='center'>".$locale['531']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable($locale['532']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".$locale['533']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {	
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
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
		opentable($locale['540']);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?sub=submissions&stage=2&t=a&submit_id=$submit_id'>
<center>
".$locale['541']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br><br>
".$locale['542']."<br>
<input type='submit' name='publish' value='".$locale['543']."' class='button'>
<input type='submit' name='delete' value='".$locale['544']."' class='button'>
</center>
</form>\n";
		closetable();
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>