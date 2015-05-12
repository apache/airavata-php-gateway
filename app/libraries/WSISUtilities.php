<?php

/**
 * Utilities for ID management with a WSO2 IS 4.6.0
 */

class WSISUtilities implements IdUtilities{

    /**
     * Return true if the given username exists in the identity server.
     * @param $username
     * @return bool
     */
    public function username_exists($username) {
        try{
            //$this->wsis_client = new WSISClient( $username);
            return WSIS::username_exists($username);
        } catch (Exception $ex) {
            print_r( $ex);
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
            return WSIS::authenticate($username, $password);
        } catch (Exception $ex) {
            var_dump( $ex);
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
            WSIS::addUser($username, $password, $first_name . " " . $last_name);
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
            return WSIS::is_existing_role( $roleName);
        } catch (Exception $ex) {
            var_dump($ex);
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
            return WSIS::add_role( $roleName);
        } catch (Exception $ex) {
            var_dump( $ex);
            throw new Exception("Unable to add role.", 0, $ex);
        }        
    }

    /**
     * Function to delete existing role
     * 
     * @param string $roleName
     * @return void
     * @throws Exception
     */
    public function deleteRole($roleName) {
        try {
            WSIS::delete_role($roleName);
        } catch (Exception $ex) {
            throw new Exception("Unable to delete role", 0, $ex);
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
            WSIS::get_all_roles();
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
            return WSIS::get_user_roles( $username);
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
        try{
            return WSIS::get_userlist_of_role( $role);
        } catch (Exception $ex) {
            var_dump( $ex); exit;
            throw new Exception("Unable to get users.", 0, NULL);
        }
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
            return WSIS::update_user_roles( $username, $roles);
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
            return WSIS::list_users();
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
            return WSIS::get_tenant_id();
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
    public function createTenant( $active, $adminUsername, $adminPassword, $email,
                                  $firstName, $lastName, $tenantDomain){
        try {
            return WSIS::create_tenant( $active, $adminUsername, $adminPassword, $email,
                                  $firstName, $lastName, $tenantDomain);
        } catch (Exception $ex) {
            var_dump( $ex); 
            //throw new Exception("Unable to create Tenant.", 0, $ex);
        }
    }

    /**
     * Connect to the user database.
     * @return mixed|void
     */
    public function connect()
    {
        // TODO: Implement connect() method.
    }
}
