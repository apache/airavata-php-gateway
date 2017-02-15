<?php

use Airavata\Model\User\Status;
use Airavata\Model\User\UserProfile;

class UserProfileUtilities
{

    public static function does_user_profile_exist() {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        return Airavata::doesUserProfileExist(Session::get('authz-token'), $userId, $gatewayId);
    }

    public static function add_user_profile($userProfileData) {

        $userProfile = new UserProfile($userProfileData);
        $userProfile->creationTime = time();
        $userProfile->lastAccessTime = time();
        $userProfile->validUntil = -1;
        $userProfile->State = Status::ACTIVE;
        return Airavata::addUserProfile(Session::get('authz-token'), $userProfile);
    }
}

?>
