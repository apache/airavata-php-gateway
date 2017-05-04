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

    function __construct($base_endpoint_url, $admin_username, $admin_password, $verify_peer) {
        $this->base_endpoint_url = $base_endpoint_url;
        $this->admin_username = $admin_username;
        $this->admin_password = $admin_password;
        $this->verify_peer = $verify_peer;
    }

    protected function getAPIAccessToken($realm) {

        return KeycloakUtil::getAPIAccessToken($this->base_endpoint_url, $realm, $this->admin_username, $this->admin_password, $this->verify_peer);
    }
}
