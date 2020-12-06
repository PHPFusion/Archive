<?php
$locale['400'] = "Rejestracja";
$locale['401'] = "Aktywacja konta";
// Registration Errors
$locale['402'] = "Podaj login, has�o i email.";
$locale['403'] = "Login zawiera niedozwolone znaki.";
$locale['404'] = "Has�a nie pasuj�.";
$locale['405'] = "Niedozwolone znaki w ha�le. U�ywaj liter i cyfr.<br>Minimalna d�ugo�� - 6 znak�w.";
$locale['406'] = "Z�y email.";
$locale['407'] = "Login ".(isset($_POST['username']) ? $_POST['username'] : "")." jest zaj�ty.";
$locale['408'] = "Enail ".(isset($_POST['email']) ? $_POST['email'] : "")." jest zaj�ty.";
$locale['409'] = "Nieaktywne konto zosta�o zarejestrowane.";
$locale['410'] = "Nieprawid�owy kod potwierdzaj�cy.";
$locale['411'] = "Tw�j email znajduje si� na czarnej li�cie.";
// Email Message
$locale['450'] = "Witaj ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Witaj na ".$settings['sitename'].". Szczeg�y Twojego konta:\n
Login: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Has�o: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktywuj konto klikaj�c w ten link:\n";
// Registration Success/Fail
$locale['451'] = "Rejestracja zako�czona";
$locale['452'] = "Mo�esz si� teraz zalogowa�.";
$locale['453'] = "Administrator niebawem aktywuje Twoje konto.";
$locale['454'] = "Rejestracja jest prawie zako�czona, dostaniesz email z danymi oraz z likniem do weryfikacji Twojego konta.";
$locale['455'] = "Twoje konto zosta�o zweryfikowane.";
$locale['456'] = "Rejestracja nie powiod�a si�";
$locale['457'] = "Wys�anie emaila nie powiod�o si�. Skontaktuj si� z <a href='mailto:".$settings['siteemail']."'>Administratorem</a>.";
$locale['458'] = "Rejestracja nie powiod�a si� z powodu zaistnia�ych b��d�w:";
$locale['459'] = "Spr�buj ponownie";
// Register Form
$locale['500'] = "Podaj dane. ";
$locale['501'] = "Email zostanie wys�any na podany adres.";
$locale['502'] = "Pola oznacznone <span style='color:#ff0000;'>*</span> musz� zosta� wype�nione. Wielko�� liter w loginie i ha�le ma znaczenie.";
$locale['503'] = " Dodatkowe dane mo�esz wprowadzi� edytuj�c profil po zalogowaniu.";
$locale['504'] = "Kod potwierdzaj�cy:";
$locale['505'] = "Podaj kod potwierdzaj�cy:";
$locale['506'] = "Rejestruj";
$locale['507'] = "W�a�ciciel witryny nie zezwoli� na rejestracj�.";
// Validation Errors
$locale['550'] = "Podaj login.";
$locale['551'] = "Podaj has�o.";
$locale['552'] = "Podaj email.";
?>
