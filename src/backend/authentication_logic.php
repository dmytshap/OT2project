<?php
session_start();

require_once 'database_user_session.php';
require_once __DIR__ . '/../env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Composerin autoloader, jotta PHPMailer toimii
require_once __DIR__ . '/../vendor/autoload.php';

// Tuleeko add vai login actioni
$action = $_POST['action'] ?? '';


// If server requested POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jos action on Generate, kutsu generateOTPAddUserToDatabase()
    if ($action == 'generate') {
        $otp = generateOTPAddUserToDatabase();
        $email = $_POST['sahkoposti_login'] ?? '';
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

                $mail->setFrom('projektitori@uef.fi', 'Projektitori');
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
        
        echo "$mailStatusMessage<br>";
        echo "Sinun OTP koodi on: $otp";
        exit;
    }
    //Jos action on login, kutsu login_check_user()
    if ($action === 'login') {
        login_check_user();
        header("Location: virhe.html");
    }
}
?>