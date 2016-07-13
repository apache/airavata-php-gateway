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
        $checkValidation["email"] = $inputs["admin-email"];

        $validator = Validator::make( $checkValidation, $rules, $messages);
        if ($validator->fails()) {
            return Redirect::to("account/dashboard", array( "errors"=>$validator->messages() );
        }
        else{
	        $gateway = AdminUtilities::request_gateway(Input::all());

			//$tm = WSIS::createTenant(1, $inputs["admin-username"] . "@" . $inputs["domain"], $inputs["admin-password"], inputs["admin-email"], $inputs["admin-firstname"], $inputs["admin-lastname"], $inputs["domain"]);

			Session::put("message", "Your request for Gateway " . $inputs["gatewayName"] . " has been created.");
			
			return Response::json( array( "gateway" =>$gateway, "tm" => $tm ) ); 
			if( $gateway ==  $inputs["gatewayName"] && is_object( $tm ) )
				return Response::json( array( "gateway" =>$gateway, "tm" => $tm ) ); 
			else
				return 0;
		}
	}


}
