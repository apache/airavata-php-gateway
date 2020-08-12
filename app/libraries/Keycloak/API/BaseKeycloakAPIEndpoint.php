<?php
namespace Keycloak\API;

use Keycloak\KeycloakUtil;

use Exception;
use Log;

class BaseKeycloakAPIEndpoint {

    protected $base_endpoint_url;
    protected $admin_username;
    protected $admin_password;
    protected $verify_peer;
    protected $cafile_path;
    protected  $client_id;
    protected  $client_secret;

    function __construct($base_endpoint_url, $admin_username, $admin_password, $verify_peer, $cafile_path, $client_id, $client_secret) {
        $this->base_endpoint_url = $base_endpoint_url;
        $this->admin_username = $admin_username;
        $this->admin_password = $admin_password;
        $this->verify_peer = $verify_peer;
        $this->cafile_path = $cafile_path;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    protected function getAPIAccessToken($realm) {

        return KeycloakUtil::getAPIAccessToken($this->base_endpoint_url, $realm, $this->admin_username, $this->admin_password, $this->verify_peer, $this->cafile_path, $this->client_id, $this->client_secret);
    }
}
