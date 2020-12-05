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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_comments.php";

if (!checkrights("4")) { header("Location:../index.php"); exit; }

if (isset($article_id)) {
	$query = "comment_item_id='$article_id' AND comment_type='A'";
	$del_query = "article_id='$article_id'";
	$url = "article_id=$article_id";
} else if (isset($news_id)) {
	$query = "comment_item_id='$news_id' AND comment_type='N'";
	$del_query = "news_id='$news_id'";
	$url = "news_id=$news_id";
} else if (isset($photo_id)) {
	$query = "comment_item_id='$photo_id' AND comment_type='P'";
	$del_query = "photo_id='$photo_id'";
	$url = "photo_id=$photo_id";
}
if (isset($_POST['save_comment'])) {
	$comment_message = stripinput($_POST['comment_message']);
	$result = dbquery("UPDATE ".$fusion_prefix."comments SET comment_message='$comment_message' WHERE comment_id='$comment_id'");
	header("Location:comments.php?$url");
}
if (isset($step) && $step == "delete") {
	$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE comment_id='$comment_id'");
	$count = dbrows(dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE $query"));
	if ($count == 0) $url = "";
	header("Location:comments.php?$url");
}
$result = dbquery(
	"SELECT ta.*, COUNT(comment_item_id) AS comment_count
	FROM ".$fusion_prefix."articles
	AS ta LEFT JOIN ".$fusion_prefix."comments
	ON article_id=comment_item_id AND comment_type='A'
	GROUP BY article_id HAVING COUNT(comment_item_id)!='0' ORDER BY article_datestamp DESC"
);
if (dbrows($result) != 0) {
	$article_list = ""; $sel = "";
	while ($data = dbarray($result)) {
		if (isset($article_id)) $sel = ($article_id == $data['article_id'] ? " selected" : "");
		$article_list .= "<option value='".$data['article_id']."'$sel>".$data['article_subject']." (".$data['comment_count'].")</option>\n";
	}
}
$result = dbquery(
	"SELECT tn.*, COUNT(comment_item_id) AS comment_count
	FROM ".$fusion_prefix."news
	AS tn LEFT JOIN ".$fusion_prefix."comments
	ON news_id=comment_item_id AND comment_type='N'
	GROUP BY news_id HAVING COUNT(comment_item_id)!='0' ORDER BY news_datestamp DESC"
);
if (dbrows($result) != 0) {
	$news_list = ""; $sel = "";
	while ($data = dbarray($result)) {
		if (isset($news_id)) $sel = ($news_id == $data['news_id'] ? " selected" : "");
		$news_list .= "<option value='".$data['news_id']."'$sel>".$data['news_subject']." (".$data['comment_count'].")</option>\n";
	}
}
$result = dbquery("
	SELECT tp.*, COUNT(comment_item_id) AS comment_count
	FROM ".$fusion_prefix."photos
	AS tp LEFT JOIN ".$fusion_prefix."comments
	ON photo_id=comment_item_id AND comment_type='P'
	GROUP BY photo_id HAVING COUNT(comment_item_id) != '0' ORDER BY photo_date DESC
");
if (dbrows($result) != 0) {
	$photo_list = ""; $sel = "";
	while ($data = dbarray($result)) {
		if (isset($photo_id)) $sel = ($photo_id == $data['photo_id'] ? " selected" : "");
		$photo_list .= "<option value='".$data['photo_id']."'$sel>".$data['photo_title']." (".$data['comment_count'].")</option>\n";
	}
}
opentable(LAN_400);
echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>\n";
if (isset($article_list)) {
	echo "<form name='articles_form' method='post' action='".FUSION_SELF."'>
<tr>
<td>".LAN_402."<br>
<select name='article_id' class='textbox' style='width:250px;'>
$article_list</select>
<input type='submit' name='view' value='".LAN_401."' class='button'>
</td>
</tr>
</form>\n";
}
if (isset($news_list)) {
	echo "<form name='news_form' method='post' action='".FUSION_SELF."'>
<tr>
<td>".LAN_403."<br>
<select name='news_id' class='textbox' style='width:250px;'>
$news_list</select>
<input type='submit' name='view' value='".LAN_401."' class='button'>
</td>
</tr>
</form>\n";
}
if (isset($photo_list)) {
	echo "<form name='news_form' method='post' action='".FUSION_SELF."'>
<tr>
<td>".LAN_404."<br>
<select name='photo_id' class='textbox' style='width:250px;'>
$photo_list</select>
<input type='submit' name='view' value='".LAN_401."' class='button'>
</td>
</tr>
</form>\n";
}
echo "</table>\n";
closetable();
if (isset($step) && $step == "edit") {
	$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE comment_id=$comment_id"));
	tablebreak();
	opentable(LAN_420);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?comment_id=$comment_id&$url'>
<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td align='center'><textarea name='comment_message' rows='5' class='textbox' style='width:400px'>".$data['comment_message']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('comment_message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('comment_message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('comment_message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('comment_message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('comment_message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('comment_message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('comment_message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('comment_message', '[small]', '[/small]');\">
</tr>
<tr>
<td align='center'><input type='submit' name='save_comment' value='".LAN_421."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
}
if (isset($article_id) || isset($news_id) || isset($photo_id)) {
	tablebreak();
	opentable(LAN_430);
	$i = 0;
	$result = dbquery(
		"SELECT * FROM ".$fusion_prefix."comments LEFT JOIN ".$fusion_prefix."users
		ON ".$fusion_prefix."comments.comment_name=".$fusion_prefix."users.user_id
		WHERE $query ORDER BY comment_datestamp ASC"
	);
	echo "<table width='100%' align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
	while ($data = dbarray($result)) {
		echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>";
		if ($data['user_name']) {
			echo "<a href='".FUSION_BASE."profile.php?lookup=".$data['comment_name']."' class='slink'>".$data['user_name']."</a>";
		} else {
			echo $data['comment_name'];
		}
		echo "</span>
<span class='small'>".LAN_41.showdate("longdate", $data['comment_datestamp'])."</span><br>
".str_replace("<br>", "", parsesmileys($data['comment_message']))."<br>
<span class='small'><a href='".FUSION_SELF."?step=edit&comment_id=".$data['comment_id']."&$url'>".LAN_431."</a> -
<a href='".FUSION_SELF."?step=delete&comment_id=".$data['comment_id']."&$url' onClick='return DeleteItem()'>".LAN_432."</a> -
<b>".LAN_433.$data['comment_ip']."</b></span>
</td>\n</tr>\n";
		$i++;
	}
	echo "</table>\n";
	closetable();
	echo "<script>
function DeleteItem()
{
return confirm(\"".LAN_434."\");
}
</script>\n";
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>