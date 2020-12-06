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

if (!isset($readmore)) {
	$rows = dbcount("(news_id)", "news", groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$i = 1;
		$result = dbquery(
			"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
			LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")
			ORDER BY news_datestamp DESC LIMIT $rowstart,10"
		);		
		$numrows = dbrows($result);
		while ($data = dbarray($result)) {
			$news_subject = "<a name='".$data['news_id']."'></a>".stripslashes($data['news_subject']);
			$news_news = stripslashes($data['news_news']);
			if ($data['news_breaks'] == "y") $news_news = nl2br($news_news);
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'], 
				"news_ext" => $data['news_extended'] ? "y" : "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
			render_news($news_subject, $news_news, $news_info);
			if ($i != $numrows) { tablebreak(); } $i++;
		}
		echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3)."\n</div>\n";
	} else {
		opentable($locale['046']);
		echo "<center><br>\n".$locale['047']."<br><br>\n</center>\n";
		closetable();
	}
} else {
	if (!isNum($readmore)) fallback(FUSION_SELF);
	include INCLUDES."comments_include.php";
	include INCLUDES."ratings_include.php";
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$readmore'"
	);
	if (dbrows($result)!=0) {
		$data = dbarray($result);
		if (checkgroup($data['news_visibility'])) {
			if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
				 $result2 = dbquery("UPDATE ".$db_prefix."news SET news_reads=news_reads+1 WHERE news_id='$readmore'");
				 $data['news_reads']++;
			}
			$news_subject = $data['news_subject'];
			$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
			if ($data['news_breaks'] == "y") { $news_news = nl2br($news_news); }
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
	} else {
		redirect(FUSION_SELF);
	}
}

require_once "side_right.php";
require_once "footer.php";
?>