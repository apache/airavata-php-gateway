<?php

require_once 'id_utilities.php';
require_once 'WSISClient.php';

//$GLOBALS['WSIS_ROOT'] = './lib/WSIS/';
//require_once $GLOBALS['WSIS_ROOT'] . 'WSISClient.php';

/**
 * Utilities for ID management with a WSO2 IS 4.6.0
 */

class WSISUtilities implements IdUtilities{
    /**
     * wso2 IS client
     * 
     * @var WSISClient
     * @access private
     */
    private $wsis_client;

    /**
     * Connect to the identity store.
     * @return mixed|void
     */
    public function connect() { 
   
        $wsis_config = Utilities::read_config();    
        if(substr($wsis_config['service-url'], -1) !== "/"){
            $wsis_config['service-url'] = $wsis_config['service-url'] . "/";
        }
        
        if(!substr($wsis_config['cafile-path'], 0) !== "/"){
            $wsis_config['cafile-path'] = "/" . $wsis_config['cafile-path'];
        }
        $wsis_config['cafile-path'] = app_path() . $wsis_config['cafile-path'];            
        
        /*
        if( Session::has("username"))
        {
            $username = Session::get("username");
            $password = Session::get("password");
        }
        else
        {
            $username = $_POST["username"];
            $password = $_POST["password"];
        }
        */
        $username = $wsis_config['admin-username'];
        $password = $wsis_config['admin-password'];
        
        $this->wsis_client = new WSISClient(
                $username,
                $password,
                $wsis_config['server'],
                $wsis_config['service-url'],
                $wsis_config['cafile-path'],
                $wsis_config['verify-peer'],
                $wsis_config['allow-self-signed']
        );    
    }

    /**
     * Return true if the given username exists in the identity server.
     * @param $username
     * @return bool
     */
    public function username_exists($username) {
        try{
            //$this->wsis_client = new WSISClient( $username);
            return $this->wsis_client->username_exists($username);
        } catch (Exception $ex) {
            print_r( $ex); exit;
            throw new Exception("Unable to check whether username exists", 0, NULL);
        }
        
    }

    /**
     * authenticate a given user
     * @param $username
     * @param $password
     * @return boolean
     */
    public function authenticate($username, $password) {
        try{
            return $this->wsis_client->authenticate($username, $password);
        } catch (Exception $ex) {
            var_dump( $ex); exit;
            throw new Exception("Unable to authenticate user", 0, NULL);
        }        
    }

    /**
     * Add a new user to the identity server.
     * @param $username
     * @param $password
     * @return void
     */
    public function add_user($username, $password, $first_name, $last_name, $email, $organization,
            $address, $country,$telephone, $mobile, $im, $url) {
        try{
            $this->wsis_client->addUser($username, $password, $first_name . " " . $last_name);
        } catch (Exception $ex) {
            var_dump($ex);
            throw new Exception("Unable to add new user", 0, NULL);
        }        
    }

    /**
     * Get the user profile
     * @param $username
     * @return mixed|void
     */
    public function get_user_profile($username)
    {
        // TODO: Implement get_user_profile() method.
    }

    /**
     * Update the user profile
     *
     * @param $username
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $organization
     * @param $address
     * @param $country
     * @param $telephone
     * @param $mobile
     * @param $im
     * @param $url
     * @return mixed
     */
    public function update_user_profile($username, $first_name, $last_name, $email, $organization, $address,
                                        $country, $telephone, $mobile, $im, $url)
    {
        // TODO: Implement update_user_profile() method.
    }

    /**
     * Function to update user password
     *
     * @param $username
     * @param $current_password
     * @param $new_password
     * @return mixed
     */
    public function change_password($username, $current_password, $new_password)
    {
        // TODO: Implement change_password() method.
    }

    /**
     * Function to remove an existing user
     *
     * @param $username
     * @return void
     */
    public function remove_user($username)
    {
        // TODO: Implement remove_user() method.
    }

    /**
     * Function to check whether a user has permission for a particular permission string(api method).
     *
     * @param $username
     * @param $permission_string
     * @return bool
     */
    public function checkPermissionForUser($username, $permission_string)
    {
        // TODO: Implement checkPermissionForUser() method.
    }

    /**
     * Function to get all the permissions that a particular user has.
     *
     * @param $username
     * @return mixed
     */
    public function getUserPermissions($username)
    {
        // TODO: Implement getUserPermissions() method.
    }

    /**
     * Function to check whether a role is existing 
     *
     * @param string $roleName 
     * @return IsExistingRoleResponse
     */
    public function isExistingRole( $roleName){
        try{
            return $this->wsis_client->is_existing_role( $roleName);
        } catch (Exception $ex) {
            var_dump($ex); exit;
            throw new Exception("Unable to check if role exists.", 0, $ex);
        }    
    }

    /**
     * Function to add new role by providing the role name.
     * 
     * @param string $roleName
     */
    public function addRole($roleName){
        try{
            return $this->wsis_client->add_role( $roleName);
        } catch (Exception $ex) {
            var_dump($ex); exit;
            throw new Exception("Unable to add role.", 0, $ex);
        }        
    }
    /**
     * Function to get the entire list of roles in the application
     *
     * @return mixed
     */
    public function getRoleNames()
    {
        try{
            return $this->wsis_client->get_all_roles();
        } catch (Exception $ex) {
            var_dump($ex);
            throw new Exception("Unable to get roles.", 0, NULL);
        }        
    }

    /**
     * Function to get the role list of a user
     *
     * @param $username
     * @return mixed
     */
    public function getRoleListOfUser($username)
    {
        try{
            return $this->wsis_client->get_user_roles( $username);
        } catch (Exception $ex) {
            var_dump($ex);
            throw new Exception("Unable to get roles.", 0, NULL);
        }  
    }

    /**
     * Function to get the user list of a particular role
     *
     * @param $role
     * @return mixed
     */
    public function getUserListOfRole($role)
    {
        // TODO: Implement getUserListOfRole() method.
    }

    /**
     * Function to add a role to a user
     *
     * @param $username
     * @param $role
     * @return void
     */
    public function addUserToRole($username, $role)
    {
        // TODO: Implement addUserToRole() method.
    }

    /**
     * Function to role from user
     *
     * @param $username
     * @param $role
     * @return void
     */

    /**
     * Function to update role list of user 
     *
     * @param UpdateRoleListOfUser $parameters
     * @return void
     */
    public function updateRoleListOfUser($username, $roles)
    {
        try{
            return $this->wsis_client->update_user_roles( $username, $roles);
        } catch (Exception $ex) {
            var_dump($ex); exit;
            throw new Exception("Unable to update User roles.", 0, NULL);
        }  
    }
    public function removeUserFromRole($username, $role)
    {
        // TODO: Implement removeUserFromRole() method.
    }

    /**
     * Function to list users
     *
     * @param void
     * @return void
     */
    public function listUsers(){
        try {
            return $this->wsis_client->list_users();
        } catch (Exception $ex) {
    
            throw new Exception( "Unable to list users", 0, $ex);
        }
    }

    /**
     * Function to get the tenant id
     *
     * @param GetTenantId $parameters
     * @return GetTenantIdResponse
     */
    public function getTenantId(){
        try {
            return $this->wsis_client->get_tenant_id();
        } catch (Exception $ex) {
            var_dump( $ex->debug_message); 
            throw new Exception("Unable to get the Tenant Id.", 0, $ex);
        }
    }
    /**
    * Function create a new Tenant
    *
    * @param Tenant $parameters
    * @return void
    */
    public function createTenant( $inputs){
        try {
            return $this->wsis_client->create_tenant( $inputs);
        } catch (Exception $ex) {
            var_dump( $ex); 
            //throw new Exception("Unable to create Tenant.", 0, $ex);
        }
    }
}
