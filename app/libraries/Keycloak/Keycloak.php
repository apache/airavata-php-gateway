<?php

namespace Keycloak;

use Keycloak\API\RoleMapper;

use Log;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Config;

class Keycloak {

    private $realm;
    private $openid_connect_discovery_url;
    private $client_id;
    private $client_secret;
    private $callback_url;
    private $verify_peer;

    private $role_mapper;

    /**
     * Constructor
     *
     */
    public function __construct($realm, $openid_connect_discovery_url, $client_id, $client_secret, $callback_url, $verify_peer, $base_endpoint_url, $admin_username, $admin_password) {

        $this->realm = $realm;
        $this->openid_connect_discovery_url = $openid_connect_discovery_url;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->callback_url = $callback_url;
        $this->verify_peer = $verify_peer;

        $this->role_mapper = new RoleMapper($base_endpoint_url, $admin_username, $admin_password, $verify_peer);
    }

    public function getOAuthRequestCodeUrl(){
        $config = $this->getOpenIDConnectDiscoveryConfiguration();
        $authorization_endpoint = $config->authorization_endpoint;

        // TODO: add state variable to request and put into session
        $url = $authorization_endpoint . '?response_type=code&client_id=' . urlencode($this->client_id)
            . '&redirect_uri=' . urlencode($this->callback_url)
            . '&scope=openid';
        return $url;
    }

    public function getOAuthToken($code){

        $config = $this->getOpenIDConnectDiscoveryConfiguration();
        $token_endpoint = $config->token_endpoint;

        // Init cUrl.
        $r = curl_init($token_endpoint);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        // Decode compressed responses.
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);

        // Add client ID and client secret to the headers.
        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($this->client_id . ":" . $this->client_secret),
        ));

        // Assemble POST parameters for the request.
        $post_fields = "code=" . urlencode($code) . "&grant_type=authorization_code&redirect_uri=" . urlencode($this->callback_url);

        // Obtain and return the access token from the response.
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_POSTFIELDS, $post_fields);

        $response = curl_exec($r);
        if ($response == false) {
            die("curl_exec() failed. Error: " . curl_error($r));
        }

        //Parse JSON return object.
        $result = json_decode($response);
        Log::debug("getOAuthToken response", array($result));

        return $result;
    }

    public function getUserProfileFromOAuthToken($token){

        $config = $this->getOpenIDConnectDiscoveryConfiguration();
        $userinfo_endpoint = $config->userinfo_endpoint;

        $r = curl_init($userinfo_endpoint);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        // Decode compressed responses.
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $token
        ));

        $response = curl_exec($r);
        if ($response == false) {
            die("curl_exec() failed. Error: " . curl_error($r));
        }

        //Parse JSON return object.
        $userinfo = json_decode($response);
        Log::debug("Keycloak userinfo", array($userinfo));
        $username = $userinfo->preferred_username;
        $firstname = $userinfo->given_name;
        $lastname = $userinfo->family_name;
        $email = $userinfo->email;

        // get roles from Keycloak API
        $role_mappings = $this->role_mapper->getRealmRoleMappingsForUser($this->realm, $userinfo->sub);
        $roles = [];
        foreach ($role_mappings as $role_mapping) {
            $roles[] = $role_mapping->name;
        }
        return array('username'=>$username, 'firstname'=>$firstname, 'lastname'=>$lastname, 'email'=>$email, 'roles'=>$roles);
    }

    private function getOpenIDConnectDiscoveryConfiguration() {

        // TODO: cache the result of the request
        $r = curl_init($this->openid_connect_discovery_url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        // Decode compressed responses.
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);

        $result = curl_exec($r);
        if ($result == false) {
            die("curl_exec() failed. Error: " . curl_error($r));
        }

        $json = json_decode($result);

        Log::debug("openid connect discovery configuration", array($json));
        return $json;
    }
}
