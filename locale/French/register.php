<?php
$locale['400'] = "Enregistrement";
$locale['401'] = "Compte activé";
// Registration Errors
$locale['402'] = "Vous devez spécifié un pseudo, mot de passe et adresse Email.";
$locale['403'] = "Le pseudo contient des caractères non valides.";
$locale['404'] = "Vos deux mots de passe ne correspondent pas.";
$locale['405'] = "Mot de passe non valide, utilisez des caractère alphanumériques seulement.<br>
Le mot de passe doit être d'une longueur minimum 6 caractères.";
$locale['406'] = "Votre adresse Email ne semble pas valide.";
$locale['407'] = "Désolé, le pseudo, ".(isset($_POST['username']) ? $_POST['username'] : "")." est déjà utilisé.";
$locale['408'] = "Désolé, l'adresse Email ".(isset($_POST['email']) ? $_POST['email'] : "")." est déjà utilisé";
$locale['409'] = "Un compte actif a été enregistré avec cette adresse Email.";
$locale['410'] = "Erreur code de validation.";
$locale['411'] = "Votre adresse Email ou votre nom de domaine Email est sur liste noire.";
// Email Message
$locale['450'] = "Bonjour ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
bienvenue sur ".$settings['sitename'].". Ici vos détails de connexion:\n
Pseudo      : ".(isset($_POST['username']) ? $_POST['username'] : "")."
Mot de passe: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
S'il vous plaît, activez votre compte via le lien suivant:\n";
// Registration Success/Fail
$locale['451'] = "Enregistrement complet";
$locale['452'] = "Vous pouvez vous connecter.";
$locale['453'] = "Un administrateur va activer votre compte bientôt.";
$locale['454'] = "Votre enregistrement est presque terminé, vous allez recevoir un Email contenant le details de votre compte et un lien pour la vérification.";
$locale['455'] = "Votre compte a été vérifié.";
$locale['456'] = "L'enregistrement à échoué";
$locale['457'] = "L'envoi du mail à échoué, S'il vous plaît contacté <a href='mailto:".$settings['siteemail']."'>Administrateur</a>.";
$locale['458'] = "L'enregistrement à échoué pour la/les raison(s) suivante(s):";
$locale['459'] = "Essayez encore";
// Register Form
$locale['500'] = "S'il vous plaît entrez vos details ci-dessous. ";
$locale['501'] = "Un Email de vérification vous seras envoyé à l'adresse spécifiée. ";
$locale['502'] = "Les champs marqués <span style='color:#ff0000;'>*</span> Doivent être remplis.
Votre pseudo et mot de passe sont sensible à la casse(MAJ/MIN).";
$locale['503'] = " Vous pouvez mettre des informations complémentaires en éditant votre profil une fois connecté.";
$locale['504'] = "Code de validation:";
$locale['505'] = "Entrez le code de validation:";
$locale['506'] = "Enregistrer";
$locale['507'] = "L'enregistrement est momentanément désactivé.";
// Validation Errors
$locale['550'] = "S'il vous plaît spécifié un pseudo.";
$locale['551'] = "S'il vous plaît spécifié un mot de passe.";
$locale['552'] = "S'il vous plaît spécifié une adresse email.";
?>
