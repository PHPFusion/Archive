<?php
define("LAN_400", "Bliv medlem");
// Registration Errors
define("LAN_410", "Du skal opgive et brugernavn, et password og en mailadresse");
define("LAN_411", "Brugernavnet indeholder karakterer, som ikke må bruges");
define("LAN_412", "De to passwords er ikke identiske");
define("LAN_413", "Dit password har ikke det rigtige format. Der må kun bruges alfanumeriske karakterer");
define("LAN_414", "Det ser ikke ud til, at den opgivne mailadresse eksisterer");
define("LAN_415", "Forkert sikkerhedskode");
define("LAN_416", "ICQ-nummer er i et forkert format. Der må kun bruges tal");
define("LAN_417", "Det ser ud til, at den opgivne MSN ID ikke findes");
define("LAN_418", "Din Yahoo ID indeholder forkerte karakterer");
define("LAN_419", "Beklager, men brugernavnet ".$_POST['username']." findes allerede.");
define("LAN_420", "Beklager, men mailadressen ".$_POST['email']." er allerede i brug.");
// Email Message
define("LAN_430", "Hej ".$_POST['username'].",<br>
<br>
Velkommen til ".$settings[sitename].", her har du de oplysninger, du skal bruge for at logge på:<br>
<br>
<span class=\"alt\">Brugernavn:</span> ".$_POST['username']."<br>
<span class=\"alt\">Password:</span> ".$_POST['password']."<br>
<br>
Venlig hilsen,<br>
<a href=\"mailto:".$settings[siteemail]."\">".$settings[siteusername]."</a>.\n");
// Registration Success/Fail
define("LAN_440", "Tilmelding er gennemført");
define("LAN_441", "Din tilmelding er gennemført og dine brugeroplysninger er sendt til dig pr. mail");
define("LAN_442", "Tilmelding blev ikke gennemført");
define("LAN_443", "Der kunne ikke sendes en mail");
define("LAN_444", "Tilmelding blev ikke gennemført af følgende årsage(r):");
define("LAN_445", "Prøv igen");
// Register Form
define("LAN_450", "For at melde dig til skal du udfylde felterne herunder.<br>
Alle felter markeret med <font color=\"red\">*</font> skal udfyldes. Vi skal bruge en
fungerende mailadresse til at verificere tilmeldingen. Du kan altid senere rette i dine oplysninger
ved at trykke på <b>Rediger profil</b> når du er logget på.");
define("LAN_451", "Brugernavn:");
define("LAN_452", "Password:");
define("LAN_453", "Gentag password:");
define("LAN_454", "Mailadresse:");
define("LAN_455", "Skal mailadressen skjules for andre?");
define("LAN_456", " Ja ");
define("LAN_457", " Nej");
define("LAN_458", "Sikkerhedskode:");
define("LAN_459", "Indskriv sikkerhedskode:");
define("LAN_460", "Bopæl:");
define("LAN_461", "ICQ-nummer");
define("LAN_462", "MSN ID:");
define("LAN_463", "Yahoo ID:");
define("LAN_464", "Adresse på hjemmeside:");
define("LAN_465", "Signatur:");
define("LAN_466", "Tilmeld bruger");
?>
