<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //Check Airavata Server is up
    $apiVersion = Airavata::getAPIVersion();
    if (empty($apiVersion))
        return View::make("server-down");

    //Check OAuth token has expired
    if(Config::get('pga_config.wsis')['auth-mode']=="oauth" && Session::has('authz-token')){
        $currentTime = time();
        if($currentTime > Session::get('oauth-expiration-time')){
            $response = WSIS::getRefreshedOAutheToken(Session::get('oauth-refresh-code'));
            if(isset($response->access_token)){
                $accessToken = $response->access_token;
                $refreshToken = $response->refresh_token;
                $expirationTime = time()/1000 + $response->expires_in - 300;
                $authzToken = new Airavata\Model\Security\AuthzToken();
                $authzToken->accessToken = $accessToken;
                Session::put('authz-token',$authzToken);
                Session::put('oauth-refresh-code',$refreshToken);
                Session::put('oauth-expiration-time',$expirationTime);
            }else{
                Session::flush();
                return Redirect::to('home');
            }
        }
    }
});


App::after(function ($request, $response) {
    //
    // Test commit.
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('login');
        }
    }
});


Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});


Route::filter('verifylogin', function () {
    if (!CommonUtilities::verify_login())
        return Redirect::to("home")->with("login-alert", true);
});

Route::filter('verifyadmin', function () {
    if (CommonUtilities::verify_login()) {
        if (!(Session::has("admin") || Session::has("admin-read-only"))) {
            return Redirect::to("home")->with("admin-alert", true);
        }
    } else
        return Redirect::to("home")->with("login-alert", true);
});

Route::filter('verifyeditadmin', function () {
    if (CommonUtilities::verify_login()) {
        if (!Session::has("admin")) {
            return Redirect::to("home")->with("admin-alert", true);
        }
    } else
        return Redirect::to("home")->with("login-alert", true);
});