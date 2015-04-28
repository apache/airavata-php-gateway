<?php
/**
 * Basic Airavata UserAPI utility functions
 */
/**
 * Import Thrift and Airavata
 */
/*$GLOBALS['THRIFT_ROOT'] = './lib/Thrift/';
require_once $GLOBALS['THRIFT_ROOT'] . 'Transport/TTransport.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Protocol/TProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TException.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TApplicationException.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TProtocolException.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Base/TBase.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Type/TType.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Type/TMessageType.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Factory/TStringFuncFactory.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'StringFunc/TStringFunc.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'StringFunc/Core.php';

$GLOBALS['AIRAVATA_ROOT'] = './lib/Airavata/';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'UserAPI/UserAPI.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'UserAPI/Models/Types.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'UserAPI/Error/Types.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'UserAPI/Types.php';

require_once './lib/UserAPIClientFactory.php';
require_once './id_utilities.php';
require_once './wsis_utilities.php';

use \Airavata\UserAPI\UserAPIClient;
use \Airavata\UserAPI\UserAPIClientFactory;
use \Airavata\UserAPI\Models\UserProfile;
use \Airavata\UserAPI\Models\APIPermissions;
use \Airavata\UserAPI\Models\AuthenticationResponse;

/**
 * Utilities for ID management with Airavata UserAPI*/
 */

class UserAPIUtilities implements IdUtilities{

    const USER_API_CONFIG_PATH = 'userapi_config.ini';

    /**
     * UserAPI client
     *
     * @var UserAPIClient
     * @access private
     */
    private $userapi_client;


    /**
     * UserAPI client factory
     *
     * @var UserAPIClientFactory
     * @access private
     */
    private $userapi_client_factory;

    /**
     * Path to the user api token file
     */
    const USERAPI_TOKEN_DB_PATH = 'userapi_tokens.xml';

    /**
     * Connect to the identity store.
     * @return mixed|void
     */
    public function connect() {
        try {
            global $userapi_config;

            if (file_exists(self::USER_API_CONFIG_PATH)) {
                $userapi_config = parse_ini_file(self::USER_API_CONFIG_PATH);
            } else {
                throw new Exception("Error: Cannot open userapi_config.xml file!");
            }

            if (!$userapi_config) {
                throw new Exception('Error: Unable to read userapi_config.xml!');
            }

            $properties = array();
            $properties['userapiServerHost'] = $userapi_config['server-host'];
            $properties['userapiServerPort'] = $userapi_config['server-port'];
            $properties['thriftTimeout'] = $userapi_config['thrift-timeout'];

            $this->userapi_client_factory = new UserAPIClientFactory($properties);
            $this->userapi_client = $this->userapi_client_factory->getUserAPIClient();
            //testing the API
            $this->userapi_client->getAPIVersion();
        } catch (Exception $ex) {
            print_r( $ex); exit;
            throw new Exception('Unable to instantiate UserAPI client.', 0, NULL);
        }
    }

    /**
     * Return true if the given username exists in the identity server.
     * @param $username
     * @return bool
     */
    public function username_exists($username) {
        try{
            return $this->userapi_client->checkUsernameExists($username,$this->getAPIToken());
        } catch (Exception $ex) {
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
            $apiPermissions = $this->userapi_client->authenticateUser($username, $password, $this->getAPIToken());
            return true;
        } catch (Exception $ex) {
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
            $profile = new UserProfile();
            $profile->firstName = $first_name;
            $profile->lastName = $last_name;
            $profile->emailAddress = $email;
            $profile->organization = $organization;
            $profile->address = $address;
            $profile->country = $country;
            $profile->telephone = $telephone;
            $profile->mobile = $mobile;
            $profile->im = $im;
            $profile->url = $url;

            $this->userapi_client->createNewUser($username, $password, $profile, $this->getAPIToken());
        } catch (Exception $ex) {
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
        try{
            $profile_obj = $this->userapi_client->getUserProfile($username, $this->getAPIToken());
            $profile_arr = array();
            $profile_arr['first_name'] = $profile_obj->firstName;
            $profile_arr['last_name'] = $profile_obj->lastName;
            $profile_arr['email_address'] = $profile_obj->emailAddress;
            $profile_arr['organization'] = $profile_obj->organization;
            $profile_arr['address'] = $profile_obj->address;
            $profile_arr['country'] = $profile_obj->country;
            $profile_arr['telephone'] = $profile_obj->telephone;
            $profile_arr['mobile'] = $profile_obj->mobile;
            $profile_arr['im'] = $profile_obj->im;
            $profile_arr['url'] = $profile_obj->url;
            return $profile_arr;
        } catch (Exception $ex) {
            throw new Exception("Unable to get user profile", 0, NULL);
        }
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
        try{
            $profile = new UserProfile();
            $profile->firstName = $first_name;
            $profile->lastName = $last_name;
            $profile->emailAddress = $email;
            $profile->organization = $organization;
            $profile->address = $address;
            $profile->country = $country;
            $profile->telephone = $telephone;
            $profile->mobile = $mobile;
            $profile->im = $im;
            $profile->url = $url;
            $this->userapi_client->updateUserProfile($username, $profile, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to update user profile", 0, NULL);
        }
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
        try{
            $this->userapi_client->updateUserPassword($username, $new_password, $current_password, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to update user password", 0, NULL);
        }
    }

    /**
     * Function to get the API token for the gateway
     * @throws Exception
     */
    private function getAPIToken(){
        $userapi_token_db = simplexml_load_file(self::USERAPI_TOKEN_DB_PATH);
        $userapi_config = parse_ini_file(self::USER_API_CONFIG_PATH);
        $token = $userapi_token_db->userapi_token[0]->token_string;
        $issue_time = $userapi_token_db->userapi_token[0]->issue_time;
        $life_time = $userapi_token_db->userapi_token[0]->life_time;
        if (file_exists(self::USER_API_CONFIG_PATH)) {
            if(empty($token) || (time()-$issue_time )>($life_time-5000)){
                $authenticationResponse  = $this->userapi_client->authenticateGateway($userapi_config['admin-username'],
                    $userapi_config['admin-password']);
                $userapi_token_db->userapi_token[0]->token_string = $authenticationResponse->accessToken;
                $token = $authenticationResponse->accessToken;
                $userapi_token_db->userapi_token[0]->issue_time = time();
                $userapi_token_db->userapi_token[0]->life_time = $authenticationResponse->expiresIn;

                //Format XML to save indented tree rather than one line
                $dom = new DOMDocument('1.0');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($userapi_token_db->asXML());
                $dom->save(self::USERAPI_TOKEN_DB_PATH);
            }
        } else {
            throw new Exception("Error: Cannot open userapi_config.xml file!");
        }
        return $token;
    }

    /**
     * Function to remove an existing user
     *
     * @param $username
     * @return void
     */
    public function remove_user($username)
    {
        try{
            $this->userapi_client->removeUser($username, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to remove user", 0, NULL);
        }
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
        try{
            return $this->userapi_client->checkPermission($username,$permission_string, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to check permission for user", 0, NULL);
        }
    }

    /**
     * Function to get all the permissions that a particular user has.
     *
     * @param $username
     * @return mixed
     */
    public function getUserPermissions($username)
    {
        try{
            $apiPermissions = $this->userapi_client->getUserPermissions($username, $this->getAPIToken());
            $result['airavata-api'] = $apiPermissions->airavataAPIPermissions;
            $result['app-catalog'] = $apiPermissions->airavataAppCatalogPermissions;
            return $result;
        } catch (Exception $ex) {
            throw new Exception("Unable add user to role", 0, NULL);
        }
    }

    /**
     * Function to get the entire list of roles in the application
     *
     * @return mixed
     */
    public function getRoleList()
    {
        try{
            return $this->userapi_client->getAllRoleNames($this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to get roles list", 0, NULL);
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
            return $this->userapi_client->getRoleListOfUser($username, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to get role list of user", 0, NULL);
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
            return $this->userapi_client->getUserListOfRole($role, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to get user list of role", 0, NULL);
        }
    }

    /**
     * Function to add a role to a user
     *
     * @param $username
     * @param $role
     * @return mixed
     */
    public function addUserToRole($username, $role)
    {
        try{
            return $this->userapi_client->addUserToRole($username,$role, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to add user to role", 0, NULL);
        }
    }

    /**
     * Function to role from user
     *
     * @param $username
     * @param $role
     * @return mixed
     */
    public function removeUserFromRole($username, $role)
    {
        try{
            return $this->userapi_client->removeUserFromRole($username,$role, $this->getAPIToken());
        } catch (Exception $ex) {
            throw new Exception("Unable to remove user from role", 0, NULL);
        }
    }
}
