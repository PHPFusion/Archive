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
include FUSION_LANGUAGES.FUSION_LAN."search.php";
include "side_left.php";

opentable(LAN_400." ".$settings['sitename']);
if ($stype == "") $stype = "n";
echo "<form name='searchform' method='post' action='$PHP_SELF'>
<center>
".LAN_401." <input type='text' name='stext' value='".stripinput($stext)."' class='textbox' style='width:200px'>
<input type='submit' name='search' value='".LAN_400."' class='button'><br>
".LAN_402." <input type='radio' name='stype' value='n'".($stype == "n" ? " checked" : "")."> ".LAN_410."
<input type='radio' name='stype' value='a'".($stype == "a" ? " checked" : "")."> ".LAN_411."
<input type='radio' name='stype' value='fp'".($stype == "fp" ? " checked" : "")."> ".LAN_412."
</center>
</form>\n";
closetable();

if (isset($stext)) {
	tablebreak();
	if ($stext != "" && strlen($stext) >= 3) {
		$query = stripinput($stext);
		if ($stype == "n") {
			opentable(LAN_403);
			$sql = dbquery("SELECT * FROM ".$fusion_prefix."news WHERE news_subject LIKE '%".$query."%' OR news_news LIKE '%".$query."%'");
			$rows = dbrows($sql);
			if (!isset($rowstart)) $rowstart = 0;
			if ($rows != 0) {
				$sql = dbquery("SELECT * FROM ".$fusion_prefix."news WHERE news_subject LIKE '%".$query."%' OR news_news LIKE '%".$query."%' ORDER BY news_datestamp DESC LIMIT $rowstart,10");
				while ($data = dbarray($sql)) {
					$numrows = dbcount("(news_id)", "news", "news_id>='".$data['news_id']."'");
					if ($numrows > 10) {
						$rstart = ceil($numrows / 10);
						$rstart = "?rowstart=".(($rstart-1)*10);
					} else {
						$rstart = "";
					}
					if (eregi($query, $data['news_subject']) && eregi($query, $data['news_news'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='news.php".$rstart."#".$data['news_id']."'>".parsesearch($data['news_subject'],$query)."</a><br>\n";
						echo parsesearch(stripslashes($data['news_news']),$query)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_423.LAN_421.LAN_424.LAN_422.showDate("longdate", $data['news_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['news_subject']) && eregi($query, $data['news_extended'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='news.php?readmore=".$data['news_id']."'>".parsesearch($data['news_subject'],$query)."</a><br>\n";
						echo parsesearch(stripslashes($data['news_extended']),$query)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_423.LAN_421.LAN_425.LAN_422.showDate("longdate", $data['news_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['news_subject'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='news.php".$rstart."#".$data['news_id']."'>".parsesearch($data['news_subject'],$query)."</a><br>\n";
						echo trimlink(stripslashes($data['news_news']),100)."<br>\n";
						echo "<span class='small2'>".LAN_420.LAN_423.LAN_422.showDate("longdate", $data['news_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['news_news'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='news.php".$rstart."#".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
						echo parsesearch(stripslashes($data['news_news']),$query)."<br>\n";
						echo "<span class='small2'>".LAN_420.LAN_424.LAN_422.showDate("longdate", $data['news_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['news_extended'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='news.php?readmore=".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
						echo parsesearch(stripslashes($data['news_extended']),$query)."<br>\n";
						echo "<span class='small2'>".LAN_420.LAN_425.LAN_422.showDate("longdate", $data['news_datestamp']).".</span><br><br>\n";
					}
				}
			} else {
				echo "<center><br>\n".LAN_440."<br><br>\n</center>\n";
			}
			closetable();
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,"$PHP_SELF?stype=n&stext=$stext&")."\n</div>\n";
		} else if ($stype == "a") {
			opentable(LAN_403);
			$sql = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_subject LIKE '%".$query."%' OR article_article LIKE '%".$query."%'");
			$rows = dbrows($sql);
			if (!isset($rowstart)) $rowstart = 0;
			if ($rows != 0) {
				$sql = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_subject LIKE '%".$query."%' OR article_article LIKE '%".$query."%' ORDER BY article_datestamp DESC LIMIT $rowstart,10");
				while ($data = dbarray($sql)) {
					if (eregi($query, $data['article_subject']) && eregi($query, $data['article_article'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='readarticle.php?article_id=".$data['article_id']."'>".parsesearch($data['article_subject'],$query)."</a><br>\n";
						echo parsesearch(stripslashes($data['article_article']),$query)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_426.LAN_421.LAN_427.LAN_422.showDate("longdate", $data['article_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['article_subject'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='readarticle.php?article_id=".$data['article_id']."'>".parsesearch($data['article_subject'],$query)."</a><br>\n";
						echo trimlink(stripslashes($data['article_article']),100)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_426.LAN_422.showDate("longdate", $data['article_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['article_article'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a><br>\n";
						echo parsesearch(stripslashes($data['article_article']),$query)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_427.LAN_422.showDate("longdate", $data['article_datestamp']).".</span><br><br>\n";
					}
				}
			} else {
				echo "<center><br>\n".LAN_440."<br><br>\n</center>\n";
			}
			closetable();
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,"$PHP_SELF?stype=a&stext=$stext&")."\n</div>\n";
		} else if ($stype == "fp") {
			opentable(LAN_403);
			$sql = dbquery(
				"SELECT tp.*, tf.* FROM ".$fusion_prefix."posts tp
				INNER JOIN ".$fusion_prefix."forums tf USING(forum_id)
				WHERE forum_access<=".iUSER." AND post_subject LIKE '%".$query."%' OR post_message LIKE '%".$query."%'"
			);
			$rows = dbrows($sql);
			if (!isset($rowstart)) $rowstart = 0;
			if ($rows != 0) {
				$sql = dbquery(
					"SELECT tp.*, tf.* FROM ".$fusion_prefix."posts tp
					INNER JOIN ".$fusion_prefix."forums tf USING(forum_id)
					WHERE forum_access<=".iUSER." AND post_subject LIKE '%".$query."%' OR post_message LIKE '%".$query."%'
					ORDER BY post_datestamp DESC LIMIT $rowstart,10"
				);
				while ($data = dbarray($sql)) {
					$numrows = dbcount("(post_id)", "posts", "thread_id='".$data['thread_id']."' AND post_id<'".$data['post_id']."'");
					if ($numrows > 20) {
						$rstart = ceil($numrows / 20);
						$rstart = "rowstart=".(($rstart-1)*20)."&";
					} else {
						$rstart = "";
					}
					if (eregi($query, $data['post_subject']) && eregi($query, $data['post_message'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data['post_id']."'>".parsesearch($data['post_subject'],$query)."</a><br>\n";
						echo parsesearch(stripslashes($data['post_message']),$query)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_428.LAN_421.LAN_429.LAN_422.showDate("longdate", $data['post_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['post_subject'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data['post_id']."'>".parsesearch($data['post_subject'],$query)."</a><br>\n";
						echo trimlink(stripslashes($data['post_message']),100)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_428.LAN_422.showDate("longdate", $data['post_datestamp']).".</span><br><br>\n";
					} else if (eregi($query, $data['post_message'])) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_FORUM."viewthread.php?".$rstart."forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."#".$data['post_id']."'>".$data['post_subject']."</a><br>\n";
						echo parsesearch(stripslashes($data['post_message']),$query)."<br>\n";
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <span class='small2'>".LAN_420.LAN_429.LAN_422.showDate("longdate", $data['post_datestamp']).".</span><br><br>\n";
					}
				}
			} else {
				echo "<center><br>\n".LAN_440."<br><br>\n</center>\n";
			}
			closetable();
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,"$PHP_SELF?stype=fp&stext=$stext&")."\n</div>\n";
		}
	} else {
		opentable(LAN_403);
		echo "<center><br>\n".LAN_441."<br><br>\n</center>\n";
		closetable();
	}
}

function parsesearch($text,$match) {
	$text = strip_tags($text);
	$aft = stristr($text,$match);
	$pos = strlen($text)-strlen($aft);
	if ($pos < 50) { $text = "..".substr($text, 0, 100).".."; } else { $text = "..".substr($text, ($pos-50), 100).".."; }
	$text = eregi_replace($match, "<span style='font-weight:bold;color:#000;background-color:#eeee00;'>".$match."</span>", $text);
	return $text;
}

echo "</td>\n";

include "side_right.php";
include "footer.php";
?>