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
+---------------------------------------------+
| Rating System coded by CrappoMan            |
| email: simonpatterson@dsl.pipex.com         |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }
include FUSION_LANGUAGES.FUSION_LAN."ratings.php";

function showratings($rating_type,$rating_item_id,$rating_link) {

	global $fusion_prefix,$settings,$userdata;
	$settings['rating_system']=1;
	
	if (isset($_POST['post_rating'])) {
		if ($_POST['rating'] > 0) {
			$result = dbquery("INSERT INTO ".$fusion_prefix."ratings VALUES('', '$rating_item_id', '$rating_type', '".$userdata['user_id']."', '".$_POST['rating']."', '".time()."', '".FUSION_IP."')");
		}
		header("Location: ".$rating_link);
	} elseif (isset($_POST['remove_rating'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."ratings WHERE rating_item_id='$rating_item_id' AND rating_type='$rating_type' AND rating_user='".$userdata['user_id']."'");
		header("Location: ".$rating_link);
	}
	
	$ratings=array(5=> RATING_120, 4=> RATING_121, 3=> RATING_122, 2=> RATING_123, 1=> RATING_124);
	
	if( $settings['rating_system']=="1") {
		tablebreak();
		opentable(RATING_100);
		$d_rating=dbarray(dbquery("SELECT rating_vote,rating_datestamp FROM ".$fusion_prefix."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."' AND rating_user='".$userdata['user_id']."'"));
		if (!iMEMBER) {
			echo "<div align='center'>".RATING_104."</div>\n";
		} elseif ($d_rating['rating_vote']>0) {
			echo "<form name='removerating' method='post' action='".$rating_link."'>
<div align='center'>".sprintf(RATING_105, $ratings[$d_rating['rating_vote']], showdate("longdate", $d_rating['rating_datestamp']))."<br><br>
<input type='submit' name='remove_rating' value='".RATING_102."' class='button'></div>
</form>";
		}else{
			echo "<form name='postrating' method='post' action='".$rating_link."'>
<div align='center'>".RATING_106.": <select name='rating' class='textbox'>
<option value='0'>".RATING_107."</option>\n";
			foreach($ratings as $rating=>$rating_info) {
				echo "<option value='".$rating."'>$rating_info</option>\n";
			}
			echo "</select>\n";
			echo "<input type='submit' name='post_rating' value='".RATING_103."' class='button'>
</form>\n";
		}
		echo "<hr>";
		$tot_votes = dbresult(dbquery("SELECT COUNT(rating_item_id) FROM ".$fusion_prefix."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."'"),0);
		if($tot_votes){
			echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td>
<table align='center' cellpadding='0' cellspacing='0'>\n";
			foreach($ratings as $rating=>$rating_info) {
				$num_votes = dbresult(dbquery("SELECT COUNT(rating_item_id) FROM ".$fusion_prefix."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."' AND rating_vote='".$rating."'"),0);
				$pct_rating = number_format(100 / $tot_votes * $num_votes);
				if ($num_votes == 0) {
					$votecount = "[".RATING_108."]";
				} elseif ($num_votes == 1) {
					$votecount = "[1 ".RATING_109."]";
				} else {
					$votecount = "[".$num_votes." ".RATING_110."]";
				}
				$class = ($rating % 2==0?"tbl1":"tbl2");
				echo "<tr>
<td class='$class'>$rating_info</td>
<td width='250' class='$class'><img src='".FUSION_THEME."images/pollbar.gif' height='12' width='".$pct_rating."%' class='poll'></td>
<td class='$class'>".$pct_rating."%</td>
<td class='$class'>$votecount</td>
</tr>\n";
			}
			echo "</td>\n</table>\n</td>\n</tr>\n</table>";
		}else{
			echo "<div align='center'>".RATING_101."</div>\n";
		}
		closetable();
	}
}
?>