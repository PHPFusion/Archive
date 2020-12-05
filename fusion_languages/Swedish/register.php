<?php
define("LAN_400", "Bli användare");
define("LAN_401", "Återställ användarkonto");
// Registration Errors
define("LAN_402", "Du måste välja ett användarnamn, ett lösenord, samt ange en epostadress");
define("LAN_403", "Ditt användarnamn innehåller otillåtna tecken");
define("LAN_404", "Lösenorden är inte identiska.");
define("LAN_405", "Ogiltigt lösenord, endast alfanumeriska tecken får användas.<br>
Lösenordet måste bestå av minst 6 tecken.");
define("LAN_406", "Din epostadress förefaller ej giltlig.");
define("LAN_407", "Tyvärr, användarnamnet ".$_POST['username']." är upptaget.");
define("LAN_408", "Tyvärr, men epostadressen ".$_POST['email']." är upptagen.");
define("LAN_409", "En användare med ett inaktivt konto är redan registrerad med denna epostadress.");
define("LAN_410", "Säkerhetskoden är felaktig.");
// Email Message
define("LAN_450", "Hej ".$_POST['username'].",\n
Välkommen till ".$settings[sitename].", här är dina inloggningsuppgifter:\n
Användarnamn: ".$_POST['username']."
Lösenord: ".$_POST['password1']."\n
Aktivera ditt medlemskap genom att klicka på följande länk:\n");
// Registration Success/Fail
define("LAN_451", "Registreringen lyckades, du kan logga in nu.");
define("LAN_452", "Du är registrerad och dina uppgifter skickas nu till dig via epost.");
define("LAN_453", "Ditt medlemskap är aktiverat, nu kan du logga in.");
define("LAN_454", "Fel uppstod vid registreringen");
define("LAN_455", "Det gick inte att skicka epost. Kontakta <a href='mailto:".$settings['siteemail']."'>Sidans administrator</a>.");
define("LAN_456", "Registreringen misslyckades på grund av följande orsak:");
define("LAN_457", "Försök igen");
// Register Form
define("LAN_500", "Skriv in dina uppgifter nedan. ");
define("LAN_501", "Ett email skickas till den epostadress du har uppgivit.. ");
define("LAN_502", "Alla markerade fält <span style='color:#ff0000;'>*</span> skall fyllas i.
OBS! Användarnamn och lösenord är skiftlägeskänsliga!");
define("LAN_503", " Du kan lägga till ytterligare information genom att välja Redigera profil när du har loggat på.");
define("LAN_504", "Säkerhetskod:");
define("LAN_505", "Skriv in säkerhetskoden:");
define("LAN_506", "Bli medlem");
define("LAN_507", "Registreringssystemet är tillfälligt deaktiverat.");
// Validation Errors
define("LAN_550", "Du måste ange ditt användarnamn.");
define("LAN_551", "Du måste ange ditt lösenord.");
define("LAN_552", "Du måste ange en epostadress.");
?>
