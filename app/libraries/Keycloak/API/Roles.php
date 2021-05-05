<?php
namespace Keycloak\API;

/**
 * Roles class
 *
 * This class provide an easy to use interface for
 * the Keycloak Roles REST API.
 */
class Roles extends BaseKeycloakAPIEndpoint {

    /**
     * Get representations of all of a realm's roles
     * GET /admin/realms/{realm}/roles
     * Returns Array of RoleRepresentation
     */
    public function getRoles($realm,$access_token){

        // get access token for admin API
        $url = $this->base_endpoint_url . '/tenant-management/v1.0.0/roles';
        $url = $url;
        $r = curl_init($url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        if($this->verify_peer && $this->cafile_path){
            curl_setopt($r, CURLOPT_CAINFO, $this->cafile_path);
        }
        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($access_token),
        ));

        $response = curl_exec($r);
        if ($response == false) {
            die("curl_exec() failed. Error: " . curl_error($r));
        }
        $result = json_decode($response);
        //Log::debug("getRealmRoleMappingsForUser result", array($result));
        return $result->roles;
    }
}
