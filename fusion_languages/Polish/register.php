<?php
define("LAN_400", "Rejestracja");
define("LAN_401", "Aktywacja Konta");
// Registration Errors
define("LAN_402", "Musisz podaæ swój nick, has³o i adres mailowy.");
define("LAN_403", "Twój nick zawiera niedozwolone znaki.");
define("LAN_404", "Podane przez Ciebie has³a ró¿ni± siê od siebie.");
define("LAN_405", "Niew³a¶ciwe has³o. Mo¿esz u¿ywaæ jedynie liczb i liter, bez znaków specjalnych.<br>
Has³o musi mieæ d³ugo¶æ przynajmniej 6 znaków.");
define("LAN_406", "Podany przez Ciebie adres e-mailowy wydaje siê byæ niepoprawny.");
define("LAN_407", "Nick ".(isset($_POST['username']) ? $_POST['username'] : "")." jest ju¿ przez kogo¶ u¿ywany.");
define("LAN_408", "Adres e-mailowy ".(isset($_POST['email']) ? $_POST['email'] : "")." jest juz zarezerwowany.");
define("LAN_409", "Nieaktywne konto zosta³o zarejestrowane.");
define("LAN_410", "Niepoprawny Kod.");
// Email Message
define("LAN_450", "Witaj ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Witaj w gronie u¿ytkowników witryny ".$settings['sitename'].". Oto dane niezbêdne do zalogowania siê:\n
Nick: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Has³o: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aby aktywowaæ swoje konto, kliknij w poni¿szy link:\n");
// Registration Success/Fail
define("LAN_451", "Rejestracja zakoñczona, mo¿esz teraz zalogowaæ siê u¿ywaj±c podanych wcze¶niej danych.");
define("LAN_452", "Rejestracja niemal dobieg³a koñca. Teraz musisz tylko aktywowaæ konto poprzez link znajduj±cy siê w li¶cie wys³anym na podany przez Ciebie adres e-mailowy.");
define("LAN_453", "Konto aktywne. Mo¿esz ju¿ siê logowaæ.");
define("LAN_454", "Rejestracja nie powiod³a siê");
define("LAN_455", "Wysy³anie maila nie zadzia³a³o, skontaktuj siê z <a href='mailto:".$settings['siteemail']."'>Administratorem Witryny</a>.");
define("LAN_456", "Rejestracja nie powiod³a sie z poni¿szego powodu/powodów:");
define("LAN_457", "Spróbuj ponownie");
// Register Form
define("LAN_500", "Wype³nij poni¿sze pola. ");
define("LAN_501", "Mail zawieraj±cy link aktywacyjny zostanie dostarczony na wskazany przez Ciebie adres e-mail. ");
define("LAN_502", "Pola oznaczone <span style='color:#ff0000;'>*</span> s± obowi±zkowe.
Wielko¶æ liter w nicku i ha¶le ma znaczenie.");
define("LAN_503", " Kiedy jeste¶ zalogowany, mo¿esz w ka¿dej chwili edytowaæ swój profil.");
define("LAN_504", "Kod Potwierdzaj±cy:");
define("LAN_505", "Wpisz powy¿szy kod:");
define("LAN_506", "Rejestruj");
define("LAN_507", "System rejestracji zosta³ tymczasowo dezaktywowany przez Administratora witryny.");
// Validation Errors
define("LAN_550", "Musisz podaæ swój nick.");
define("LAN_551", "Musisz podaæ has³o.");
define("LAN_552", "Musisz podaæ swój adres e-mail.");
?>
