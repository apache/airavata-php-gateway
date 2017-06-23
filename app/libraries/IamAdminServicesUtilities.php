<?php

class IamAdminServicesUtilities {

    public static function registerUser($username, $email, $first_name, $last_name, $password) {

        $admin_authz_token = IamAdminServicesUtilities::getAdminAuthzToken();
        return IamAdminServices::registerUser($admin_authz_token, $username, $email, $first_name, $last_name, $password);
    }

    public static function addInitialRoleToUser($username) {

        $admin_authz_token = IamAdminServicesUtilities::getAdminAuthzToken();
        $initialRoleName = CommonUtilities::getInitialRoleName();
        IamAdminServices::addRoleToUser($admin_authz_token, $username, $initialRoleName);
    }

    public static function enableUser($username) {

        $admin_authz_token = IamAdminServicesUtilities::getAdminAuthzToken();
        return IamAdminServices::enableUser($admin_authz_token, $username);
    }

    public static function resetUserPassword($username, $new_password) {

        $admin_authz_token = IamAdminServicesUtilities::getAdminAuthzToken();
        return IamAdminServices::resetUserPassword($admin_authz_token, $username, $new_password);
    }

    private static function getAdminAuthzToken() {
        return Keycloak::getAdminAuthzToken();
    }
}
 ?>