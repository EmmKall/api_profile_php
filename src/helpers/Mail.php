<?php

namespace Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{

    private function setConfig() {
        $mail           = new PHPMailer();
        $mail->isSMTP();
        $mail->Host     = $_ENV[ 'EMAIL_HOST' ];
        $mail->SMTPAuth = true;
        $mail->Port     = $_ENV[ 'EMAIL_PORT' ];
        $mail->Username = $_ENV[ 'EMAIL_USERNAME' ];
        $mail->Password = $_ENV[ 'EMAIL_PASS' ];
        $mail->setFrom( $_ENV[ 'EMAIL_FROM' ] );
        $mail->addAddress( $_ENV[ 'EMAIL_FROM' ] );
        return $mail;
    }

    public function sendConfirmation( $mail, $name, $body ){
        $mail = $this->setConfig();
        $mail->Subject = 'Confirm account';
        $mail->isHTML( TRUE );
        $mail-> CharSet = 'UTF-8';
        $mail->Body = $body;
        $mail->send();
    }

    public static function create_body( string $pass ): string
    {
        $body = "<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><meta name=\"viewport\" content=\"width=device-width,initial-scale=1\"><title>TaskList</title><style type=\"text/css\">.p-1{padding:1rem}.p-2{padding:2rem}.p-3{padding:3rem}.my-0{margin-top:0;margin-bottom:0}.rounded-lg{border-radius:1rem}.shadow-lg{box-shadow:rgba(0,0,0,.25) 0 54px 55px,rgba(0,0,0,.12) 0 -12px 30px,rgba(0,0,0,.12) 0 4px 6px,rgba(0,0,0,.17) 0 12px 13px,rgba(0,0,0,.09) 0 -3px 5px}.text-center{text-align:center}.mx-auto{margin:auto}.text-blue{color:#336bff}.text-white{color:#fff}.text-gray{color:#686868}.bg-blue{background:#336bff}.bg-gray{background:#dedede}.bg-white{background:#fff}a{text-decoration:none;cursor:pointer;font-weight:bolder}</style></head><body><div class=\"p-3 mx-auto bg-gray\"><div class=\"p-3 bg-white text-blue rounded-lg shadow-lg\"><h1 class=\"my-0 text-blue text-center\">Tasklist</h1><h3 class=\"my-0 text-blue text-center\">Recover password</h3><p class=\"my-0\">TaskList ha actualizado sus credenciales</p><p class=\"my-0\">Se ha enviado tu nuevo password, por favor ingresa al sistema con tus nuevas credenciales</p><p class=\"my-0\">Nuevo password: {$pass}</p><p class=\"my-0 text-gray\">Si usted no ha modificado sus credenciales, ingrese al sistema para actualizar sus credenciales</p><p class=\"my-0 text-gray\">Si este correo no es para usted, haga caso omiso al contenido</p><p>Ingrese siguiente el siguiente link:<a class=\"\" href=\"{$_ENV[ 'URL_APP' ] }login\" target=\"_blank\">TaskList App</a></p></div></div></body></html>";
        return $body;
    }

    public static function sendFastMail( $to, $subject, $body )
    {
        mail( $to, $subject, $body );
    }

    public static function send_mail( string $to, string $body, string $subject, array $files = [] ) //string $copyTo = '', string $hideCopyTo = ''
    {
        $wasSend = false;
        $mail = new PHPMailer();
        $mail->isSMTP();                                      //Send using SMTP para gmail
        try
        {
            //Configure
            $mail->SMTPDebug  = SMTP::DEBUG_OFF;               //Debug mode DEBUG_OFF 0, DEBUG_CLIENT 1, DEBUF_SERVER 2

            $mail->Host       = $_ENV[ 'EMAIL_HOST' ];            //From
            $mail->Port       = $_ENV[ 'EMAIL_PORT' ];            // Port GMAIL TLS 587, SSL 465

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      //Enable implicit SSL o TSL ENCRYPTION_STARTTLS

            $mail->SMTPAuth   = true;                             //SMTP authentication
            $mail->Password   = $_ENV[ 'EMAIL_PASS' ];            //Password email

            //Recipients
            $mail->Username = $_ENV[ 'EMAIL_FROM' ];
            $mail->Password = $_ENV[ 'EMAIL_PASS' ];

            $mail->setFrom( $_ENV[ 'EMAIL_FROM' ] , $_ENV[ 'SYSTEM_NAME' ] );
            //$mail->addReplyTo( 'copyFrom', 'nameCopyFrom' );   //Add in case to add copy from

            //To
            $mail->addAddress( $to, 'User of' . $_ENV[ 'SYSTEM_NAME' ] );  //To send email
            $mail->Subject = $subject;                            //Subject

            //Mail of content
            $mail->isHTML( true );
            $mail->CharSet = 'UTF-8';
            $mail->Body = sprintf( $body );
            /* Alternative text */
            $mail->AltBody = $_ENV[ 'SYSTEM_NAME' ];

            // Attachments
            if( sizeof( $files ) > 0)
            {
                foreach ($files as $file ) {
                    $mail->addAttachment( $file );                    //Attachment file
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional to add a name 
                }
            }
            
            if( $mail->send() ){ $wasSend = true; }

        } catch( Exception $e )
        {
            //Implement Log
            echo $e->getMessage();
        }
        return $wasSend;
    }



}
