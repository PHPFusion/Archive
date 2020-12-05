<?php
if (Member()) {
	@openside($userdata['user_name']);
	$result = dbquery("SELECT count(message_id) FROM ".$fusion_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_read='0'");
echo "· <a href='".fusion_basedir."editprofile.php' class='slink'>".LAN_60."</a><br>
· <a href='".fusion_basedir."messages.php' class='slink'>".LAN_61."</a> [".dbresult($result, 0)."]<br>
· <a href='".fusion_basedir."members.php' class='slink'>".LAN_62."</a><br>\n";
	if (Admin()) {
		echo "· <a href='".fusion_basedir."fusion_admin/index.php' class='slink'>".LAN_63."</a><br>\n";
	}
	echo "· <a href='".fusion_basedir."index.php?logout=yes' class='slink'>".LAN_64."</a>\n";
} else {
	@openside(LAN_65);
	echo "<div align='center'>$loginerror
<form name='loginform' method='post' action='$PHP_SELF'>
".LAN_66."<br>
<input type='text' name='user_name' class='textbox' style='width:100px'><br>
".LAN_67."<br>
<input type='password' name='user_pass' class='textbox' style='width:100px'><br>
<input type='checkbox' name='remember_me' value='y'>".LAN_70."<br><br>
<input type='submit' name='login' value='Login' class='button'><br>
</form>
<br>
<a href='".fusion_basedir."register.php' class='slink'>".LAN_68."</a><br>
<a href='".fusion_basedir."lostpassword.php' class='slink'>".LAN_69."</a>
</div>\n";
}
@closeside();
?>