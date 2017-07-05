<?php

class GatewayRequestUpdateController extends BaseController
{

    public function updateGateway(){

        $gateway = AdminUtilities::update_form( Input::get("gateway-id"), Input::all() );

        $gatewayData = array ("airavataInternalGatewayId" => $gateway->airavataInternalGatewayId,
                              "gatewayId" => $gateway->gatewayId,
                              "gatewayName" => $gateway->gatewayName,
                              "emailAddress" => $gateway->emailAddress,
                              "publicProjectDescription" => $gateway->gatewayPublicAbstract,
                              "gatewayURL" => $gateway->gatewayURL,
                              "adminFirstName" => $gateway->gatewayAdminFirstName,
                              "adminLastName" => $gateway->gatewayAdminLastName,
                              "adminUsername" => $gateway->identityServerUserName,
                              "adminEmail" => $gateway->gatewayAdminEmail,
                              "projectDetails" => $gateway->reviewProposalDescription);

        return View::make("account/update")->with(array('gatewayData'=>$gatewayData));

    }

    public function updateDetails(){

        $inputs = Input::all();
        $rules = array(
            "password" => "required|min:6|max:48|regex:/^.*(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!$#*]).*$/",
            "confirm_password" => "required|same:password",
            "email" => "required|email",
        );

        $messages = array(
            'password.regex' => 'Password needs to contain at least (a) One lower case letter (b) One Upper case letter and (c) One number (d) One of the following special characters - !@#$&*',
        );

        $checkValidation = array();
        $checkValidation["password"] = $inputs["admin-password"];
        $checkValidation["confirm_password"] = $inputs["admin-password-confirm"];
        $checkValidation["email"] = $inputs["admin-email"];

        $validator = Validator::make( $checkValidation, $rules, $messages);
        if ($validator->fails()) {
            Session::put("validationMessages", $validator->messages() );
            return Redirect::back()
                ->withInput(Input::except('password', 'password_confirm'))
                ->withErrors($validator);
        }
        else {
            $returnVal = AdminUtilities::user_update_gateway(Input::get("internal-gateway-id"), Input::all());

            if ($returnVal == 1) {
                $email = Config::get('pga_config.portal')['admin-emails'];
                EmailUtilities::gatewayUpdateMailToProvider(Input::get("email-address"), Input::get("gateway-id"));
                EmailUtilities::gatewayUpdateMailToAdmin($email, Input::get("gateway-id"));
                Session::put("message", "Your Gateway has been updated");
            } else
                Session::put("errorMessages", "Error: Please try again or contact admin to report the issue.");
        }

        return Redirect::to("admin/dashboard");

    }

}