/**
 * Utilities for sharing projects and experiments between users
 *
 * @author Jeff Kinnison <jkinniso@nd.edu>
 */

var access_enum = {
    NONE: '0',
    READ: '1',
    WRITE: '2'
};

var dummy_user_data = [
    {
        username: 'testuser1',
        firstname: 'Jane',
        lastname: 'Doe',
        email: 'jadoe@institution.edu',
        access: access_enum.NONE
    },
    {
        username: 'testuser2',
        firstname: 'Ego',
        lastname: 'Id',
        email: 'freud@institution.gov',
        access: access_enum.NONE
    },
    {
        username: 'testuser3',
        firstname: 'Ivan',
        lastname: 'Ivanov',
        email: 'notkgb@totallynotkgb.ru',
        access: access_enum.NONE
    },
    {
        username: 'testuser4',
        firstname: 'Grok',
        lastname: 'Smytheson',
        email: 'popsicle@prehistoric.com',
        access: access_enum.ADMIN
    },
    {
        username: 'testuser5',
        firstname: 'Identifier',
        lastname: 'Appellation',
        email: 'idapp@institution.edu',
        access: access_enum.EDIT
    }
];

var dummy_group_data = [
    {
        username: 'Venusian Climate Studies',
        firstname: 'Gazorpazorp',
        lastname: 'Field',
        email: 'gfield@venus.plt',
        access: access_enum.NONE
    },
    {
        username: 'Molecular Dynamics Rawks',
        firstname: 'Jorgen',
        lastname: 'Jorgenson',
        email: 'jjorg@deshaw.org',
        access: access_enum.NONE
    },
    {
        username: 'Socialist Distributed Algorithms',
        firstname: 'Richard',
        lastname: 'Stallman',
        email: 'allmayhaz@cloud.org',
        access: access_enum.NONE
    },
    {
        username: 'Stonferd Center for New Age Math',
        firstname: 'Gugliermo',
        lastname: 'Marconi',
        email: 'gmarconi@stonferd.edu',
        access: access_enum.VIEW
    },
    {
        username: 'CIT Center for Autonomous Studies',
        firstname: 'Madison',
        lastname: 'Li',
        email: 'madili@cit.edu',
        access: access_enum.EDIT
    },
];

$(function() {
    var comparator_map, comparator, $original_shared_list, $revoke_list;
    comparator_map = {
            "username": usernameComparator,
            "firstlast": firstLastComparator,
            "lastfirst": lastFirstComparator,
            "email": emailComparator
    };
    comparator = usernameComparator;

    /* Share box functions */

    /**
     * Create the popup containing sharing functionality
     *
     * @param id The id of the resource being shared
     * @return The share box JQuery element.
     */
    var createShareBox = function(resource_id) {
        var $share_box, $user_section, $share_section, $button_section;
        if (($('#share-box')).length === 0) {
            $share_box = $('<div id="share-box" class="modal-fade" tabindex="-1" role="dialog"> \
                <div class="modal-dialog modal-lg"> \
                    <div class="modal-content"> \
                        <div class="modal-header"> \
                            <button type="button" id="share-box-x" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
                            <h3 class="modal-title">Share this project</h3> \
                        </div> \
                        <div class="modal-body"> \
                            <label>Click on the users you would like to share with.</label> \
                            <input id="share-box-filter" class="form-control" type="text" placeholder="Filter the user list" /> \
                            <label>Show</label>' //\
                            // <div id="show-results-group" class="btn-group" role="group" aria-label="Show Groups or Users">\
                            //     <button type="button" class="show-groups show-results-btn btn btn-primary">Groups</button> \
                            //     <button type="button" class="show-users show-results-btn btn btn-default">Users</button> \
                            // </div> \
                            + '<label>Order By</label> \
                            <select class="order-results-selector"> \
                                <option value="username">Username</option> \
                                <option value="firstlast">First, Last Name</option> \
                                <option value="lastfirst">Last, First Name</option> \
                                <option value="email">Email</option> \
                            </select> \
                            <ul id="share-box-users" class="form-control"></ul> \
                            <label>Set permissions with the drop-down menu on each user, or click the x to cancel sharing.</label> \
                            <ul id="share-box-share" class="form-control"></ul> \
                        </div> \
                        <div class="modal-footer"> \
                            <button type="button" id="share-box-button" class="btn btn-primary">Save</button> \
                            <button type="button" id="share-box-close" class="btn btn-default" data-dismiss="modal">Cancel</button> \
                        </div> \
                    </div> \
                </div> \
            </div>');

            if (resource_id) {
                $share_box.data({'resource_id': resource_id});
            }
        }
        return $share_box;
    }

    var createTestData = function () {
        var $users, $share, $user, data, access;

        $users = $('#share-box-users');
        $share = $('#share-box-share');

        for (var user in users) {
            if (users.hasOwnProperty(user)) {
                data = users[user];
                access = access_enum.NONE;
                if (data.hasOwnProperty(access)) {
                    if (data.access.write) {
                        access = access_enum.WRITE;
                    }
                    else if (data.access.read) {
                        access = access_enu.READ;
                    }
                }
                $user = createThumbnail(user, data.firstname, data.lastname, data.email, access);
                $user.addClass('user-thumbnail');
                $user.addClass('share-box-users-item');
                $users.append($user);
            }
        }

        // for (var group in dummy_group_data) {
        //     if (dummy_group_data.hasOwnProperty(group)) {
        //         data = dummy_group_data[group];
        //         $group = createThumbnail(data.username, data.firstname, data.lastname, data.email, data.access);
        //         $group.addClass('group-thumbnail');
        //         if (data.access === access_enum.NONE) {
        //             $group.addClass('share-box-users-item');
        //             $users.append($group);
        //         }
        //         else {
        //             $group.addClass('share-box-share-item');
        //             $group.find('.sharing-thumbnail-access').prop("disabled", false).show();
        //             $group.find('.sharing-thumbnail-unshare').show();
        //             $share.append($group);
        //         }
        //     }
        // }

        $('.user-thumbnail').show();
        //$('.group-thumbnail').show();
    }





    /* Share box event handlers */

    // Create, populate, and show the share box
    $('body').on('click', 'button#project-share, button#experiment-share', function(e) {
        var $share_list;
        e.stopPropagation();
        e.preventDefault();
        if ($('#share-box').length === 0) {
            $('body').append(createShareBox());
            createTestData();
        }

        $share_list = $('#shared-users').children();

        if ($share_list.filter('.sharing-thumbnail').length > 0) {
            $share_list.sort(comparator);
            $share_list.each(function(index, element) {
                var $e;
                $e = $(element);
                $e.find('.sharing-thumbnail-access').prop('disabled', false);
                $e.find('.sharing-thumbnail-unshare').show();
                $e.detach().appendTo($('#share-box-share'));
            })
        }
        $original_shared_list = $('#share-box-share').children();
        $('#share-box').animate({top: "1%"})
        return false;
    });

    $('body').on('click', 'input[type="reset"]', function (e) {
        var $shared_users;
        $shared_users = $('.share-box-share-item');
        $shared_users.toggleClass('.share-box-share-item .share-box-users-item');
        $shared_users.find('.sharing-thumbnail-access').val(access_enum.NONE).hide();
        $shared_users.detach().appendTo('#share-box-users');
        $('.order-results-selector').trigger('change');
        $('#shared-users').addClass('text-align-center');
        $('#shared-users').prepend('<p>This project has not been shared</p>');
    });

    // Filter the list as the user types
    $('body').on('keyup', '#share-box-filter', function(e) {
        var $target, pattern, visible, $users;
        e.stopPropagation();
        e.preventDefault();
        $target = $(e.target);
        pattern = $target.val().toLowerCase();
        if (!pattern || pattern === '') {
            pattern = /.+/;
        }
        visible = ($('.show-groups').hasClass('btn-primary') ? '.group-thumbnail' : '.user-thumbnail');
        $users = $('#share-box-users').children(visible);
        userFilter($users, pattern);
        return false;
    });

    $('body').on('click', '.show-results-btn', function(e) {
        var $target;
        e.preventDefault();
        e.stopPropagation();
        $target = $(e.target);
        if ($target.hasClass("show-groups") && !$target.hasClass('btn-primary')) {
            $('.group-thumbnail').show();
            $('.user-thumbnail').hide();
            $('.show-groups').addClass('btn-primary');
            $('.show-groups').removeClass('btn-default');
            $('.show-users').addClass('btn-default');
            $('.show-users').removeClass('btn-primary');
        }
        else if ($target.hasClass("show-users") && !$target.hasClass('btn-primary')) {
            $('.user-thumbnail').show();
            $('.group-thumbnail').hide();
            $('.show-users').addClass('btn-primary');
            $('.show-users').removeClass('btn-default');
            $('.show-groups').addClass('btn-default');
            $('.show-groups').removeClass('btn-primary');
        }
        return false;
    });

    $('body').on('change', '.order-results-selector', function(e) {
        var $target, $sibling, $sorted;
        $target = $(e.target);
        console.log($target);
        comparator = comparator_map[$target.val()];
        $('.order-results-selector').val($target.val());
        $sibling = $target.siblings('#shared-users, #share-box-users');
        $sorted = $sibling.children('.sharing-thumbnail');
        $sorted.detach();
        $sorted.sort(comparator);
        $sibling.append($sorted);
    });

    // Save the sharing permissions of each selected user
    $('body').on('click', '#share-box-button', function(e) {
        var data, resource_id, $share_list, share_settings;
        e.stopPropagation();
        e.preventDefault();
        data = $("#share-box").data()
        $share_list = $("#share-box-share").children();
        share_settings = {};
        if (data.hasOwnProperty('resource_id')) {
            resource_id = data.resource_id;
            updateUserPrivileges(resource_id, $share_list);
        }
        else {
            $('#shared-users').empty();
            if ($share_list.filter('.sharing-thumbnail').length > 0) {
                $share_list.sort(comparator_map.username);
                $share_list.each(function(index, element) {
                    var $e, data, settings;
                    $e = $(element);
                    data = $e.data();
                    if (data.hasOwnProperty('currentaccess')) {
                        data.access = data.currentaccess;
                        $e.data(data);
                    }
                    share_settings[data.username] = data.access;
                    $e.find('.sharing-thumbnail-access').prop('disabled', true);
                    $e.find('.sharing-thumbnail-unshare').hide();
                });
                $('#share-settings').val(JSON.stringify(share_settings));
                $('#shared-users').removeClass('text-align-center');
                $share_list.detach().appendTo($('#shared-users'));
            }
            else {
                $('#shared-users').addClass('text-align-center');
                $('#shared-users').prepend('<p>This project has not been shared</p>');
            }
            $('#share-box').animate({top: '100%'});
        }
        return false;
    });

    // Close the share box
    $('body').on('click', '#share-box-close, #share-box-x', function(e) {
        e.stopPropagation();
        e.preventDefault();
        $('#shared-users').empty();
        if ($original_shared_list.length > 0) {
            $original_shared_list.each(function(index, element) {
                var $e, data;
                $e = $(element);
                data = $e.data();
                if (data.hasOwnProperty('currentaccess')) {
                    data.currentaccess = data.access;
                }
                $e.find('select').val(data.access).prop('disabled', true);
                $e.find('.sharing-thumbnail-unshare').hide();
            });
            $('shared-users').removeClass('text-align-center');
            $original_shared_list.detach().appendTo('#shared-users');
        }
        else {
            $('#shared-users').addClass('text-align-center');
            $('#shared-users').prepend('<p>This project has not been shared</p>');
        }
        $('#share-box').animate({top: "100%"});
        return false;
    });

    // Handle sharing and unsharing
    $('body').on('click', '.share-box-users-item, .sharing-thumbnail-unshare', function(e) {
        var $target;
        e.stopPropagation();
        e.preventDefault();
        $target = $(e.target).closest('.sharing-thumbnail');
        changeShareState($target);
        return false;
    });

    // Handle changing access level
    $('body').on('change', '.sharing-thumbnail-access', function(e) {
        var $target, $parent, data;
        $target = $(e.target);
        $parent = $target.closest('.sharing-thumbnail');
        data = $parent.data();
        data.currentaccess = $target.val();
        $parent.data(data);
    });





    /* Set up the sharing interface */
    createShareBox();
    createTestData();
});
