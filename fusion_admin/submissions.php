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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_submissions.php";
require "navigation.php";

if (!Admin()) { header("Location: ../index.php"); exit; }

if (Admin()) {
	if ($stage == "" || $stage == "1") {
		if ($t == "l") {
			if (isset($delete)) {
				opentable(LAN_400);
				$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$delete'");
				echo "<br><div align='center'>".LAN_401."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
				closetable();
			}
		} else if ($t == "n") {
			if (isset($delete)) {
				opentable(LAN_460);
				$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$delete'");
				echo "<br><div align='center'>".LAN_461."<br><br>
<a href='$PHP_SELF'>".LAN_462."</a><br><br>
<a href='index.php'>".LAN_463."</a></div><br>\n";
				closetable();
			}
		} else if ($t == "a") {
			if (isset($delete)) {
				opentable(LAN_510);
				$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$delete'");
				echo "<br><div align='center'>".LAN_511."<br><br>
<a href='$PHP_SELF'>".LAN_512."</a><br><br>
<a href='index.php'>".LAN_513."</a></div><br>\n";
				closetable();
			}
		} else {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_links ORDER BY sublink_datestamp DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$links .= "<tr><td>".$data['sublink_sitename']."</td>
<td align='right' width='100'><span class='small'><a href='$PHP_SELF?stage=2&t=l&sublink_id=".$data['sublink_id']."'>".LAN_420."</a></span> |
<span class='small'><a href='$PHP_SELF?t=l&delete=".$data['sublink_id']."'>".LAN_421."</a></span></td></tr>";
				}
			} else {
				$links = "<tr><td colspan='2'>".LAN_422."</td></tr>";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_news ORDER BY subnews_datestamp DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$news .= "<tr><td>".$data['subnews_subject']."</td>
<td align='right' width='100'><span class='small'><a href='$PHP_SELF?stage=2&t=n&subnews_id=".$data['subnews_id']."'>".LAN_480."</a></span> |
<span class='small'><a href='$PHP_SELF?t=n&delete=".$data['subnews_id']."'>".LAN_481."</a></span></td></tr>";
				}
			} else {
				$news = "<tr><td colspan='2'>".LAN_482."</td></tr>";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_articles ORDER BY sub_article_datestamp DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$articles .= "<tr><td>".$data['sub_article_subject']."</td>
<td align='right' width='100'><span class='small'><a href='$PHP_SELF?stage=2&t=a&sub_article_id=".$data['sub_article_id']."'>".LAN_520."</a></span> |
<span class='small'><a href='$PHP_SELF?t=a&delete=".$data['sub_article_id']."'>".LAN_521."</a></span></td></tr>";
				}
			} else {
				$articles = "<tr><td colspan='2'>".LAN_522."</td></tr>";
			}
			opentable(LAN_404);
			echo "<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>
<tr><td colspan='2' class='altbg'>".LAN_423."</td></tr>
$links
<tr><td colspan='2' class='altbg'>".LAN_483."</td></tr>
$news
<tr><td colspan='2' class='altbg'>".LAN_523."</td></tr>
$articles
</table>\n";
			closetable();
		}
	}
	if ($stage == "2" && $t == "l") {
		if (isset($_POST['add'])) {
			$result = dbquery("INSERT INTO ".$fusion_prefix."weblinks VALUES('', '$link_sitename', '$link_description', '$link_url', '$link_category', '".time()."', '0')");
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$sublink_id'");
			opentable(LAN_430);
			echo "<br><div align='center'>".LAN_431."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable(LAN_432);
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$sublink_id'");
			echo "<br><div align='center'>".LAN_433."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
			if (dbrows($result) != 0) {
				while($data = dbarray($result)) {
					$opts .= "<option value='".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</option>\n";
				}
			} else {
				$opts .= "<option value='0'>".LAN_434."</option>\n";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$sublink_id'");
			$data = dbarray($result);
			$posted = strftime($settings['longdate'], $data['sublink_datestamp']+($settings['timeoffset']*3600));
			opentable(LAN_440);
			echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td style='text-align:center;'>".LAN_441."<a href='mailto:".$data['sublink_email']."'>".$data['sublink_name']."</a>".LAN_442."$posted</td>
</tr>
<tr>
<td style='text-align:center;'><a href='".$data['sublink_url']."' target='_blank'>".$data['sublink_sitename']."</a> (".$data['sublink_url'].")</td>
</tr>
<tr>
<td style='text-align:center;'><span class='alt'>".LAN_443."</span> ".$data['sublink_category']."</td>
</tr>
<form name='publish' method='post' action='$PHP_SELF?stage=2&t=l&sublink_id=$sublink_id'>
<tr>
<td><center><br>
".LAN_444."<br><br>
</center>
<table>
<tr>
<td>".LAN_445."</td>
<td><input type='text' name='link_sitename' value='".$data['sublink_sitename']."' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td>".LAN_446."</td>
<td><input type='text' name='link_url' value='".$data['sublink_url']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".LAN_447."</td>
<td><input type='text' name='link_description' value='".$data['sublink_description']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".LAN_443."</td>
<td><select name='link_category' class='textbox' style='width:200px'>
$opts</select></td>
</tr>
</table>
<center><input type='submit' name='add' value='".LAN_448."' class='button'><br><br>
".LAN_449."<br>
<input type='submit' name='delete' value='".LAN_450."' class='button'></center>
</td>
</tr>
</form>
</table>";
			closetable();
		}
	}
	if ($stage == "2" && $t == "n") {
		if (isset($_POST['publish'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			$data = dbarray($result);
			$subject = stripinput($data['subnews_subject']);
			$news = addslashes($data['subnews_news']);
			if ($data['subnews_extended'] != "") {
				$extendednews = addslashes($data['subnews_extended']);
			}
			$result = dbquery("INSERT INTO ".$fusion_prefix."news VALUES('', '$subject', '$news', '$extendednews', '".$data['subnews_breaks']."', '".$userdata['user_id']."', '".time()."', '0', '0')");
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			opentable(LAN_490);
			echo "<br><div align='center'>".LAN_491."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable(LAN_492);
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			echo "<br><div align='center'>".LAN_493."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
			closetable();
		} else {	
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			$data = dbarray($result);
			$subject = $data['subnews_subject'];
			$news = stripslashes($data['subnews_news']);
			if ($data['subnews_extended'] != "") {
				$extendednews = stripslashes($data['subnews_extended']);
			}
			if ($data['subnews_breaks'] == "y") {
				$news = nl2br($news);
				if ($data['subnews_extended'] != "") {
					$extendednews = nl2br($extendednews);
				}
			}
			opentable($subject);
			echo $news;
			closetable();
			if ($extendednews != "") {
				tablebreak();
				opentable($subject);
				echo $extendednews;
				closetable();
			}
			tablebreak();
			opentable(LAN_500);
			echo "<form name='publish' method='post' action='$PHP_SELF?sub=submissions&stage=2&t=n&subnews_id=$subnews_id'>
<center>
".LAN_501."<br>
<input type='submit' name='publish' value='".LAN_502."' class='button'><br><br>
".LAN_503."<br>
<input type='submit' name='delete' value='".LAN_504."' class='button'>
</center>
</form>\n";
			closetable();
		}
	}
	if ($stage == "2" && $t == "a") {
		if (isset($_POST['publish'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			$data = dbarray($result);
			$subject = stripinput($data['sub_article_subject']);
			$description = addslashes($data['sub_article_description']);
			$article = addslashes($data['sub_article_body']);
			$result = dbquery("INSERT INTO ".$fusion_prefix."articles VALUES('', '".$data['sub_article_cat']."', '$subject', '$description', '$article', '".$data['sub_article_breaks']."', '0', '".$userdata['user_id']."', '".time()."', '0')");
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			opentable(LAN_530);
			echo "<br><div align='center'>".LAN_531."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable(LAN_532);
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			echo "<br><div align='center'>".LAN_533."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a></div><br>\n";
			closetable();
		} else {	
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			$data = dbarray($result);
			$subject = $data['sub_article_subject'];
			$description = stripslashes($data['sub_article_description']);
			$article = stripslashes($data['sub_article_body']);
			if ($data['sub_article_breaks'] == "y") {
				$article = nl2br($data['sub_article_body']);
			}
			opentable($subject);
			echo $description;
			closetable();
			tablebreak();
			opentable($subject);
			echo $article;
			closetable();
			tablebreak();
			opentable(LAN_540);
			echo "<form name='publish' method='post' action='$PHP_SELF?sub=submissions&stage=2&t=a&sub_article_id=$sub_article_id'>
<center>
".LAN_541."<br>
<input type='submit' name='publish' value='".LAN_542."' class='button'><br><br>
".LAN_543."<br>
<input type='submit' name='delete' value='".LAN_544."' class='button'>
</center>
</form>\n";
			closetable();
		}
	}
}

echo "</td>\n";
require "../footer.php";
?>