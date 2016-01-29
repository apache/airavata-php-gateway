<?php

class AdminController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('verifyadmin');
		Session::put("nav-active", "user-console");
	}

	public function dashboard(){
		return View::make("account/dashboard");
	}

	public function addAdminSubmit(){
		WSIS::update_user_roles( Input::get("username"), array( "new"=>array("admin"), "deleted"=>array() ) );

   		return View::make("account/admin-dashboard")->with("message", "User has been added to Admin.");
	}

	public function usersView(){
		if( Input::has("role"))
		{
			$users = AdminController::getUsersWithRole( Input::get("role"));
		}
		else
	    	$users =  WSIS::listUsers();

	    $roles = WSIS::getAllRoles();
        Session::put("admin-nav", "manage-users");
	    return View::make("admin/manage-users", array("users" => $users, "roles" => $roles));
	}

	public function getUserCountInRole(){
			$users = AdminController::getUsersWithRole( Input::get("role"));
			return count( $users);
	}

    public function searchUsersView(){
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
        Session::put("admin-nav", "manage-roles");
        return View::make("admin/manage-roles", array("roles" => $roles));
	}

	public function experimentsView(){
        Session::put("admin-nav", "exp-statistics");
		return View::make("admin/manage-experiments" );
	}

	public function resourcesView(){
		$data = CRUtilities::getBrowseCRData(false);
		$allCRs = $data["crObjects"];
		return View::make("admin/manage-resources", array("resources" => $allCRs) );
	}

	public function addRole(){
		WSIS::addRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been added.");
	}

    public function addRolesToUser(){
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
        $roles["deleted"] = array(Input::all()["roleName"]);
        $roles["new"] = array();
        $username = Input::all()["username"];
        WSIS::updateUserRoles($username, $roles);
        return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");
    }

	public function getRoles(){
		return json_encode((array)WSIS::getUserRoles(Input::get("username")));
	}

	public function deleteRole(){
		WSIS::deleteRole( Input::get("role") );
		return Redirect::to("admin/dashboard/roles")->with( "message", "Role has been deleted.");

	}

	public function credentialStoreView(){
        Session::put("admin-nav", "credential-store");
        $tokens = AdminUtilities::get_all_ssh_tokens();
        //var_dump( $tokens); exit;
		return View::make("admin/manage-credentials", array("tokens" => $tokens ) );
	}

	public function updateUserRoles(){
		if( Input::has("add"))
			return WSIS::updateUserRoles(Input::get("username"), array("new"=> Input::get("roles"), "deleted" => array() ) );
		else
			return WSIS::updateUserRoles(Input::get("username"), array("new"=> array(), "deleted" => Input::get("roles") ) );
	}

    public function experimentStatistics()
    {
        if (Request::ajax()) {
            $inputs = Input::all();
            $expStatistics = AdminUtilities::get_experiment_execution_statistics(strtotime($inputs['fromTime']) * 1000
                , strtotime($inputs['toTime']) * 1000);
            return View::make("admin/experiment-statistics", array("expStatistics" => $expStatistics));
        }
    }

    public function getExperimentsOfTimeRange()
    {
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

	public function createSSH(){
		$newToken = AdminUtilities::create_ssh_token();
		$pubkey = AdminUtilities::get_pubkey_from_token( $newToken);
		return Response::json( array( "token" => $newToken, "pubkey" => $pubkey));

	}

	public function removeSSH(){
		$removeToken = Input::get("token");
		if( AdminUtilities::remove_ssh_token( $removeToken) )
			return 1;
		else
			return 0;

	}

	public function getUsersWithRole( $role){
			$users = WSIS::getUserlistOfRole( $role);
			if( isset( $users->return))
		    	$users = $users->return;
		    else
		    	$users = array();

		    return $users;
	}


	/* ---- Super Admin Functions ------- */

	public function addGateway(){
		$inputs = Input::all();

		$rules = array(
            "username" => "required|min:6",
            "password" => "required|min:6|max:48|regex:/^.*(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!$#%*]).*$/",
            "confirm_password" => "required|same:password",
            "email" => "required|email",
        );

        $messages = array(
            'password.regex' => 'Password needs to contain at least (a) One lower case letter (b) One Upper case letter and (c) One number (d) One of the following special characters - !@#$%&*',
        );

        $checkValidation = array();
        $checkValidation["username"] = $inputs["admin-username"];
        $checkValidation["password"] = $inputs["admin-password"];
        $checkValidation["confirm_password"] = $inputs["admin-password-confirm"];
        $checkValidation["email"] = $inputs["admin-email"];

        $validator = Validator::make( $checkValidation, $rules, $messages);
        if ($validator->fails()) {
            return Response::json( $validator->messages() );
        }
        else{
	        $gateway = AdminUtilities::add_gateway(Input::all());

			$tm = WSIS::createTenant(1, $inputs["admin-username"] . "@" . $inputs["domain"], $inputs["admin-password"],
				$inputs["admin-email"], $inputs["admin-firstname"], $inputs["admin-lastname"], $inputs["domain"]);

			Session::put("message", "Gateway " . $inputs["gatewayName"] . " has been added.");
			
			return Response::json( array( "gateway" =>$gateway, "tm" => $tm ) ); 
			if( $gateway ==  $inputs["gatewayName"] && is_object( $tm ) )
				return Response::json( array( "gateway" =>$gateway, "tm" => $tm ) ); 
			else
				return 0;
			//return Redirect::to("admin/dashboard/gateway")->with("message", "Gateway has been successfully added.");
		}
	}

    public function enableComputeResource(){
        $resourceId = Input::get("resourceId");
        $computeResource = CRUtilities::get_compute_resource($resourceId);
        $computeResource->enabled = true;
        CRUtilities::register_or_update_compute_resource($computeResource, true);
    }

    public function disableComputeResource(){
        $resourceId = Input::get("resourceId");
        $computeResource = CRUtilities::get_compute_resource($resourceId);
        $computeResource->enabled = false;
        CRUtilities::register_or_update_compute_resource($computeResource, true);
    }

    public function enableStorageResource(){
        $resourceId = Input::get("resourceId");
        $storageResource = SRUtilities::get_storage_resource($resourceId);
        $storageResource->enabled = true;
        SRUtilities::register_or_update_storage_resource($storageResource, true);
    }

    public function disableStorageResource(){
        $resourceId = Input::get("resourceId");
        $storageResource = SRUtilities::get_storage_resource($resourceId);
        $storageResource->enabled = false;
        SRUtilities::register_or_update_storage_resource($storageResource, true);
    }
}