<?php
define("LAN_400", "Aanmelden");
define("LAN_401", "Activeer account");
// Fouten aanmelding
define("LAN_402", "Lidnaam, wachtwoord & email adres zijn verplicht.");
define("LAN_403", "Lidnaam bevat ongeldige tekens.");
define("LAN_404", "Uw wachtwoorden zijn niet gelijk.");
define("LAN_405", "Ongeldig wachtwoord, gebruik alleen alfanumerieke tekens.<br>
Een wachtwoord moet minstens 6 tekens lang zijn.");
define("LAN_406", "Uw emailadres is niet geldig.");
define("LAN_407", "Sorry, deze lidnaam ".(isset($_POST['username']) ? $_POST['username'] : "")." is al in gebruik.");
define("LAN_408", "Sorry, dit emailadres ".(isset($_POST['email']) ? $_POST['email'] : "")." is al in gebruik.");
define("LAN_409", "Een inactieve account is geregistreerd met dit emailadres.");
define("LAN_410", "Verkeerde validatie code.");
define("LAN_411", "Uw emailadres of emaildomein staat op de Zwarte Lijst.");
// Emailbericht
define("LAN_450", "Hallo ".$_POST['username'].",\n
Welkom op ".$settings['sitename'].". Hier zijn uw login gegevens:\n
Lidnaam: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Wachtwoord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Activeer uw account door op de volgende link te klikken:\n");
// Succes/muslukking aanmelding
define("LAN_451", "Registratie voltooid. U kunt nu inloggen.");
define("LAN_452", "Uw registratie is bijna voltooid. U krijgt nu een email met uw logingegevens en een link om uw account te activeren.");
define("LAN_453", "Uw account is geactiveerd. U kunt nu inloggen.");
define("LAN_454", "Aanmelding mislukt");
define("LAN_455", "Het versturen van de email is mislukt. Neem contact op met de <a href='mailto:".$settings['siteemail']."'>beheerder van de website</a>.");
define("LAN_456", "Aanmelding is mislukt door de volgende reden(en):");
define("LAN_457", "Probeer het nogmaals");
// Formulier aannmelden
define("LAN_500", "Vult u hier de gegevens in. ");
define("LAN_501", "Een bevestigingsemail wordt naar het opgegeven emailadres gestuurd. ");
define("LAN_502", "Velden gemarkeerd met <span style='color:#ff0000;'>*</span> zijn verplicht.
Uw lidnaam en wachtwoord zijn hoofdlettergevoelig.");
define("LAN_503", " U kan meer informatie toevoegen door naar Profiel Wijzigen te gaan als u bent ingelogd.");
define("LAN_504", "Validatie code:");
define("LAN_505", "Voer validatie code in:");
define("LAN_506", "Aanmelden");
define("LAN_507", "Het aanmedlingssysteem is op het moment uitgeschakeld.");
// Validatiefouten
define("LAN_550", "Vul een lidnaam in.");
define("LAN_551", "Vul een wachtwoord in.");
define("LAN_552", "Vul een emailadres in.");
?>