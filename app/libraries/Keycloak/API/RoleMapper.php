<?php
namespace Keycloak\API;

use Exception;
use Log;

/**
 * RoleMapper class
 *
 * This class provide an easy to use interface for
 * the Keycloak RoleMapper REST API.
 */
class RoleMapper extends BaseKeycloakAPIEndpoint {

    /**
     * Get realm-level role mappings for a user
     * GET /admin/realms/{realm}/users/{id}/role-mappings/realm
     *
     * Returns Array of RoleRepresentations
     */
    public function getRealmRoleMappingsForUser($user_id){

        // curl -H "Authorization: bearer $access_token" https://149.165.156.62:8443/auth/admin/realms/airavata/users/2c9ad2c6-0212-4aef-a5fb-9df862578934/role-mappings/realm

        // get access token for admin API

        $url = $this->base_endpoint_url . '/user-management/v1.0.0/user';
        $params = "?client_id=" . urlencode($this->client_id). "&user.username=".urlencode($user_id);
        $url = $url.$params;

        // Log::debug("getRealmRoleMappingsForUser url", array($url));
        $r = curl_init($url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        if($this->verify_peer && $this->cafile_path){
            curl_setopt($r, CURLOPT_CAINFO, $this->cafile_path);
        }
        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($this->client_id . ":" . $this->client_secret),
        ));
        $response = curl_exec($r);
        if ($response == false) {
            Log::error("Failed to retrieve realm role mappings for user");
            die("curl_exec() failed. Error: " . curl_error($r));
        }
        $result = json_decode($response);
        // Log::debug("getRealmRoleMappingsForUser result", array($result));
        return $result->realm_roles;
    }

    /**
     * Add realm-level role mappings for a user
     * POST /admin/realms/{realm}/users/{user_id}/role-mappings/realm
     */
    public function addRealmRoleMappingsToUser($realm, $user_id, $role_representations) {

        // get access token for admin API
        $access_token = $this->getAPIAccessToken();
        $url = $this->base_endpoint_url . 'user-management/v1.0.0/users/roles';
        $r = curl_init($url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        if($this->verify_peer && $this->cafile_path){
            curl_setopt($r, CURLOPT_CAINFO, $this->cafile_path);
        }

        curl_setopt($r, CURLOPT_POST, true);

        $roles = [];
        foreach ($role_representations as $role) {
                    $roles[] = $role->name;
        }

        $usernames = [];
        $usernames[] = $user_id;
        $client_level = false;

        $json =   array("roles"=> $roles, "usernames"=> $usernames, "client_level" => $client_level);


        $data = json_encode($json);
         Log::debug("addRealmRoleMappingsToUser data=$data");
        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " .$access_token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($r, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($r);

        if ($response == false || ! ($response->status)) {
            Log::error("Failed to add realm role mappings for user");
            die("curl_exec() failed. Error: " . curl_error($r));
        }
        return;
    }

    /*
     * Delete realm-level role mappings for a user
     * DELETE /admin/realms/{realm}/users/{user_id}/role-mappings/realm
     */
    public function deleteRealmRoleMappingsToUser($realm, $user_id, $role_representations) {

        // get access token for admin API
        $access_token = $this->getAPIAccessToken();
        $url = $this->base_endpoint_url . 'user-management/v1.0.0/user/roles';
        // Log::debug("deleteRealmRoleMappingsToUser", array($url, $role_representations));
        $r = curl_init($url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $this->verify_peer);
        if($this->verify_peer && $this->cafile_path){
            curl_setopt($r, CURLOPT_CAINFO, $this->cafile_path);
        }

        curl_setopt($r, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($r, CURLOPT_POST, true);

        $roles = [];
        foreach ($role_representations as $role) {
            $roles[] = $role->name;
        }

        $json =   array("roles"=> $roles, "username"=> $user_id);

        $data = json_encode($json);
         Log::debug("deleteRealmRoleMappingsToUser data=$data");
        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " .$access_token,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($r, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($r);

        if ($response == false || ! ($response->status)) {
            Log::error("Failed to add realm role mappings for user");
            die("curl_exec() failed. Error: " . curl_error($r));
        }
        return;
    }
}
