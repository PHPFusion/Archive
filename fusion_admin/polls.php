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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_polls.php";

if (!checkrights("I")) { header("Location:../index.php"); exit; }

if (isset($_POST['save'])) {
	$poll_title = stripinput($poll_title);
	foreach($_POST['poll_option'] as $key => $value) {
		$poll_option[$key] = stripinput($_POST['poll_option'][$key]);
	}
	if (isset($poll_id)) {
		if (!isNum($poll_id)) { header("Location:polls.php"); exit; }
		$ended = (isset($_POST['close']) ? time() : 0);
		$result = dbquery("UPDATE ".$fusion_prefix."polls SET poll_title='$poll_title', poll_opt_0='$poll_option[0]', poll_opt_1='$poll_option[1]', poll_opt_2='$poll_option[2]', poll_opt_3='$poll_option[3]', poll_opt_4='$poll_option[4]', poll_opt_5='$poll_option[5]', poll_opt_6='$poll_option[6]', poll_opt_7='$poll_option[7]', poll_opt_8='$poll_option[8]', poll_opt_9='$poll_option[9]', poll_ended='$ended' WHERE poll_id='$poll_id'");
		opentable(LAN_400);
		echo "<center><br>\n".LAN_401."<br><br>\n<a href='index.php'>".LAN_405."</a><br><br>\n</center>\n";
		closetable();
	} else {
		$result = dbquery("UPDATE ".$fusion_prefix."polls SET poll_ended='".time()."' WHERE poll_ended='0'");
		$result = dbquery("INSERT INTO ".$fusion_prefix."polls VALUES('', '$poll_title', '$poll_option[0]', '$poll_option[1]', '$poll_option[2]', '$poll_option[3]', '$poll_option[4]', '$poll_option[5]', '$poll_option[6]', '$poll_option[7]', '$poll_option[8]', '$poll_option[9]', '".time()."', '0')");
		opentable(LAN_400);
		echo "<center><br>\n".LAN_402."<br><br>\n<a href='index.php'>".LAN_405."</a><br><br>\n</center>\n";
		closetable();
	}
} else if (isset($_POST['delete'])) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id'");
	if (dbrows($result) != 0) $result = dbquery("DELETE FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id'");
	opentable(LAN_403);
	echo "<center><br>\n".LAN_404."<br><br>\n<a href='index.php'>".LAN_405."</a><br><br>\n</center>\n";
	closetable();
} else {
	if (isset($_POST['preview'])) {
		$poll = ""; $i = 0;
		$poll_title = stripinput($poll_title);
		while ($i < count($_POST['poll_option'])) {
			$poll_option[$i] = stripinput($_POST['poll_option'][$i]);
			$poll .= "<input type='checkbox' name='option[$i]'> $poll_option[$i]<br><br>\n";
			$i++;
		}
		$opt_count = (isset($_POST['opt_count']) && $_POST['opt_count'] != 10 ? count($poll_option) : $_POST['opt_count']);
		opentable(LAN_410);
		echo "<table align='center' width='280' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>$poll_title<br><br>
$poll</td>
</tr>
<tr>
<td align='center'><input type='button' name='blank' value='".LAN_411."' class='button' style='width:70px'></td>
</tr>
</table>\n";
		closetable();
		tablebreak();
	}
	$editlist = "";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."polls ORDER BY poll_id DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$editlist .= "<option value='$data[poll_id]'>".stripslashes($data['poll_title'])."</option>\n";
		}
		opentable(LAN_420);
		echo "<form name='editform' method='post' action='".FUSION_SELF."'>
<center>
<select name='poll_id' class='textbox' style='width:200px;'>
$editlist</select>
<input type='submit' name='edit' value='".LAN_421."' class='button'>
<input type='submit' name='delete' value='".LAN_422."' class='button'>
</center>
</form>\n";
		closetable();
		tablebreak();
	}
	if (isset($_POST['edit'])) {
		if (!isNum($poll_id)) { header("Location:polls.php"); exit; }
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."polls WHERE poll_id='$poll_id'"));
		$poll_title = $data['poll_title'];
		for ($i=0; $i<=9; $i++) {
			if ($data["poll_opt_".$i]) $poll_option[$i] = $data["poll_opt_".$i];
		}
		$opt_count = count($poll_option);
		$poll_started = $data['poll_started'];
		$poll_ended = $data['poll_ended'];
	}
	if (isset($_POST['addoption'])) {
		$poll_title = stripinput($_POST['poll_title']);
		foreach($_POST['poll_option'] as $key => $value) {
			$poll_option[$key] = stripinput($_POST['poll_option'][$key]);
		}
		$opt_count = ($_POST['opt_count'] != 10 ? count($poll_option) + 1 : $_POST['opt_count']);
	}
	$i = 0; $opt = 1;
	$poll_title = isset($poll_title) ? $poll_title : "";
	$opt_count = isset($opt_count) ? $opt_count : 2;
	if (isset($poll_id)) $poll_ended = isset($poll_ended) ? $poll_ended : 0;
	opentable((isset($poll_id) ? LAN_431 : LAN_430));
	echo "<form name='pollform' method='post' action='".FUSION_SELF.(isset($poll_id) ? "?poll_id=$poll_id&poll_ended=$poll_ended" : "")."'>
<table align='center' width='280' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td width='80'>".LAN_433."</td><td><input type='text' name='poll_title' value='$poll_title' class='textbox' style='width:200px'></td>
</tr>
<tr>\n";
	while ($i != $opt_count) {
		$poll_opt = isset($poll_option[$i]) ? $poll_option[$i] : "";
		echo "<tr>\n<td width='80'>".LAN_434."$opt</td>\n";
		echo "<td><input type='text' name='poll_option[$i]' value='$poll_opt' class='textbox' style='width:200px'></td>\n</tr>\n";
		$i++; $opt++;
	}
	echo "</table>
<table align='center' width='280' cellpadding='0' cellspacing='0'>
<tr>
<td align='center'><br>\n";
	if (isset($poll_id) && $poll_ended == 0) {
		echo "<input type='checkbox' name='close' value='yes'>".LAN_435."<br><br>\n";
	}
	if (!isset($poll_id) || isset($poll_id) && $poll_ended == 0) {
		echo "<input type='hidden' name='opt_count' value='$opt_count'>
<input type='submit' name='addoption' value='".LAN_438."' class='button'>
<input type='submit' name='preview' value='".LAN_439."' class='button'>
<input type='submit' name='save' value='".LAN_440."' class='button'>\n";
	} else {
		echo LAN_436.showdate("shortdate", $poll_started)."<br>\n";
		echo LAN_437.showdate("shortdate", $poll_ended)."<br>\n";
	}
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>