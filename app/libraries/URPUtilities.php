<?php

use Airavata\API\Error\AiravataSystemException;
use Airavata\Model\AppCatalog\UserResourceProfile\UserResourceProfile;
use Airavata\Model\AppCatalog\UserResourceProfile\UserComputeResourcePreference;

class URPUtilities
{

    public static function get_or_create_user_resource_profile()
    {
        $userResourceProfile = URPUtilities::get_user_resource_profile();
        // Check if user has UserResourceProfile by checking isNull flag
        if ($userResourceProfile->isNull)
        {
            $userResourceProfile = URPUtilities::create_user_resource_profile();
        }
        return $userResourceProfile;
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
        $credentialStoreToken = AdminUtilities::create_ssh_token_with_description("Default SSH Key");
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

        return URPUtilities::create_credential_summary_map(
            Airavata::getAllSSHPubKeysSummaryForUserInGateway(Session::get('authz-token'), $gatewayId, $userId));
    }

    // Create array of CredentialSummary objects where the token is the key
    private static function create_credential_summary_map($credentialSummaries) {

        $credentialSummaryMap = array();
        foreach ($credentialSummaries as $csIndex => $credentialSummary) {
            $credentialSummaryMap[$credentialSummary->token] = $credentialSummary;
        }
        return $credentialSummaryMap;
    }

    public static function add_or_update_user_CRP($inputs)
    {
        $timeDifference = Session::get("user_timezone");
        $addOrSubtract = "-";
        if( $timeDifference > 0)
            $addOrSubtract = "+";
        $inputs = Input::all();
        if( $inputs["reservationStartTime"] != "")
            $inputs["reservationStartTime"] = strtotime( $addOrSubtract . " " . Session::get("user_timezone") . " hours", strtotime( $inputs["reservationStartTime"]) ) * 1000;
        if( $inputs["reservationEndTime"] != "")
            $inputs["reservationEndTime"] = strtotime( $addOrSubtract . " " . Session::get("user_timezone") . " hours", strtotime($inputs["reservationEndTime"]) ) * 1000;

        $userComputeResourcePreference = new UserComputeResourcePreference($inputs);
        $userId = Session::get('username');

        return Airavata::addUserComputeResourcePreference(Session::get('authz-token'), $userId, $inputs["gatewayId"], $inputs["computeResourceId"], $userComputeResourcePreference);
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