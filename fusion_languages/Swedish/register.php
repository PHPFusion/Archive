<?php
define("LAN_400", "Bli anv�ndare");
define("LAN_401", "�terst�ll anv�ndarkonto");
// Registration Errors
define("LAN_402", "Du m�ste v�lja ett anv�ndarnamn, ett l�senord, samt ange en epostadress");
define("LAN_403", "Ditt anv�ndarnamn inneh�ller otill�tna tecken");
define("LAN_404", "L�senorden �r inte identiska.");
define("LAN_405", "Ogiltigt l�senord, endast alfanumeriska tecken f�r anv�ndas.<br>
L�senordet m�ste best� av minst 6 tecken.");
define("LAN_406", "Din epostadress f�refaller ej giltlig.");
define("LAN_407", "Tyv�rr, anv�ndarnamnet ".$_POST['username']." �r upptaget.");
define("LAN_408", "Tyv�rr, men epostadressen ".$_POST['email']." �r upptagen.");
define("LAN_409", "En anv�ndare med ett inaktivt konto �r redan registrerad med denna epostadress.");
define("LAN_410", "S�kerhetskoden �r felaktig.");
// Email Message
define("LAN_450", "Hej ".$_POST['username'].",\n
V�lkommen till ".$settings[sitename].", h�r �r dina inloggningsuppgifter:\n
Anv�ndarnamn: ".$_POST['username']."
L�senord: ".$_POST['password1']."\n
Aktivera ditt medlemskap genom att klicka p� f�ljande l�nk:\n");
// Registration Success/Fail
define("LAN_451", "Registreringen lyckades, du kan logga in nu.");
define("LAN_452", "Du �r registrerad och dina uppgifter skickas nu till dig via epost.");
define("LAN_453", "Ditt medlemskap �r aktiverat, nu kan du logga in.");
define("LAN_454", "Fel uppstod vid registreringen");
define("LAN_455", "Det gick inte att skicka epost. Kontakta <a href='mailto:".$settings['siteemail']."'>Sidans administrator</a>.");
define("LAN_456", "Registreringen misslyckades p� grund av f�ljande orsak:");
define("LAN_457", "F�rs�k igen");
// Register Form
define("LAN_500", "Skriv in dina uppgifter nedan. ");
define("LAN_501", "Ett email skickas till den epostadress du har uppgivit.. ");
define("LAN_502", "Alla markerade f�lt <span style='color:#ff0000;'>*</span> skall fyllas i.
OBS! Anv�ndarnamn och l�senord �r skiftl�gesk�nsliga!");
define("LAN_503", " Du kan l�gga till ytterligare information genom att v�lja Redigera profil n�r du har loggat p�.");
define("LAN_504", "S�kerhetskod:");
define("LAN_505", "Skriv in s�kerhetskoden:");
define("LAN_506", "Bli medlem");
define("LAN_507", "Registreringssystemet �r tillf�lligt deaktiverat.");
// Validation Errors
define("LAN_550", "Du m�ste ange ditt anv�ndarnamn.");
define("LAN_551", "Du m�ste ange ditt l�senord.");
define("LAN_552", "Du m�ste ange en epostadress.");
?>
