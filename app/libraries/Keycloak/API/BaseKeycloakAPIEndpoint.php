<?php
namespace Keycloak\API;

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

        // http://www.keycloak.org/docs/2.5/server_development/topics/admin-rest-api.html
        // curl -d client_id=admin-cli -d username=username \
        //   -d "password=password" -d grant_type=password https://149.165.156.62:8443/auth/realms/master/protocol/openid-connect/token

        $r = curl_init($this->base_endpoint_url . '/realms/' . rawurlencode($realm) . '/protocol/openid-connect/token');
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);

        // Assemble POST parameters for the request.
        $post_fields = "client_id=admin-cli&username=" . urlencode($this->admin_username) . "&password=" . urlencode($this->admin_password) . "&grant_type=password";

        // Obtain and return the access token from the response.
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_POSTFIELDS, $post_fields);

        $response = curl_exec($r);
        if ($response == false) {
            Log::error("Failed to retrieve API Access Token");
            die("curl_exec() failed. Error: " . curl_error($r));
        }

        $result = json_decode($response);
        // Log::debug("API Access Token result", array($result));
        return $result->access_token;
    }
}
