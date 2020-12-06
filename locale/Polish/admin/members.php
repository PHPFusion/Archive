<?php
// Member Management Options
$locale['400'] = "U¿ytkownicy";
$locale['401'] = "U¿ytkownik";
$locale['402'] = "Dodaj";
$locale['403'] = "Typ u¿ytkownika";
$locale['404'] = "Opcje";
$locale['405'] = "Zobacz";
$locale['406'] = "Edytuj";
$locale['407'] = "Odbanuj";
$locale['408'] = "Zabanuj";
$locale['409'] = "Kasuj";
$locale['410'] = "Nie ma u¿ytkowników, których nicki rozpoczynaj± siê na ";
$locale['411'] = "Poka¿ wszystkich";
$locale['412'] = "Aktywuj konto";
// Ban/Unban/Delete Member
$locale['420'] = "Zabanowano";
$locale['421'] = "Odbanowano";
$locale['422'] = "Usuniêto u¿ytkownika";
$locale['423'] = "Czy napewno chcesz usun±æ tego u¿ytkownika?";
$locale['424'] = "Aktywowano konto";
$locale['425'] = "Konto aktywowano ";
$locale['426'] = "Witaj [USER_NAME],\n
Twoje konto w witrynie ".$settings['sitename']." zosta³o aktywowane.\n
Mo¿esz siê teraz zalogowaæ korzystaj±c ze swojej nazwy u¿ytkownika i has³a.\n
Pozdrawiam,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Edytuj";
$locale['431'] = "Zaktualizowano profil";
$locale['432'] = "Powrót do Zarz±dzania U¿ytkownikami";
$locale['433'] = "Powrót do Panelu Admina";
$locale['434'] = "Profil u¿ytkownika nie zosta³ zaktualizowany:";
// Extra Edit Member Details form options
$locale['440'] = "SZapisz zmiany";
// Update Profile Errors
$locale['450'] = "Nie mo¿esz edytowaæ konta Super Administratora.";
$locale['451'] = "Musisz podaæ nazwê u¿ytkownika i adres e-mail.";
$locale['452'] = "Nazwa u¿ytkownika [nick] zawiera niedozwolone znaki.";
$locale['453'] = "Nick ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." jest ju¿ przez kogo¶ u¿ywany.";
$locale['454'] = "Nieprawid³owy adres e-mail.";
$locale['455'] = "Adres e-mail ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." jest ju¿ przez kogo¶ u¿ywany.";
$locale['456'] = "Oba has³a musz± byæ takie same!";
$locale['457'] = "Nieprawid³owe has³o - mo¿esz u¿ywaæ tylko liter i cyfr.<br>
Has³o musi mieæ przynajmniej 6 znaków d³ugo¶ci";
$locale['458'] = "<b>UWAGA:</b> nieoczekiwany b³±d podczas wykonywania skryptu.";
// View Member Profile
$locale['470'] = "Profil U¿ytkownika: ";
$locale['471'] = "Ogólne Informacje";
$locale['472'] = "Statystyki";
$locale['473'] = "Grupy U¿ytkowników";
// Add Member Errors
$locale['480'] = "Dodaj u¿ytkownika";
$locale['481'] = "Utworzono nowe konto.";
$locale['482'] = "Nie uda³o siê utworzyæ nowego konta.";
?>
