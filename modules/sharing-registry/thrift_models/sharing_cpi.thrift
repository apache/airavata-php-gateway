/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 */

namespace java org.apache.airavata.sharing.registry.service.cpi

include "./sharing_models.thrift"

service SharingRegistryService {

    /**
      <p>API method to create a new domainId.</p>
    */
    string createDomain(1: required sharing_models.Domain domainId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to update a domainId.</p>
    */
    bool updateDomain(1: required sharing_models.Domain domainId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to delete domainId.</p>
    */
    bool deleteDomain(1: required string domainId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to retrieve a domainId.</p>
    */
    sharing_models.Domain getDomain(1: required string domainId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get all domainIds.</p>
    */
    list<sharing_models.Domain> getDomains(1: required i32 offset, 2: required i32 limit) throws (1: sharing_models.SharingRegistryException sre);

    /**
     <p>API method to register a user in the system</p>
    */
    string registerUser(1: required sharing_models.User user) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to update existing user</p>
    */
    bool updatedUser(1: required sharing_models.User user) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to delete user</p>
    */
    bool deleteUser(1: required string userId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get a user</p>
    */
    sharing_models.User getUser(1: required string userId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get a list of users in a specific domainId. Users will be reverse sorted based on the created time.</p>
     <li>domainId : Domain id</li>
     <li>offset : Starting result number</li>
     <li>limit : Number of max results to be sent</li>
    */
    list<sharing_models.User> getUsers(1: required string domainId, 2: required i32 offset, 3: required i32 limit) throws (1: sharing_models.SharingRegistryException sre);

    /**
     <p>API method to create a new group</p>
    */
    string createGroup(1: required sharing_models.UserGroup group) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to update a group</p>
    */
    bool updateGroup(1: required sharing_models.UserGroup group) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to delete a group</p>
    */
    bool deleteGroup(1: required string groupId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get a group</p>
    */
    sharing_models.UserGroup getGroup(1: required string groupId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get groups in a domainId. Results are reverse sorted based on created time.</p>
    */
    list<sharing_models.UserGroup> getGroups(1: required string domainId, 2: required i32 offset, 3: required i32 limit)

    /**
     <p>API method to add list of users to a group</p>
    */
    bool addUsersToGroup(1: required list<string> userIds, 2: required string groupId) throws (1: sharing_models.SharingRegistryException sre);
    /**
     <p>API method to remove users from a group</p>
    */
    bool removeUsersFromGroup(1: required list<string> userIds, 2: required string groupId) throws (1: sharing_models.SharingRegistryException sre);
    /**
     <p>API method to get list of child users in a group. Only the direct members will be returned. Results are reverse time sorted based on creation time</p>
    */
    list<sharing_models.User> getGroupMembersOfTypeUser(1: required string groupId, 2: required i32 offset, 3: required i32 limit) throws (1: sharing_models.SharingRegistryException sre);
    /**
     <p>API method to get list of child groups in a group. Only the direct members will be returned. Results are reverse time sorted based on creation time</p>
    */
    list<sharing_models.UserGroup> getGroupMembersOfTypeGroup(1: required string groupId, 2: required i32 offset, 3: required i32 limit) throws (1: sharing_models.SharingRegistryException sre);
    /**
     <p>API method to add a child group to a parent group.</p>
    */
    bool addChildGroupsToParentGroup(1: required list<string> childIds, 2: required string groupId) throws (1: sharing_models.SharingRegistryException sre);
    /**
     <p>API method to remove a child group from parent group.</p>
    */
    bool removeChildGroupFromParentGroup(1: required string childId, 2: required string groupId) throws (1: sharing_models.SharingRegistryException sre);

    /**
     <p>API method to create a new entity type</p>
    */
    string createEntityType(1: required sharing_models.EntityType entityType) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to update entity type</p>
    */
    bool updateEntityType(1: required sharing_models.EntityType entityType) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to delete entity type</p>
    */
    bool deleteEntityType(1: required string entityTypeId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get an entity type</p>
    */
    sharing_models.EntityType getEntityType(1: required string entityTypeId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get entity types in a domainId. Results are reverse time sorted based on creation time</p>
    */
    list<sharing_models.EntityType> getEntityTypes(1: required string domainId, 2: required i32 offset, 3: required i32 limit) throws (1: sharing_models.SharingRegistryException sre);


    /**
     <p>API method to register new entity</p>
    */
    string registerEntity(1: required sharing_models.Entity entity) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to update entity</p>
    */
    bool updateEntity(1: required sharing_models.Entity entity) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to delete entity</p>
    */
    bool deleteEntity(1: required string entityId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get entity</p>
    */
    sharing_models.Entity getEntity(1: required string entityId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to search entities</p>
    */
    list<sharing_models.Entity> searchEntities(1: required string userId, 2: required string entityTypeId, 3: required list<sharing_models.SearchCriteria> filters, 4: required i32 offset, 5: required i32 limit) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get a list of shared users given the entity id</p>
    */
    list<sharing_models.User> getListOfSharedUsers(1: required string entityId, 2: required string permissionTypeId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get a list of shared groups given the entity id</p>
    */
    list<sharing_models.UserGroup> getListOfSharedGroups(1: required string entityId, 2: required string permissionTypeId) throws (1: sharing_models.SharingRegistryException sre)

    /**
     <p>API method to create permission type</p>
    */
    string createPermissionType(1: required sharing_models.PermissionType permissionType) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to update permission type</p>
    */
    bool updatePermissionType(1: required sharing_models.PermissionType permissionType) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to delete permission type</p>
    */
    bool deletePermissionType(1: required string entityTypeId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get permission type</p>
    */
    sharing_models.PermissionType getPermissionType(1: required string permissionTypeId) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to get list of permission types in a given domainId. Results are reverse time sorted based on creation time</p>
    */
    list<sharing_models.PermissionType> getPermissionTypes(1: required string domainId, 2: required i32 offset, 3: required i32 limit) throws (1: sharing_models.SharingRegistryException sre)

    /**
     <p>API method to share an entity with users</p>
    */
    bool shareEntityWithUsers(1: required string domainId, 2: required string entityId, 3: required list<string> userList, 4: required string perssionTypeId, 5: required bool cascadePermission) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to revoke sharing from a list of users</p>
    */
    bool revokeEntitySharingFromUsers(1: required string domainId, 2: required string entityId, 3: required list<string> userList, 4: required string perssionTypeId ) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to share an entity with list of groups</p>
    */
    bool shareEntityWithGroups(1: required string domainId, 2: required string entityId, 3: required list<string> groupList, 4: required string perssionTypeId, 5: required bool cascadePermission) throws (1: sharing_models.SharingRegistryException sre)
    /**
     <p>API method to revoke sharing from list of users</p>
    */
    bool revokeEntitySharingFromGroups(1: required string domainId, 2: required string entityId, 3: required list<string> groupList, 4: required string perssionTypeId) throws (1: sharing_models.SharingRegistryException sre)

    /**
     <p>API method to check whether a user has access to a specific entity</p>
    */
    bool userHasAccess(1: required string domainId, 2: required string userId, 3: required string entityId, 4: required string permissionTypeId) throws (1: sharing_models.SharingRegistryException sre)
}