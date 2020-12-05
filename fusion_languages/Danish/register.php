<?php
define("LAN_400", "Bliv medlem");
define("LAN_401", "Genopret medlemskab");
// Registration Errors

define("LAN_402", "Du skal opgive et brugernavn, et kodeord og en mailadresse");
define("LAN_403", "Brugernavnet indeholder karakterer, som ikke m� bruges");
define("LAN_404", "De to kodeord er ikke identiske");
define("LAN_405", "Ugyldigt password, anvend kun alfanumeriske karakterer.<br>
Password skal v�re p� mindst 6 karakterer.");
define("LAN_406", "Det ser ikke ud til, at den opgivne mailadresse eksisterer");
define("LAN_407", "Beklager, men brugernavnet ".$_POST['username']." anvendes allerede.");
define("LAN_408", "Beklager, men mailadressen ".$_POST['email']." bruges allerede.");
define("LAN_409", "En bruger med en inaktiv konto er registreret med denne mailadresse.");
define("LAN_410", "Sikkerhedskoden var forkert.");
// Email Message
define("LAN_450", "Hej ".$_POST['username'].",\n
Velkommen som medlem p� ".$settings['sitename'].". Her er de oplysninger, du skal bruge for at logge p�:\n
Brugernavn: ".$_POST['username']."
Kodeord: ".$_POST['password1']."\n
Aktiver dit medlemskab ved at trykke p� f�lgende link:\n");
// Registration Success/Fail
define("LAN_451", "Tilmeldingen er gennemf�rt, du kan logge p� nu.");
define("LAN_452", "Din tilmelding er n�sten f�rdig. Du vil modtage en mail med dine p�logningsoplysninger og et link, som du skal trykke p� for at aktivere medlemskabet.");
define("LAN_453", "Dit medlemskab er blevet aktiveret, nu kan du logge p�.");
define("LAN_454", "Fejl ved tilmelding");
define("LAN_455", "Der kunne ikke sendes en mail. Kontakt <a href='mailto:".$settings['siteemail']."'>Sidens administrator</a>.");
define("LAN_456", "Tilmelding kunne ikke gennemf�res af f�lgende �rsag(er):");
define("LAN_457", "Pr�v igen");
// Register Form
define("LAN_500", "Skriv dine oplysninger herunder. ");
define("LAN_501", "En mail vil blive sendt til den mailadresse, du har opgivet. ");
define("LAN_502", "Felter markeret <span style='color:#ff0000;'>*</span> skal udfyldes.
Ved brugernavn og kodeord skelnes der mellem store og sm� bogstaver.");
define("LAN_503", " Du kan tilf�je yderligere informationer ved at v�lge Rediger profil, efter at du er logget p�.");
define("LAN_504", "Sikkerhedskode:");
define("LAN_505", "Indskriv sikkerhedskode:");
define("LAN_506", "Bliv medlem");
define("LAN_507", "Tilmeldingssystemet er aktuelt sl�et fra.");
// Validation Errors
define("LAN_550", "V�r s� venlig at skrive et brugernavn.");
define("LAN_551", "V�r s� venlig at skrive et kodeord.");
define("LAN_552", "V�r s� venlig at opgive en mailadresse.");
?>
