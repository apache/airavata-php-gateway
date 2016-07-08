<?php

use Airavata\Model\Group\ResourceType;
use Airavata\Model\Group\ResourcePermissionType;

class GrouperUtilities
{

    /**
     * @return mixed                array of usernames of the users in the gateway
     */
    public static function getAllGatewayUsers()
    {
        return Airavata::getAllUsersInGateway(Session::get('authz-token'), Config::get('pga_config.airavata')['gateway-id']);
    }

    /**
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @param $userPermissionMap    A map of map<username,Airavata\Model\Group\ResourcePermissionType>
     */
    public static function shareResourceWithUsers($resourceId, $dataResourceType, $userPermissionMap)
    {
        $newUserPermissionsMap = [];
        foreach($userPermissionMap as $key=> $value){
            if (strpos($key, '@') !== false) {
                $parts = explode('@', $key);
                $key = $parts[0] . "@" . Config::get('pga_config.airavata')['gateway-id'];
            }else{
                $key = $key . "@" . Config::get('pga_config.airavata')['gateway-id'];
            }
            $newUserPermissionsMap[$key] = $value;
        }
        Airavata::shareResourceWithUsers(Session::get('authz-token'), $resourceId, $dataResourceType, $newUserPermissionsMap);
    }

    /**
     * @param $resourceId           Experiment or Project ID
     * @param $dataResourceType     e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @param $userPermissionMap    A map of map<username,Airavata\Model\Group\ResourcePermissionType>
     */
    public static function revokeSharingOfResourceFromUsers($resourceId, $dataResourceType, $userPermissionMap)
    {
        $newUserPermissionsMap = [];
        foreach($userPermissionMap as $key=> $value){
            if (strpos($key, '@') !== false) {
                $parts = explode('@', $key);
                $key = $parts[0] . "@" . Config::get('pga_config.airavata')['gateway-id'];
            }else{
                $key = $key . "@" . Config::get('pga_config.airavata')['gateway-id'];
            }
            $newUserPermissionsMap[$key] = $value;
        }
        Airavata::revokeSharingOfResourceFromUsers(Session::get('authz-token'), $resourceId, $dataResourceType, $newUserPermissionsMap);
    }

    /**
     * @param $resourceId        Experiment or Project ID
     * @param $dataResourceType  e.g Airavata\Model\Group\ResourceType:PROJECT,Airavata\Model\Group\ResourceType:EXPERIMENT
     * @param $permissionType    e.g Airavata\Model\Group\ResourcePermissionType::READ, Airavata\Model\Group\ResourcePermissionType::WRITE
     * @return array             array of usernames
     */
    public static function getAllAccessibleUsers($resourceId, $dataResourceType, $permissionType)
    {
        $temp = Airavata::getAllAccessibleUsers(Session::get('authz-token'), $resourceId, $dataResourceType, $permissionType);
        $userList = [];
        foreach ($temp as $key => $u) {
            if (strpos($u, '@') !== false) {
                $parts = explode('@', $u);
                $u = $parts[0];
            }
            $userList[] = $u;
        }

        return $userList;
    }
}