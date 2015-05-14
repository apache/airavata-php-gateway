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
			//add user to admin role
			$app_config = Utilities::read_config();
			$idStore->updateRoleListOfUser( Input::get("username"), array( "new"=>array( Config::get('wsis::admin-role-name')), "deleted"=>array() ) );

			return Redirect::to("admin/dashboard/users?role=" . Config::get('wsis::admin-role-name'))->with("Gateway Admin has been added.");

		}
		else
		{
			echo ("username doesn't exist only."); exit;
		}
	}

	public function rolesView(){
		$idStore = $this->idStore;
		$roles = $idStore->getRoleNames();
		var_dump( $roles); exit;
		return View::make("admin/manage-roles", array("roles" => $roles));
	}

	public function experimentsView(){
		$idStore = $this->idStore;
		//$roles = $idStore->getExperiments();

		return View::make("admin/manage-experiments" );
	}

	public function addRole(){
		$idStore = $this->idStore;

		$idStore->addRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been added.");
	}

	public function getRoles(){
		$idStore = $this->idStore;

		return json_encode( (array)$idStore->getRoleListOfUser( Input::get("username") ) );
	}

	public function deleteRole(){
		$idStore = $this->idStore;

		$idStore->deleteRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");

	}

	public function credentialStoreView(){
		$idStore = $this->idStore;

		return View::make("admin/manage-credentials", array("tokens" => array()) );
	}


	/* ---- Super Admin Functions ------- */

	public function addGateway(){

		$inputs = Input::all();

		$idStore = $this->idStore;

		$gateway = AdminUtilities::addGateway(Input::all() );

		$tm = $idStore->createTenant(1, $inputs["admin-username"], $inputs["admin-password"], $inputs["admin-email"],
                                  $inputs["admin-firstname"], $inputs["admin-lastname"], $inputs["domain"]);
		
		return $gateway;
	}
}