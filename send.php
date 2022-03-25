<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

if(isset($_POST["send"]))
{
    $sender_name   = $_POST["sender_name"];
    $sender        = $_POST["sender"];
    $subject       = $_POST["subject"];
    $attachments   = $_FILES["attachments"]["name"];
    $recipient     = explode(",",$_POST["recipient"]);
    $body          =       $_POST["body"];
    


    // echo "<pre>";
    // print_r($recipient);
    // echo "</pre>";
    // die();

    $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'hostcreator465@gmail.com';                     //SMTP username
    $mail->Password   = '';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($sender, $sender_name);
    foreach($recipient as $res)
    {
    $mail->addAddress($res);     //Add a recipient
    }
    //Attachments
    for ($i=0; $i < count($attachments); $i++) 
    {
        $file_tmp = $_FILES["attachments"]["tmp_name"][$i];
        $file_name = $_FILES["attachments"]["name"][$i];
        move_uploaded_file($file_tmp,"attachments/".$file_name);
        $mail->addAttachment("attachments/".$file_name);         //Add attachments
    }
    
   

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}



