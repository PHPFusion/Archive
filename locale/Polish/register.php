<?php
$locale['400'] = "Rejestracja";
$locale['401'] = "Aktywacja konta";
// Registration Errors
$locale['402'] = "Podaj login, has³o i email.";
$locale['403'] = "Login zawiera niedozwolone znaki.";
$locale['404'] = "Has³a nie pasuj±.";
$locale['405'] = "Niedozwolone znaki w ha¶le. U¿ywaj liter i cyfr.<br>Minimalna d³ugo¶æ - 6 znaków.";
$locale['406'] = "Z³y email.";
$locale['407'] = "Login ".(isset($_POST['username']) ? $_POST['username'] : "")." jest zajêty.";
$locale['408'] = "Enail ".(isset($_POST['email']) ? $_POST['email'] : "")." jest zajêty.";
$locale['409'] = "Nieaktywne konto zosta³o zarejestrowane.";
$locale['410'] = "Nieprawid³owy kod potwierdzaj±cy.";
$locale['411'] = "Twój email znajduje siê na czarnej li¶cie.";
// Email Message
$locale['450'] = "Witaj ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Witaj na ".$settings['sitename'].". Szczegó³y Twojego konta:\n
Login: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Has³o: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktywuj konto klikaj±c w ten link:\n";
// Registration Success/Fail
$locale['451'] = "Rejestracja zakoñczona";
$locale['452'] = "Mo¿esz siê teraz zalogowaæ.";
$locale['453'] = "Administrator niebawem aktywuje Twoje konto.";
$locale['454'] = "Rejestracja jest prawie zakoñczona, dostaniesz email z danymi oraz z likniem do weryfikacji Twojego konta.";
$locale['455'] = "Twoje konto zosta³o zweryfikowane.";
$locale['456'] = "Rejestracja nie powiod³a siê";
$locale['457'] = "Wys³anie emaila nie powiod³o siê. Skontaktuj siê z <a href='mailto:".$settings['siteemail']."'>Administratorem</a>.";
$locale['458'] = "Rejestracja nie powiod³a siê z powodu zaistnia³ych b³êdów:";
$locale['459'] = "Spróbuj ponownie";
// Register Form
$locale['500'] = "Podaj dane. ";
$locale['501'] = "Email zostanie wys³any na podany adres.";
$locale['502'] = "Pola oznacznone <span style='color:#ff0000;'>*</span> musz± zostaæ wype³nione. Wielko¶æ liter w loginie i ha¶le ma znaczenie.";
$locale['503'] = " Dodatkowe dane mo¿esz wprowadziæ edytuj±c profil po zalogowaniu.";
$locale['504'] = "Kod potwierdzaj±cy:";
$locale['505'] = "Podaj kod potwierdzaj±cy:";
$locale['506'] = "Rejestruj";
$locale['507'] = "W³a¶ciciel witryny nie zezwoli³ na rejestracjê.";
// Validation Errors
$locale['550'] = "Podaj login.";
$locale['551'] = "Podaj has³o.";
$locale['552'] = "Podaj email.";
?>
