<?php

use Airavata\API\Error\AiravataSystemException;
use Airavata\Model\AppCatalog\UserResourceProfile\UserResourceProfile;
use Airavata\Model\AppCatalog\UserResourceProfile\UserComputeResourcePreference;
use Airavata\Model\AppCatalog\UserResourceProfile\UserStoragePreference;
use Airavata\Model\Credential\Store\SummaryType;

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
        $credentialStoreToken = AdminUtilities::create_ssh_token_for_user("Default SSH Key");
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

        $all_ssh_pub_key_summaries = Airavata::getAllCredentialSummaryForUsersInGateway(Session::get('authz-token'), SummaryType::SSH, $gatewayId, $userId);
        foreach ($all_ssh_pub_key_summaries as $ssh_pub_key_summary) {
            # strip whitespace from public key: there can't be trailing
            # whitespace in a public key entry in the authorized_keys file
            $ssh_pub_key_summary->publicKey = trim($ssh_pub_key_summary->publicKey);
        }
        return URPUtilities::create_credential_summary_map($all_ssh_pub_key_summaries);
    }

    // Create array of CredentialSummary objects where the token is the key
    private static function create_credential_summary_map($credentialSummaries) {

        $credentialSummaryMap = array();
        foreach ($credentialSummaries as $csIndex => $credentialSummary) {
            $credentialSummaryMap[$credentialSummary->token] = $credentialSummary;
        }
        return $credentialSummaryMap;
    }

    public static function add_or_update_user_CRP($inputs, $update = false)
    {
        $inputs = Input::all();
        if( $inputs["reservationStartTime"] != "")
            $inputs["reservationStartTime"] = CommonUtilities::convertLocalToUTC(strtotime($inputs["reservationStartTime"])) * 1000;
        if( $inputs["reservationEndTime"] != "")
            $inputs["reservationEndTime"] = CommonUtilities::convertLocalToUTC(strtotime($inputs["reservationEndTime"])) * 1000;

        $userComputeResourcePreference = new UserComputeResourcePreference($inputs);
        // Log::debug("add_or_update_user_CRP: ", array($userComputeResourcePreference));
        $userId = Session::get('username');
        if ($update)
        {
            return Airavata::updateUserComputeResourcePreference(Session::get('authz-token'), $userId, $inputs["gatewayId"], $inputs["computeResourceId"], $userComputeResourcePreference);
        } else
        {
            return Airavata::addUserComputeResourcePreference(Session::get('authz-token'), $userId, $inputs["gatewayId"], $inputs["computeResourceId"], $userComputeResourcePreference);
        }
    }

    public static function delete_user_CRP($computeResourceId)
    {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        $result = Airavata::deleteUserComputeResourcePreference(Session::get('authz-token'), $userId, $gatewayId, $computeResourceId);
        // Log::debug("deleteUserComputeResourcePreference($userId, $gatewayId, $computeResourceId) => $result");
        return $result;
    }

    /*
     * Get all user's compute resource preferences, keyed by compute resource id.
     */
    public static function get_all_user_compute_resource_prefs()
    {

        $userComputeResourcePreferencesById = array();
        $userResourceProfile = URPUtilities::get_user_resource_profile();
        if (!$userResourceProfile->isNull)
        {
            $userComputeResourcePreferences = $userResourceProfile->userComputeResourcePreferences;
            // Put $userComputeResourcePreferences in a map keyed by computeResourceId
            foreach( $userComputeResourcePreferences as $userComputeResourcePreference )
            {
                $userComputeResourcePreferencesById[$userComputeResourcePreference->computeResourceId] = $userComputeResourcePreference;
            }
        }
        return $userComputeResourcePreferencesById;
    }

    public static function add_or_update_user_SRP($inputs, $update = false)
    {
        $inputs = Input::all();

        $userStoragePreference = new UserStoragePreference($inputs);
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        $storageResourceId = $inputs["storageResourceId"];
        if ($update)
        {
            return Airavata::updateUserStoragePreference(Session::get('authz-token'), $userId, $inputs["gatewayId"], $inputs["storageResourceId"], $userStoragePreference);
        } else
        {
            // Log::debug("addUserStoragePreference($userId, $gatewayId, $storageResourceId)", array($userStoragePreference));
            $result = Airavata::addUserStoragePreference(Session::get('authz-token'), $userId, $gatewayId, $storageResourceId, $userStoragePreference);
            return $result;
        }
    }

    public static function delete_user_SRP($storageResourceId)
    {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        $result = Airavata::deleteUserStoragePreference(Session::get('authz-token'), $userId, $gatewayId, $storageResourceId);
        // Log::debug("deleteUserStoragePreference($userId, $gatewayId, $storageResourceId) => $result");
        return $result;
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