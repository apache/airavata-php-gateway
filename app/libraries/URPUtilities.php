<?php

use Airavata\API\Error\AiravataSystemException;
use Airavata\Model\AppCatalog\UserResourceProfile\UserResourceProfile;

class URPUtilities
{

    public static function get_or_create_user_resource_profile()
    {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        try {
            return Airavata::getUserResourceProfile(Session::get('authz-token'), $userId, $gatewayId);
        } catch (AiravataSystemException $ase) {
            // TODO: replace try/catch with null check once backend is updated, see AIRAVATA-2117
            // Assume that exception was thrown because there is no UserResourceProfile

            // Create a minimal UserResourceProfile with an SSH credential store token
            $credentialStoreToken = AdminUtilities::create_ssh_token();
            $userResourceProfileData = new UserResourceProfile(array(
                    "userId" => $userId,
                    "gatewayID" => $gatewayId,
                    "" => $credentialStoreToken
                )
            );
            Airavata::registerUserResourceProfile(Session::get('authz-token'), $userResourceProfileData);

            return Airavata::getUserResourceProfile(Session::get('authz-token'), $userId, $gatewayId);
        }
    }

    // Only used for testing
    public static function delete_user_resource_profile()
    {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        Airavata::deleteUserResourceProfile(Session::get('authz-token'), $userId, $gatewayId);
    }
}

?>