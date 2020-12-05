<?php
// Member Management Options
define("LAN_400", "Members");
define("LAN_401", "User");
define("LAN_402", "Add");
define("LAN_403", "User Type");
define("LAN_404", "Options");
define("LAN_405", "View");
define("LAN_406", "Edit");
define("LAN_407", "UnBan");
define("LAN_408", "Ban");
define("LAN_409", "Delete");
define("LAN_410", "There are no user names beginning with ");
define("LAN_411", "Show All");
// Ban/Unban/Delete Member
define("LAN_420", "Ban Imposed");
define("LAN_421", "Ban Removed");
define("LAN_422", "Member Deleted");
define("LAN_423", "Are you sure you wish to delete this member?");
// Edit Member Details
define("LAN_430", "Edit Member");
define("LAN_431", "Member details updated");
define("LAN_432", "Return to Members Admin");
define("LAN_433", "Return to Admin Index");
define("LAN_434", "Unable to Update Member details:");
// Extra Edit Member Details form options
define("LAN_440", "Save Changes");
// Update Profile Errors
define("LAN_450", "Cannot edit primary administrator.");
define("LAN_451", "You must specify a user name & email address.");
define("LAN_452", "User name contains invalid characters.");
define("LAN_453", "The user name ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is in use.");
define("LAN_454", "Invalid email address.");
define("LAN_455", "The email address ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is in use.");
define("LAN_456", "New Passwords do not match.");
define("LAN_457", "Invalid password, use alpha numeric characters only.<br>
Password must be a minimum of 6 characters long.");
define("LAN_458", "<b>Warning:</b> unexpected script execution.");
// View Member Profile
define("LAN_470", "Member Profile: ");
define("LAN_471", "General Information");
define("LAN_472", "Statistics");
define("LAN_473", "User Groups");
// Add Member Errors
define("LAN_480", "Add Member");
define("LAN_481", "The member account has been created.");
define("LAN_482", "The member account could not be created.");
?>