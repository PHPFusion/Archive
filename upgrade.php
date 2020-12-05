<?
require "header.php";
require fusion_basedir."subheader.php";

require "navigation.php";
echo "</td>
<td width=\"10\"></td>
<td valign=\"top\">\n";
tablebreak();
//if ($settings[version] < "300" && $userdata[user_mod] == "Administrator") {
//	$access = "yes";
//} else if ($settings[version] >= "300" && $userdata[user_mod] < "3" && $userdata != "") {
//	$access = "yes";
//}
//if ($access == "yes") {
//	opentable("PHP-Fusion X2 Upgrade");
//	if (empty($stage) || $stage == "") {
//		if ($settings[version] < 300) {
//			echo "<form name=\"upgradeform\" method=\"post\" action=\"$PHP_SELF?stage=2\">
//<center>
//A database upgrade is available for this installation of PHP-Fusion X2.<br>
//It is recommended that you back-up your database prior to completing this process.<br>
//<br>
//The following changes will be applied:<br>
//<br>
//· attachments, attachmax, attachtypes, displaypoll & numofshouts columns will be added to settings table.<br>
//· user_hide_email & user_avatar columns will be added to users table.<br>
//· user_mod columns in users table will be converted to numerical values.<br>
//· version number will be set to 300.<br>
//<br>
//<input type=\"submit\" name=\"upgrade\" value=\"Upgrade\" class=\"button\" style=\"width:100px\">
//</center>
//</form>\n";
//		} else {
//			echo "<center>There is no upgrade available for this installation of PHP-Fusion X2.</center>\n";
//		}
//	}
//	if ($stage == 2) {
//		if (isset($_POST['upgrade'])) {
//			$result = dbquery("CREATE TABLE ".$x2_prefix."forum_attachments (
//			attach_id smallint(5) unsigned NOT NULL auto_increment,
//			thread_id smallint(5) unsigned NOT NULL default '0',
//			post_id smallint(5) unsigned NOT NULL default '0',
//			attach_name varchar(100) NOT NULL default '',
//			attach_ext varchar(5) NOT NULL default '',
//			attach_size int(20) unsigned NOT NULL default '0',
//			PRIMARY KEY (attach_id)
//			) TYPE=MyISAM;");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."settings ADD attachments tinyint(1) unsigned default '0' NOT NULL AFTER numofthreads");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."settings ADD attachmax int(12) unsigned default '150000' NOT NULL AFTER attachments");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."settings ADD attachtypes varchar(150) default '.gif,.jpg,.png,.zip,.rar,.tar' NOT NULL AFTER attachmax");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."settings ADD displaypoll tinyint(1) unsigned default '0' NOT NULL AFTER guestposts");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."settings ADD numofshouts tinyint(1) unsigned default '0' NOT NULL AFTER displaypoll");
//			$result = dbquery("UPDATE ".$x2_prefix."users SET user_mod='1' WHERE user_id='1'");
//			$result = dbquery("UPDATE ".$x2_prefix."users SET user_mod='2' WHERE user_id!='1' AND user_mod='Administrator'");
//			$result = dbquery("UPDATE ".$x2_prefix."users SET user_mod='3' WHERE user_mod='Moderator'");
//			$result = dbquery("UPDATE ".$x2_prefix."users SET user_mod='4' WHERE user_mod='Member'");
//			$result = dbquery("ALTER TABLE users CHANGE user_mod user_mod tinyint(2) unsigned default '4' NOT NULL");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."users ADD user_hide_email tinyint(1) unsigned default '1' NOT NULL AFTER user_email");
//			$result = dbquery("ALTER TABLE ".$x2_prefix."users ADD user_avatar varchar(100) default '' NOT NULL AFTER user_web");
//			$result = dbquery("UPDATE ".$x2_prefix."settings SET version='300'");
//			echo "Upgrade Complete\n";
//		}
//	}
//	closetable();
//}
$result = dbquery("UPDATE ".$fusion_prefix."users SET user_mod='0' WHERE user_mod='4'");
$result = dbquery("UPDATE ".$fusion_prefix."users SET user_mod='4' WHERE user_mod='1'");
$result = dbquery("UPDATE ".$fusion_prefix."users SET user_mod='1' WHERE user_mod='3'");
$result = dbquery("UPDATE ".$fusion_prefix."users SET user_mod='3' WHERE user_mod='4'");
tablebreak();
echo "</td>
<td width=\"10\"></td>
</tr>
</table>\n";

require "footer.php";
?>