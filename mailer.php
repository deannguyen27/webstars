<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
$secret = "6LfGzYkaAAAAAAXOZCFSYxQWO7skOQ38O5KSTPTk";
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
$resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                      ->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);;

if ($resp->isSuccess()) {   


    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'webstars.mailer@gmail.com';                     //SMTP username
        $mail->Password   = 'Welc0me!2019';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('webstars.mailer@gmail.com', 'Webstars Mailer');
        
        $mail->addAddress('alain@webstars.org', 'Alain Nguyen');     //Add a recipient
        $mail->addAddress('don@webstars.org', 'Don');     //Add a recipient
        $mail->addAddress('nguyenvu27@gmail.com', 'Nguyen Vu');     //Add a recipient
        
        //$mail->addReplyTo('webstars.mailer@gmail.com', 'Webstars Mailer');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        // Form Data
        $name = $_REQUEST['name'] ;
        $email = $_REQUEST['email'] ;
        $subject = $_REQUEST['subject'];
        $message = $_REQUEST['message'] ;
        
        $mail->addReplyTo($email, $name); // Form Submitter's ID

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = 'New Lead Enquiry <br/>' . PHP_EOL . PHP_EOL .
                'Name: ' . $name . '<br/><br/>' . PHP_EOL .          
                'Message: ' . $message . '<br/>' . PHP_EOL;

        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            $ret['code'] = 'error';
            $ret['msg'] = 'Message could not be sent.';
           // echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $ret['code'] = 'success';
            $ret['msg'] = 'Message has been sent';
            
        }
       
    } catch (Exception $e) {
        $ret['code'] = 'error';
        $ret['msg'] = 'Message could not be sent.';
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    $ret['code'] = 'error';
    $ret['msg'] = 'Missing verify the captcha.';
}

echo json_encode($ret);