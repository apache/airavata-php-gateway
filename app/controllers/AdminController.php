<?php

class AdminController extends BaseController {

	public function __construct()
	{
		Session::put("nav-active", "user-console");
	}

	public function console(){
		return View::make("admin/dashboard");
	}

	public function dashboard(){
		//only for super admin
		//Session::put("scigap_admin", true);

		$crData = CRUtilities::getEditCRData();
		$gateways = CRUtilities::getAllGatewayProfilesData();

		$gatewayData = array( 
														"gateways" => $gateways, 
														"computeResources" => CRUtilities::getAllCRObjects(),
														"crData" => $crData);
		if( Session::has("scigap_admin"))
			$view = "scigap-admin/manage-gateway";
		else
			$view = "admin/manage-gateway";

			return View::make( $view, $gatewayData);
	}

	public function addAdminSubmit(){
        WSIS::update_user_roles( Input::get("username"), array( "new"=>array("admin"), "deleted"=>array() ) );

   		return View::make("account/admin-dashboard")->with("message", "User has been added to Admin.");
	}

	public function usersView(){
		if( Input::has("role"))
		{
			$users = WSIS::getUserlistOfRole(Input::get("role"));
			if( isset( $users->return))
		    	$users = $users->return;
		    else
		    	$users = array();
		}
		else
	    	$users =  WSIS::listUsers();
	    
	    $roles = WSIS::getAllRoles();

	    return View::make("admin/manage-users", array("users" => $users, "roles" => $roles));

	}

	public function addGatewayAdminSubmit(){
		//check if username exists
		if(WSIS::usernameExists( Input::get("username")) )
		{
            WSIS::updateUserRoles(Input::get("username"), array( "new"=>array( Config::get('wsis::admin-role-name')), "deleted"=>array() ) );
			return Redirect::to("admin/dashboard/users?role=" . Config::get('wsis::admin-role-name'))->with("Gateway Admin has been added.");
		}
		else
		{
			echo ("username doesn't exist only."); exit;
		}
	}

	public function rolesView(){

		$roles = WSIS::getAllRoles();
		return View::make("admin/manage-roles", array("roles" => $roles));
	}

	public function experimentsView(){
		return View::make("admin/manage-experiments" );
	}

	public function resourcesView(){
		$data = CRUtilities::getBrowseCRData();
		$allCRs = $data["crObjects"];
		return View::make("admin/manage-resources", array("resources" => $allCRs) );
	}

	public function addRole(){
		WSIS::addRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been added.");
	}

	public function getRoles(){
		return json_encode((array)WSIS::getUserRoles(Input::get("username")));
	}

	public function deleteRole(){
		WSIS::deleteRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");

	}

	public function credentialStoreView(){
		return View::make("admin/manage-credentials", array("tokens" => array()) );
	}

	public function updateUserRoles(){
		if( Input::has("add"))
			return WSIS::updateUserRoles(Input::get("username"), array("new"=> Input::get("roles"), "deleted" => array() ) );
		else
			return WSIS::updateUserRoles(Input::get("username"), array("new"=> array(), "deleted" => Input::get("roles") ) );
	}


	/* ---- Super Admin Functions ------- */

	public function addGateway(){

		$inputs = Input::all();

		$gateway = AdminUtilities::addGateway(Input::all() );

		$tm = WSIS::createTenant(1, $inputs["admin-username"], $inputs["admin-password"], $inputs["admin-email"],
                                  $inputs["admin-firstname"], $inputs["admin-lastname"], $inputs["domain"]);
		
		return $gateway;
	}
}