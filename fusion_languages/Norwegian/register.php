<?php
define("LAN_400", "Bli medlem");
// Registration Errors
define("LAN_410", "Du skal oppgi et brukernavn, et passord og en mailadresse");
define("LAN_411", "Brukernavnet inneholder karakterer, som ikke m� brukes");
define("LAN_412", "De to passordene er ikke identiske");
define("LAN_413", "Ditt passord har ikke det rigtige format. Det m� kun brukes alfanumeriske tegn");
define("LAN_414", "Det ser ikke ut til, at den oppgitte mailadresse eksisterer");
define("LAN_415", "Feil godkjennings kode");
define("LAN_416", "ICQ-nummer er i et ugyldig format. Det m� kun bruges tall");
define("LAN_417", "Det ser ut til, at den oppgitte MSN ID ikke finnes");
define("LAN_418", "Din Yahoo ID inneholder ugyldige tegn");
define("LAN_419", "Beklager, men brukernavnet ".$_POST['username']." finnes allerede.");
define("LAN_420", "Beklager, men mailadressen ".$_POST['email']." er allerede i bruk.");
// Email Message
define("LAN_430", "Hei ".$_POST['username'].",<br>
<br>
Velkommen til ".$settings[sitename].", her har du de opplysninger, du skal bruke for � logge p�:<br>
<br>
<span class=\"alt\">Brukernavn:</span> ".$_POST['username']."<br>
<span class=\"alt\">Passord:</span> ".$_POST['password']."<br>
<br>
Vennlig hilsen,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "Tilmelding er gjennomf�rt");
define("LAN_441", "Din tilmelding er gjennomf�rt og dine brukeropplysninger er sendt til deg pr. mail");
define("LAN_442", "Tilmelding ble ikke gjennomf�rt");
define("LAN_443", "Det kunne ikke sendes en mail");
define("LAN_444", "Tilmelding ble ikke gjennemf�rt av f�lgende �rsake(r):");
define("LAN_445", "Pr�v igjen");
// Register Form
define("LAN_450", "For � melde deg til skal du fylle ut feltene under.<br>
Alle felter markeret med <font color=\"red\">*</font> skal fylles ut. Vi skal bruke en
fungerende mailadresse til � bekrefte tilmeldingen. Du kan alltid senere rette i dine opplysninger
ved � trykke p� <b>Rediger profil</b> n�r du er logget p�.");
define("LAN_451", "Brukernavn:");
define("LAN_452", "Passord:");
define("LAN_453", "Gjenta passord:");
define("LAN_454", "Mailadresse:");
define("LAN_455", "Skal mailadressen skjules for andre?");
define("LAN_456", " Ja ");
define("LAN_457", " Nei");
define("LAN_458", "Sikkerhetskode:");
define("LAN_459", "Skriv inn sikkerhetskode:");
define("LAN_460", "Bosted:");
define("LAN_461", "ICQ-nummer");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Adresse p� hjemmeside:");
define("LAN_465", "Signatur:");
define("LAN_466", "Tilmeld bruker");
?>
