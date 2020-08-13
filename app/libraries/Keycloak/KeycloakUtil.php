<?php

namespace Keycloak;

use Log;

class KeycloakUtil
{

    public static function getAPIAccessToken($openid_connect_discovery_url, $realm, $admin_username, $admin_password, $verify_peer, $cafile_path, $client_id, $client_sec)
    {

        $config = KeycloakUtil::getOpenIDConnectDiscoveryConfiguration($openid_connect_discovery_url,$client_id,$client_sec);

        $token_endpoint = $config->token_endpoint;

        $r = curl_init($token_endpoint);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, $verify_peer);
        if ($verify_peer && $cafile_path) {
            curl_setopt($r, CURLOPT_CAINFO, $cafile_path);
        }

        // Assemble POST parameters for the request.
        $post_fields = "client_id=admin-cli&username=" . urlencode($admin_username) . "&password=" . urlencode($admin_password) . "&grant_type=password";

        // Obtain and return the access token from the response.
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_POSTFIELDS, $post_fields);

        $response = curl_exec($r);
        if ($response == false) {
            Log::error("Failed to retrieve API Access Token");
            die("curl_exec() failed. Error: " . curl_error($r));
        }

        $result = json_decode($response);

        return $result->access_token;
    }

    public static function getOpenIDConnectDiscoveryConfiguration($openid_connect_discovery_url, $client_id, $client_secret)
    {

        $post_files = "?client_id=" . urlencode($client_id);
        $url = $openid_connect_discovery_url . $post_files;

        // TODO: cache the result of the request
        $r = curl_init($url);

        curl_setopt($r, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($client_id . ":" . $client_secret),
        ));


        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        // Decode compressed responses.
        curl_setopt($r, CURLOPT_ENCODING, 1);

        $result = curl_exec($r);
        if ($result == false) {
            die("curl_exec() failed. Error: " . curl_error($r));
        }

        $json = json_decode($result);


        // Log::debug("openid connect discovery configuration", array($json));
        return $json;
    }


}
