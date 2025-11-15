<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../config/error_logging.php';
require __DIR__ . '/../../../vendor/autoload.php';

$mail = new PHPMailer(true);

$config = parse_ini_file(__DIR__ . '/../../../config.ini', true);
$username = $config['gmail']['username'];
$password = $config['gmail']['password'];

try {
  $mail->SMTPDebug  = SMTP::DEBUG_OFF; // SMTP::DEBUG_SERVER | SMTP::DEBUG_OFF (production)
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = $username;
  $mail->Password   = $password;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port       = 465;

  //Recipients
  $mail->setFrom($username, 'STMCP'); // replace with STMCP email
  $mail->addAddress($recipientEmail, $recipientName); // (name is optional)

  $mail->isHTML(true);
  $mail->Subject = 'Membership Application [sent using PHPMailer]';

  $templatePath = __DIR__ . '/../../templates/application_received.html';
  $body = file_get_contents($templatePath);
  $body = str_replace(
    ['{{name}}', '{{temp_username}}', '{{temp_password}}', '{{one_star_logo}}'],
    [$recipientName, $tempUsername, $tempPassword, 'one_star_logo'],
    $body
  );

  $mail->addEmbeddedImage(__DIR__ . '/../../assets/img/logo/one-star-logo.png', 'one_star_logo');

  $mail->Body = $body;
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();

  // sendResponse('success', 'Message has been sent');
} catch (Exception $e) {
  error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
  sendResponse('error', $e->getMessage());
}
