<?php

use Airavata\Model\Workspace\Gateway;

public function addGateway( $input){
	$gateway = new Gateway();
	$gateway->domain = $input["domainName"];
	$gateway->gatewayName = $input["gatewayName"];
	$gateway->emailAddress = $input["admin-email"];
	return Airavata::addGateway( $gateway);
}