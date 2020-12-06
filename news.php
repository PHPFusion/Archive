<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";

if (isset($readmore) && !isNum($readmore)) fallback(FUSION_SELF);

// Predefined variables, do not edit these values
$i = 0; $rc = 0; $ncount = 1; $ncolumn = 1; $news_[0] = ""; $news_[1] = ""; $news_[2] = "";

// This number should be an odd number to keep layout tidy
$news_per_page = 11;

if (!isset($readmore)) {
	$rows = dbcount("(news_id)", "news", groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	if (!isset($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$result = dbquery(
			"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
			LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")
			ORDER BY news_datestamp DESC LIMIT $rowstart,$news_per_page"
		);
		$nrows = round((dbrows($result) - 1) / 2);
		while ($data=dbarray($result)) {
			$news_cat_image = "";
			if ($data['news_cat'] != 0) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='".$data['news_cat']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					if (file_exists(IMAGES_NC.$data2['news_cat_image'])) $news_cat_image = "<a href='news_cats.php?cat_id=".$data2['news_cat_id']."'><img src='".IMAGES_NC.$data2['news_cat_image']."' alt='".$data2['news_cat_name']."' align='left' style='border:0px;margin-right:5px'></a>";
				}
			}
			$news_news = stripslashes($data['news_news']);
			if ($data['news_breaks'] == "y") $news_news = nl2br($news_news);
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_comments = dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'");
			if ($rows <= 2 || $ncount == 1) {
				$news_[0] .= "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td class='tbl1'><b><a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".$data['news_subject']."</b></td>
</tr>
<tr>
<td class='tbl1' style='text-align:justify'>$news_news</td>
</tr>
<tr>
<td align='right' class='tbl1'>\n";
				if (checkrights("N")) $news_[0] .= "<form name='editnews".$data['news_id']."' method='post' action='".ADMIN."news.php?news_id=".$data['news_id']."'>\n";
				$news_[0] .= "<span class='small2'><img src='".THEME."images/bullet.gif' alt=''> <a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a> ".$locale['041'].showdate("longdate", $data['news_datestamp'])." &middot;\n";
				if ($data['news_extended'] || $data['news_allow_comments']) {
					$news_[0] .= ($data['news_extended'] ? "<a href='".FUSION_SELF."?readmore=".$data['news_id']."'>".$locale['042']."</a> &middot;\n" : "");
					$news_[0] .= ($data['news_allow_comments'] ? "<a href='".FUSION_SELF."?readmore=".$data['news_id']."'>".$news_comments.$locale['043']."</a> &middot;\n" : "");
					$news_[0] .= $data['news_reads'].$locale['044']." &middot;\n";
				}
				$news_[0] .= "<a href='print.php?type=N&amp;item_id=".$data['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='border:0px;vertical-align:middle;'></a>";
				if (checkrights("N")) { $news_[0] .= " &middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editnews".$data['news_id'].".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a></span>\n</form>\n"; } else { $news_[0] .= "</span>\n"; }
				$news_[0] .= "</td>\n</tr>\n</table>\n";
				if ($ncount != $rows) $news_[0] .= "<div><img src='".THEME."images/blank.gif' alt='' width='1' height='5'></div>\n";
			} else {
				if ($i == $nrows && $ncolumn != 2) { $ncolumn = 2; $i = 0; }
				$row_color = ($rc % 2 == 0 ? "tbl2" : "tbl1");
				$news_[$ncolumn] .= "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td class='$row_color'><b><a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".$data['news_subject']."</b></td>
</tr>
<tr>
<td class='$row_color' style='text-align:justify'>$news_news</td>
</tr>
<tr>
<td align='right' class='$row_color'>\n";
				if (checkrights("N")) $news_[$ncolumn] .= "<form name='editnews".$data['news_id']."' method='post' action='".ADMIN."news.php?news_id=".$data['news_id']."'>\n";
				$news_[$ncolumn] .= "<span class='small2'><img src='".THEME."images/bullet.gif' alt=''> <a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a> ".$locale['041'].showdate("longdate", $data['news_datestamp']);
				if ($data['news_extended'] || $data['news_allow_comments']) {
					$news_[$ncolumn] .= "<br>\n";
					$news_[$ncolumn] .= ($data['news_extended'] ? "<a href='".FUSION_SELF."?readmore=".$data['news_id']."'>".$locale['042']."</a> &middot;\n" : "");
					$news_[$ncolumn] .= ($data['news_allow_comments'] ? "<a href='".FUSION_SELF."?readmore=".$data['news_id']."'>".$news_comments.$locale['043']."</a> &middot;\n" : "");
					$news_[$ncolumn] .= $data['news_reads'].$locale['044']." &middot;\n";
				} else {
					$news_[$ncolumn] .= " &middot;\n";
				}
				$news_[$ncolumn] .= "<a href='print.php?type=N&amp;item_id=".$data['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='border:0px;vertical-align:middle;'></a>\n";
				if (checkrights("N")) { $news_[$ncolumn] .= " &middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editnews".$data['news_id'].".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a></span>\n</form>\n"; } else { $news_[$ncolumn] .= "</span>\n"; }
				$news_[$ncolumn] .= "</td>\n</tr>\n</table>\n";
				if ($ncolumn == 1 && $i < ($nrows - 1)) $news_[$ncolumn] .= "<div><img src='".THEME."images/blank.gif' alt='' width='1' height='5'></div>\n";
				if ($ncolumn == 2 && $i < (dbrows($result) - $nrows - 2)) $news_[$ncolumn] .= "<div><img src='".THEME."images/blank.gif' alt='' width='1' height='5'></div>\n";
				$i++; $rc++;
			}
			$ncount++;
		}
		opentable($locale['046']);
		echo "<table cellpadding='0' cellspacing='0' style='width:100%'>\n<tr>\n<td colspan='3' style='width:100%'>\n";
		echo $news_[0];
		echo "</td>\n</tr>\n<tr>\n<td style='width:50%;vertical-align:top;'>\n";
		echo $news_[1];
		echo "</td>\n<td style='width:5px'><img src='".THEME."images/blank.gif' alt='' width='5' height='1'></td>\n<td style='width:50%;vertical-align:top;'>\n";
		echo $news_[2];
		echo "</td>\n</tr>\n</table>\n";
		closetable();
		if ($rows > $news_per_page) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$news_per_page,$rows,3)."\n</div>\n";
	} else {
		opentable($locale['046']);
		echo "<div style='text-align:center'><br>\n".$locale['047']."<br><br>\n</div>\n";
		closetable();
	}
} else {
	require_once INCLUDES."comments_include.php";
	require_once INCLUDES."ratings_include.php";
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE ".groupaccess('news_visibility')." AND news_id='$readmore'"
	);
	if (dbrows($result)!=0) {
		$data = dbarray($result);
		$news_cat_image = "";
		if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
			 $result2 = dbquery("UPDATE ".$db_prefix."news SET news_reads=news_reads+1 WHERE news_id='$readmore'");
			 $data['news_reads']++;
		}
		$news_subject = $data['news_subject'];
		if ($data['news_cat'] != 0) {
			$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='".$data['news_cat']."'");
			if (dbrows($result2)) {
				$data2 = dbarray($result2);
				$news_cat_image = "<a href='news_cats.php?cat_id=".$data2['news_cat_id']."'><img src='".IMAGES_NC.$data2['news_cat_image']."' alt='".$data2['news_cat_name']."' align='left' style='border:0px;margin-right:5px'></a>";
			}
		}
		$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
		if ($data['news_breaks'] == "y") { $news_news = nl2br($news_news); }
		if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
		$news_info = array(
			"news_id" => $data['news_id'],
			"user_id" => $data['user_id'],
			"user_name" => $data['user_name'],
			"news_date" => $data['news_datestamp'],
			"news_ext" => "n",
			"news_reads" => $data['news_reads'],
			"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
			"news_allow_comments" => $data['news_allow_comments']
		);
		render_news($news_subject, $news_news, $news_info);
		if ($data['news_allow_comments']) showcomments("N","news","news_id",$readmore,FUSION_SELF."?readmore=$readmore");
		if ($data['news_allow_ratings']) showratings("N",$readmore,FUSION_SELF."?readmore=$readmore");
	} else {
		redirect(FUSION_SELF);
	}
}

require_once "side_right.php";
require_once "footer.php";
?>