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
+----------------------------------------------------+
| Private Message system developed by CrappoMan
| email: simonpatterson@dsl.pipex.com
+----------------------------------------------------*/
require_once "maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
require_once INCLUDES."sendmail_include.php";

include LOCALE.LOCALESET."messages.php";

$settings['privmsg_subject_tooltip']=1;	// use subject tooltip to show message preview
$userdata['user_pm_ban']=0; // set to 1 to ban user from pm'ing. Used if adding new field 'user_pm_ban' to to 'xxx_users' table
$itemsperpage=20; // number of messages to display per page

function builduserclassoptionlist($selected_user_class=1,$restricted=false){
	global $locale;
	$user_class_option_list="";
	$user_class_option_list .= "<optgroup label='".$locale['442']."'>\n";
	$levels=array(101=>$locale['user1'],$locale['user2'],$locale['user3']);
	foreach($levels as $level=>$modlevel){
		if($level==$selected_user_class) {$sel=" selected";}else{$sel="";}
		if($restricted) {$userlevel=iUSER;}else{$userlevel=max(array_keys($levels));}
		if($level<=$userlevel) $user_class_option_list.="<option $sel value='".$level."'>".$modlevel."</option>\n";
	}

	$groups_qry = dbquery("SELECT group_id,group_name FROM ".DB_PREFIX."user_groups");
	if (dbrows($groups_qry) != 0) {
	$user_class_option_list .= "<optgroup label='".$locale['443']."'>\n";
		while ($user_groups = dbarray($groups_qry)) {
			$sel = ($user_groups['group_id'] == $selected_user_id ? " selected" : "");
			$user_class_option_list.="<option ".$sel." value='g-".$user_groups['group_id']."'>".$user_groups['group_name']."</option>\n";				
		}
	}
	
	return $user_class_option_list;
}
function builduseroptionlist($selected_user_id=1){
	global $locale;
	$user_option_list="";
	$levels = array(
		0 => array($locale['user3'], "103"),
		1 => array($locale['user2'], "102"),
		2 => array($locale['user1'], "101")
	);
	while(list($key, $user_level) = each($levels)) {
 		$uresult = dbquery("SELECT * FROM ".DB_PREFIX."users WHERE user_level='".$user_level['1']."' ORDER BY user_name");
		if (dbrows($uresult) > 0) {
			$user_option_list .= "<optgroup label='".$user_level['0']."'>\n";
			while($udata=dbarray($uresult)) {
				$sel = ($udata['user_id'] == $selected_user_id ? " selected" : "");
				$user_option_list.="<option ".$sel." value='".$udata['user_id']."'>".$udata['user_name']."</option>\n";
			}
			$user_option_list.="</optgroup>\n";
		}
	}

	return $user_option_list;
}
function displayMessagePreview($prev_subject,$prev_message,$site_broadcast=false){
	global $locale,$settings,$userdata;
	opentable($locale['438']);
	if(isset($_POST['chk_sitebroadcast'])){
		$prev_recipient=$locale['408'];
	}else{
		$prev_recipient="<a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."' title='".$locale['506']."'>".$userdata['user_name']."</a>";
	}
	$prev_msgdate=strftime($settings['longdate'], time()+($settings['timeoffset']*3600));
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td>\n
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr><td align='right' class='tbl2' width='1%'>".$locale['406'].":</td><td class='tbl1'>".$prev_recipient."</td></tr>\n
<tr><td align='right' class='tbl2'>".$locale['407'].":</td><td class='tbl1'>".$prev_msgdate."</td></tr>\n
<tr><td align='right' class='tbl2'>".$locale['405'].":</td><td class='tbl1'>".$prev_subject."</td></tr>\n
<tr><td class='tbl1' colspan='2'>".$prev_message."</td></tr>\n
</table>\n</td>\n</tr>\n</table>\n";
	closetable();
	tablebreak();
}
$msg_folders=array(
	"inbox"	 => 0,
	"sentbox" => 1,
	"savebox" => 2,
	"options" => 3
);
$folder_status=array(
	array("<b>","</b>","","","","","",""),
	array("","","<b>","</b>","","","",""),
	array("","","","","<b>","</b>","",""),
	array("","","","","","","<b>","</b>")
);

// Save user options
if(isset($saveoptions)) {
	if ($_POST['update_type']=='new') {
		dbquery("INSERT INTO ".$db_prefix."messages_options ( user_id, pm_email_notify, pm_save_sent )
			VALUES ( 
			'".$userdata['user_id']."',
			'".$_POST['pm_email_notify']."',
			'".$_POST['pm_save_sent']."')"
		);

	} else {
		dbquery("UPDATE ".$db_prefix."messages_options SET 
			pm_email_notify='".$_POST['pm_email_notify']."',
			pm_save_sent='".$_POST['pm_save_sent']."'
			WHERE user_id='".$userdata['user_id']."'"
		);
	}

	$message = $locale['624'];
}

if(!isset($folder)) $folder="inbox";
if(!array_key_exists($folder,$msg_folders)) $folder="inbox";
$folder_stat=$folder_status[$msg_folders[$folder]];
$result_where_message_folder="message_folder=".$msg_folders[$folder];
if (isset($msg_view)) {
	if (!isNum($msg_view)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_view;
} elseif (isset($msg_reply)) {
	if (!isNum($msg_reply)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_reply;
} elseif (isset($_POST['reply_preview'])) {
	if (!isNum($msg_reply_preview)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_reply_preview;
	$msg_reply = $msg_reply_preview;
} elseif (isset($msg_setread)) {
	if (!isNum($msg_setread)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_setread;
} elseif (isset($msg_setunread)) {
	if (!isNum($msg_setunread)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_setunread;
} elseif (isset($msg_save)) {
	if (!isNum($msg_save)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_save;
	$cnt_messages = '1';
} elseif (isset($msg_unsave)) {
	if (!isNum($msg_unsave)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_unsave;
	$cnt_messages = '1';
} elseif (isset($msg_delete)) {
	if (!isNum($msg_delete)) fallback("messages.php");
	$result_where_message_id="message_id=".$msg_delete;
} elseif (isset($chk_mark)) {
	if(is_array($chk_mark) && count($chk_mark)>1){
		// Count how many elements (messages) in array
		$cnt_messages = count($chk_mark);
		$result_where_message_id="message_id IN(".implode(',',$chk_mark).")";
	}else{
		$cnt_messages = '1';
		$result_where_message_id="message_id=".$chk_mark[0];
	}
}

$result_where_message_to="message_to=".$userdata['user_id'];	// a double check to make sure the script only operates on the current users messages

$sender_id=(isset($_POST['chk_sitebroadcast'])?0:$userdata['user_id']);

if(isset($_POST['send_preview'])||isset($_POST['reply_preview'])){
	if(isset($_POST['chk_showsig'])) $checked_sig=" checked";
	if(isset($_POST['chk_disablesmileys'])) $checked_smileys=" checked";
	if(isset($_POST['chk_sendtoall'])) $checked_sendtoall=" checked";
	if(isset($_POST['chk_sitebroadcast'])) $checked_sitebroadcast=" checked";
	$prev_subject=stripinput($_POST['subject']);
	$prev_message=stripinput($_POST['message']);
	if($checked_sig) $prev_message=$prev_message."\n\n".$userdata['user_sig'];
	if(!$checked_smileys) $prev_message=parsesmileys($prev_message);
	$prev_message=nl2br(parseubb($prev_message));
	// $msg_send=$msg_to;
}

if(iGUEST){ // not logged in - display "iMEMBERs only"
	opentable($locale['400']);
	echo "<center><br />".$locale['483']."<br /><br /></center>\n";
}elseif($userdata['user_pm_ban']==1){ // user is banned from pm'ing
	opentable($locale['400']);
	echo "<center><br />".$locale['484']."<br /><br /></center>\n";
}elseif(isset($_POST['btn_cancel'])){ // handle cancel button
	redirect(FUSION_SELF."?folder=".$folder);
}elseif(isset($_POST['btn_setread']) || isset($msg_setread)){ // set message as read
	dbquery("UPDATE ".$db_prefix."messages SET message_read=1 WHERE ".$result_where_message_id." AND ".$result_where_message_to);
	redirect(FUSION_SELF."?folder=".$folder);
}elseif(isset($_POST['btn_setunread']) || isset($msg_setunread)){ // set message as un-read
	dbquery("UPDATE ".$db_prefix."messages SET message_read=0 WHERE ".$result_where_message_id." AND ".$result_where_message_to);
	redirect(FUSION_SELF."?folder=".$folder);
}elseif(isset($_POST['btn_save']) || isset($msg_save)){	// move message to 'savebox' folder
	// Count how many messages in archive/savebox
	$cnt_savebox_qry = dbquery("SELECT COUNT(message_id) cnt_savebox FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_folder='2' GROUP BY message_to");
	if (dbrows($cnt_savebox_qry) != 0) { $cnt_savebox = dbresult($cnt_savebox_qry,0); }
	// Get limit
	$limit_savebox=dbresult(dbquery("SELECT pm_savebox FROM ".$db_prefix."messages_options WHERE user_id='0'"),0);
	// Check if current + new messages is > limit
	if ($limit_savebox != '0' && ($cnt_messages + $cnt_savebox) > $limit_savebox) { $error = $locale['629']; }

	if (isset($error)) {
		opentable($locale['627']);
		echo "<div style='text-align:center;'><br />".$error."<br /><br /></div>";
	} else {
		dbquery("UPDATE ".$db_prefix."messages SET message_folder=2 WHERE ".$result_where_message_id." AND ".$result_where_message_to);
		redirect(FUSION_SELF."?folder=".$folder);
	}
}elseif(isset($_POST['btn_unsave']) || isset($msg_unsave)){ // move message to 'inbox' folder
	// Count how many messages in inbox
	$cnt_inbox_qry = dbquery("SELECT COUNT(message_id) cnt_inbox FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_folder='0' GROUP BY message_to");
	if (dbrows($cnt_inbox_qry) != 0) { $cnt_inbox = dbresult($cnt_inbox_qry,0); }
	// Get limit
	$limit_inbox=dbresult(dbquery("SELECT pm_inbox FROM ".$db_prefix."messages_options WHERE user_id='0'"),0);
	// Check if current + new messages is > limit
	if ( $limit_inbox != '0' && ($cnt_messages + $cnt_inbox) > $limit_inbox) { $error = $locale['629']; }
	
	if (isset($error)) {
		opentable($locale['627']);
		echo "<div style='text-align:center;'><br />".$error."<br /><br /></div>";
	} else {
		dbquery("UPDATE ".$db_prefix."messages SET message_folder=0 WHERE ".$result_where_message_id." AND ".$result_where_message_to);
		redirect(FUSION_SELF."?folder=".$folder);
	}
}elseif(isset($_POST['btn_delete']) || isset($msg_delete)){ // delete message
	dbquery("DELETE FROM ".$db_prefix."messages WHERE ".$result_where_message_id." AND ".$result_where_message_to);
	redirect(FUSION_SELF."?folder=".$folder);
}elseif(isset($msg_view)){ // view message
$result=dbquery(
		"SELECT tm.*, user_id, user_name FROM ".$db_prefix."messages tm LEFT JOIN ".$db_prefix."users ".
		"ON message_from=user_id WHERE ".$result_where_message_id." AND ".$result_where_message_to
	);
	if(dbrows($result)==1){
		$data=dbarray($result);
		$subject=stripslashes($data['message_subject']);
		$message=parseubb(nl2br(stripslashes($data['message_message'])));
		if($data['message_smileys']=="y") $message=parsesmileys($message);
		$msgdate=strftime($settings['longdate'], $data['message_datestamp']+($settings['timeoffset']*3600));
		if($data['message_read']==0){
			$result=dbquery("UPDATE ".$db_prefix."messages SET message_read='1' WHERE ".$result_where_message_id." AND ".$result_where_message_to);
		}
		$msg_fld_name=array_search($data['message_folder'],$msg_folders);
		opentable($locale['431']." (".$msg_fld_name.")");
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr><td align='right' class='tbl2' width='1%'>".($data['message_folder']==1?$locale['421']:$locale['406']).":</td><td class='tbl1'>";
		if($data['message_from']==0){
			echo $locale['408'];
		}else{
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' title='".$locale['506']."'>".$data['user_name']."</a>";
		}
		echo "</td></tr>
<tr><td align='right' class='tbl2'>".($data['message_folder']==1?$locale['426']:$locale['407']).":</td><td class='tbl1'>".$msgdate."</td></tr>
<tr><td align='right' class='tbl2'>".$locale['405'].":</td><td class='tbl1'>".$subject."</td></tr>
<tr><td class='tbl1' colspan='2'>".$message."</td></tr>
</table>\n</td>\n</tr>\n</table>\n
<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n<tr>
<td><input type='button' class='button' value='".$locale['432']."' onclick=\"location.href='".FUSION_SELF."?folder=".$msg_fld_name."'\"></td>
<td align='right'>\n";
		if($data['message_folder']!=1&&$data['user_id']!=0){
			echo "<input type='button' class='button' value='".$locale['433']."' onclick=\"location.href='".FUSION_SELF."?msg_reply=".$msg_view."'\"> ";
		}
		if($data['message_folder']==2){
			echo "<input type='button' class='button' value='".$locale['413']."' onclick=\"location.href='".FUSION_SELF."?folder=".$msg_fld_name."&msg_unsave=".$msg_view."'\"> ";
		}else{
			echo "<input type='button' class='button' value='".$locale['412']."' onclick=\"location.href='".FUSION_SELF."?folder=".$msg_fld_name."&msg_save=".$msg_view."'\"> ";
		}
		echo "<input type='button' class='button' value='".$locale['415']."' onclick=\"location.href='".FUSION_SELF."?folder=".$msg_fld_name."&msg_setunread=".$msg_view."'\">
<input type='button' class='button' value='".$locale['416']."' onclick=\"location.href='".FUSION_SELF."?folder=".$msg_fld_name."&msg_delete=".$msg_view."'\">
</td>\n</tr>\n</table>\n";
	}else{
opentable($locale['480']);
		echo "<center><br />".$locale['481']."<br /><br /></center>\n";
	}
}elseif(isset($msg_send)||isset($_POST['send_preview'])){ // write message
	if($msg_send<>"" && (((int)$msg_send)==$msg_send)){
		$type = 'user';
		$uresult=dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id='".$msg_send."'");
		$rows=dbrows($uresult);
		if($rows==1) $data=dbarray($uresult);
	}else{
		$rows=1;
	}
	if($rows==1){
		if(isset($_POST['send_preview'])) displayMessagePreview($prev_subject,$prev_message);
		opentable($locale['420']);
		echo "<script language='Javascript'>function ValidateForm(frm){if((frm.subject.value=='')||(frm.message.value=='')){alert('".$locale['486']."');return false;}else return true;}</script>
<form name='inputform' method='post' action='".FUSION_SELF."' onSubmit=\"return ValidateForm(this)\">
<input type='hidden' name='folder' value='".$folder."'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td>\n
<table border='0' cellpadding='0' cellspacing='1' width='100%'>\n
<tr><td align='right' class='tbl2' width='1%'><nobr>".$locale['421'].":</nobr></td><td class='tbl1'>";
		if(($msg_send<>"")&&!isset($prev_message)){
			echo "<a href='".BASEDIR."profile.php?lookup=".$msg_send."' title='".$locale['506']."'>".$data['user_name']."</a><input type='hidden' name='msg_to' value='".$msg_send."'>";
		}else{
			echo "<select name='msg_to' class='textbox'>\n".builduseroptionlist($msg_to)."</select>";
			if(iSUPERADMIN){
				if (!isset($msg_to_class)) $msg_to_class = 1;
				echo "<input name='chk_sendtoall' type='checkbox' ".(isset($checked_sendtoall)?"selected":"")." onclick=\"javascript:msg_to.disabled=chk_sendtoall.checked;msg_to.value='';msg_to_class.disabled=!chk_sendtoall.checked;\">".$locale['434'].":
				<select name='msg_to_class' ".(isset($checked_sendtoall)?'':'disabled')." class='textbox'>".builduserclassoptionlist($msg_to_class,true)."</select>";
			}
		}
	 
		echo "</td></tr>
<tr><td align='right' class='tbl2'><nobr>".$locale['405'].":</nobr></td><td class='tbl1'><input type='text' name='subject' value=\"".(isset($subject) ? stripslashes($subject) : "")."\" maxlength='32' class='textbox' style='width:400px;'></td></tr>
<tr><td align='right' class='tbl2' valign='top'><nobr>".$locale['422'].":</nobr></td><td class='tbl1'><textarea name='message' cols='80' rows='15' class='textbox'>".(isset($message) ? stripslashes($message) : "")."</textarea></td>
</tr>
<tr><td align='right' class='tbl2' valign='top'></td>
<td class='tbl1'>
<table><tr><td>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\">
<br />".displaysmileys("message")."
<br /></td></tr></table></td></tr>
</td></tr>
<tr><td align='right' class='tbl2' valign='top'><nobr>".$locale['425'].":</nobr></td><td class='tbl1'>
<input type='checkbox' name='chk_disablesmileys' value='y'".(isset($checked_smileys) ? $checked_smileys : "")." />".$locale['427'];
if($userdata['user_sig']) echo "<br />\n<input type='checkbox' name='chk_showsig' value='sig' ".$checked_sig.">".$locale['428'];
if(iADMIN) echo "<br />\n<input type='checkbox' name='chk_sitebroadcast' value='y' ".(isset($checked_sitebroadcast) ? $checked_sitebroadcast : "").">".$locale['441'];
echo "</td></tr>\n</table>\n</table>\n
<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n<tr>
<td><input type='button' value='".$locale['435']."' class='button' onclick=\"location.href='".FUSION_SELF."?folder=".$folder."'\"></td>
<td align='right'>\n
<input type='submit' name='send_preview' value='".$locale['429']."' class='button'>
<input type='submit' name='send_message' value='".$locale['430']."' class='button'>
</td>\n</tr>\n</table>\n</form>\n";
	}else{
		opentable($locale['480']);
		echo "<center><br />".$locale['482']."<br /><br /></center>\n";
	}
}elseif(isset($_POST['send_message'])){	// send message
	$smileys=(isset($_POST['chk_disablesmileys'])?'n':'y');
	$subject=stripinput($_POST['subject']);
	$message=stripinput($_POST['message']);
	if(isset($_POST['chk_showsig'])) $message.="\n\n".$userdata['user_sig'];

	// Get PM folder restrictions
	// (room for future option to restrict number of pm's for a specific user)
	$limits=dbarray(dbquery("SELECT pm_inbox,pm_sentbox FROM ".$db_prefix."messages_options WHERE user_id='0'"));
	$limit_inbox=$limits['pm_inbox'];
	$limit_sentbox=$limits['pm_sentbox'];

	if(isset($_POST['chk_sendtoall'])){
		// Determine if Group or User Level
		if (strstr($msg_to_class, 'g-')) {
			$msg_to_class=str_replace('g-','',$msg_to_class);

			// Select all users from users table and loop through list
			$user_groups_qry = dbquery("SELECT user_id,user_groups FROM ".$db_prefix."users");
			while ($ugroups = dbarray($user_groups_qry)) {

				// Explode the array into a list of user group id's
				$user_groups = explode(".", $ugroups['user_groups']);

				// If there's a match, then send to that user
				if (in_array($msg_to_class, $user_groups)) {
					$uresult=dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id=".$ugroups['user_id']);
					while($udata=dbarray($uresult)){
						if($udata['user_id']!=$userdata['user_id']) $result=dbquery("INSERT INTO ".$db_prefix."messages VALUES('','".$udata['user_id']."','".$sender_id."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
						// Lookup receiving users options
						$options_qry = dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='".$udata['user_id']."'");
						if (dbrows($options_qry) != 0) { $options = dbarray($options_qry); } else { unset($options); }
						// Send notification email if user option is set
						if (isset($options) && $options['pm_email_notify'] == '1') {
							sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$udata['user_name'].$locale['626']);
						}
					}
				}
			}
		} else {
			$uresult=dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level='".$msg_to_class."' ORDER BY user_id");
			while($udata=dbarray($uresult)){
				if($udata['user_id']!=$userdata['user_id']) $result=dbquery("INSERT INTO ".$db_prefix."messages VALUES('','".$udata['user_id']."','".$sender_id."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
				// Lookup receiving users options
				$options_qry = dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='".$udata['user_id']."'");
				if (dbrows($options_qry) != 0) { $options = dbarray($options_qry); } else { unset($options); }
				// Send notification email if user option is set
				if (isset($options) && $options['pm_email_notify'] == '1') {
					sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$udata['user_name'].$locale['626']);
				}
			}
		}
		
	}elseif(isset($msg_to)&&(((int)$msg_to)==$msg_to)){
		
		// Count inbox usage
		$cnt_inbox_qry = dbquery("SELECT COUNT(message_id) cnt_inbox FROM ".$db_prefix."messages WHERE message_to='".$msg_to."' AND message_folder='0' GROUP BY message_to");
		if (dbrows($cnt_inbox_qry)!=0) { $cnt_inbox = dbresult($cnt_inbox_qry,0); } else { $cnt_inbox = 0; }
	
		// Check if user has reached inbox limit
		if ($limit_inbox == '0' || ($cnt_inbox < $limit_inbox)) {
			$result=dbquery("INSERT INTO ".$db_prefix."messages VALUES('','".$msg_to."','".$sender_id."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
		} else {
			$error = $locale['628'];
		}

		// Get user options
		$options_qry = dbquery("SELECT pm_email_notify FROM ".$db_prefix."messages_options WHERE user_id='".$msg_to."'");
		if (dbrows($options_qry) != 0) {
			$options = dbarray($options_qry); 
		} else { 
			 $options = dbarray(dbquery("SELECT pm_email_notify FROM ".$db_prefix."messages_options WHERE user_id='0'"));
		}

		// Send notification email if user option is set
		if ($options['pm_email_notify'] == '1') {
			$uresult=dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$msg_to."'");
			$udata=dbarray($uresult);
			sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$udata['user_name'].$locale['626']);
		}
	}

	// If there's an error, display error and don't save PM
	if (isset($error)) {
		opentable($locale['627']);
			echo "<div style='text-align:center;'><br />".$error."<br /><br /></div>";
	} else {
		// Lookup sending users options
		$options_qry = dbquery("SELECT pm_save_sent FROM ".$db_prefix."messages_options WHERE user_id='".$userdata['user_id']."'");
		if (dbrows($options_qry) != 0) { 
			$options = dbarray($options_qry);
		} else { 
			$options = dbarray(dbquery("SELECT pm_save_sent FROM ".$db_prefix."messages_options WHERE user_id='0'"));
		}

		if ($options['pm_save_sent'] == '1') {

			// Check how many messages in sentbox
			$cnt_sentbox_qry=dbquery("SELECT COUNT(message_id) cnt_sentbox FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_folder='1' GROUP BY message_to");
			if (dbrows($cnt_sentbox_qry)!=0) { $cnt_sentbox = dbresult($cnt_sentbox_qry,0); } else { $cnt_sentbox = 0; }
			
			// If equal to or greater than limit, delete difference (delete oldest sent messages)
			if ($limit_sentbox != '0' && $cnt_sentbox >= $limit_sentbox) {

				$difference = $cnt_sentbox - $limit_sentbox + 1;
				$del_sent_qry = dbquery("SELECT message_id FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_folder='1' ORDER BY message_id ASC LIMIT 0,".$difference);

				while ($del_pm = dbarray($del_sent_qry)) { dbquery("DELETE FROM ".$db_prefix."messages WHERE message_id='".$del_pm['message_id']."'");}
			}

			$result = dbquery("INSERT INTO ".$db_prefix."messages VALUES('','".$userdata['user_id']."','".$msg_to."','".$subject."','".$message."','".$smileys."','0','".time()."','1')"
			);
		}
		redirect(FUSION_SELF."?folder=".$folder);
	}
}elseif(isset($msg_reply)||isset($_POST['reply_preview'])){	// reply to message
	$result=dbquery(
		"SELECT * FROM ".$db_prefix."messages LEFT JOIN ".$db_prefix."users ".
		"ON message_from=user_id WHERE ".$result_where_message_id." AND ".$result_where_message_to
	);

	if(dbrows($result)==1){
		if(isset($_POST['reply_preview'])) displayMessagePreview($prev_subject,$prev_message);
		$data=dbarray($result);
		$recipient="<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' title='".$locale['506']."'>".$data['user_name']."</a>";
		$subject=stripslashes($data['message_subject']);
		if(!strstr($subject,"RE: ")) $subject="RE: ".$subject;
		$orig_message=parseubb(nl2br(stripslashes($data['message_message'])));
		if($data['message_smileys']=="y") $orig_message=parsesmileys($orig_message);
		$msgdate=strftime($settings['longdate'], $data['message_datestamp']+($settings['timeoffset']*3600));
		opentable($locale['439']);
		echo "<script language='Javascript'>function ValidateForm(frm){if((frm.subject.value=='')||(frm.message.value=='')){alert('".$locale['486']."');return false;}else return true;}</script>
<form name='inputform' method='post' action='".FUSION_SELF."' onSubmit=\"return ValidateForm(this)\">
<input type='hidden' name='folder' value='".$folder."'>
<input type='hidden' name='msg_to' value='".$data['user_id']."'>
<input type='hidden' name='msg_reply_preview' value='".$msg_reply."'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr><td align='right' class='tbl2' width='1%'><nobr>".$locale['421'].":</nobr></td><td class='tbl1'>".$recipient."</td></tr>
<tr><td align='right' class='tbl2'><nobr>".$locale['405'].":</nobr></td><td class='tbl1'><input type='text' name='subject' value=\"".stripslashes($subject)."\" maxlength='32' class='textbox' style='width:400px;'></td></tr>
<tr><td align='right' class='tbl2' valign='top'><nobr>".$locale['422'].":</nobr></td><td class='tbl1'>".$orig_message."
<br /><span class='small alt'>".sprintf($locale['440'], $msgdate)."</span></td></tr>
<tr><td align='right' class='tbl2' valign='top'><nobr>".(0?$locale['422']:$locale['433']).":</nobr></td><td class='tbl1'><textarea id='message' name='message' cols='80' rows='15' class='textbox'>".stripslashes($message)."</textarea></td></tr>
<tr><td align='right' class='tbl2' valign='top'></td>
<td class='tbl1'>
<table><tr><td>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\">
<br />".displaysmileys("message")."
<br /></td></tr></table></td></tr>";
		echo "<tr><td align='right' class='tbl2' valign='top'><nobr>".$locale['425'].":</nobr></td><td class='tbl1'>
<input type='checkbox' name='chk_disablesmileys' value='y'".$checked_smileys." />".$locale['427'];
		if($userdata[user_sig]) echo "<br />\n<input type='checkbox' name='chk_showsig' value='sig' ".$checked_sig.">".$locale['428'];
		echo "</td></tr>\n</table>\n</table>
<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n<tr>
<td><input type='button' value='".$locale['435']."' class='button' onclick=\"location.href='".FUSION_SELF."?folder=".$folder."'\"></td>
<td align='right'>
<input type='submit' name='reply_preview' value='".$locale['436']."' class='button'>
<input type='submit' name='reply_message' value='".$locale['437']."' class='button'>
</td>\n</tr>\n</table>\n</form>\n";
	}else{
		opentable($locale['480']);
		echo "<center><br />".$locale['481']."<br /><br /></center>\n";
	}
}elseif(isset($_POST['reply_message'])){ // send message
	$smileys=(isset($_POST['chk_disablesmileys'])?'n':'y');
	$subject=stripinput($_POST['subject']);
	$message=stripinput($_POST['message']);
	if(isset($_POST['chk_showsig'])) $message.="\n\n".$userdata['user_sig'];
	if(isset($msg_to)&&(((int)$msg_to)==$msg_to)){

		// Get message limits
		$limits=dbarray(dbquery("SELECT pm_inbox,pm_sentbox FROM ".$db_prefix."messages_options WHERE user_id='0'"));
		$limit_inbox=$limits['pm_inbox'];
		$limit_sentbox=$limits['pm_sentbox'];

		// Count inbox usage
		$cnt_inbox_qry = dbquery("SELECT COUNT(message_id) cnt_inbox FROM ".$db_prefix."messages WHERE message_to='".$msg_to."' AND message_folder='0' GROUP BY message_to");
		if (dbrows($cnt_inbox_qry)!=0) { $cnt_inbox = dbresult($cnt_inbox_qry,0); } else { $cnt_inbox = 0; }
	
		// Check if user has reached inbox limit
		if ($limit_inbox == '0' || ($cnt_inbox < $limit_inbox)) {
			$result=dbquery("INSERT INTO ".$db_prefix."messages VALUES('', '".$msg_to."', '".$userdata['user_id']."', '".$subject."', '".$message."', '".$smileys."', '0', '".time()."', 0)");
		} else {
			$error = $locale['628'];
		}

		// Get user options
		$options_qry = dbquery("SELECT pm_email_notify FROM ".$db_prefix."messages_options WHERE user_id='".$msg_to."'");
		if (dbrows($options_qry) != 0) {
			$options = dbarray($options_qry); 
		} else { 
			 $options = dbarray(dbquery("SELECT pm_email_notify FROM ".$db_prefix."messages_options WHERE user_id='0'"));
		}

		// Send notification email if user option is set
		if ($options['pm_email_notify'] == '1') {
			$uresult=dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$msg_to."'");
			$udata=dbarray($uresult);
			sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$udata['user_name'].$locale['626']);
		}

		// Lookup sending users options
		$options_qry = dbquery("SELECT pm_save_sent FROM ".$db_prefix."messages_options WHERE user_id='".$userdata['user_id']."'");
		if (dbrows($options_qry) != 0) { 
			$options = dbarray($options_qry);
		} else { 
			$options = dbarray(dbquery("SELECT pm_save_sent FROM ".$db_prefix."messages_options WHERE user_id='0'"));
		}

		if ($options['pm_save_sent'] == '1') {

			// Check how many messages in sentbox
			$cnt_sentbox_qry=dbquery("SELECT COUNT(message_id) cnt_sentbox FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_folder='1' GROUP BY message_to");
			if (dbrows($cnt_sentbox_qry)!=0) { $cnt_sentbox = dbresult($cnt_sentbox_qry,0); } else { $cnt_sentbox = 0; }
			
			// If equal to or greater than limit, delete difference (delete oldest sent messages)
			if ($limit_sentbox != '0' && $cnt_sentbox >= $limit_sentbox) {

				$difference = $cnt_sentbox - $limit_sentbox + 1;
				$del_sent_qry = dbquery("SELECT message_id FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_folder='1' ORDER BY message_id ASC LIMIT 0,".$difference);

				while ($del_pm = dbarray($del_sent_qry)) { dbquery("DELETE FROM ".$db_prefix."messages WHERE message_id='".$del_pm['message_id']."'");}
			}
		}
	
	}
		
	if (isset($error)) {
		opentable($locale['627']);
		echo "<div style='text-align:center;'><br />".$error."<br /><br /></div>";
	} else {
		if ($options['pm_save_sent'] == '1') {
			$result = dbquery("INSERT INTO ".$db_prefix."messages VALUES('','".$userdata['user_id']."','".$msg_to."','".$subject."','".$message."','".$smileys."','0','".time()."','1')");
		}
		redirect(FUSION_SELF."?folder=".$folder);
	}

} else {

	$srch_fields=array(
	//code=>array(add_slashes,'field name','display name');
		"s"=>array(1,'message_subject',$locale['462']),
		"m"=>array(1,'message_message',$locale['463']),
		"f"=>array(0,'user_name',$locale['464'])
	);
	$sort_fields=array(
	//code=>array('field name','display name');
		"d"=>array('message_datestamp DESC',$locale['465']),
		"f"=>array('message_from',$locale['464']),
		"s"=>array('message_subject',$locale['462'])
	);
	if(isset($_POST['srch_reset'])){
		unset($show,$srch_text,$srch_type,$sort_type);
	}elseif(isset($srch_text) && $srch_text<>"" && $srch_type<>""){
		if($srch_fields[$srch_type][0]){$srch_text2=stripinput($srch_text);}else{$srch_text2=$srch_text;}
		$srch_text2=str_replace(array('?','*'),array('_','%'),$srch_text2);
		$srch_where=" AND ".$srch_fields[$srch_type][1]." LIKE '$srch_text2'";
	}elseif(isset($show)){
		$show=strtolower($show);
		if($show!="all") $srch_where=" AND LEFT(user_name,1)='".$show."'";
	}else{
		$srch_text="";
		$srch_where="";
	}
	if(isset($sort_type) && $sort_type<>""){
		$sort_where=$sort_fields[$sort_type][0];
	}else{
		$sort_where="message_datestamp DESC";
	}
	if($srch_text!=""){
		$title=sprintf($locale['458'],$srch_text,$srch_fields[$srch_type][2]);
	}elseif(isset($show)){
		($show!="all"?$title=sprintf($locale['459'],$show):"");
	}else{
		$title="";
	}
	if(isset($sort_type)){
		$title.=sprintf($locale['460'],$sort_fields[$sort_type][1]);
	}
	if($title!=""){
		$title="<table border='0' cellpadding='0' cellspacing='0' width='100%'>
<tr><td colspan='3'><span class='small2'>".$title."</span></td></tr></table><br />";
	}

	opentable($locale['400']);
	$data=dbarray(dbquery(
		"SELECT COUNT(message_id) cnt_total, COUNT(IF(message_folder=0, 1, null)) cnt_inbox, ".
		"COUNT(IF(message_folder=1, 1, null)) cnt_sentbox, COUNT(IF(message_folder=2, 1, null)) cnt_savebox ".
		"FROM ".$db_prefix."messages WHERE ".$result_where_message_to." GROUP BY message_to"
	));

	// Get folder size restrictions (room for future option to restrict number of pm's for a specific user)
	$limit = dbarray(dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='0'"));

	if (isset($data['cnt_total'])) { $cnt_inbox=$data['cnt_total']; } else { $cnt_total='0'; }
	if (isset($data['cnt_inbox'])) { $cnt_inbox=$data['cnt_inbox']; } else { $cnt_inbox='0'; }
	if (isset($data['cnt_sentbox'])) { $cnt_sentbox=$data['cnt_sentbox']; } else { $cnt_sentbox='0'; }
	if (isset($data['cnt_savebox'])) { $cnt_savebox=$data['cnt_savebox']; } else { $cnt_savebox='0'; }
	$limit_inbox=$limit['pm_inbox'];
	$limit_sentbox=$limit['pm_sentbox'];
	$limit_savebox=$limit['pm_savebox'];

	// Set display of top bar
	if ($limit_inbox != '0') { $inbox_display = " (".$cnt_inbox."/".$limit_inbox.")"; } else { $inbox_display = " (".$cnt_inbox.")"; }
	if ($limit_sentbox != '0') { $sentbox_display = " (".$cnt_sentbox."/".$limit_sentbox.")"; } else { $sentbox_display = " (".$cnt_sentbox.")"; }
	if ($limit_savebox != '0') { $savebox_display = " (".$cnt_savebox."/".$limit_savebox.")"; } else { $savebox_display = " (".$cnt_savebox.")"; }

	if($cnt_total!=0 || $folder != 'settings'){
		tablebreak();
		echo $title."<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr>\n<td width='1%'>\n
<table border='0' cellpadding='0' cellspacing='0' width='1%'><tr><td>\n
<input type='button' class='button' title='".$locale['500']."' onclick=\"location.href='".FUSION_SELF."?folder=".$folder."&msg_send'\" value='".$locale['401']."'</input>\n</td>\n</tr>\n</table>\n
</td><td width='*'></td><td width='1%' align='right'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td>\n
<table border='0' cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n
<td width='25%' class='".($folder=='inbox'?'tbl2':'tbl1')."' align='center'><nobr>\n".$folder_stat[0]."<a href='".FUSION_SELF."?folder=inbox' title='".$locale['501']."'>".$locale['402'].$inbox_display."</a>".$folder_stat[1]."<nobr>\n</td>\n
<td width='25%' class='".($folder=='sentbox'?'tbl2':'tbl1')."' align='center'><nobr>\n".$folder_stat[2]."<a href='".FUSION_SELF."?folder=sentbox' title='".$locale['502']."'>".$locale['403'].$sentbox_display."</a>".$folder_stat[3]."<nobr>\n</td>\n
<td width='25%' class='".($folder=='savebox'?'tbl2':'tbl1')."' align='center'><nobr>\n".$folder_stat[4]."<a href='".FUSION_SELF."?folder=savebox' title='".$locale['503']."'>".$locale['404'].$savebox_display."</a>".$folder_stat[5]."<nobr>\n</td>\n
<td width='25%' class='".($folder=='options'?'tbl2':'tbl1')."' align='center'><nobr>\n".$folder_stat[6]."<a href='".FUSION_SELF."?folder=options' title='".$locale['516']."'>".$locale['620']."</a>".$folder_stat[7]."<nobr>\n</td>\n
</tr>\n</table>\n</td></tr></table>\n</td></tr></table>\n";
		tablebreak();

		// Display Options Page
		if ($folder == 'options') {

			$options_qry = dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='".$userdata['user_id']."'");
			if (dbrows($options_qry) != 0) {
				$options = dbarray($options_qry);
				$update_type = 'update';
			} else {
				$update_type = 'new';
			}

			// Select default value for options
			$displayoptions['pm_email_notify'][0] = "<option value='0'".($options['pm_email_notify'] == "0" ? " selected" : "").">".$locale['632']."</option>";
			$displayoptions['pm_email_notify'][1] = "<option value='1'".($options['pm_email_notify'] == "1" ? " selected" : "").">".$locale['631']."</option>";
			$displayoptions['pm_save_sent'][0] = "<option value='0'".($options['pm_save_sent'] == "0" ? " selected" : "").">".$locale['632']."</option>";
			$displayoptions['pm_save_sent'][1] = "<option value='1'".($options['pm_save_sent'] == "1" ? " selected" : "").">".$locale['631']."</option>";

			// If value is set to '1' then display the correct default value of yes
			$default_options = dbarray(dbquery("SELECT pm_email_notify,pm_save_sent FROM ".$db_prefix."messages_options WHERE user_id='0'"),0);
			if ($default_options['pm_email_notify'] == 1) { rsort($displayoptions['pm_email_notify']); }
			if ($default_options['pm_save_sent'] == 1) { rsort($displayoptions['pm_save_sent']); }

			echo "<br /><div style='text-align:center'>
<form name='optionsform' method='post' action='".FUSION_SELF."?folder=options'>
<table border='0' cellpadding='0' cellspacing='0' width='500px' class='tbl-border'>
<tr>
<td class='tbl2' align='center' colspan='2'>".$locale['620']."</td>
</tr>
<tr>
<td class='tbl1' width='60%'>".$locale['621']."</td>
<td class='tbl1' width='40%'>
<select name='pm_email_notify' class='textbox'>".
$displayoptions['pm_email_notify'][0].
$displayoptions['pm_email_notify'][1].
"</select>	
</td>
</tr>
<tr>
<td class='tbl1' width='60%'>".$locale['622']."</td>
<td class='tbl1' width='40%'>
<select name='pm_save_sent' class='textbox'>".
$displayoptions['pm_save_sent'][0].
$displayoptions['pm_save_sent'][1].
"</select>	
</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl1'>
<br /><input type='submit' name='saveoptions' value='".$locale['623']."' class='button'>
<input type='hidden' name='update_type' value='".$update_type."'>
</td>
</tr>
</table>
</form>";

		} else {

			$rows=dbresult(dbquery(
				"SELECT COUNT(*) FROM ".$db_prefix."messages LEFT JOIN ".$db_prefix."users ON user_id=message_from ".
				"WHERE ".$result_where_message_folder." AND ".$result_where_message_to.$srch_where
			),0);
			if(!isset($rowstart) || !isNum($rowstart)) $rowstart=0;
			$result=dbquery(
				"SELECT * FROM ".$db_prefix."messages LEFT JOIN ".$db_prefix."users ".
				"ON user_id=message_from WHERE ".$result_where_message_folder." AND ".$result_where_message_to.$srch_where.
				" ORDER BY message_read,".$sort_where." LIMIT ".$rowstart.",".$itemsperpage
			);
			$msgcount=dbrows($result);
			if($msgcount>0){
				echo "<form method='post' name='frm_privmsg' action='".FUSION_SELF."' onSubmit=\"return ValidateForm(this,'chk_mark[]')\">\n
<input type='hidden' name='folder' value='".$folder."'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class='tbl-border'><tr><td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='tbl2' width='55%'>".$locale['405']."</td>
<td class='tbl2' width='25%'>".($msg_folders[$folder]==1?$locale['421']:$locale['406'])."</td>
<td class='tbl2' width='1%' align='center'>".$locale['407']."</td>
</tr>\n";
				$unread_shown=0;
				$r=0;
				while($data=dbarray($result)){
					$msgdate=strftime($settings['forumdate'], $data['message_datestamp']+($settings['timeoffset']*3600));
					if($data['message_read']<>$unread_shown){
						if($r>0)echo "<tr><td colspan='10' height='4' class='tbl2'></td></tr>\n";
						$unread_shown=1;
					}
					echo "<tr>\n<td class='tbl1'>";
					if($data['message_read']==0){echo "<b>";}
					echo "<input type='checkbox' name='chk_mark[]' value='".$data['message_id']."' title='".$locale['504']."'>";
					$msg_title=($settings['privmsg_subject_tooltip']?substr(stripslashes($data['message_message']),0,75)."'":$locale['505']);
					echo "<a href='".FUSION_SELF."?msg_view=".$data['message_id']."' title='".$msg_title."'>".$data['message_subject']."</a>";
					if($data['message_read']==0){echo "</b>";}
					echo "</td>\n<td class='tbl1'>";
					if($data['message_from']==0){
						echo $locale['408'];
					}else{
						echo "<a href='".BASEDIR."profile.php?lookup=".$data['message_from']."' title='".$locale['506']."'>".$data['user_name']."</a>";
					}
					echo "</td>\n<td class='tbl1' align='center'><nobr>".$msgdate."</nobr></td>\n";
					$r++;
					echo "</tr>\n";
				}
				echo "</table>\n</td>\n</tr>\n</table>\n
<script language='Javascript'>
	function setChecked(frmName,chkName,val){
		dml=document.forms[frmName];
		len=dml.elements.length;
		for(i=0;i<len;i++){
			if(dml.elements[i].name==chkName){
				dml.elements[i].checked=val;
			}
		}
	}

	function ValidateForm(dml,chkName){
		len=dml.elements.length;
		for(i=0;i<len;i++){
			if((dml.elements[i].name==chkName)&&(dml.elements[i].checked==1)) return true
		}
		alert('".$locale['485']."')
		return false;
	}
</script>\n";
			}else{
				echo "<center><br />".($srch_where==""?$locale['461']:$locale['453'])."<br /><br /></center>\n";
			}
			if($msgcount>0){
				tablebreak();
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr>\n
<td><nobr><span class='small'><input type='button' class='button' title='".$locale['507']."' onclick=\"javascript:setChecked('frm_privmsg','chk_mark[]',1);\" value='".$locale['410']."'</input>
<input type='button' class='button' title='".$locale['508']."' onclick=\"javascript:setChecked('frm_privmsg','chk_mark[]',0);\" value='".$locale['411']."'</input><nobr></span></td>\n
<td align='right'>".$locale['409']." \n";
	if($folder=='savebox'){
					echo "<input type='submit' class='button' name='btn_unsave' title='".$locale['512']."' value='".$locale['413']."'> ";
				}else{
					echo "<input type='submit' class='button' name='btn_save' title='".$locale['511']."' value='".$locale['412']."'> ";
				}
				echo "<input type='submit' class='button' name='btn_setread' title='".$locale['513']."' value='".$locale['414']."'>
<input type='submit' class='button' name='btn_setunread' title='".$locale['514']."' value='".$locale['415']."'> 
<input type='submit' class='button' name='btn_delete' title='".$locale['515']."' value='".$locale['416']."'>\n
</td>\n</tr>\n</table>\n";
				tablebreak();
			}
			if($msgcount>0 || $srch_where!=""){
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' class='body'>
<tr><td colspan='20' align='center'>\n".$locale['417']." <a href='".FUSION_SELF."?folder=".$folder."&show=all' title='".$locale['509']."'>".$locale['418']."</a>";
				$srch_letters=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
				foreach($srch_letters as $srch_letter){
					echo "|<a href='".FUSION_SELF."?folder=".$folder."&show=".$srch_letter."' title='".sprintf($locale['510'],$srch_letter)."'>".$srch_letter."</a>";
				}
				echo "</td></tr>\n</table>\n";
			}
			echo "</form>";
	if($msgcount>0 || $srch_where!=""){
				closetable();
				if(isset($show)){
					$link="?show=".$show."&";
				}elseif(isset($srch_text)&&isset($srch_type)){
					$link="?srch_text=".$srch_text."&srch_type=".$srch_type."&";
				}else{
					$link="?";
				}
				// Make pagination (Support for when 'Sender with Letter ?' is set)
				if (isset($show)) { $url = "?folder=$folder&show=$show&"; } else { $url = "?folder=$folder&"; }
				echo "<div align='center' style='margin:5px;'>".makePageNav($rowstart,$itemsperpage,$rows,3,FUSION_SELF.$url)."</div>\n";

				opentable($locale['450']);
				echo "<form method='post' action='".FUSION_SELF."'>
<input type='hidden' name='folder' value='".$folder."'>
<table align='center' cellspacing='0' cellpadding='0' class='body'>
<tr><td valign='top' align='right'>".$locale['454']."&nbsp;</td><br />
<td><input type='text' name='srch_text' class='textbox' value=\"".$srch_text."\" style='width:150px'><br />
<span class='small'>".$locale['457']."</span><br /><br /></td></tr>
<tr><td valign='top' align='right'>".$locale['455']."&nbsp;</td><td>\n
<select name='srch_type' class='textbox' style='width:150px'>\n";
				foreach($srch_fields as $key=>$srch_field){
					$sel=($key==$srch_type?"selected":"");
					echo "<option value='".$key."'$sel>".$srch_field[2]."</option>\n";
				}
				echo "</select></td></tr><tr><td valign='top' align='right'>".$locale['456']."&nbsp;</td><td>\n
				<select name='sort_type' class='textbox' style='width:150px'>\n";
				foreach($sort_fields as $key=>$sort_field){
					$sel=($key==$sort_type?"selected":"");
					echo "<option value='".$key."'$sel>".$sort_field[1]."</option>\n";
				}
				echo "</select></td></tr><tr><td align='right'> </td><td><br />
				<input type='submit' name='srch_submit' class='button' value='".$locale['451']."'>
				<input type='submit' name='srch_reset' class='button' value='".$locale['452']."'>
				</td></tr></table></form>\n";
			}
		}
	}else{
		echo "<center><br />".$locale['419']."<br /><br />
<input type='button' class='button' title='".$locale['500']."' onclick=\"location.href='".FUSION_SELF."?folder=".$folder."&msg_send'\" value='".$locale['401']."'</input>
<br /><br /></center>\n";
	}
}
closetable();

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>