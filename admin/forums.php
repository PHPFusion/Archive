<?
/*
-------------------------------------------------------
	PHP Fusion X3
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require "header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_forums.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	if ($action == "delete" && $t == "cat") {
		opentable(LAN_200);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='$forum_id'");
		if (dbrows($result) == 0) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
			echo "<center><br>
".LAN_201."<br><br>
<a href=\"forums.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		} else {
			echo "<center><br>
".LAN_204."<br>
<span class=\"small\">".LAN_205."</span><br><br>
<a href=\"forums.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		}
		closetable();
	} else if ($action == "delete" && $t == "forum") {
		opentable(LAN_206);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE forum_id='$forum_id'");
		if (dbrows($result) == 0) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
			echo "<center><br>
".LAN_207."<br><br>
<a href=\"forums.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		} else {
			echo "<center><br>
".LAN_208."<br>
<span class=\"small\">".LAN_209."</span><br><br>
<a href=\"forums.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		}
		closetable();
	} else {
		if (isset($_POST[save_cat])) {
			if ($action == "edit" && $t == "cat") {
				$cat_name = addslashes($cat_name);
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_name='$cat_name', forum_order='$cat_order' WHERE forum_id='$forum_id'");
				unset($action, $t, $cat_name, $cat_order);
			} else {
				$cat_name = addslashes($cat_name);
				$result = dbquery("INSERT INTO ".$fusion_prefix."forums VALUES('', '0', '$cat_name', '$cat_order', '', '0', '0', '0', '0')");
				unset($action, $t, $cat_name, $cat_order);
			}
		}
		if (isset($_POST[save_forum])) {
			if ($action == "edit" && $t == "forum") {
				$forum_name = addslashes($forum_name);
				$forum_description = addslashes($forum_description);
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_name='$forum_name', forum_order='$forum_order', forum_description='$forum_description' WHERE forum_id='$forum_id'");
				unset($action, $t, $forum_cat, $forum_name, $forum_order, $forum_description);
			} else {
				$forum_name = addslashes($forum_name);
				$forum_description = addslashes($forum_description);
				$result = dbquery("INSERT INTO ".$fusion_prefix."forums VALUES('', '$forum_cat', '$forum_name', '$forum_order', '$forum_description', '0', '0', '0', '0')");
				unset($action, $t, $forum_cat, $forum_name, $forum_order, $forum_description);
			}
		}
		if ($action == "edit") {
			if ($t == "cat") {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
				$data = dbarray($result);
				$cat_name = stripslashes($data[forum_name]);
				$cat_order = $data[forum_order];
				$cat_title = LAN_220;
				$cat_action = "$PHP_SELF?action=edit&forum_id=$data[forum_id]&t=cat";
				$forum_title = LAN_221;
				$forum_action = "$PHP_SELF";
			} else if ($t == "forum") {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
				$data = dbarray($result);
				$forum_name = stripslashes($data[forum_name]);
				$forum_description = stripslashes($data[forum_description]);
				$forum_cat = $data[forum_cat];
				$forum_order = $data[forum_order];
				$forum_title = LAN_222;
				$forum_action = "$PHP_SELF?action=edit&forum_id=$data[forum_id]&t=forum";
				$cat_title = LAN_223;
				$cat_action = "$PHP_SELF";
			}
		} else {
			$cat_title = LAN_223;
			$cat_action = "$PHP_SELF";
			$forum_title = LAN_221;
			$forum_action = "$PHP_SELF";
		}
		opentable($cat_title);
		echo "<table align=\"center\" width=\"300\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"addcat\" method=\"post\" action=\"$cat_action\">
<tr>
<td>".LAN_240."<br>
<input type=\"textbox\" name=\"cat_name\" value=\"$cat_name\" class=\"textbox\" style=\"width:230px;\"></td>
<td width=\"50\">".LAN_241."<br>
<input type=\"textbox\" name=\"cat_order\" value=\"$cat_order\" class=\"textbox\" style=\"width:45px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_cat\" value=\"".LAN_242."\" class=\"button\" style=\"width:100px;\"></td>
</tr>
</form>
</table>\n";
		closetable();
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if ($action == "edit" && $t == "forum") {
					if ($data2[forum_id] == $forum_cat) { $sel = " selected"; } else { $sel = ""; }
				}
				$cat_opts .= "<option value=\"$data2[forum_id]\"$sel>".stripslashes($data2[forum_name])."</option>\n";
			}
		}
		tablebreak();
		opentable($forum_title);
		echo "<table align=\"center\" width=\"300\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"addforum\" method=\"post\" action=\"$forum_action\">
<tr>
<td colspan=\"2\">".LAN_260."<br>
<input type=\"textbox\" name=\"forum_name\" value=\"$forum_name\" class=\"textbox\" style=\"width:285px;\"></td>
</tr>
<tr>
<td colspan=\"2\">".LAN_261."<br>
<textarea name=\"forum_description\" rows=\"2\" class=\"textbox\" style=\"width:285px;\">$forum_description</textarea></td>
</tr>
<tr>
<td>".LAN_262."<br>
<select name=\"forum_cat\" class=\"textbox\" style=\"width:225px;\">
$cat_opts</select></td>
<td width=\"55\">".LAN_263."<br>
<input type=\"textbox\" name=\"forum_order\" value=\"$forum_order\" class=\"textbox\" style=\"width:45px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_forum\" value=\"".LAN_264."\" class=\"button\" style=\"width:100px;\"></td>
</tr>
</form>
</table>\n";
		closetable();
		tablebreak();
		opentable(LAN_280);
		$forum .= "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$forum .= "<tr>
<td class=\"altbg\">".stripslashes($data[forum_name])."</td>
<td class=\"altbg\">$data[forum_order]</td>
<td class=\"altbg\"><a href=\"$PHP_SELF?action=edit&forum_id=$data[forum_id]&t=cat\">".LAN_281."</a> -
<a href=\"$PHP_SELF?action=delete&forum_id=$data[forum_id]&t=cat\">".LAN_282."</a></td>
</tr>\n";
				$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='$data[forum_id]' ORDER BY forum_order");
				if (dbrows($result) != 0) {
					while ($data2 = dbarray($result2)) {
						$forum .= "<tr>
<td><a href=\"\">".stripslashes($data2[forum_name])."</a><br>
<span class=\"small\">".stripslashes($data2[forum_description])."</span></td>
<td>$data2[forum_order]</td>
<td><a href=\"$PHP_SELF?action=edit&forum_id=$data2[forum_id]&t=forum\">".LAN_281."</a> -
<a href=\"$PHP_SELF?action=delete&forum_id=$data2[forum_id]&t=forum\">".LAN_282."</a></td>
</tr>\n";
					}
				} else {
					$forum .= "<tr>
<td colspan=\"3\">".LAN_283."</td>
</tr>\n";
				}
			}
		} else {
			$forum .= "<tr>
<td colspan=\"3\">".LAN_284."</td>
</tr>\n";
		}
		$forum .= "</table>\n";
		echo $forum;
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>