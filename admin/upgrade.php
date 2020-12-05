<?
if ($userdata[mod] == "Administrator") {
	if ($stage == "" || $stage == 1) {
		opentable("Upgrade");
		echo "This feature will keep your website database up to date, you should ensure that you have
uploaded the latest files before performing any upgrades. Official updates can be obtained from the PHP-Fusion™
website at <a href=\"http://www.digitaldominion.co.uk/\">digital dominion</a>.<br><br>\n";
		if ($settings[version] < 132) {
			echo "<form name=\"upgradeform\" method=\"post\" action=\"$PHP_SELF?sub=upgrade&stage=2\">
Your installation appears to older than the current release, would you like to upgrade to version 1.32?<br>
<div align=\"center\"><br>
<input type=\"submit\" name=\"yes\" value=\"yes\" class=\"button\" style=\"width: 60px\">
</form>\n";
		} else {
			echo "PHP-Fusion™ Version 1.32 detected, no upgrade is available at this time\n";
		}
		closetable();
	}
	if ($stage == 2 && $settings[version] < 132) {
		opentable("Upgrade Complete");
		echo "The upgrade procedures have been performed, the following changes were applied to
the database:<br><br>\n";
		$result = dbquery("ALTER TABLE weblinks ADD referals SMALLINT(6) NOT NULL DEFAULT '0' AFTER linkorder");
		if ($result) {
			echo "· referals column successfully added to weblinks table<br>\n";
		}
		$result = dbquery("DROP TABLE IF EXISTS linksubmits");
		$result = dbquery("CREATE table linksubmits (
		slid smallint(6) NOT NULL auto_increment,
		sitename varchar(64) NOT NULL default '',
		siteurl varchar(255) NOT NULL default '',
		category varchar(32) NOT NULL default '',
		name varchar(32) NOT NULL default '',
		email varchar(64) NOT NULL default '',
		submittime varchar(10) NOT NULL default '',
		userip varchar(15) NOT NULL default '',
		PRIMARY KEY (slid)
		) TYPE=MyISAM;");
		if ($result) {
			echo "· linksubmits table successfully created<br>";
		}
		$result = dbquery("DROP TABLE IF EXISTS newssubmits");
		$result = dbquery("CREATE table newssubmits (
		snid smallint(6) NOT NULL auto_increment,
		name varchar(32) NOT NULL default '',
		email varchar(64) NOT NULL default '',
		subject varchar(255) NOT NULL default '',
		news text NOT NULL,
		extendednews text NOT NULL,
		submittime varchar(10) NOT NULL default '',
		userip varchar(15) NOT NULL default '',
		PRIMARY KEY (snid)
		) TYPE=MyISAM;");
		if ($result) {
			echo "· newssubmits table successfully created<br>";
		}
		$result = dbquery("UPDATE settings SET version='132'");
		if ($result) {
			echo "· Site Version successfully updated to v1.32.<br>\n";
		}
		echo "<div align=\"center\"><br>
<a href=\"index.php\">Return to Admin Home</a></div>\n";
		closetable();
	}	
}
?>