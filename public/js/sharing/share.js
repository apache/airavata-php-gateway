/**
 * Utilities for sharing projects and experiments between users
 *
 * @author Jeff Kinnison <jkinniso@nd.edu>
 */

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

    var createTestData = function () {
        var $users, $share, $user;

        $users = $('#share-box-users');
        $share = $('#shared-users');

        for (var user in users) {
            if (users.hasOwnProperty(user)) {
                var data = users[user];
                var access = access_enum.NONE;
                if (data.hasOwnProperty("access")) {
                    console.log("Found access parameter");
                    if (data.access.write) {
                        access = access_enum.WRITE;
                    }
                    else if (data.access.read) {
                        access = access_enum.READ;
                    }
                }

                $user = createThumbnail(user, data.firstname, data.lastname, data.email, access);
                $user.find('.sharing-thumbnail-access').hide();

                $user.addClass('user-thumbnail');
                if (access === access_enum.NONE) {
                    $user.addClass('share-box-users-item');
                    $users.append($user);
                }
                else {
                    console.log("adding shared user");
                    $user.addClass('share-box-share-item');
                    $share.append($user);
                }
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
        if ($share.children().length === 0) {
            $share.append($('<p>This has not been shared</p>')).addClass('text-align-center');
        }
        $('.user-thumbnail').show();
        //$('.group-thumbnail').show();
    }





    /* Share box event handlers */

    // Create, populate, and show the share box
    $('body').on('click', 'button#project-share, button#experiment-share', function(e) {
        var $share_list;
        e.stopPropagation();
        e.preventDefault();

        $share_list = $('#shared-users').children();

        if ($share_list.filter('.sharing-thumbnail').length > 0) {
            $share_list.sort(comparator);
            $share_list.each(function(index, element) {
                var $e;
                $e = $(element);
                $e.find('.sharing-thumbnail-access-text').hide();
                $e.find('.sharing-thumbnail-access').prop('disabled', false).show();
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
        $('#shared-users').prepend('<p>This has not been shared</p>');
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
        var data, resource_id, $share_list, $update_list, share_settings, access;
        e.stopPropagation();
        e.preventDefault();
        data = $("#share-box").data()
        $share_list = $("#share-box-share").children();
        $update_list = $('.sharing-updated');
        share_settings = {};
        if (data.hasOwnProperty('resource_id')) {
            resource_id = data.resource_id;
            updateUserPrivileges(resource_id, $share_list);
        }
        else {
            $('#shared-users').empty();
            if ($share_list.filter('.sharing-thumbnail').length > 0) {
                $share_list.sort(comparator_map.username);
                $update_list.each(function(index, element) {
                    var $e, data, settings;
                    $e = $(element);
                    data = $e.data();
                    if (data.hasOwnProperty('currentaccess')) {
                        data.access = data.currentaccess;
                        $e.data(data);
                    }
                    share_settings[data.username] = data.access;
                    access = parseInt($e.find('.sharing-thumbnail-access').prop('disabled', true).hide().val(), 10);
                    $e.find('.sharing-thumbnail-access-text').text(access_text[access]).show();
                    $e.find('.sharing-thumbnail-unshare').hide();
                });
                $('#share-settings').val(JSON.stringify(share_settings));
                $('#shared-users').removeClass('text-align-center');
                $share_list.detach().appendTo($('#shared-users'));
            }
            else {
                $('#shared-users').addClass('text-align-center');
                $('#shared-users').prepend('<p>This has not been shared</p>');
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
                var $e, data, access;
                $e = $(element);
                data = $e.data();
                if (data.hasOwnProperty('currentaccess')) {
                    data.currentaccess = data.access;
                }
                access = (data.access.write ? access_enum.WRITE : access_enum.READ);
                $e.find('.sharing-thumbnail-access').val(access).prop('disabled', true).hide();
                $e.find('.sharing-thumbnail-access-text').text(access_text[access]).show();
                $e.find('.sharing-thumbnail-unshare').hide();
            });
            $('shared-users').removeClass('text-align-center');
            $original_shared_list.detach().appendTo('#shared-users');
        }
        else {
            $('#shared-users').addClass('text-align-center');
            $('#shared-users').prepend('<p>This has not been shared</p>');
        }
        $('.sharing-updated').removeClass('sharing-updated');
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
        // if ($target.closest('ul, div').hasClass('share-box-share')) {
        //     $target.find('.sharing-thumbnail-access-text').hide();
        //     $target.find('.sharing-thumbnail-access').show();
        // }
        // else {
        //     $target.find('.sharing-thumbnail-access').hide();
        //     $target.find('.sharing-thumbnail-access-text').show();
        // }
        $('.share-box-filter').trigger('keydown');
        $('.order-results-selector').trigger('change');
        return false;
    });

    // Handle changing access level
    $('body').on('change', '.sharing-thumbnail-access', function(e) {
        var $target, $parent, data, access;
        $target = $(e.target);
        $parent = $target.closest('.sharing-thumbnail');
        data = $parent.data();
        access = parseInt($target.val());
        if (access > 0) {
            data.currentaccess.read = true;
        }
        if (access > 1) {
            data.currentaccess.write = true;
        }
        $parent.find('.sharing-thumbnail-access-text').val(access_text[access]);
        $parent.data(data);
        $parent.addClass('sharing-updated');
    });





    /* Set up the sharing interface */
    createTestData();
});
