<?php

namespace Keycloak;

use Log;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Config;

class Keycloak {

    private $openid_connect_discovery_url;
    private $client_id;
    private $client_secret;
    private $callback_url;
    private $verify_peer;

    /**
     * Constructor
     *
     */
    public function __construct($openid_connect_discovery_url, $client_id, $client_secret, $callback_url, $verify_peer) {

        $this->openid_connect_discovery_url = $openid_connect_discovery_url;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->callback_url = $callback_url;
        $this->verify_peer = $verify_peer;
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

    private function getOpenIDConnectDiscoveryConfiguration() {

        $r = curl_init($this->openid_connect_discovery_url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        // Decode compressed responses.
        curl_setopt($r, CURLOPT_ENCODING, 1);
        curl_setopt($r, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($r);

        $json = json_decode($result);

        Log::debug("openid connect discovery configuration", array($json));
        return $json;
    }
}
