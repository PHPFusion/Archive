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
require fusion_langdir."admin/admin_polls.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	if (isset($_POST['save'])) {
		$i = 0;
		$poll_title = addslashes($poll_title);
		while ($i != $optcount) {
			$poll_option[$i] = addslashes($poll_option[$i]);
			$i++;
		}
		if (isset($poll_id)) {
			if (isset($_POST['conclude'])) { $ended = time(); } else { $ended = 0; }
			$result = dbquery("UPDATE ".$fusion_prefix."polls SET poll_title='$poll_title', poll_optcount='$optcount', poll_opt_0='$poll_option[0]', poll_opt_1='$poll_option[1]', poll_opt_2='$poll_option[2]', poll_opt_3='$poll_option[3]', poll_opt_4='$poll_option[4]', poll_opt_5='$poll_option[5]', poll_opt_6='$poll_option[6]', poll_opt_7='$poll_option[7]', poll_opt_8='$poll_option[8]', poll_opt_9='$poll_option[9]', poll_ended='$ended' WHERE poll_id='$poll_id'");
			opentable(LAN_200);
			echo "<center><br>
".LAN_201."<br><br>
<a href=\"index.php\">".LAN_202."</a><br><br>
</center>\n";
			closetable();
		} else {
			$result = dbquery("UPDATE ".$fusion_prefix."polls SET poll_ended='".time()."' WHERE poll_ended='0'");
			$result = dbquery("INSERT INTO ".$fusion_prefix."polls VALUES('', '$poll_title', '$optcount', '$poll_option[0]', '$poll_option[1]', '$poll_option[2]', '$poll_option[3]', '$poll_option[4]', '$poll_option[5]', '$poll_option[6]', '$poll_option[7]', '$poll_option[8]', '$poll_option[9]', '0', '".time()."', '0')");
			opentable(LAN_203);
			echo "<center><br>
".LAN_204."<br><br>
<a href=\"index.php\">".LAN_202."</a><br><br>
</center>\n";
			closetable();
		}
	} else if (isset($_POST['delete'])) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id'");
		if (dbrows($result) != 0) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id'");
			}
		opentable(LAN_205);
		echo "<center><br>
".LAN_206."<br><br>
<a href=\"index.php\">".LAN_202."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($optcount)) { $count = $optcount; } else { $count = 2; }
		if (isset($_POST['preview'])) {
			$i = 0;
			$poll_title = stripslashes($poll_title);
			while ($i != $count) {
				$poll_option[$i] = stripslashes($poll_option[$i]);
				$poll .= "<input type=\"checkbox\" name=\"option[$i]\"> $poll_option[$i]";
				$i++;
				if ($i != $count) { $poll .= "<br><br>\n"; } else { $poll .= "<hr>\n"; }
			}
			opentable(LAN_207);
			echo "<table align=\"center\" width=\"280\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td>$poll_title<hr>
$poll</td>
</tr>
<tr>
<td align=\"center\"><input type=\"button\" name=\"blank\" value=\"".LAN_208."\" class=\"button\" style=\"width:70px\"></td>
</tr>
</table>\n";
			closetable();
			tablebreak();
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."polls ORDER BY poll_id DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$editlist .= "<option value=\"$data[poll_id]\">".stripslashes($data[poll_title])."</option>\n";
			}
			opentable(LAN_209);
			echo "<form name=\"editform\" method=\"post\" action=\"$PHP_SELF\">
<center>
<select name=\"poll_id\" class=\"textbox\" style=\"width:200px;\">
$editlist</select>
<input type=\"submit\" name=\"edit\" value=\"".LAN_210."\" class=\"button\" style=\"width:60px;\">
<input type=\"submit\" name=\"delete\" value=\"".LAN_211."\" class=\"button\" style=\"width:60px;\">
</center>
</form>\n";
			closetable();
			tablebreak();
		}
		if (isset($_POST['edit'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id'");
			$data = dbarray($result);
			$poll_title = stripslashes($data[poll_title]);
			$count = $data[poll_optcount];
			$i = 0;
			while ($i != $count) {
				$poll_option[$i] = stripslashes($data["poll_opt_".$i]);
				$i++;
			}
		}
		if (isset($_POST['addoption'])) {
			if ($optcount != 10) { $count = count($poll_option) + 1; } else { $count = $optcount; }
			$poll_title = stripslashes($poll_title);
			$i = 0;
			while ($i != $count) {
				$poll_option[$i] = stripslashes($poll_option[$i]);
				$i++;
			}
		}
		$i = 0; $opt = 1;
		if (isset($poll_id)) {
			$action = $PHP_SELF."?poll_id=$poll_id";
			opentable("Edit Poll");
			if ($data[poll_ended] > 0) {
				while ($i != $count) {
					$result = dbquery("SELECT count(vote_opt) FROM ".$fusion_prefix."poll_votes WHERE vote_opt='$i' AND poll_id='$data[poll_id]'");
					$numvotes = dbresult($result, 0);
					$optvotes = number_format(100/$data[poll_votes]*$numvotes);
					if ($numvotes == 1) {
						$votecount = "[1 ".LAN_212."]";
					} else {
						$votecount = "[$numvotes ".LAN_213."]";
					}
					$poll_option[$i] = stripslashes($data["poll_opt_".$i]);
					$poll .= "$poll_option[$i]<br>
<img src=\"".fusion_themedir."images/pollbar.gif\" height=\"12\" width=\"$optvotes%\" class=\"poll\"><br>
<span class=\"small\">".$optvotes."% ".$votecount."</span><br><br>\n";
					$i++;
				}
				echo "<table align=\"center\" width=\"280\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td>".stripslashes($poll_title)."<hr>
$poll
<center><span class=\"small\"><span class=\"alt\">".LAN_214."</span> $data[poll_votes]<br>
<span class=\"alt\">".LAN_215."</span> ".strftime($settings[shortdate], $data[poll_started]+($settings[timeoffset]*3600))."<br>
<span class=\"alt\">".LAN_216."</span> ".strftime($settings[shortdate], $data[poll_ended]+($settings[timeoffset]*3600))."</span></center></td>
</tr>
</table>\n";
			} else {
				echo "<form name=\"test\" method=\"post\" action=\"$action\">
<table align=\"center\" width=\"280\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"80\">".LAN_217."</td><td><input type=\"text\" name=\"poll_title\" value=\"$poll_title\" class=\"textbox\" style=\"width:200px\"></td>
</tr>
<tr>\n";
				while ($i != $count) {
					echo "<tr>
<td width=\"80\">".LAN_218." $opt</td><td><input type=\"text\" name=\"poll_option[$i]\" value=\"$poll_option[$i]\" class=\"textbox\" style=\"width:200px\"></td>
</tr>\n";
					$i++; $opt++;
				}
				echo "</table>
<table align=\"center\" width=\"280\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"center\">";
				if (isset($poll_id)) {
					echo "<br><input type=\"checkbox\" name=\"conclude\" value=\"yes\">".LAN_219."<br><br>\n";
				}
echo "<input type=\"hidden\" name=\"optcount\" value=\"$count\">
<input type=\"submit\" name=\"addoption\" value=\"".LAN_220."\" class=\"button\" style=\"width:70px\">
<input type=\"submit\" name=\"preview\" value=\"".LAN_221."\" class=\"button\" style=\"width:70px\">
<input type=\"submit\" name=\"save\" value=\"".LAN_222."\" class=\"button\" style=\"width:70px\">
</tr>
</table>
</form>\n";
			}
			closetable();
		} else {
			$action = "$PHP_SELF";
			opentable(LAN_223);
			echo "<form name=\"test\" method=\"post\" action=\"$action\">
<table align=\"center\" width=\"280\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"80\">".LAN_217."</td><td><input type=\"text\" name=\"poll_title\" value=\"$poll_title\" class=\"textbox\" style=\"width:200px\"></td>
</tr>
<tr>\n";
			while ($i != $count) {
				echo "<tr>
<td width=\"80\">".LAN_218." $opt</td><td><input type=\"text\" name=\"poll_option[$i]\" value=\"$poll_option[$i]\" class=\"textbox\" style=\"width:200px\"></td>
</tr>\n";
				$i++; $opt++;
			}
			echo "</table>
<table align=\"center\" width=\"280\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"center\">";
			if (isset($poll_id)) {
				echo "<br><input type=\"checkbox\" name=\"conclude\" value=\"yes\">".LAN_219."<br><br>\n";
			}
echo "<input type=\"hidden\" name=\"optcount\" value=\"$count\">
<input type=\"submit\" name=\"addoption\" value=\"".LAN_220."\" class=\"button\" style=\"width:70px\">
<input type=\"submit\" name=\"preview\" value=\"".LAN_221."\" class=\"button\" style=\"width:70px\">
<input type=\"submit\" name=\"save\" value=\"".LAN_222."\" class=\"button\" style=\"width:70px\">
</tr>
</table>
</form>\n";
			closetable();
		}
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>