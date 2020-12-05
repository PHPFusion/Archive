<?php
define("LAN_400", "Anmelden");
// Registration Errors
define("LAN_410", "Du musst einen Benutzernamen, das Passwort und Deine Email Adresse angeben");
define("LAN_411", "Der Benutzername beinhaltet ung&uuml;ltige Zeichen");
define("LAN_412", "Deine zwei Passw&ouml;rter stimmen nicht &uuml;berein");
define("LAN_413", "Ung&uuml;ltiges Passwort, bitte nur Buchstaben und Ziffern verwenden");
define("LAN_414", "Deine Emailadresse sieht ung&uuml;tig aus");
define("LAN_415", "Ung&uuml;ltiger Pr&uuml;fcode");
define("LAN_416", "Ung&uuml;ltige ICQ#, bitte nur Ziffern verwenden");
define("LAN_417", "Die MSN ID sieht ung&uml;ltig aus");
define("LAN_418", "Die Yahoo ID beinhaltet ung&uuml;ltige Zeichen");
define("LAN_419", "Leider ist der Benutzername  ".$_POST['username']." bereits vergeben.");
define("LAN_420", "Leider wird die Email Addresse ".$_POST['email']." bereits benutzt.");
// Email Message
define("LAN_430", "Hallo ".$_POST['username'].",<br>
<br>
Willkommen auf ".$settings[sitename].", hier sind Deine Benutzerdaten:<br>
<br>
<span class=\"alt\">Benutzername:</span> ".$_POST['username']."<br>
<span class=\"alt\">Passwort:</span> ".$_POST['password']."<br>
<br>
Liebe Gruesse,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "Die Anmeldung war erfolgreich");
define("LAN_441", "Deine Anmeldung war erfolgreich und deine Zugangsdaten wurden Dir per Mail gesandt");
define("LAN_442", "Anmeldung fehlgeschlagen");
define("LAN_443", "Leider konnte die Email nicht gesandt werden");
define("LAN_444", "Die Anmeldung ist aus folgenden Gr&uuml;nden fehlgeschlagen:");
define("LAN_445", "Bitte nochmals versuchen");
// Register Form
define("LAN_450", "Um einen Account anzumelden gib bitte nachstehend Deine Daten an.<br>
Felder mit <font color=\"red\">*</font> Markierung sind anzugeben, die anderen Felder sind wahlfrei.
Dein Your Account wird &uuml;ber die angegebene Mailadresse gepr&uuml;ft.
Du kannst alle diese Details jederzeit &auml;ndern, indem Du auf Profil bearbeiten
klickst, sobald Du angemeldet bist.");
define("LAN_451", "Benutzername:");
define("LAN_452", "Passwort:");
define("LAN_453", "Passwort wiederholen:");
define("LAN_454", "Email Adresse:");
define("LAN_455", "Email verstecken?");
define("LAN_456", " Ja ");
define("LAN_457", " Nein");
define("LAN_458", "Pr&uuml;fcode:");
define("LAN_459", "Pr&uuml;fcode eingeben:");
define("LAN_460", "Ort/Wohnort:");
define("LAN_461", "ICQ#");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Homepage URL:");
define("LAN_465", "Signature:");
define("LAN_466", "Anmelden");
?>
