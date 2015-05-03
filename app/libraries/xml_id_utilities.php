<?php
/**
 * Utilities for ID management with an XML file
 */

//include 'id_utilities.php';

class XmlIdUtilities implements IdUtilities
{
    const DB_PATH = 'users.xml';

    /**
     * Connect to the user database.
     * @return mixed|void
     */
    public function connect()
    {
        global $db;


        try
        {
            if (file_exists(self::DB_PATH))
            {
                $db = simplexml_load_file(self::DB_PATH);
            }
            else
            {
                throw new Exception("Error: Cannot connect to database!");
            }


            if (!$db)
            {
                throw new Exception('Error: Cannot open database!');
            }
        }
        catch (Exception $e)
        {
            echo '<div>' . $e->getMessage() . '</div>';
        }
    }

    /**
     * Return true if the given username exists in the database.
     * @param $username
     * @return bool
     */
    public function username_exists($username)
    {
        global $db;

        foreach($db->xpath('//username') as $db_username)
        {
            if ($db_username == $username)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Authenticate the user given username and password.
     * @param $username
     * @param $password
     * @return int|mixed
     */
    public function authenticate($username, $password)
    {
        global $db;

        $hashed_password = md5($password);
        
        $user = $db->xpath('//user[username="' . $username . '"]');

        if (sizeof($user) == 1)
        {
            return $user[0]->password_hash == $hashed_password;
        }
        elseif(sizeof($user) == 0)
        {
            return -1;
        }
        else // duplicate users in database
        {
            return -2;
        }
    }

    /**
     * Add a new user to the database.
     * @param $username
     * @param $password
     * @return mixed|void
     */
    public function add_user($username, $password, $first_name, $last_name, $email, $organization,
            $address, $country,$telephone, $mobile, $im, $url)
    {
        global $db;

        $users = $db->xpath('//users');

        $user = $users[0]->addChild('user');

        $user->addChild('username', $username);
        $user->addChild('password_hash', md5($password));

        //Format XML to save indented tree rather than one line
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($db->asXML());
        $dom->save('users.xml');
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
     * Function to get the entire list of roles in the application
     *
     * @return mixed
     */
    public function getRoleList()
    {
        // TODO: Implement getRoleList() method.
    }

    /**
     * Function to get the role list of a user
     *
     * @param $username
     * @return mixed
     */
    public function getRoleListOfUser($username)
    {
        // TODO: Implement getRoleListOfUser() method.
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
    public function removeUserFromRole($username, $role)
    {
        // TODO: Implement removeUserFromRole() method.
    }

    /**
     * Function to get the entire list of roles in the application
     *
     * @return mixed
     */
    public function getRoleNames()
    {
        // TODO: Implement getRoleNames() method.
    }

    /**
     * Function to check whether a role is existing
     *
     * @param string $roleName
     * @return IsExistingRoleResponse
     */
    public function isExistingRole($roleName)
    {
        // TODO: Implement isExistingRole() method.
    }

    /**
     * Function to add new role by providing the role name.
     *
     * @param string $roleName
     */
    public function addRole($roleName)
    {
        // TODO: Implement addRole() method.
    }

    /**
     * Function to update role list of user
     *
     * @param UpdateRoleListOfUser $parameters
     * @return void
     */
    public function updateRoleListOfUser($username, $roles)
    {
        // TODO: Implement updateRoleListOfUser() method.
    }
}
