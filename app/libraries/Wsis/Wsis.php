<?php

namespace Wsis;

use Wsis\Stubs\UserStoreManager;
use Wsis\Stubs\TenantManager;

class Wsis {

    /**
     * @var UserStoreManager
     * @access private
     */
    private $userStoreManager;

    /**
     * @var
     * @access private
     */
    private $tenantManager;

    /**
     * @var string
     * @access private
     */
    private $server;

    /**
     * @var string
     * @access private
     */
    private $service_url;


    /**
     * Constructor
     *
     * @param string $admin_username
     * @param string $admin_password
     * @param string $server
     * @param string $service_url
     * @param string $cafile_path
     * @param bool   $verify_peer
     * @param bool   $allow_selfsigned_cer
     * @throws Exception
     */
    public function __construct($admin_username, $admin_password = null, $server,
                                $service_url,$cafile_path, $verify_peer, $allow_selfsigned_cert) {

        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => $verify_peer,
                "allow_self_signed"=> $allow_selfsigned_cert,
                'cafile' => $cafile_path,
                'CN_match' => $server,
            )
        ));

        $parameters = array(
            'login' => $admin_username,
            'password' => $admin_password,
            'stream_context' => $context,
            'trace' => 1,
            'features' => SOAP_WAIT_ONE_WAY_CALLS
        );

        $this->server = $server;
        $this->service_url = $service_url;

        try {
            $this->userStoreManager = new UserStoreManager($service_url, $parameters);
            $this->tenantManager = new TenantManager($service_url, $parameters);
        } catch (Exception $ex) {
            print_r( $ex); exit;
            throw new Exception("Unable to instantiate client", 0, $ex);
        }
    }


    /**
     * Function to add new user
     *
     * @param string $userName
     * @param string $password
     * @return void
     * @throws Exception
     */
    public function addUser($userName, $password, $fullName) {
        try {
            $this->userStoreManager->addUser($userName, $password, $fullName);
        } catch (Exception $ex) {
            throw new Exception("Unable to add new user", 0, $ex);
        }
    }

    /**
     * Function to delete existing user
     *
     * @param string $username
     * @return void
     * @throws Exception
     */
    public function deleteUser($username) {
        try {
            $this->userStoreManager->deleteUser($username);
        } catch (Exception $ex) {
            throw new Exception("Unable to delete user", 0, $ex);
        }
    }


    /**
     * Function to authenticate user
     *
     * @param string $username
     * @param string $password
     * @return boolean
     * @throws Exception
     */
    public function authenticate($username, $password){
        try {
            return $this->userStoreManager->authenticate($username, $password);
        } catch (Exception $ex) {
            var_dump( $ex); exit;
            throw new Exception("Unable to authenticate user", 0, $ex);
        }
    }

    /**
     * Function to check whether username exists
     *
     * @param string $username
     * @return boolean
     * @throws Exception
     */
    public function username_exists($username){
        try {
            return $this->userStoreManager->isExistingUser($username);
        } catch (Exception $ex) {
            throw new Exception("Unable to verify username exists", 0, $ex);
        }
    }

    /**
     * Function to check whether a role is existing
     *
     * @param string $roleName
     * @return IsExistingRoleResponse
     */
    public function is_existing_role( $roleName){
        try {
            return $this->userStoreManager->isExistingRole( $roleName);
        } catch (Exception $ex) {
            throw new Exception("Unable to check if the role exists", 0, $ex);
        }
    }

    /**
     * Function to add new role by providing the role name.
     *
     * @param string $roleName
     */
    public function add_role($roleName){
        try {
            return $this->userStoreManager->addRole( $roleName);
        } catch (Exception $ex) {
            throw new Exception("Unable to add this role", 0, $ex);
        }
    }

    /**
     * Function to delete existing role
     *
     * @param string $roleName
     * @return void
     * @throws Exception
     */
    public function delete_role($roleName) {
        try {
            $this->userStoreManager->deleteRole($roleName);
        } catch (Exception $ex) {
            var_dump( $ex); exit;

            throw new Exception("Unable to delete role", 0, $ex);
        }
    }

    /**
     * Function to get the list of all existing roles
     *
     * @return roles list
     */
    public function get_all_roles(){
        try {
            return $this->userStoreManager->getRoleNames();
        } catch (Exception $ex) {
            throw new Exception("Unable to get all roles", 0, $ex);
        }
    }

    /**
     * Function to get role of a user
     *
     * @return user role
     */
    public function get_user_roles( $username){
        try {
            return $this->userStoreManager->getRoleListOfUser( $username);
        } catch (Exception $ex) {
            throw new Exception("Unable to get User roles.", 0, $ex);
        }
    }

    /**
     * Function to get the user list of role
     *
     * @param GetUserListOfRole $parameters
     * @return GetUserListOfRoleResponse
     */
    public function get_userlist_of_role( $role){
        try {
            return $this->userStoreManager->getUserListOfRole( $role);
        } catch (Exception $ex) {
            var_dump( $ex); exit;
            throw new Exception("Unable to get user list of roles.", 0, $ex);
        }
    }

    /**
     * Function to update role list of user
     *
     * @param UpdateRoleListOfUser $parameters
     * @return void
     */
    public function update_user_roles( $username, $roles){
        try {
            return $this->userStoreManager->updateRoleListOfUser( $username, $roles);
        } catch (Exception $ex) {
            throw new Exception("Unable to update role of the user.", 0, $ex);
        }
    }

    /**
     * Function to list users
     *
     * @param void
     * @return void
     */
    public function list_users(){
        try {
            return $this->userStoreManager->listUsers();
        } catch (Exception $ex) {
            var_dump( $ex->debug_message);
            throw new Exception("Unable to list users.", 0, $ex);
        }
    }

    /**
     * Function to get the tenant id
     *
     * @param GetTenantId $parameters
     * @return GetTenantIdResponse
     */
    public function get_tenant_id(){
        try {
            return $this->userStoreManager->getTenantId();
        } catch (Exception $ex) {
            var_dump( $ex->debug_message);
            throw new Exception("Unable to get the tenant Id.", 0, $ex);
        }
    }

    /**
     * Function create a new Tenant
     * @param $active
     * @param $adminUsername
     * @param $adminPassword
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param $tenantDomain
     * @throws Exception
     */
    public function create_tenant($active, $adminUsername, $adminPassword, $email,
                                  $firstName, $lastName, $tenantDomain){
        try {
            return $this->tenantManager->addTenant($active, $adminUsername, $adminPassword, $email,
                $firstName, $lastName, $tenantDomain);
        } catch (Exception $ex) {
            /**
             * Fixme -  There is an issue in the Remote IS which throws an exception when called this method
             * But the tenant creation works. Therefore ignores the exception for the moment.
             */
            //throw new Exception("Unable to create Tenant.", 0, $ex);
        }
    }
} 