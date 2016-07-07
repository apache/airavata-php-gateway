<?php

use Airavata\Model\Group\ResourceType;
use Airavata\Model\Group\ResourcePermissionType;

class GrouperUtilities
{
    public static function shareResourceWithUsers($resourceId, $dataResourceType, $userPermissionMap)
    {
        Airavata::shareResourceWithUsers(Session::get('authz-token'), $resourceId, $dataResourceType, $userPermissionMap);
    }

    public static function revokeSharingOfResourceFromUsers($resourceId, $dataResourceType, $userPermissionMap)
    {
        Airavata::revokeSharingOfResourceFromUsers(Session::get('authz-token'), $resourceId, $dataResourceType, $userPermissionMap);
    }

    public static function getAllAccessibleUsers($resourceId, $dataResourceType, $permissionType)
    {
        return Airavata::getAllAccessibleUsers($resourceId, $dataResourceType, $permissionType);
    }
}