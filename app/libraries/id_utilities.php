<?php
/**
 * Interface for ID management
 */

interface IdUtilities
{
    /**
     * Connect to the user database.
     * @return mixed|void
     */
    public function connect();

    /**
     * Return true if the given username exists in the database.
     * @param $username
     * @return bool
     */
    public function username_exists($username);

    /**
     * Authenticate user given username and password.
     * @param $username
     * @param $password
     * @return int|mixed
     */
    public function authenticate($username, $password);

    /**
     * Create new user
     *
     * @param $username
     * @param $password
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
    public function add_user($username, $password, $first_name, $last_name, $email, $organization,
            $address, $country,$telephone, $mobile, $im, $url);

    /**
     * Function to remove an existing user
     *
     * @param $username
     * @return void
     */
    public function remove_user($username);

    /**
     * Get the user profile
     * @param $username
     * @return mixed|void
     */
    public function get_user_profile($username);

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
        $country, $telephone, $mobile, $im, $url);

    /**
     * Function to update user password
     *
     * @param $username
     * @param $current_password
     * @param $new_password
     * @return mixed
     */
    public function change_password($username, $current_password, $new_password);

    /**
     * Function to check whether a user has permission for a particular permission string(api method).
     *
     * @param $username
     * @param $permission_string
     * @return bool
     */
    public function checkPermissionForUser($username, $permission_string);

    /**
     * Function to get all the permissions that a particular user has.
     *
     * @param $username
     * @return mixed
     */
    public function getUserPermissions($username);

    /**
     * Function to get the entire list of roles in the application
     *
     * @return mixed
     */
    public function getRoleNames();
    
    /**
     * Function to check whether a role is existing 
     *
     * @param string $roleName 
     * @return IsExistingRoleResponse
     */
    public function isExistingRole( $roleName);

    /**
     * Function to add new role by providing the role name.
     * 
     * @param string $roleName
     */
    public function addRole($roleName);

    /**
     * Function to get the role list of a user
     *
     * @param $username
     * @return mixed
     */
    public function getRoleListOfUser($username);
    /**
     * Function to update role list of user 
     *
     * @param UpdateRoleListOfUser $parameters
     * @return void
     */
    public function updateRoleListOfUser( $username, $roles);

    /**
     * Function to get the user list of a particular role
     *
     * @param $role
     * @return mixed
     */
    public function getUserListOfRole($role);

    /**
     * Function to add a role to a user
     *
     * @param $username
     * @param $role
     * @return void
     */
    public function addUserToRole($username, $role);

    /**
     * Function to role from user
     *
     * @param $username
     * @param $role
     * @return void
     */
    public function removeUserFromRole($username, $role);
} 