<?php
define("LAN_400", "Bli användare");
// Registration Errors
define("LAN_410", "Du måste välja ett användarnamn, ett lösenord, samt ange en epostadress");
define("LAN_411", "Ditt användarnamn innehåller otillåtna tecken");
define("LAN_412", "Lösenorden är inte identiska.");
define("LAN_413", "Ogiltligt lösenordsformat. Endast bokstäver och siffror får användas.");
define("LAN_414", "Din epostadress förefaller ej giltlig.");
define("LAN_415", "Felaktig verifieringskod!");
define("LAN_416", "ICQ - numret är i fel format. Endast tal kan användas.");
define("LAN_417", "Ditt MSN - ID förefaller ej giltligt.");
define("LAN_418", "Ditt Yahoo - ID innehåller felaktiga tecken.");
define("LAN_419", "Beklagar, men användarnamnet ".$_POST['username']." är redan registrerat.");
define("LAN_420", "Beklagar, men epostadressen ".$_POST['email']." är redan registrerad.");
// Email Message
define("LAN_430", "Hej ".$_POST['username'].",<br>
<br>
Välkommen till ".$settings[sitename].", här är dina inloggningsuppgifter:<br>
<br>
<span class=\"alt\">Användarnamn:</span> ".$_POST['username']."<br>
<span class=\"alt\">Lösenord:</span> ".$_POST['password']."<br>
<br>
Vänliga hälsningar,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "Registreringen lyckades.");
define("LAN_441", "Du är registrerad och dina uppgifter skickas nu till dig via epost.");
define("LAN_442", "Registreringen misslyckades.");
define("LAN_443", "Det gick inte att skicka epost.");
define("LAN_444", "Registreringen misslyckades på grund av följande orsak:");
define("LAN_445", "Försök igen");
// Register Form
define("LAN_450", "För att registrera dig ska du fylla i nedanstående fält.<br>
Alla fält markerade med <font color=\"red\">*</font> skall fyllas i.Vi måste ha en fungerande epostadress för att verifiera din registrering. Du kan alltid redigera dina uppgifter genom att gå till <b>Redigera profil</b> när du är inloggad.");
define("LAN_451", "Användarnamn:");
define("LAN_452", "Lösenord:");
define("LAN_453", "Repetera lösenord:");
define("LAN_454", "Epostadress:");
define("LAN_455", "Skall epostadressen döljas för andra användare?");
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
define("LAN_466", "Registrera användare.");
?>
