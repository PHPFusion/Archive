<?php
define("LAN_400", "Bli anv�ndare");
// Registration Errors
define("LAN_410", "Du m�ste v�lja ett anv�ndarnamn, ett l�senord, samt ange en epostadress");
define("LAN_411", "Ditt anv�ndarnamn inneh�ller otill�tna tecken");
define("LAN_412", "L�senorden �r inte identiska.");
define("LAN_413", "Ogiltligt l�senordsformat. Endast bokst�ver och siffror f�r anv�ndas.");
define("LAN_414", "Din epostadress f�refaller ej giltlig.");
define("LAN_415", "Felaktig verifieringskod!");
define("LAN_416", "ICQ - numret �r i fel format. Endast tal kan anv�ndas.");
define("LAN_417", "Ditt MSN - ID f�refaller ej giltligt.");
define("LAN_418", "Ditt Yahoo - ID inneh�ller felaktiga tecken.");
define("LAN_419", "Beklagar, men anv�ndarnamnet ".$_POST['username']." �r redan registrerat.");
define("LAN_420", "Beklagar, men epostadressen ".$_POST['email']." �r redan registrerad.");
// Email Message
define("LAN_430", "Hej ".$_POST['username'].",<br>
<br>
V�lkommen till ".$settings[sitename].", h�r �r dina inloggningsuppgifter:<br>
<br>
<span class=\"alt\">Anv�ndarnamn:</span> ".$_POST['username']."<br>
<span class=\"alt\">L�senord:</span> ".$_POST['password']."<br>
<br>
V�nliga h�lsningar,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "Registreringen lyckades.");
define("LAN_441", "Du �r registrerad och dina uppgifter skickas nu till dig via epost.");
define("LAN_442", "Registreringen misslyckades.");
define("LAN_443", "Det gick inte att skicka epost.");
define("LAN_444", "Registreringen misslyckades p� grund av f�ljande orsak:");
define("LAN_445", "F�rs�k igen");
// Register Form
define("LAN_450", "F�r att registrera dig ska du fylla i nedanst�ende f�lt.<br>
Alla f�lt markerade med <font color=\"red\">*</font> skall fyllas i.Vi m�ste ha en fungerande epostadress f�r att verifiera din registrering. Du kan alltid redigera dina uppgifter genom att g� till <b>Redigera profil</b> n�r du �r inloggad.");
define("LAN_451", "Anv�ndarnamn:");
define("LAN_452", "L�senord:");
define("LAN_453", "Repetera l�senord:");
define("LAN_454", "Epostadress:");
define("LAN_455", "Skall epostadressen d�ljas f�r andra anv�ndare?");
define("LAN_456", " Ja ");
define("LAN_457", " Nej");
define("LAN_458", "Verifieringskod:");
define("LAN_459", "Ange verifieringskod:");
define("LAN_460", "Hemort:");
define("LAN_461", "ICQ - nummer");
define("LAN_462", "MSN - ID:");
define("LAN_463", "Yahoo - ID:");
define("LAN_464", "Adress till egen hemsida:");
define("LAN_465", "Signatur:");
define("LAN_466", "Registrera anv�ndare.");
?>
