<?php

use Airavata\Model\Workspace\Gateway;

public function addGateway( $input){
	$airavataClient = Session::get("airavataClient");
	$gateway = new Gateway();
	$gateway->domain = $input["domainName"];
	$gateway->gatewayName = $input["gatewayName"];
	$gateway->emailAddress = $input["admin-email"];
	return $airavataClient->addGateway( $gateway);
}