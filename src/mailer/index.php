<?php
// This file is only for reference to see how sending email works.


//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/Exception.php';
require __DIR__ . '/PHPMailer.php';
require __DIR__ . '/SMTP.php';

//Load Composer's autoloader to enable phpdotenv in dev environments.
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// Tarkistetaan onko lomake lähetetty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);


    try {

    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $_ENV['SMTP_HOST'] ?? '';                    //Set the SMTP server to send through
    $mail->Port       = $_ENV['SMTP_PORT'] ?? '';                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->SMTPAuth   = $_ENV['SMTP_AUTH'] ?? '';                    //Enable SMTP authentication
    $mail->Username   = $_ENV['SMTP_USER'] ?? '';                    //SMTP username
    $mail->Password   = $_ENV['SMTP_PASSWORD'] ?? '';                //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

    //Recipients
    $mail->setFrom('projektitori@moimail.uk', 'Projektitori');
    $mail->addAddress($_ENV['MAIL_TO'] ?? '', $_ENV['MAIL_TO_NAME'] ?? '');//Add a recipient
//    $mail->addReplyTo('info@example.com', 'Information');

    //Content
    $mail->CharSet = 'UTF-8';                                   //Set charset to UTF-8 for special characters
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = 'Kiitos ideasta!';
    $mail->Body    = 'Hei, kiitos kun lähestyit meitä ideallasi. Se oli varmasti <b>LOISTAVA</b>!';
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<h1>Sähköpostin testisivu</h1>
<form method="POST">
    <button type="submit" name="send_email">Lähetä sähköposti</button>
</form>