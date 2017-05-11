<?php

class AccountController extends BaseController
{

    public function __construct()
    {
        Session::put("nav-active", "user-dashboard");
    }

    public function createAccountView()
    {
        return View::make('account/create');
    }

    public function createAccountSubmit()
    {
        $rules = array(
            "username" => "required|min:6",
            "password" => "required|min:6|max:48|regex:/^.*(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!$#*]).*$/",
            "confirm_password" => "required|same:password",
            "email" => "required|email",
        );

        $messages = array(
            'password.regex' => 'Password needs to contain at least (a) One lower case letter (b) One Upper case letter and (c) One number (d) One of the following special characters - !@#$&*',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) {
            return Redirect::to("create")
                ->withInput(Input::except('password', 'password_confirm'))
                ->withErrors($validator);
        }

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        if (Keycloak::usernameExists($username)) {
            return Redirect::to("create")
                ->withInput(Input::except('password', 'password_confirm'))
                ->with("username_exists", true);
        } else {

            $admin_authz_token = Keycloak::getAdminAuthzToken();

            $gatewayId = Config::get('pga_config.airavata')['gateway-id'];
            $user_details = new Airavata\Model\User\UserProfile();
            $user_details->userId = $username;
            $user_details->emails = array($email);
            $user_details->firstName = $first_name;
            $user_details->lastName = $last_name;
            $user_details->gatewayId = $gatewayId;
            $user_details->creationTime = 0;
            $user_details->lastAccessTime = 0;
            $user_details->validUntil = 0;
            $user_details->State = Airavata\Model\User\Status::PENDING;

            // TODO: do we need to pass this if we are passing an access token?
            // Couldn't the backend just use the access token?
            $realm_admin_credentials = new Airavata\Model\Credential\Store\PasswordCredential();
            $realm_admin_credentials->gatewayId = $gatewayId;
            $realm_admin_credentials->portalUserName = Config::get('pga_config.wsis')['admin-username'];
            $realm_admin_credentials->loginUserName = Config::get('pga_config.wsis')['admin-username'];
            $realm_admin_credentials->password = Config::get('pga_config.wsis')['admin-password'];

            IamAdminServices::registerUser($admin_authz_token, $user_details, $realm_admin_credentials, $password);

            /*add user to the initial role */

            // TODO: add user to initial role, etc.
            // $initialRoleName = CommonUtilities::getInitialRoleName();
            // $allRoles = Keycloak::getAllRoles();
            // 
            // $userRoles["new"] = $initialRoleName;
            // 
            // if(  Config::get('pga_config.portal')['super-admin-portal'] == true ){
            // 
            //     $userRoles["new"] = array("gateway-provider", "admin");
            // }
            // $userRoles["deleted"] = array();
            // // FIXME: this requires the $user_id, not the $username
            // Keycloak::updateUserRoles( $username, $userRoles);

            CommonUtilities::print_success_message('Account confirmation request was sent to your email account');
            return View::make('home');
        }
    }

    public function loginView()
    {
        if(Config::get('pga_config.wsis')['oauth-grant-type'] == "authorization_code"){
            $url = Keycloak::getOAuthRequestCodeUrl();
            return Redirect::away($url);
        }else{
            if(CommonUtilities::id_in_session()){
                return Redirect::to("home");
            }else
                return View::make('account/login');
        }
    }

    public function loginSubmit()
    {
        if (CommonUtilities::form_submitted()) {
            $wsisConfig = Config::get('pga_config.wsis');
            $username = Input::get("username");

            $password = $_POST['password'];
            $response = Keycloak::authenticate($username, $password);
            if(!isset($response->access_token)){
                if (Keycloak::isUpdatePasswordRequired($username)) {
                    return Redirect::to("login")->with("update-password-required", true);
                } else {
                    return Redirect::to("login")->with("invalid-credentials", true);
                }
            }

            $accessToken = $response->access_token;
            $refreshToken = $response->refresh_token;
            $expirationTime = time() + $response->expires_in - 300; // 5 minutes safe margin

            $userProfile = Keycloak::getUserProfileFromOAuthToken($accessToken);
            $username = $userProfile['username'];
            $userRoles = $userProfile['roles'];

            $authzToken = new Airavata\Model\Security\AuthzToken();
            $authzToken->accessToken = $accessToken;
            $authzToken->claimsMap['gatewayID'] = Config::get('pga_config.airavata')['gateway-id'];
            $authzToken->claimsMap['userName'] = $username;

            Session::put('authz-token',$authzToken);
            Session::put('oauth-refresh-code',$refreshToken);
            Session::put('oauth-expiration-time',$expirationTime);
            Session::put("user-profile", $userProfile);

            Session::put("roles", $userRoles);
            if (in_array(Config::get('pga_config.wsis')['admin-role-name'], $userRoles)) {
                Session::put("admin", true);
            }
            if (in_array(Config::get('pga_config.wsis')['read-only-admin-role-name'], $userRoles)) {
                Session::put("authorized-user", true);
                Session::put("admin-read-only", true);
            }
            if (in_array(Config::get('pga_config.wsis')['user-role-name'], $userRoles)) {
                Session::put("authorized-user", true);
            }
            //gateway-provider-code
            if (in_array("gateway-provider", $userRoles)) {
                Session::put("gateway-provider", true);
            }
            //only for super admin
            if(  Config::get('pga_config.portal')['super-admin-portal'] == true && Session::has("admin")){
                Session::put("super-admin", true);
            }
            CommonUtilities::store_id_in_session($username);
            Session::put("gateway_id", Config::get('pga_config.airavata')['gateway-id']);

            if(Session::has("admin") || Session::has("admin-read-only") || Session::has("authorized-user")){
                return $this->initializeWithAiravata($username);
            }

            if(Session::has("admin") || Session::has("admin-read-only")){

                return Redirect::to("admin/dashboard");
            }else{
                return Redirect::to("account/dashboard");
            }
        }

    }

    public function oauthCallback()
    {
        if (!isset($_GET["code"])) {
            return Redirect::to('home');
        }

        $code = $_GET["code"];
        $response = Keycloak::getOAuthToken($code);
        if(!isset($response->access_token)){
            return Redirect::to('home');
        }

        $accessToken = $response->access_token;
        $refreshToken = $response->refresh_token;
        $expirationTime = time() + $response->expires_in - 300; //5 minutes safe margin

        $userProfile = Keycloak::getUserProfileFromOAuthToken($accessToken);
        Log::debug("userProfile", array($userProfile));
        $username = $userProfile['username'];

        $userRoles = $userProfile['roles'];

        //FIXME There is a bug in WSO2 IS which doest not return the admin role for the default admin user.
        //FIXME Hence as a workaround we manually add it here.
        if ($username == Config::get('pga_config.wsis')['admin-username']
            || $username == Config::get('pga_config.wsis')['admin-username'] . '@' . Config::get('pga_config.wsis')['tenant-domain']){
            $userRoles[] = Config::get('pga_config.wsis')['admin-role-name'];
        }

        $authzToken = new Airavata\Model\Security\AuthzToken();
        $authzToken->accessToken = $accessToken;
        $authzToken->claimsMap = array('userName'=>$username, 'gatewayID'=> Config::get('pga_config.airavata')['gateway-id']);
        Session::put('authz-token',$authzToken);
        Session::put('oauth-refresh-code',$refreshToken);
        Session::put('oauth-expiration-time',$expirationTime);
        Session::put("user-profile", $userProfile);

        if (in_array(Config::get('pga_config.wsis')['admin-role-name'], $userRoles)) {
            Session::put("admin", true);
        }
        if (in_array(Config::get('pga_config.wsis')['read-only-admin-role-name'], $userRoles)) {
            Session::put("admin-read-only", true);
        }
        if (in_array(Config::get('pga_config.wsis')['user-role-name'], $userRoles)) {
            Session::put("authorized-user", true);
        }

        CommonUtilities::store_id_in_session($username);
        Session::put("gateway_id", Config::get('pga_config.airavata')['gateway-id']);

        if(Session::get("admin") || Session::get("admin-read-only") || Session::get("authorized-user")){
            return $this->initializeWithAiravata($username);
        }
        return Redirect::to("home");
    }

    private function initializeWithAiravata($username){

        // Log the user out if Airavata is down. If a new user we want to make
        // sure we create the default project and setup experiment storage
        // before they do anything else.
        if (!CommonUtilities::isAiravataUp()) {
            Session::flush();
            return Redirect::to("home")->with("airavata-down", true);
        }

        //creating a default project for user
        $projects = ProjectUtilities::get_all_user_projects(Config::get('pga_config.airavata')['gateway-id'], $username);
        if($projects == null || count($projects) == 0){
            //creating a default project for user
            ProjectUtilities::create_default_project($username);
        }

        $dirPath = Config::get('pga_config.airavata')['experiment-data-absolute-path'] . "/" . Session::get('username');
        if(!file_exists($dirPath)){
            $old_umask = umask(0);
            mkdir($dirPath, 0777, true);
            umask($old_umask);
        }

        if(Session::has("admin") || Session::has("admin-read-only")){
            return Redirect::to("admin/dashboard");
        }else{
            return Redirect::to("account/dashboard");
        }
    }

    public function forgotPassword()
    {
//        $capatcha = WSIS::getCapatcha()->return;
        return View::make("account/forgot-password");
    }

    public function forgotPasswordSubmit()
    {
        $username = Input::get("username");
        if(empty($username)){
            CommonUtilities::print_error_message("Please provide a valid username");
            return View::make("account/forgot-password");
        }else{
            $wsisConfig = Config::get('pga_config.wsis');
            if( $wsisConfig['tenant-domain'] == "")
                $username = $username;
            else
                $username = $username . "@" . $wsisConfig['tenant-domain'];
            try{
                $key = WSIS::validateUser(Input::get("userAnswer"),Input::get("imagePath"),Input::get("secretKey"), $username);
                if(!empty($key)){
                    $result = WSIS::sendPasswordResetNotification($username, $key);
                    if($result===true){
                        CommonUtilities::print_success_message("Password reset notification was sent to your email account");
                        return View::make("home");
                    }else{
                        CommonUtilities::print_error_message("Failed to send password reset notification email");
                        return View::make("home");
                    }
                }else{
                    CommonUtilities::print_error_message("Failed to validate the given username");
                    return View::make("account/forgot-password");
                }
            }catch (Exception $ex){
                CommonUtilities::print_error_message("Password reset operation failed");
                return View::make("home");
            }
        }
    }

    public function dashboard(){

        $userProfile = Session::get("user-profile");

        if( in_array( "gateway-provider", $userProfile["roles"]) ) {
            $gatewayOfUser = "";

            $gatewaysInfo = CRUtilities::getAllGateways();
            foreach ($gatewaysInfo as $index => $gateway) {
                if ($gateway->emailAddress == $userProfile["email"]) {
                    Session::set("gateway_id", $gateway->gatewayId);
                    $gatewayOfUser = $gateway->gatewayId;
                    Session::forget("super-admin");
                    break;
                }
            }
            if ($gatewayOfUser == "") {
                $userInfo["username"] = $userProfile["username"];
                $userInfo["email"] = $userProfile["email"];
                Session::put("new-gateway-provider", true);
            }
        }

        return View::make("account/dashboard");
    }

    public function resetPassword()
    {
        $confirmation = Input::get("confirmation");
        $username = Input::get("username");
        if(empty($username) || empty($confirmation)){
            return View::make("home");
        }else{
            $wsisConfig = Config::get('pga_config.wsis');
            if( $wsisConfig['tenant-domain'] == "")
                $username = $username;
            else
                $username = $username . "@" . $wsisConfig['tenant-domain'];
            try{
                $key = WSIS::validateConfirmationCode($username, $confirmation);
                if(!empty($key)){
                    return View::make("account/reset-password", array("key" => $key, "username"=>$username));
                }else{
                    return View::make("home");
                }
            }catch (Exception $e){
                return View::make("home");
            }
        }

    }

    public function confirmAccountCreation()
    {
        $confirmation = Input::get("confirmation");
        $username = Input::get("username");
        if(empty($username) || empty($confirmation)){
            return View::make("home");
        }else{
            try{
//                if(Input::has("userAnswer")){
                    $result = WSIS::confirmUserRegistration($username, $confirmation, Config::get('pga_config.wsis')['tenant-domain']);
                    if($result->verified){
                        $this->sendAccountCreationNotification2Admin($username);
                        return Redirect::to("login");
//                    }else if(!$result->verified && preg_match('/Error while validating captcha for user/',$result->error) ){
//                        CommonUtilities::print_error_message("Captcha Verification failed!");
//                        $capatcha = WSIS::getCapatcha()->return;
//                        return View::make("account/verify-human", array("username"=>$username,"code"=>$confirmation,
//                            "imagePath"=>$capatcha->imagePath, "secretKey"=>$capatcha->secretKey,
//                            "imageUrl"=> Config::get("pga_config.wsis")["service-url"] . $capatcha->imagePath));
                    }else{
                        CommonUtilities::print_error_message("Account confirmation failed! Please contact the Gateway Admin");
                        return View::make("home");
                    }
//                }else{
//                    $capatcha = WSIS::getCapatcha()->return;
//                    return View::make("account/verify-human", array("username"=>$username,"code"=>$confirmation,
//                        "imagePath"=>$capatcha->imagePath, "secretKey"=>$capatcha->secretKey,
//                        "imageUrl"=> Config::get("pga_config.wsis")["service-url"] . $capatcha->imagePath));
//                }
            }catch (Exception $e){
                CommonUtilities::print_error_message("Account confirmation failed! Please contact the Gateway Admin");
                return View::make("home");
            }
        }
    }

    private function sendAccountCreationNotification2Admin($username){

        $mail = new PHPMailer;

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

        $recipients = Config::get('pga_config.portal')['admin-emails'];
        foreach($recipients as $recipient){
            $mail->addAddress($recipient);
        }

        $mail->isHTML(true);

        $mail->Subject = "New User Account Was Created Successfully";
        $userProfile = Keycloak::getUserProfile($username);
        $wsisConfig = Config::get('pga_config.wsis');
        if( $wsisConfig['tenant-domain'] == "")
            $username = $username;
        else
            $username = $username . "@" . $wsisConfig['tenant-domain'];

        $str = "Gateway Portal: " . $_SERVER['SERVER_NAME'] ."<br/>";
        $str = $str . "Username: " . $username ."<br/>";
        $str = $str . "Name: " . $userProfile["firstname"] . " " . $userProfile["lastname"] . "<br/>";
        $str = $str . "Email: " . $userProfile["email"];

        $mail->Body = $str;
        $mail->send();
    }

    public function resetPasswordSubmit()
    {
        $rules = array(
            "new_password" => "required|min:6",
            "confirm_new_password" => "required|same:new_password",
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to("reset-password")
                ->withInput(Input::except('new_password', 'confirm)new_password'))
                ->withErrors($validator);
        }

        $key =  $_POST['key'];
        $username =  $_POST['username'];
        $new_password =  $_POST['new_password'];

        try{
            $result = WSIS::resetPassword($username, $new_password, $key);
            if($result){
                CommonUtilities::print_success_message("User password was reset successfully");
                return View::make("account/login");
            }else{
                CommonUtilities::print_error_message("Resetting user password operation failed");
                return View::make("account/home");
            }
        }catch (Exception $e){
            CommonUtilities::print_error_message("Resetting user password operation failed");
            return View::make("account/home");
        }
    }


    public function logout()
    {
       Session::flush();
       if(Config::get('pga_config.wsis')['oauth-grant-type'] == "authorization_code"){
           return Redirect::away(Keycloak::getOAuthLogoutUrl(URL::to("/")));
       }
       return Redirect::to('home');
    }

    public function allocationRequestView(){
        return View::make("account/request-allocation");
    }

    public function allocationRequestSubmit(){
        return 'result';
    }

    public function noticeSeenAck(){
        Session::put( "notice-count", Input::get("notice-count"));
        Session::put("notice-seen", true);
    }
}
