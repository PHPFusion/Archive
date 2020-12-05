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
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_news-articles.php";
include FUSION_ADMIN."navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

if (iSUPERADMIN) {
	if ($action == "delete") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_cat='$cat_id'");
		if (dbrows($result) != 0) {
			opentable(LAN_450);
			echo "<center><br>
".LAN_451."<br>
<span class='small'>".LAN_452."</span><br><br>
<a href='article_cats.php'>".LAN_453."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		} else {
			$result = dbquery("DELETE FROM ".$fusion_prefix."article_cats WHERE article_cat_id='$cat_id'");
			opentable(LAN_450);
			echo "<center><br>
".LAN_454."<br><br>
<a href='article_cats.php'>".LAN_453."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		}
		closetable();
	} else {
		if (isset($_POST['save_cat'])) {
			$cat_name = stripinput($_POST['cat_name']);
			$cat_description = stripinput($_POST['cat_description']);
			if ($action == "edit") {
				$result = dbquery("UPDATE ".$fusion_prefix."article_cats SET article_cat_name='$cat_name', article_cat_description='$cat_description' WHERE article_cat_id='$cat_id'");
				unset($action, $cat_name, $cat_description, $cat_id);
			} else {
				$result = dbquery("INSERT INTO ".$fusion_prefix."article_cats VALUES('', '$cat_name', '$cat_description')");
				unset($cat_name, $cat_description);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats WHERE article_cat_id='$cat_id'");
			$data = dbarray($result);
			$cat_name = $data['article_cat_name'];
			$cat_description = $data['article_cat_description'];
			$formaction = "$PHP_SELF?action=edit&cat_id=".$data['article_cat_id'];
			opentable(LAN_455);
		} else {
			$formaction = "$PHP_SELF";
			opentable(LAN_456);
		}
		echo "<form name='addcat' method='post' action='$formaction'>
<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='130'>".LAN_457."</td>
<td><input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='130'>".LAN_458."</td>
<td><input type='text' name='cat_description' value='$cat_description' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td align='center' colspan='2'>
<input type='submit' name='save_cat' value='".LAN_459."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		opentable(LAN_460);
		echo "<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name");
		if (dbrows($result) != 0) {
			echo "<tr>
<td class='tbl2'>".LAN_461."</td>
<td align='right' width='60' class='tbl2'>".LAN_462."</td>
</tr>\n";
			while ($data = dbarray($result)) {
				echo "<tr>
<td><a href='$PHP_SELF?action=edit&cat_id=$data[article_cat_id]'>".$data['article_cat_name']."</a><br>
<span class='small'>".$data['article_cat_description']."</span></td>
<td align='right' valign='top'><a href='$PHP_SELF?action=delete&cat_id=".$data['article_cat_id']."'>".LAN_463."</a></td>
</tr>\n";
			}
			echo "</table>\n";
		} else {
			echo "<tr><td align='center'>".LAN_464."</td></tr>
</table>\n";
		}
		closetable();
	}
}

echo "</td>\n";
include "../footer.php";
?>