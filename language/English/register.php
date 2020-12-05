<?php
define("LAN_200", "Register");
// Registration Errors
define("LAN_210", "You must provide a Username, Password & Email Address");
define("LAN_211", "User Name contains invalid characters");
define("LAN_212", "Your two Passwords do not match");
define("LAN_213", "Invalid Password, please use alpha numeric characters only");
define("LAN_214", "Your Email Address does not appear to be valid");
define("LAN_215", "Invalid ICQ#, please use numeric characters only");
define("LAN_216", "MSN ID does not appear to be valid");
define("LAN_217", "Yahoo ID contains invalid characters");
define("LAN_218", "Sorry, the User Name ".$_POST['username']." is in use.");
// Email Message
define("LAN_230", "Hello ".$_POST['username'].",<br>
<br>
Welcome to ".$settings[sitename].", here are your login details:<br>
<br>
<span class=\"alt\">Username:</span> ".$_POST['username']."<br>
<span class=\"alt\">Password:</span> ".$_POST['password']."<br>
<br>
Regards,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_240", "Registration Successful");
define("LAN_241", "Your registration was successful and your details have been Emailed");
define("LAN_242", "Registration Failed");
define("LAN_243", "Could not send Email");
define("LAN_244", "Registration failed for the following reason(s):");
define("LAN_245", "Please Try Again");
// Register Form
define("LAN_250", "To register an account, please enter your details below.<br>
Fields marked <font color=\"red\">*</font> must be completed. Your account will
be verified using the Email Address you supply. You can edit any of these details
at any time by clicking on Edit Profile whenever you are Logged in.");
define("LAN_251", "User Name:");
define("LAN_252", "Password:");
define("LAN_253", "Repeat Password:");
define("LAN_254", "Email Address:");
define("LAN_255", "Hide Email?");
define("LAN_256", " Yes ");
define("LAN_257", " No");
define("LAN_258", "Location:");
define("LAN_259", "ICQ#");
define("LAN_260", "MSN ID:");
define("LAN_261", "Yahoo ID:");
define("LAN_262", "Website URL:");
define("LAN_263", "Signature:");
define("LAN_264", "Register");
?>