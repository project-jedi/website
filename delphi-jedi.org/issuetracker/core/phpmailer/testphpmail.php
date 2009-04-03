<?

require("class.phpmailer.php");

$mail = new phpmailer();

$mail->IsMail();

/*$mail->IsSMTP();     
$mail->Mailer   = "smtp";  
$mail->Host     = "82.67.45.121"; // SMTP-Server
$mail->Port     = 25;*/
//$mail->SMTPAuth = true;     // SMTP mit Authentifizierung benutzen
// $mail->SMTPDebug = true;
//$mail->SMTPAuth = true;
//$mail->Username = "jedi";  // SMTP-Benutzername
//$mail->Password = "jedimail"; // SMTP-Passwort
$mail->LE = "\n";
$mail->From     = "webmaster@delphi-jedi.org";
$mail->FromName = "Max Mustermann";
$mail->AddAddress("obones@free.fr","fdsfds");

$mail->WordWrap = 50;                              // Zeilenumbruch einstellen
// $mail->AddAttachment("/var/tmp/file.tar.gz");      // Attachment
// $mail->AddAttachment("/tmp/image.jpg", "new.jpg");
$mail->IsHTML(true);                               // als HTML-E-Mail senden

$mail->Subject  =  "Test of PHPMailer";
$mail->Body     =  "Test with <b>PHPMailer</b>";
$mail->AltBody  =  "Hello, this is a test";

if(!$mail->Send())
{
    echo "Unable to send email <p>";
    echo "Mailer Error: " . $mail->ErrorInfo;
    exit;
}

echo "Email sent";

?>