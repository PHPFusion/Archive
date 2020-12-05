<?php
define("LAN_400", "Register");
// Registration Errors
define("LAN_410", "You must provide a Username, Password & Email Address");
define("LAN_411", "User Name contains invalid characters");
define("LAN_412", "Your two Passwords do not match");
define("LAN_413", "Invalid Password, please use alpha numeric characters only");
define("LAN_414", "Your Email Address does not appear to be valid");
define("LAN_415", "Incorrect validation code");
define("LAN_416", "Invalid ICQ#, please use numeric characters only");
define("LAN_417", "MSN ID does not appear to be valid");
define("LAN_418", "Yahoo ID contains invalid characters");
define("LAN_419", "Sorry, the User Name ".$_POST['username']." is in use.");
define("LAN_420", "Sorry, the Email Address ".$_POST['email']." is in use.");
// Email Message
define("LAN_430", "Hello ".$_POST['username'].",<br>
<br>
Welcome to ".$settings[sitename].", here are your login details:<br>
<br>
<span class=\"alt\">Username:</span> ".$_POST['username']."<br>
<span class=\"alt\">Password:</span> ".$_POST['password']."<br>
<br>
Regards,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.");
// Registration Success/Fail
define("LAN_440", "Registration Successful");
define("LAN_441", "Your registration was successful and your details have been Emailed");
define("LAN_442", "Registration Failed");
define("LAN_443", "Could not send Email");
define("LAN_444", "Registration failed for the following reason(s):");
define("LAN_445", "Please Try Again");
// Register Form
define("LAN_450", "To register an account, please enter your details below.<br>
Fields marked <font color=\"red\">*</font> must be completed. Your account will
be verified using the Email Address you supply. You can edit any of these details
at any time by clicking on Edit Profile whenever you are Logged in.");
define("LAN_451", "User Name:");
define("LAN_452", "Password:");
define("LAN_453", "Repeat Password:");
define("LAN_454", "Email Address:");
define("LAN_455", "Hide Email?");
define("LAN_456", " Yes ");
define("LAN_457", " No");
define("LAN_458", "Validation Code:");
define("LAN_459", "Enter Validation Code:");
define("LAN_460", "Location:");
define("LAN_461", "ICQ#");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Website URL:");
define("LAN_465", "Signature:");
define("LAN_466", "Register");
?>