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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";

if (!isset($readmore)) {
	$rows = dbcount("(news_id)", "news", "(news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	if (!isset($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$i = 1;
		$result = dbquery(
			"SELECT tn.*, user_id, user_name FROM ".$fusion_prefix."news tn
			LEFT JOIN ".$fusion_prefix."users tu ON tn.news_name=tu.user_id
			WHERE (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")
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
				"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'")
			);
			render_news($news_subject, $news_news, $news_info);
			if ($i != $numrows) { tablebreak(); } $i++;
		}
		echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3)."\n</div>\n";
	} else {
		opentable(LAN_46);
		echo "<center><br>\n".LAN_47."<br><br>\n</center>\n";
		closetable();
	}
} else {
	if (!isNum($readmore)) { header("Location:".$PHP_SELF); exit; }
	include FUSION_INCLUDES."comments_include.php";
	include FUSION_INCLUDES."ratings_include.php";
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$fusion_prefix."news tn
		LEFT JOIN ".$fusion_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$readmore'"
	);
	if (dbrows($result)!=0) {
		$data = dbarray($result);
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
			"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'")
		);
		render_news($news_subject, $news_news, $news_info);
		showcomments("N","news","news_id",$readmore,"$PHP_SELF?readmore=$readmore");
		showratings("N",$readmore,"$PHP_SELF?readmore=$readmore");
		if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
			 $result2 = dbquery("UPDATE ".$fusion_prefix."news SET news_reads=news_reads+1 WHERE news_id='$readmore'");
		}
	} else {
		header("Location:".$PHP_SELF);
	}
}

include "side_right.php";
include "footer.php";
?>