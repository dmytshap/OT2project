<?php
session_start();

require 'database_user_session.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;


// Composerin autoloader, jotta phpdotenv toimii
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
    $dotenv = Dotenv::createImmutable(__DIR__ , '/../.env');
    $dotenv->safeLoad();
}


// Tuleeko add vai login actioni
$action = $_POST['action'] ?? '';


// If server requested POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jos action on Generate, kutsu generateOTPAddUserToDatabase()
    if ($action == 'generate') {
        $otp = generateOTPAddUserToDatabase();
        $email = $_POST['input-email'] ?? '';
        $mailStatusMessage = 'Sähköpostia ei voitu lähettää, mutta OTP-koodi luotiin.';

        if ($otp && $email) {
            $mail = new PHPMailer(true);

            try {
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = $_ENV['SMTP_HOST'] ?? '';
                $mail->Port       = $_ENV['SMTP_PORT'] ?? '';
                $mail->SMTPAuth   = $_ENV['SMTP_AUTH'] ?? '';
                $mail->Username   = $_ENV['SMTP_USER'] ?? '';
                $mail->Password   = $_ENV['SMTP_PASSWORD'] ?? '';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

                $mail->setFrom('projektitori@moimail.uk', 'Projektitori');
                $mail->addAddress($email);

                $mail->CharSet = 'UTF-8';
                $mail->isHTML(true);
                $mail->Subject = 'OTP-koodi kirjautumiseen';
                $mail->Body    = 'Kirjautumisen OTP-koodisi on: <b>' . htmlspecialchars($otp) . '</b>.';

                $mail->send();
                $mailStatusMessage = 'Sähköpostipalvelin toimii. OTP-koodi lähetettiin sähköpostiin.';
            } catch (Exception $e) {
                $mailStatusMessage = "OTP-koodia ei voitu lahettaa. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        
        $_SESSION['auth_mail_status'] = $mailStatusMessage;
        $_SESSION['auth_debug_otp'] = $otp;
        header('Location: /vahvistuskoodi?email=' . urlencode($email));
        exit;
    }
    //Jos action on login, kutsu login_check_user()
    if ($action === 'login') {
        login_check_user();
        exit;
    }
}
?>