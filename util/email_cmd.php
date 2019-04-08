<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 16/10/2018
 * Time: 16:45
 */

require '../PHPMailer-6.0.5/src/Exception.php';
require '../PHPMailer-6.0.5/src/PHPMailer.php';
require '../PHPMailer-6.0.5/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function SendEmail($from, $fromName, $list_emails, $subject, $body) {
//    echo $from . ' - ' . $fromName . ' - ' . $to . ' - ' . $subject;
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    $mail->CharSet = 'UTF-8';
    try {
        //Server settings
//    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'email-smtp.us-east-1.amazonaws.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'AKIAJRS2KEXE77J2ID6A';                 // SMTP username
        $mail->Password = 'AoWoYGIDWO7tQXttvArcXTRS6sJllRd1YBvLOeYmyJV7';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
//    $mail->setFrom('giaovien@trangnguyen.edu.vn', 'Giáo viên Trạng Nguyên');
        $mail->setFrom($from, $fromName);
//        $mail->addAddress('tan.mac@trangnguyen.edu.vn', 'Mạc Tân');     // Add a recipient
//        $mail->addAddress($to);               // Name is optional
        foreach ($list_emails as $email) {
            $mail->addAddress($email);
        }
        $mail->addReplyTo($from, 'Reply');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

        //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail->send();
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}
