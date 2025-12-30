<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../config/error_logging.php';
require __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

$config = parse_ini_file(__DIR__ . '/../../config.ini', true);
$username = $config['gmail']['username'];
$password = $config['gmail']['password'];

try {
  $mail->SMTPDebug  = SMTP::DEBUG_OFF; // SMTP::DEBUG_SERVER (for development)
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = $username;
  $mail->Password   = $password;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port       = 465;

  //Recipient
  $mail->setFrom($username, 'STMCP'); // replace with STMCP email
  $mail->addAddress($recipientEmail, $recipientName);

  $mail->isHTML(true);
  $mail->Subject = 'Application Received! Welcome to the club!';

  $templatePath = __DIR__ . '/../templates/successful_application_email.html';
  $body = file_get_contents($templatePath);
  $body = str_replace(
    ['{{name}}', '{{temp_username}}', '{{temp_password}}', '{{one_star_logo}}'],
    [$recipientName, $tempUsername, $tempPassword, 'one_star_logo'],
    $body
  );

  $mail->addEmbeddedImage(__DIR__ . '/../../src/images/logo/one_star_logo.png', 'one_star_logo');

  $mail->Body = $body;
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();

  // sendResponse('Message has been sent', [], 'success');
} catch (Exception $e) {
  error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
  sendResponse('error', $e);
} catch (\Throwable $e) {
  error_log("Error sending email: $e");
  sendResponse('Sending email failed');
}
