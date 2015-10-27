<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/*
 * User Routes
*/

Route::get("create", "AccountController@createAccountView");

Route::post("create", "AccountController@createAccountSubmit");

Route::get("login", "AccountController@loginView");

Route::post("login", "AccountController@loginSubmit");

//Route::get("callback-url", "AccountController@oauthCallback");

Route::get("logout", "AccountController@logout");

Route::get("forgot-password", "AccountController@forgotPassword");

Route::get("reset-password", "AccountController@resetPassword");

Route::post("reset-password", "AccountController@resetPasswordSubmit");

Route::post("forgot-password", "AccountController@forgotPasswordSubmit");

Route::get("confirm-user-registration", "AccountController@confirmAccountCreation");

Route::get("setUserTimezone", function () {
    Session::set("user_timezone", Input::get("timezone"));
});
/*
 * The following routes will not work without logging in.
 *
*/

/*
 * Project Routes
*/

Route::get("project/create", "ProjectController@createView");

Route::post("project/create", "ProjectController@createSubmit");

Route::get("project/summary", "ProjectController@summary");

Route::get("project/edit", "ProjectController@editView");

Route::post("project/edit", "ProjectController@editSubmit");

Route::get("project/browse", "ProjectController@browseView");

Route::post("project/browse", "ProjectController@browseView");

/*
 * Experiment Routes
*/

Route::get("experiment/create", "ExperimentController@createView");

Route::post("experiment/create", "ExperimentController@createSubmit");

Route::get("experiment/summary", "ExperimentController@summary");

Route::post("experiment/summary", "ExperimentController@expChange");

Route::get("experiment/clone", "ExperimentController@cloneExperiment");

Route::get("experiment/edit", "ExperimentController@editView");

Route::post("experiment/edit", "ExperimentController@editSubmit");

Route::post("experiment/cancel", "ExperimentController@expCancel");

Route::get("experiment/getQueueView", "ExperimentController@getQueueView");

Route::get("experiment/browse", "ExperimentController@browseView");

Route::post("experiment/browse", "ExperimentController@browseView");
/*
 * Compute Resources Routes
*/

Route::get("cr/create", function () {
    return Redirect::to("cr/create/step1");
});

Route::get("cr/create", "ComputeResourceController@createView");

Route::post("cr/create", "ComputeResourceController@createSubmit");

Route::get("cr/edit", "ComputeResourceController@editView");

Route::post("cr/edit", "ComputeResourceController@editSubmit");

Route::get("cr/view", "ComputeResourceController@viewView");

Route::get("cr/browse", "ComputeResourceController@browseView");

Route::post("cr/delete-jsi", "ComputeResourceController@deleteActions");

Route::post("cr/delete-dmi", "ComputeResourceController@deleteActions");

Route::post("cr/delete-cr", "ComputeResourceController@deleteActions");
/*
 * Application Catalog Routes
*/

Route::get("app/module", "ApplicationController@showAppModuleView");

Route::post("app/module-create", "ApplicationController@modifyAppModuleSubmit");

Route::post("app/module-edit", "ApplicationController@modifyAppModuleSubmit");

Route::post("app/module-delete", "ApplicationController@deleteAppModule");

Route::get("app/interface", "ApplicationController@showAppInterfaceView");

Route::post("app/interface-create", "ApplicationController@createAppInterfaceSubmit");

Route::post("app/interface-edit", "ApplicationController@editAppInterfaceSubmit");

Route::post("app/interface-delete", "ApplicationController@deleteAppInterface");

Route::get("app/deployment", "ApplicationController@showAppDeploymentView");

Route::post("app/deployment-create", "ApplicationController@createAppDeploymentSubmit");

Route::post("app/deployment-edit", "ApplicationController@editAppDeploymentSubmit");

Route::post("app/deployment-delete", "ApplicationController@deleteAppDeployment");

Route::get("gp/create", "GatewayprofileController@createView");

Route::post("gp/create", "GatewayprofileController@createSubmit");

Route::post("gp/edit", "GatewayprofileController@editGP");

Route::get("gp/browse", "GatewayprofileController@browseView");

Route::post("gp/delete-gp", "GatewayprofileController@delete");

Route::post("gp/remove-cr", "GatewayprofileController@delete");

Route::post("gp/add-crp", "GatewayprofileController@modifyCRP");

Route::post("gp/update-crp", "GatewayprofileController@modifyCRP");

Route::post("gp/credential-store-token-change", "GatewayprofileController@cstChange");

//Management Dashboard

Route::get("admin/console", "AdminController@console");

Route::get("admin/dashboard", "AdminController@dashboard");

Route::get("admin/dashboard/gateway", "AdminController@dashboard");

Route::get("admin/dashboard/users", "AdminController@usersView");

Route::post("admin/dashboard/users", "AdminController@searchUsersView");

Route::get("admin/dashboard/roles", "AdminController@rolesView");

Route::get("admin/dashboard/experiments", "AdminController@experimentsView");

Route::get("admin/dashboard/experimentsOfTimeRange", "AdminController@getExperimentsOfTimeRange");

Route::get("admin/dashboard/experimentStatistics", "AdminController@experimentStatistics");

Route::get("admin/dashboard/resources", "AdminController@resourcesView");

Route::get("admin/dashboard/experiment/summary", function () {
    return Redirect::to("experiment/summary?expId=" . $_GET["expId"] . "&dashboard=true");
});

Route::get("admin/dashboard/credential-store", "AdminController@credentialStoreView");

Route::get("manage/users", "AdminController@usersView");

Route::post("admin/adduser", "AdminController@addAdminSubmit");

Route::post("admin/addgatewayadmin", "AdminController@addGatewayAdminSubmit");

Route::post("admin/add-role", "AdminController@addRole");

Route::post("admin/check-roles", "AdminController@getRoles");

Route::post("admin/delete-role", "AdminController@deleteRole");

Route::post("admin/enable-cr", "AdminController@enableComputeResource");

Route::post("admin/disable-cr", "AdminController@disableComputeResource");

Route::post("admin/add-roles-to-user", "AdminController@addRolesToUser");

Route::post("admin/remove-role-from-user", "AdminController@removeRoleFromUser");

//Super Admin Specific calls

Route::post("admin/add-gateway", "AdminController@addGateway");
Route::get("admin/add-gateway", "AdminController@addGateway");

/*
* Theme Pages Routes
*/
Route::get( "pages/{theme_view}", function( $theme_view){

	//In some cases, theme doesn't get loaded in session, so doing that here
	//as well incase it does not.
	if(! Session::has("theme")){
		Session::put("theme", Config::get('pga_config.portal')['theme']);
	}
    return View::make("pages", array("page" => $theme_view) );
});

//Airavata Server Check
Route::get("airavata/down", function () {
    return View::make("server-down");
});
/*
 * Test Routes.
*/
/*
Route::get("testjob", function () {
    //print_r( Session::all());
});
*/
/*handling errors on production */
App::missing(function($exception)
{
    return Response::view('error', array(), 404);
});

/*
 * Following base Routes need to be at the bottom.
*/
Route::controller("home", "HomeController");

Route::controller("/", "HomeController");
