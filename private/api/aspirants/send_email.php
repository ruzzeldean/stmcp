<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
  $mail->addAddress($recipientEmail, $recipientName);

  $mail->isHTML(true);
  $mail->Subject = "Congratulations! You're Now an Official Member of STMCP";

  $templatePath = __DIR__ . '/../../templates/approved_application_email.html';
  $body = file_get_contents($templatePath);
  $body = str_replace(
    ['{{name}}', '{{one_star_logo}}'],
    [$recipientName, 'one_star_logo'],
    $body
  );


  $mail->addEmbeddedImage(__DIR__ . '/../../assets/images/logo/one_star_logo.png', 'one_star_logo');

  $mail->Body = $body;
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();

} catch (Exception $e) {
  error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
  sendResponse('error', $e->getMessage());
}
