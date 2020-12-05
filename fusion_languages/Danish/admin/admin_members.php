<?php
// Member Management Options
define("LAN_400", "Medlemsadministration");
define("LAN_401", "Bruger");
define("LAN_402", "Tilføj");
define("LAN_403", "Brugerstatus");
define("LAN_404", "Valgmuligheder");
define("LAN_405", "Se");
define("LAN_406", "Rediger");
define("LAN_407", "Ophæv udelukkelse");
define("LAN_408", "Udeluk");
define("LAN_409", "Slet");
define("LAN_410", "Ingen brugernavne begynder med ");
define("LAN_411", "Vis alle");
// Ban/Unban/Delete Member
define("LAN_420", "Udelukkelse gennemført");
define("LAN_421", "Udelukkelse fjernet");
define("LAN_422", "Medlemmet er slettet");
define("LAN_423", "Er du sikker på, at du vil slette dette medlem?");
// Edit Member Details
define("LAN_430", "Rediger medlemsoplysninger");
define("LAN_431", "Medlemsoplysninger er ændret");
define("LAN_432", "Vend tilbage til medlemsadministration");
define("LAN_433", "Vend tilbage til administrationspanel");
define("LAN_434", "Ude af stand til at ændre medlemsoplysninger:");
// Extra Edit Member Details form options
define("LAN_440", "Gem ændringer");
// Update Profile Errors
define("LAN_450", "Hovedadministratoren kan ikke ændres.");
define("LAN_451", "Du skal angive et brugernavn og en mailadresse.");
define("LAN_452", "Brugernavnet indeholder forbudte karakterer.");
define("LAN_453", "Brugernavnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." anvendes allerede.");
define("LAN_454", "Fejlagtig mailadresse.");
define("LAN_455", "Mailadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." anvendes allerede.");
define("LAN_456", "De to kodeord er ikke identiske.");
define("LAN_457", "Ugyldigt password, anvend kun alfanumeriske karakterer.<br>
Password skal være på mindst 6 karakterer.");
define("LAN_458", "<b>Advarsel:</b> uventet script afvikling.");
// View Member Profile
define("LAN_470", "Medlemsoplysninger: ");
define("LAN_471", "Generel information");
define("LAN_472", "Statistik");
define("LAN_473", "Brugergrupper");
// Update Profile Errors
define("LAN_480", "Tilføj medlem");
define("LAN_481", "Medlemmet er oprettet.");
define("LAN_482", "Medlemmet kunne ikke oprettes.");
?>
