<?php


class EmailUtilities
{

    public static function sendVerifyEmailAccount($username, $firstName, $lastName, $email){
        $portalConfig = Config::get('pga_config.portal');
        $validTime = isset($portalConfig['mail-verify-code-valid-time']) ? $portalConfig['mail-verify-code-valid-time'] : 30;
        $code = uniqid();
        Cache::put('PGA-VERIFY-EMAIL-' . $username, $code, $validTime);

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->account_verification->subject;
        $body = trim(implode($emailTemplates->account_verification->body));

        $body = str_replace("\$url", URL::to('/') . '/confirm-user-registration?username=' . $username . '&code=' . $code, $body);
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

    public static function sendVerifyUpdatedEmailAccount($username, $firstName, $lastName, $email){
        $portalConfig = Config::get('pga_config.portal');
        $validTime = isset($portalConfig['mail-verify-code-valid-time']) ? $portalConfig['mail-verify-code-valid-time'] : 30;
        $code = uniqid();
        Cache::put('PGA-VERIFY-UPDATED-EMAIL-' . $username, $code, $validTime);

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->email_update_verification->subject;
        $body = trim(implode($emailTemplates->email_update_verification->body));

        $body = str_replace("\$url", URL::to('/') . '/user-profile-confirm-email?username=' . $username . '&code=' . $code, $body);
        $body = str_replace("\$firstName", $firstName, $body);
        $body = str_replace("\$lastName", $lastName, $body);
        $body = str_replace("\$validTime", $validTime, $body);

        EmailUtilities::sendEmail($subject, [$email], $body);
    }

    public static function verifyUpdatedEmailAccount($username, $code){
        if(Cache::has('PGA-VERIFY-UPDATED-EMAIL-' . $username)){
            $storedCode = Cache::get('PGA-VERIFY-UPDATED-EMAIL-' . $username);
            Cache::forget('PGA-VERIFY-UPDATED-EMAIL-' . $username);
            return $storedCode == $code;
        }else{
            return false;
        }
    }


    public static function sendPasswordResetEmail($username, $firstName, $lastName, $email){
        $portalConfig = Config::get('pga_config.portal');
        $validTime = isset($portalConfig['mail-verify-code-valid-time']) ? $portalConfig['mail-verify-code-valid-time'] : 30;
        $code = uniqid();
        Cache::put('PGA-RESET-PASSWORD-' . $username, $code, $validTime);

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->password_reset->subject;
        $body = trim(implode($emailTemplates->password_reset->body));

        $body = str_replace("\$url", URL::to('/'). '/reset-password?username=' . urlencode($username) . '&code='.urlencode($code), $body);
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

    //PGA sends email to Admin about new request
    public static function gatewayRequestMail($firstName, $lastName, $email, $gatewayName){

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->gateway_request->subject;
        $body = trim(implode($emailTemplates->gateway_request->body));

        $body = str_replace("\$url", URL::to('/') . '/admin/dashboard/gateway', $body);
        $body = str_replace("\$firstName", $firstName, $body);
        $body = str_replace("\$lastName", $lastName, $body);
        $body = str_replace("\$gatewayName", $gatewayName, $body);

        EmailUtilities::sendEmail($subject, [$email], $body);

    }

    //PGA sends email to User when Gateway is UPDATED
    public static function mailToUser($firstName, $lastName, $email, $gatewayId){

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->update_to_user->subject;
        $body = trim(implode($emailTemplates->update_to_user->body));

        $body = str_replace("\$url", URL::to('/') . '/admin/dashboard', $body);
        $body = str_replace("\$firstName", $firstName, $body);
        $body = str_replace("\$lastName", $lastName, $body);
        $body = str_replace("\$gatewayId", $gatewayId, $body);

        EmailUtilities::sendEmail($subject, [$email], $body);

    }

    //PGA sends email to Admin when Gateway is UPDATED
    public static function mailToAdmin($email, $gatewayId){

        $emailTemplates = json_decode(File::get(app_path() . '/config/email_templates.json'));
        $subject = $emailTemplates->update_to_admin->subject;
        $body = trim(implode($emailTemplates->update_to_admin->body));

        $body = str_replace("\$url", URL::to('/') . '/admin/dashboard/gateway', $body);
        $body = str_replace("\$gatewayId", $gatewayId, $body);

        EmailUtilities::sendEmail($subject, [$email], $body);

    }

    public static function sendEmail($subject, $recipients, $body){

        $mail = new PHPMailer();

        $mail->isSMTP();
        // Note: setting SMTPDebug will cause output to be dumped into the
        // response, so only enable for testing purposes
        // $mail->SMTPDebug = 3;
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