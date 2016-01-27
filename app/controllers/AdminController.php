<?php

class AdminController extends BaseController {

	public function __construct()
	{
		Session::put("nav-active", "user-console");
	}

	public function dashboard(){
		return View::make("account/dashboard");
	}

	public function addAdminSubmit(){
		$this->beforeFilter('verifyadmin');
		WSIS::update_user_roles( Input::get("username"), array( "new"=>array("admin"), "deleted"=>array() ) );

   		return View::make("account/admin-dashboard")->with("message", "User has been added to Admin.");
	}

	public function usersView(){
		$this->beforeFilter('verifyadmin');
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
        Session::put("admin-nav", "manage-users");
	    return View::make("admin/manage-users", array("users" => $users, "roles" => $roles));

	}

    public function searchUsersView(){
		$this->beforeFilter('verifyadmin');
        if(Input::has("search_val"))
        {
            $users =  WSIS::searchUsers(Input::get("search_val"));
        }
        else
            $users = WSIS::listUsers();

		if(!isset($users) || empty($users)){
			$users = array();
		}
        $roles = WSIS::getAllRoles();
        Session::put("admin-nav", "manage-users");
        return View::make("admin/manage-users", array("users" => $users, "roles" => $roles));

    }

    public function gatewayView(){
		$this->beforeFilter('verifyadmin');
    	//only for super admin
		//Session::put("super-admin", true);
		$crData = CRUtilities::getEditCRData();
		$gateways = CRUtilities::getAllGatewayProfilesData();
		$tokens = AdminUtilities::get_all_ssh_tokens();
		$srData = SRUtilities::getEditSRData();

		//$dsData = CRUtilities::getAllDataStoragePreferences( $gateways);
		$gatewayData = array( 
								"gateways" => $gateways, 
								"computeResources" => CRUtilities::getAllCRObjects(),
								"crData" => $crData,
								"storageResources" => SRUtilities::getAllSRObjects(),
								"srData" => $srData,
								"tokens" => $tokens
							);
		$view = "admin/manage-gateway";

        Session::put("admin-nav", "gateway-prefs");
		return View::make( $view, $gatewayData);
    }

	public function addGatewayAdminSubmit(){
		$this->beforeFilter('verifyadmin');
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
		$this->beforeFilter('verifyadmin');
		$roles = WSIS::getAllRoles();
        Session::put("admin-nav", "manage-roles");
        return View::make("admin/manage-roles", array("roles" => $roles));
	}

	public function experimentsView(){
		$this->beforeFilter('verifyadmin');
        Session::put("admin-nav", "exp-statistics");
		return View::make("admin/manage-experiments" );
	}

	public function resourcesView(){
		$this->beforeFilter('verifyadmin');
		$data = CRUtilities::getBrowseCRData(false);
		$allCRs = $data["crObjects"];
		return View::make("admin/manage-resources", array("resources" => $allCRs) );
	}

	public function addRole(){
		$this->beforeFilter('verifyadmin');
		WSIS::addRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been added.");
	}

    public function addRolesToUser(){
		$this->beforeFilter('verifyadmin');
        $currentRoles = (array)WSIS::getUserRoles(Input::get("username"));
        $roles["new"] = array_diff(Input::all()["roles"], $currentRoles);
        $roles["deleted"] = array_diff($currentRoles, Input::all()["roles"]);

        $index = array_search('Internal/everyone',$roles["new"]);
        if($index !== FALSE){
            unset($roles["new"][$index]);
        }

        $index = array_search('Internal/everyone',$roles["deleted"]);
        if($index !== FALSE){
            unset($roles["deleted"][$index]);
        }

        $username = Input::all()["username"];
        WSIS::updateUserRoles($username, $roles);
        return Redirect::to("admin/dashboard/roles")->with( "message", "Roles has been added.");
    }

    public function removeRoleFromUser(){
		$this->beforeFilter('verifyadmin');
        $roles["deleted"] = array(Input::all()["roleName"]);
        $roles["new"] = array();
        $username = Input::all()["username"];
        WSIS::updateUserRoles($username, $roles);
        return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");
    }

	public function getRoles(){
		$this->beforeFilter('verifyadmin');
		return json_encode((array)WSIS::getUserRoles(Input::get("username")));
	}

	public function deleteRole(){
		$this->beforeFilter('verifyadmin');
		WSIS::deleteRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");

	}

	public function credentialStoreView(){
		$this->beforeFilter('verifyadmin');
        Session::put("admin-nav", "credential-store");
        $tokens = AdminUtilities::get_all_ssh_tokens();
        //var_dump( $tokens); exit;
		return View::make("admin/manage-credentials", array("tokens" => $tokens ) );
	}

	public function updateUserRoles(){
		$this->beforeFilter('verifyadmin');
		if( Input::has("add"))
			return WSIS::updateUserRoles(Input::get("username"), array("new"=> Input::get("roles"), "deleted" => array() ) );
		else
			return WSIS::updateUserRoles(Input::get("username"), array("new"=> array(), "deleted" => Input::get("roles") ) );
	}


	/* ---- Super Admin Functions ------- */

	public function addGateway(){
		$this->beforeFilter('verifyadmin');
		$inputs = Input::all();

        $gateway = AdminUtilities::add_gateway(Input::all());

		$tm = WSIS::createTenant(1, $inputs["admin-username"] . "@" . $inputs["domain"], $inputs["admin-password"],
			$inputs["admin-email"], $inputs["admin-firstname"], $inputs["admin-lastname"], $inputs["domain"]);

		Session::put("message", "Gateway " . $inputs["gatewayName"] . " has been added.");
		return Response::json( $tm);
		//return Redirect::to("admin/dashboard/gateway")->with("message", "Gateway has been successfully added.");
	}


    public function experimentStatistics()
    {
		$this->beforeFilter('verifyadmin');
        if (Request::ajax()) {
            $inputs = Input::all();
            $expStatistics = AdminUtilities::get_experiment_execution_statistics(strtotime($inputs['fromTime']) * 1000
                , strtotime($inputs['toTime']) * 1000);
            return View::make("admin/experiment-statistics", array("expStatistics" => $expStatistics));
        }
    }

    public function getExperimentsOfTimeRange()
    {
		$this->beforeFilter('verifyadmin');
        if (Request::ajax()) {
            $inputs = Input::all();
            $expContainer = AdminUtilities::get_experiments_of_time_range($inputs);
            $expStates = ExperimentUtilities::getExpStates();
            return View::make("partials/experiment-container", 
            	array(	"expContainer" => $expContainer,
                		"expStates" => $expStates,
                		"dashboard" => true
                	));
        }
    }

    public function enableComputeResource(){
		$this->beforeFilter('verifyadmin');
        $resourceId = Input::get("resourceId");
        $computeResource = CRUtilities::get_compute_resource($resourceId);
        $computeResource->enabled = true;
        CRUtilities::register_or_update_compute_resource($computeResource, true);
    }

    public function disableComputeResource(){
		$this->beforeFilter('verifyadmin');
        $resourceId = Input::get("resourceId");
        $computeResource = CRUtilities::get_compute_resource($resourceId);
        $computeResource->enabled = false;
        CRUtilities::register_or_update_compute_resource($computeResource, true);
    }

    public function enableStorageResource(){
		$this->beforeFilter('verifyadmin');
        $resourceId = Input::get("resourceId");
        $storageResource = SRUtilities::get_storage_resource($resourceId);
        $storageResource->enabled = true;
        SRUtilities::register_or_update_storage_resource($storageResource, true);
    }

    public function disableStorageResource(){
		$this->beforeFilter('verifyadmin');
        $resourceId = Input::get("resourceId");
        $storageResource = SRUtilities::get_storage_resource($resourceId);
        $storageResource->enabled = false;
        SRUtilities::register_or_update_storage_resource($storageResource, true);
    }


	public function createSSH(){
		$this->beforeFilter('verifyadmin');
		$newToken = AdminUtilities::create_ssh_token();
		$pubkey = AdminUtilities::get_pubkey_from_token( $newToken);
		return Response::json( array( "token" => $newToken, "pubkey" => $pubkey));

	}

	public function removeSSH(){
		$this->beforeFilter('verifyadmin');
		$removeToken = Input::get("token");
		if( AdminUtilities::remove_ssh_token( $removeToken) )
			return 1;
		else
			return 0;

	}

}