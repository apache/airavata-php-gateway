<?php

class GatewayController extends BaseController {

	public function requestGateway(){
		$inputs = Input::all();

		$rules = array(
            "username" => "required|min:6",
            "password" => "required|min:6|max:48|regex:/^.*(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!$#*]).*$/",
            "confirm_password" => "required|same:password",
            "email" => "required|email",
        );

        $messages = array(
            'password.regex' => 'Password needs to contain at least (a) One lower case letter (b) One Upper case letter and (c) One number (d) One of the following special characters - !@#$&*',
        );

        $checkValidation = array();
        $checkValidation["username"] = $inputs["admin-username"];
        $checkValidation["password"] = $inputs["admin-password"];
        $checkValidation["confirm_password"] = $inputs["admin-password-confirm"];
        $checkValidation["email"] = $inputs["email-address"];

        $validator = Validator::make( $checkValidation, $rules, $messages);
        if ($validator->fails()) {
            Session::put("message", implode(",", $validator->messages() ));
            return Redirect::to("admin/dashboard");
        }
        else{
	        $gateway = AdminUtilities::request_gateway(Input::all());

			Session::put("message", "Your request for Gateway " . $inputs["gatewayName"] . " has been created.");
			
            return Redirect::to("admin/dashboard");
		}
	}


}
