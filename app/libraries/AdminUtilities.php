<?php

use Airavata\Model\Workspace\Gateway;

class AdminUtilities
{

    public static function addGateway($input)
    {
        $gateway = new Gateway();
        $gateway->gatewayId = $input["gatewayName"];
        $gateway->domain = $input["domain"];
        $gateway->gatewayName = $input["gatewayName"];
        $gateway->emailAddress = $input["admin-email"];
        return Airavata::addGateway($gateway);
    }

}