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

        $organization = isset($_POST['organization']) ? $_POST['organization'] : null;
        $address = isset($_POST['address']) ? $_POST['address'] : null;
        $country = isset($_POST['country']) ? $_POST['country'] : null;
        $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : null;
        $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : null;
        $im = isset($_POST['im']) ? $_POST['im'] : null;
        $url = isset($_POST['url']) ? $_POST['url'] : null;

        if (WSIS::usernameExists($username)) {
            return Redirect::to("create")
                ->withInput(Input::except('password', 'password_confirm'))
                ->with("username_exists", true);
        } else {

            WSIS::registerUserAccount($username, $password, $email, $first_name, $last_name, $organization, $address, $country, $telephone, $mobile, $im, $url,
                Config::get('pga_config.wsis')['tenant-domain']);

            /*add user to role - user-pending */

            $allRoles = WSIS::getAllRoles();
            if(! in_array( "user-pending", $allRoles)){
                WSIS::addRole( "user-pending");
            }

            $userRoles["new"] = "user-pending";

            if(  Config::get('pga_config.portal')['super-admin-portal'] == true ){

                if(! in_array( "gateway-provider", $allRoles)){
                    WSIS::addRole( "gateway-provider");
                }
                $userRoles["new"] = array("gateway-provider", "admin");
            }
            $userRoles["deleted"] = array();
            WSIS::updateUserRoles( $username, $userRoles);

            CommonUtilities::print_success_message('Account confirmation request was sent to your email account');
            return View::make('home');
        }
    }

    public function loginView()
    {
        if(Config::get('pga_config.wsis')['oauth-grant-type'] == "authorization_code"){
            $url = WSIS::getOAuthRequestCodeUrl();
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
            if( $wsisConfig['tenant-domain'] == "")
                $username = Input::get("username");
            else
                $username = Input::get("username") . "@" . $wsisConfig['tenant-domain'];

            $password = $_POST['password'];
            $response = WSIS::authenticate($username, $password);
            if(!isset($response->access_token)){
                return Redirect::to("login")->with("invalid-credentials", true);
            }

            $accessToken = $response->access_token;
            $refreshToken = $response->refresh_token;
            $expirationTime = time() + $response->expires_in - 5; //5 seconds safe margin

            $userProfile = WSIS::getUserProfileFromOAuthToken($accessToken);
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
        $response = WSIS::getOAuthToken($code);
        if(!isset($response->access_token)){
            return Redirect::to('home');
        }

        $accessToken = $response->access_token;
        $refreshToken = $response->refresh_token;
        $expirationTime = time() + $response->expires_in - 5; //5 seconds safe margin

        $userProfile = WSIS::getUserProfileFromOAuthToken($accessToken);
        $username = $userProfile['username'];
        $userRoles = $userProfile['roles'];

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
        //Check Airavata Server is up
        try{
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
        }catch (Exception $ex){
            CommonUtilities::print_error_message("Unable to Connect to the Airavata Server Instance!");
            return View::make('home');
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
                        CommonUtilities::print_error_message("Account confirmation failed!");
                        return View::make("home");
                    }
//                }else{
//                    $capatcha = WSIS::getCapatcha()->return;
//                    return View::make("account/verify-human", array("username"=>$username,"code"=>$confirmation,
//                        "imagePath"=>$capatcha->imagePath, "secretKey"=>$capatcha->secretKey,
//                        "imageUrl"=> Config::get("pga_config.wsis")["service-url"] . $capatcha->imagePath));
//                }
            }catch (Exception $e){
                CommonUtilities::print_error_message("Account confirmation failed!");
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
        $userProfile = WSIS::getUserProfile($username);
        $wsisConfig = Config::get('pga_config.wsis');
        if( $wsisConfig['tenant-domain'] == "")
            $username = $username;
        else
            $username = $username . "@" . $wsisConfig['tenant-domain'];

        $str = "Gateway Portal: " . $_SERVER['SERVER_NAME'] ."<br/>";
        $str = $str . "Username: " . $username ."<ber/>";
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
//        Session::flush();
//        if(Config::get('pga_config.wsis')['auth-mode'] == "oauth"){
//            return Redirect::away(WSIS::getOAuthLogoutUrl());
//        }
//        return Redirect::to('home');

        Session::flush();
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

    public function getCredentialStore() {

        $userResourceProfile = URPUtilities::get_or_create_user_resource_profile();
        $userCredentialSummaries = URPUtilities::get_all_ssh_pub_keys_summary_for_user();
        $defaultCredentialSummary = $userCredentialSummaries[$userResourceProfile->credentialStoreToken];
        foreach ($userCredentialSummaries as $credentialSummary) {
            $credentialSummary->canDelete = ($credentialSummary->token != $defaultCredentialSummary->token);
        }

        return View::make("account/credential-store", array(
            "userResourceProfile" => $userResourceProfile,
            "credentialSummaries" => $userCredentialSummaries,
            "defaultCredentialSummary" => $defaultCredentialSummary
        ));
    }

    public function setDefaultCredential() {

        $defaultToken = Input::get("defaultToken");
        $userResourceProfile = URPUtilities::get_user_resource_profile();
        $userResourceProfile->credentialStoreToken = $defaultToken;
        URPUtilities::update_user_resource_profile($userResourceProfile);

        $credentialSummaries = URPUtilities::get_all_ssh_pub_keys_summary_for_user();
        $description = $credentialSummaries[$defaultToken]->description;

        return Redirect::to("account/credential-store")->with("message", "SSH Key '$description' is now the default");
    }

    public function addCredential() {

        $rules = array(
            "credential-description" => "required",
        );

        $messages = array(
            "credential-description.required" => "A description is required for a new SSH key",
        );

        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) {
            return Redirect::to("account/credential-store")
                ->withErrors($validator);
        }

        $description = Input::get("credential-description");

        if (AdminUtilities::create_ssh_token_with_description($description)) {
            return Redirect::to("account/credential-store")->with("message", "SSH Key '$description' was added");
        }
    }

    public function deleteCredential() {

        $userResourceProfile = URPUtilities::get_user_resource_profile();
        $credentialStoreToken = Input::get("credentialStoreToken");
        if ($credentialStoreToken == $userResourceProfile->credentialStoreToken) {
            return Redirect::to("account/credential-store")->with("error-message", "You are not allowed to delete the default SSH key.");
        }

        $credentialSummaries = URPUtilities::get_all_ssh_pub_keys_summary_for_user();
        $description = $credentialSummaries[$credentialStoreToken]->description;

        if (AdminUtilities::remove_ssh_token($credentialStoreToken)) {
            return Redirect::to("account/credential-store")->with("message", "SSH Key '$description' was deleted");
        }
    }

    public function getComputeResources(){

        $userResourceProfile = URPUtilities::get_or_create_user_resource_profile();

        $allCRs = CRUtilities::getAllCRObjects();
        foreach( $allCRs as $index => $crObject)
        {
            $allCRsById[$crObject->computeResourceId] = $crObject;
        }
        // Add crDetails to each UserComputeResourcePreference
        foreach ($userResourceProfile->userComputeResourcePreferences as $index => $userCompResPref) {
            $userCompResPref->crDetails = $allCRsById[$userCompResPref->computeResourceId];
            // To figure out the unselectedCRs, remove this compute resource from allCRsById
            unset($allCRsById[$userCompResPref->computeResourceId]);
        }
        $unselectedCRs = array_values($allCRsById);

        $credentialSummaries = URPUtilities::get_all_ssh_pub_keys_summary_for_user();
        $defaultCredentialSummary = $credentialSummaries[$userResourceProfile->credentialStoreToken];

        return View::make("account/user-compute-resources", array(
            "userResourceProfile" => $userResourceProfile,
            "computeResources" => $allCRs,
            "unselectedCRs" => $unselectedCRs,
            "credentialSummaries" => $credentialSummaries,
            "defaultCredentialSummary" => $defaultCredentialSummary
        ));
    }

    public function addUserComputeResourcePreference() {

        if( URPUtilities::add_or_update_user_CRP( Input::all()) )
        {
            return Redirect::to("account/user-compute-resources")->with("message","Compute Resource Account Settings have been saved.");
        }
    }

    public function updateUserComputeResourcePreference() {

        if( URPUtilities::add_or_update_user_CRP( Input::all(), true ) )
        {
            return Redirect::to("account/user-compute-resources")->with("message","Compute Resource Account Settings have been updated.");
        }
    }

    public function deleteUserComputeResourcePreference() {
        $computeResourceId = Input::get("rem-user-crId");
        $result = URPUtilities::delete_user_CRP( $computeResourceId );
        if( $result )
        {
            return Redirect::to("account/user-compute-resources")->with("message","Compute Resource Account Settings have been deleted.");
        }
    }

    public function getStorageResources(){

        $userResourceProfile = URPUtilities::get_or_create_user_resource_profile();

        $allSRs = SRUtilities::getAllSRObjects();
        foreach( $allSRs as $index => $srObject )
        {
            $allSRsById[$srObject->storageResourceId] = $srObject;
        }
        // Add srDetails to each UserStoragePreference
        foreach ($userResourceProfile->userStoragePreferences as $index => $userStoragePreference) {
            $userStoragePreference->srDetails = $allSRsById[$userStoragePreference->storageResourceId];
            // To figure out the unselectedSRs, remove this storage resource from allSRsById
            unset($allSRsById[$userStoragePreference->storageResourceId]);
        }
        $unselectedSRs = array_values($allSRsById);

        $credentialSummaries = URPUtilities::get_all_ssh_pub_keys_summary_for_user();
        $defaultCredentialSummary = $credentialSummaries[$userResourceProfile->credentialStoreToken];

        return View::make("account/user-storage-resources", array(
            "userResourceProfile" => $userResourceProfile,
            "storageResources" => $allSRs,
            "unselectedSRs" => $unselectedSRs,
            "credentialSummaries" => $credentialSummaries,
            "defaultCredentialSummary" => $defaultCredentialSummary
        ));
    }

    public function addUserStorageResourcePreference() {

        if( URPUtilities::add_or_update_user_SRP( Input::all()) )
        {
            return Redirect::to("account/user-storage-resources")->with("message","Storage Resource Account Settings have been saved.");
        }
    }

    public function updateUserStorageResourcePreference() {

        if( URPUtilities::add_or_update_user_SRP( Input::all(), true ) )
        {
            return Redirect::to("account/user-storage-resources")->with("message","Storage Resource Account Settings have been updated.");
        }
    }

    public function deleteUserStorageResourcePreference() {
        $storageResourceId = Input::get("rem-user-srId");
        $result = URPUtilities::delete_user_SRP( $storageResourceId );
        if( $result )
        {
            return Redirect::to("account/user-storage-resources")->with("message","Storage Resource Account Settings have been deleted.");
        }
    }
}
