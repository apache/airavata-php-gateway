<?php

class AccountController extends BaseController
{

    public function createAccountView()
    {
        return View::make('account/create');
    }

    public function createAccountSubmit()
    {
        $rules = array(
            "username" => "required|min:6",
            "password" => "required|min:6",
            "confirm_password" => "required|same:password",
            "email" => "required|email",
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return Redirect::to("create")
                ->withInput(Input::except('password', 'password_confirm'))
                ->withErrors($validator);
        }

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $_POST['email'];
        $organization = $_POST['organization'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $telephone = $_POST['telephone'];
        $mobile = $_POST['mobile'];
        $im = $_POST['im'];
        $url = $_POST['url'];

        if (WSIS::usernameExists($username)) {
            return Redirect::to("create")
                ->withInput(Input::except('password', 'password_confirm'))
                ->with("username_exists", true);
        } else {
            WSIS::addUser($username, $password, $first_name, $last_name, $email, $organization,
                $address, $country, $telephone, $mobile, $im, $url);

            //creating a default project for user
            ProjectUtilities::create_default_project($username);
            CommonUtilities::print_success_message('New user created!');
            return View::make('account/login');
        }
    }

    public function loginView()
    {
        return View::make('account/login');
    }

    public function loginSubmit()
    {

        if (CommonUtilities::form_submitted()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            try {
                if (WSIS::authenticate($username, $password)) {
                    $userRoles = (array)WSIS::getUserRoles($username);
                    if (in_array(Config::get('pga_config.wsis')['admin-role-name'], $userRoles)) {
                        Session::put("admin", true);
                    }
                    if (in_array(Config::get('pga_config.wsis')['read-only-admin'], $userRoles)) {
                        Session::put("admin-read-only", true);
                    }

                    CommonUtilities::store_id_in_session($username);
                    CommonUtilities::print_success_message('Login successful! You will be redirected to your home page shortly.');
                    //TODO::If this option is not safe, have to find a better method to send credentials to identity server on every connection.
                    Session::put("gateway_id", Config::get('pga_config.airavata')['gateway-id']);
                    Session::put("password", $_POST["password"]);

                    return Redirect::to("home");

                } else {
                    return Redirect::to("login")->with("invalid-credentials", true);
                }
            } catch (Exception $ex) {
                return Redirect::to("login")->with("invalid-credentials", true);
            }
        }

    }

    public function forgotPassword()
    {
        return View::make("account/forgot-password");
    }

    public function logout()
    {
        Session::flush();
        return Redirect::to('home');
    }

}
