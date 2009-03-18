<?

require("class.phpmailer.php");

$mail = new phpmailer();

$mail->IsSMTP();     
$mail->Mailer   = "smtp";  
$mail->Host     = "82.67.45.121"; // SMTP-Server
$mail->Port     = 25;
$mail->SMTPAuth = true;     // SMTP mit Authentifizierung benutzen
// $mail->SMTPDebug = true;
$mail->SMTPAuth = true;
$mail->Username = "jedi";  // SMTP-Benutzername
$mail->Password = "jedimail"; // SMTP-Passwort
$mail->From     = "webmaster@delphi-jedi.org";
$mail->FromName = "Max Mustermann";
$mail->AddAddress("ma.thoma@gmx.de","Herr Beispiel");

$mail->WordWrap = 50;                              // Zeilenumbruch einstellen
// $mail->AddAttachment("/var/tmp/file.tar.gz");      // Attachment
// $mail->AddAttachment("/tmp/image.jpg", "new.jpg");
$mail->IsHTML(true);                               // als HTML-E-Mail senden

$mail->Subject  =  "Test mit PHPMailer";
$mail->Body     =  "Test mit <b>PHPMailer</b>";
$mail->AltBody  =  "Hallo Empfaenger, dies ist ein Test mit dem PHPMailer unter
Linux und mit PHP ";

if(!$mail->Send())
{
    echo "Die Nachricht konnte nicht versandt werden <p>";
    echo "Mailer Error: " . $mail->ErrorInfo;
    exit;
}

echo "Die Nachricht wurde erfolgreich versandt";

?>