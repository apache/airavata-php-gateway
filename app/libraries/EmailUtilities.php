<?php


class EmailUtilities
{

    public static function sendVerifyEmailAccount($username, $firstName, $lastName, $email){
        $validTime = Config::get('pga_config.portal')['mail-verify-code-valid-time'];
        $code = uniqid();
        Cache::put('PGA-VERIFY-EMAIL-' . $username, $code, $validTime);

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->account_verification->subject;
        $body = trim(implode($emailTemplates->account_verification->body));

        $body = str_replace("\$url", URL::to('/') . '/confirmAccountCreation?username=' . $username . '&code=' . $code, $body);
        $body = str_replace("\$firstName", $firstName, $body);
        $body = str_replace("\$lastName", $lastName, $body);
        $body = str_replace("\$validTime", $validTime, $body);

        EmailUtilities::sendEmail($subject, [$email], $body);
    }

    public static function verifyEmailVerification($username, $code){
        if(Cache::has('PGA-VERIFY-EMAIL-' . $username)){
            $storedCode = Cache::get('PGA-VERIFY-EMAIL-' . $username);
            Cache::forget('PGA-VERIFY-EMAIL-' . $username);
            return $storedCode == $code;
        }else{
            return false;
        }
    }

    public static function sendPasswordResetEmail($username, $firstName, $lastName, $email){
        $validTime = Config::get('pga_config.portal')['mail-verify-code-valid-time'];
        $code = uniqid();
        Cache::put('PGA-RESET-PASSWORD-' . $username, $code, $validTime);

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->password_reset->subject;
        $body = trim(implode($emailTemplates->password_reset->body));

        $body = str_replace("\$url", URL::to('/'). '/resetPassword?username=' . $username . '&code='.$code, $body);
        $body = str_replace("\$firstName", $firstName, $body);
        $body = str_replace("\$lastName", $lastName, $body);
        $body = str_replace("\$validTime", $validTime, $body);

        EmailUtilities::sendEmail($subject, [$email], $body);
    }

    public static function verifyPasswordResetCode($username, $code){
        if(Cache::has('PGA-RESET-PASSWORD-' . $username)){
            $storedCode = Cache::get('PGA-RESET-PASSWORD-' . $username);
            Cache::forget('PGA-RESET-PASSWORD-' . $username);
            return $storedCode == $code;
        }else{
            return false;
        }
    }

    public static function sendEmail($subject, $recipients, $body){

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPDebug = 3;
        $mail->Host = Config::get('pga_config.portal')['portal-smtp-server-host'];

        $mail->SMTPAuth = true;

        $mail->Username = Config::get('pga_config.portal')['portal-email-username'];
        $mail->Password = Config::get('pga_config.portal')['portal-email-password'];

        $mail->SMTPSecure = "tls";
        $mail->Port = intval(Config::get('pga_config.portal')['portal-smtp-server-port']);

        $mail->From = Config::get('pga_config.portal')['portal-email-username'];
        $mail->FromName = "Airavata PHP Gateway";

        $mail->Encoding    = '8bit';
        $mail->ContentType = 'text/html; charset=utf-8\r\n';

        foreach($recipients as $recipient){
            $mail->addAddress($recipient);
        }

        $mail->Subject = $subject;
        $mail->Body = html_entity_decode($body);
        $mail->send();
    }
}