<?php


class UserProfileUtilities
{

    public static function does_user_profile_exist()
    {
        $userId = Session::get('username');
        $gatewayId = Session::get('gateway_id');
        return Airavata::doesUserProfileExist(Session::get('authz-token'), $userId, $gatewayId);
    }
}

?>
