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
    protected $openid_discovery_endpoint_url;
    protected  $custos_credential_uri;

    function __construct($openid_discovery_endpoint_url, $base_endpoint_url, $admin_username, $admin_password, $verify_peer, $cafile_path, $client_id, $client_secret, $custos_credential_uri) {
        $this->base_endpoint_url = $base_endpoint_url;
        $this->admin_username = $admin_username;
        $this->admin_password = $admin_password;
        $this->verify_peer = $verify_peer;
        $this->cafile_path = $cafile_path;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->openid_discovery_endpoint_url = $openid_discovery_endpoint_url;
        $this->custos_credential_uri = $custos_credential_uri;
    }

    protected function getAPIAccessToken() {

        return KeycloakUtil::getAPIAccessToken($this->openid_discovery_endpoint_url, $this->custos_credential_uri, $this->admin_username, $this->admin_password, $this->verify_peer, $this->cafile_path, $this->client_id, $this->client_secret);
    }
}
