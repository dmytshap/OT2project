<?php
// This file is only for reference to see how sending email works.


//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mailer/Exception.php';
require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';

//Load Composer's autoloader (created by composer, not included with PHPMailer)
//require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


try {

    // -------------------------------------------
    // This part needs to be in .env file!!!
    // -------------------------------------------
    putenv('SMTP_HOST=mail-eu.smtp2go.com');
    putenv('SMTP_PORT=8465');
    putenv('SMTP_AUTH=true');
    putenv('SMTP_USER=...........');
    putenv('SMTP_PASSWORD=...........');
    // -------------------------------------------

    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = getenv('SMTP_HOST');                    //Set the SMTP server to send through
    $mail->Port       = getenv('SMTP_PORT');                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->SMTPAuth   = getenv('SMTP_AUTH');                    //Enable SMTP authentication
    $mail->Username   = getenv('SMTP_USER');                    //SMTP username
    $mail->Password   = getenv('SMTP_PASSWORD');                //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

    //Recipients
    $mail->setFrom('projektitori@moimail.uk', 'Projektitori');
    $mail->addAddress('temp1000@moimail.uk', 'Elmo Ilmoittaja');//Add a recipient
//    $mail->addReplyTo('info@example.com', 'Information');

    //Content
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = 'Kiitos ideasta!';
    $mail->Body    = 'Hei, kiitos kun lähestyit meitä ideallasi. Se oli varmasti <b>LOISTAVA</b>!';
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}