<?php

use Airavata\API\Error\AiravataSystemException;
use Airavata\Model\AppCatalog\UserResourceProfile\UserResourceProfile;

class URPUtilities
{

    public static function get_or_create_user_resource_profile()
    {
        try {
            return URPUtilities::get_user_resource_profile();
        } catch (AiravataSystemException $ase) {
            // TODO: replace try/catch with null check once backend is updated, see AIRAVATA-2117
            // Assume that exception was thrown because there is no UserResourceProfile

            return URPUtilities::create_user_resource_profile();
        }
    }

    public static function get_user_resource_profile()
    {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        return Airavata::getUserResourceProfile(Session::get('authz-token'), $userId, $gatewayId);
    }

    public static function create_user_resource_profile()
    {

        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        // TODO add a description to the SSH token
        $credentialStoreToken = AdminUtilities::create_ssh_token();
        $userResourceProfileData = new UserResourceProfile(array(
                "userId" => $userId,
                "gatewayID" => $gatewayId,
                "credentialStoreToken" => $credentialStoreToken
            )
        );
        Airavata::registerUserResourceProfile(Session::get('authz-token'), $userResourceProfileData);

        return Airavata::getUserResourceProfile(Session::get('authz-token'), $userId, $gatewayId);
    }

    public static function update_user_resource_profile($userResourceProfile)
    {

        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        Airavata::updateUserResourceProfile(Session::get('authz-token'), $userId, $gatewayId, $userResourceProfile);
    }

    public static function get_all_ssh_pub_keys_summary_for_user()
    {

        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');

        // TODO use the real method once it has the credentialStoreToken in it
        // $credSummaries = Airavata::getAllSSHPubKeysSummaryForUserInGateway(Session::get('authz-token'), $gatewayId, $userId);
        $userResourceProfile = URPUtilities::get_or_create_user_resource_profile();
        $publicKey = AdminUtilities::get_pubkey_from_token($userResourceProfile->credentialStoreToken);
        $credSummaries = array(
            array(
                "publicKey" => $publicKey,
                "description" => "Default SSH Public Key",
                "credentialStoreToken" => $userResourceProfile->credentialStoreToken
            ),
            array(
                "publicKey" => "dummy public key",
                "description" => "Public Key #2",
                "credentialStoreToken" => "abc123"
            ),
            array(
                "publicKey" => "dummy public key",
                "description" => "Public Key #3",
                "credentialStoreToken" => "def456"
            )
        );

        return $credSummaries;
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