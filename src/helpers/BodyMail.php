<?php

namespace Helper;

class BodyMail {

    private static function getFormat(){
        $body = '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>API EMM_KALL</title> <style type="text/css"> body { background: #EEEEEE; display: flex; flex-direction: column; justify-content: space-between; max-width: 90rem; border: 0.2rem solid #0C84B8; border-radius: 1rem; } h1, h2, h3, h4 { color: #0C84B8; font-weight: bolder; margin: 0.2rem auto; } .container { margin: 0rem auto; width: 100%; padding: 0.5rem 2rem; } p, span { color: #636363; } </style> </head> <body> <div class="container"> <section> <h1 style="text-align: center; text-decoration: underline;">API EMMKALL</h1> <h2 style="text-align: center;">$title</h2> <div style="margin: 1rem auto;">$body</div> </section> <section style="text-align: center; line-height: 0.1rem;"> <p style="font-weight: bolder;">API EmmKall-Dev</p> <p>If you are unaware of this email, please ignore it and delete it.</p> <p>This email was sent automatically</p> </section> </div> <footer style="border-bottom-right-radius: 0.8rem; border-bottom-left-radius: 0.8rem; background: #000000; color: #FFFFFF; padding: 0.1rem 2rem; "> <p style="text-align: center;">Made by <a style="text-decoration: none; color: #EEEEEE;" href="https://emmkall.github.io/#/" target="_blank">EmmKall-Dev</a></p> </footer> </body> </html>';
            return $body;
    }

    public static function register( $name, $token ) {
        $content = self::getFormat();
        $body = '<p style="color: #0C84B8;">Welcome <b>' . $name . '</b> to <b>' . $_ENV[ 'SYSTEM_NAME' ] . '</b></p>';
        $body .= '<p>You are one step away from completing your registration in Api EmmKall, click on the following link to complete your registration and be able to interact with the system.</p>';
        $body .= '<p>Comfirm on the next link: <a target="_blank" href=" ' . $_ENV[ 'URL_APP' ] . 'user/confirm/' . $token .'" style="font-bold: bolder; color:#0C84B8; ">Confirm your account</a></p>';
        $content = str_replace( '$title', 'Confirm your account', $content ); 
        $content = str_replace( '$body', $body, $content );
        return $content;
    }
    
    public static function forgetPassword( $password, $name ) {
        $content = self::getFormat();
        $body = '<p style="color: #0C84B8;">' . $name . ' your password was recovered <b>' . $_ENV[ 'SYSTEM_NAME' ] . '</b></p>';
        $body = '<p>Here is your new password: <span style="font-weight: bolder; color: #0C84B8; ">' . $password . '</span></p>';
        $body .= '<p>You are one step away from completing your registration in Api EmmKall, click on the following link to complete your registration and be able to interact with the system.</p>';
        $content = str_replace( '$title', 'Password forgotten', $content ); 
        $content = str_replace( '$body', $body, $content );
        return $content;
    }

}