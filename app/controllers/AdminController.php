<?php

class AdminController extends BaseController {

    private $idStore = null;

	public function __construct()
	{
		$this->beforeFilter('verifyadmin');
		$idStore = new WSISUtilities();
        try
	    {
	        $idStore->connect();
	    }
	    catch (Exception $e)
	    {
	        Utilities::print_error_message('<p>Error connecting to ID store.
	            Please try again later or report a bug using the link in the Help menu</p>' .
	            '<p>' . $e->getMessage() . '</p>');
	    }
	    $this->idStore = $idStore;
	    //Session::put("idStore", $idStore);
		Session::put("nav-active", "user-console");
	}

	public function console(){
		return View::make("admin/dashboard");
	}

	public function dashboard(){
		//only for super admin
		//Session::put("scigap_admin", true);
		$idStore = $this->idStore;

		$crData = CRUtilities::getEditCRData();
		$gateways = CRUtilities::getAllGatewayProfilesData();
		return View::make("admin/manage-gateway", array( 
														"gateways" => $gateways, 
														"computeResources" => CRUtilities::getAllCRObjects(),
														"crData" => $crData));
	}

	public function addAdminSubmit(){
		$idStore = $this->idStore;
	    $idStore->updateRoleListOfUser( Input::get("username"), array( "new"=>array("admin"), "deleted"=>array() ) );

   		return View::make("account/admin-dashboard")->with("message", "User has been added to Admin.");
	}

	public function usersView(){
		$idStore = $this->idStore;
		if( Input::has("role"))
		{
			$users = $idStore->getUserListOfRole( Input::get("role"));
			if( isset( $users->return))
		    	$users = $users->return;
		    else
		    	$users = array();
		}
		else
	    	$users = $idStore->listUsers();
	    
	    $roles = $idStore->getRoleNames();

	    return View::make("admin/manage-users", array("users" => $users, "roles" => $roles));

	}

	public function addGatewayAdminSubmit(){
		$idStore = $this->idStore;
		//check if username exists
		if( $idStore->username_exists( Input::get("username")) )
		{
			//first add if this role does not exist
			$gatewayName = str_replace(" ", "_", Input::get("gateway_name"));
			$app_config = Utilities::read_config();
			$role = $app_config["gateway-role-prepend"] . $gatewayName . $app_config["gateway-role-admin-append"];
			//var_dump( $role); //exit;
			//$role = "gateway_default_b8a153f1-6291_admin";
			if( ! $idStore->isExistingRole( $role) )
			{
				$idStore->addRole( $role );
			}

			//add user to gateway_admin role
			$idStore->updateRoleListOfUser( Input::get("username"), array( "new"=>array( $role), "deleted"=>array() ) );

			return Redirect::to("manage/admins")->with("Gateway Admin has been added.");

		}
		else
		{
			echo ("username doesn't exist only."); exit;
		}
	}

	public function rolesView(){
		$idStore = $this->idStore;
		$roles = $idStore->getRoleNames();

		return View::make("admin/manage-roles", array("roles" => $roles));
	}

	public function addRole(){
		$idStore = $this->idStore;

		$idStore->addRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been added.");
	}

	public function deleteRole(){
		$idStore = $this->idStore;

		$idStore->deleteRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");

	}
}