<?php
define("LAN_400", "Register");
define("LAN_401", "Activate Account");
// Registration Errors
define("LAN_402", "You must specify a user name, password & email address.");
define("LAN_403", "User name contains invalid characters.");
define("LAN_404", "Your two Passwords do not match.");
define("LAN_405", "Invalid password, use alpha numeric characters only.<br>
Password must be a minimum of 6 characters long.");
define("LAN_406", "Your email address does not appear to be valid.");
define("LAN_407", "Sorry, the user name ".(isset($_POST['username']) ? $_POST['username'] : "")." is in use.");
define("LAN_408", "Sorry, the email address ".(isset($_POST['email']) ? $_POST['email'] : "")." is in use.");
define("LAN_409", "An inactive account has been registered with the email address.");
define("LAN_410", "Incorrect validation code.");
define("LAN_411", "Your email address or email domain is blacklisted.");
// Email Message
define("LAN_450", "Hello ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Welcome to ".$settings['sitename'].". Here are your login details:\n
Username: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Password: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Please activate your account via the following link:\n");
// Registration Success/Fail
define("LAN_451", "Registration complete, you can now log in.");
define("LAN_452", "Your registration is almost complete, you will recieve an email containing your login details along with a link to activate your account.");
define("LAN_453", "Your account has been activated, you can now log in.");
define("LAN_454", "Registration Failed");
define("LAN_455", "Send mail failed, please contact the <a href='mailto:".$settings['siteemail']."'>Site Administrator</a>.");
define("LAN_456", "Registration failed for the following reason(s):");
define("LAN_457", "Please Try Again");
// Register Form
define("LAN_500", "Please enter your details below. ");
define("LAN_501", "A verification email will be sent to your specified email address. ");
define("LAN_502", "Fields marked <span style='color:#ff0000;'>*</span> must be completed.
Your user name and password is case-sensitive.");
define("LAN_503", " You can enter additional information by going to Edit Profile once you are logged in.");
define("LAN_504", "Validation Code:");
define("LAN_505", "Enter Validation Code:");
define("LAN_506", "Register");
define("LAN_507", "The registration system is currently disabled.");
// Validation Errors
define("LAN_550", "Please specify a user name.");
define("LAN_551", "Please specify a password.");
define("LAN_552", "Please specify an email address.");
?>