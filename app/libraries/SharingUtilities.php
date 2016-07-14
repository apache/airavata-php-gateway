<?php
use Airavata\Model\Group\ResourceType;
use Airavata\Model\Group\ResourcePermissionType;

class SharingUtilities {

    /**
     * Determine if the resource has been shared with any users.
     *
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @return True if the resource has been shared, false otherwise.
     */
    public static function resourceIsShared($resourceId, $dataResourceType) {
        $read = GrouperUtilities::getAllAccessibleUsers($resourceId, $dataResourceType, ResourcePermissionType::READ);
        return (count($read) > 0 ? true : false);
    }

    /**
     * Determine if the user has read privileges on the resource.
     *
     * @param $uid                  The user to check
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @return True if the user has read permission, false otherwise.
     */
    public static function userCanRead($uid, $resourceId, $dataResourceType) {
        if (WSIS::usernameExists($uid)) {
            $read = GrouperUtilities::getAllAccessibleUsers($resourceId, $dataResourceType, ResourcePermissionType::READ);
            return (array_key_exists($uid, $read) ? true : false);
        }
        else {
            return false;
        }
    }

    /**
     * Get the permissions settings for the specified resource.
     *
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @return An array [$uid => [read => bool, write => bool]]
     */
    public static function getAllUserPermissions($resourceId, $dataResourceType) {
        $users = array();

        $read = GrouperUtilities::getAllAccessibleUsers($resourceId, $dataResourceType, ResourcePermissionType::READ);
        $write = GrouperUtilities::getAllAccessibleUsers($resourceId, $dataResourceType, ResourcePermissionType::WRITE);

        $read = array_filter($read, function($uid) {
            return ($uid !== Session::get('username') && WSIS::usernameExists($uid));
        });

        $write = array_filter($write, function($uid) {
            return ($uid !== Session::get('username') && WSIS::usernameExists($uid));
        });

        foreach($read as $uid) {
            $users[$uid] = array('read' => true, 'write' => false);
        }

        foreach($write as $uid) {
            $users[$uid]['write'] = true;
        }

        return $users;
    }

    /**
     * Retrieve profile information for all user in the supplied list.
     *
     * @param $uids An array of uids
     * @return An array [uid => [firstname => string, lastname => string, email => string]]
     */
    public static function getUserProfiles($uids) {
        $uids = array_filter($uids, function($uid) {
            return ($uid !== Session::get('username') && WSIS::usernameExists($uid));
        });
        $profiles = array();
        foreach ($uids as $uid) {
            $profiles[$uid] = WSIS::getUserProfile($uid);
        }
        return $profiles;
    }

    /**
     * Retrieve profile and permissions information for users with access to the given resource.
     *
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @return An array [uid => [firstname => string, lastname => string, email => string, access => [read => bool, write => bool]]]
     */
    public static function getProfilesForSharedUsers($resourceId, $dataResourceType) {
        $perms = SharingUtilities::getAllUserPermissions($resourceId, $dataResourceType);
        $profs = SharingUtilities::getUserProfiles(array_keys($perms));

        foreach ($profs as $uid => $prof) {
            $prof["access"] = $perms[$uid];
            $profs[$uid] = $prof;
        }

        return $profs;
    }

    /**
     * Retrieve profile and permissions information for users without access to the given resource.
     *
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @return An array [uid => [firstname => string, lastname => string, email => string]] of all users without access
     */
    public static function getProfilesForUnsharedUsers($resourceId, $dataResourceType) {
        $users = GrouperUtilities::getAllGatewayUsers();
        $read = GrouperUtilities::getAllAccessibleUsers($resourceId, $dataResourceType, ResourcePermissionType::READ);

        $unshared = array_diff_key($users, $read);

        return SharingUtilities::getUserProfiles($unshared);
    }

    /**
     * Retrieve profile and permissions information for all users for the given resource.
     *
     *
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @return An array [uid => [firstname => string, lastname => string, email => string, access => [read => bool, write => bool]]]
     *         with access only defined for users with permissions.
     */
    public static function getAllUserProfiles($resourceId=null, $dataResourceType=null) {
        $profs = SharingUtilities::getUserProfiles(GrouperUtilities::getAllGatewayUsers());
        if ($resourceId) {
            $perms = SharingUtilities::getAllUserPermissions($resourceId, $dataResourceType);

            foreach ($perms as $uid => $access) {
                $profs[$uid]['access'] = $access;
            }
        }
        return $profs;
    }

    public static function mixProjectPermissionsWithExperiment($projectId, $expId=null) {
        $proj = SharingUtilities::getProfilesForSharedUsers($projectId, ResourceType::PROJECT);
        $exp = SharingUtilities::getAllUserProfiles($expId, ResourceType::EXPERIMENT);

        foreach ($proj as $uid => $prof) {
            if (!array_key_exists($uid, $exp)) {
                $exp[$uid] = $prof;
            }
        }

        return $exp;
    }
}

?>
