<?php
define("LAN_400", "Registreer");
define("LAN_401", "Activeer Account");
// Registration Errors
define("LAN_402", "Gebruikersnaam, wachtwoord & email adres zijn verplicht.");
define("LAN_403", "Gebruikersnaam bevat ongeldige tekens.");
define("LAN_404", "Uw wachtwoorden zijn niet gelijk.");
define("LAN_405", "Ongeldig wachtwoord, gebruik alleen alfanumerieke tekens.<br>
Wachtwoord moet minstens 6 tekens lang zijn.");
define("LAN_406", "Uw emailadres is niet geldig.");
define("LAN_407", "Sorry, de gebruikersnaam ".$_POST['username']." is al in gebruik.");
define("LAN_408", "Sorry, het emailadres ".$_POST['email']." is al in gebruik");
define("LAN_409", "Een inactieve account is geregistreerd met het emailadres.");
define("LAN_410", "Verkeerde validatie code.");
// Email Message
define("LAN_450", "Hallo ".$_POST['username'].",\n
Welkom op ".$settings['sitename'].". Hier zijn je login gegevens:\n
Gebruikersnaam: ".$_POST['username']."
Wachtwoord: ".$_POST['password1']."\n
Activeer uw account door op de volgende link te klikken:\n");
// Registration Success/Fail
define("LAN_451", "Registratie voltooid, u kunt nu inloggen.");
define("LAN_452", "Uw registratie is bijna voltooid, u krijgt nu een email met uw logingegevens en een link om uw account te activeren.");
define("LAN_453", "Uw account is geactiveerd, u kunt nu inloggen.");
define("LAN_454", "Registratie mislukt");
define("LAN_455", "Email sturen mislukt, neem contact op met de <a href='mailto:".$settings['siteemail']."'>Website Administrator</a>.");
define("LAN_456", "Registratie is mislukt vanwege de volgende reden(en):");
define("LAN_457", "Probeer nogmaals");
// Register Form
define("LAN_500", "Vult u hier de gegevens in. ");
define("LAN_501", "Een bevestigingsemail wordt naar het opgegeven emailadres gestuurd. ");
define("LAN_502", "Velden gemarkeerd met <span style='color:#ff0000;'>*</span> zijn verplicht.
Uw gebruikersnaam en wachtwoord zijn hoofdlettergevoelig.");
define("LAN_503", " U kan meer informatie toevoegen door naar Profiel Wijzigen te gaan als u bent ingelogd.");
define("LAN_504", "Validatie Code:");
define("LAN_505", "Voer Validatie Code in:");
define("LAN_506", "Registreer");
define("LAN_507", "Het registreer systeem is op het moment uitgeschakeld.");
// Validation Errors
define("LAN_550", "Vul een gebruikersnaam in.");
define("LAN_551", "Vul een wachtwoord in.");
define("LAN_552", "Vul een emailadres in.");
?>