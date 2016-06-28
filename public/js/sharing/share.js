/**
 * Utilities for sharing projects and experiments between users
 *
 * @author Jeff Kinnison <jkinniso@nd.edu>
 */

$(function() {
	/* Share box functions */

	/**
	 * Create the popup containing sharing functionality
	 *
	 * @param id The id of the resource being shared
	 * @return The share box JQuery element.
	 */
	var createShareBox = function(resource_id) {
		var $box, $filter, $user_list, $share_list, $btn_share, $btn_cancel;
		if (($('#share-box')).length === 0) {
			$box = $('<div id="share-box" class="row"</div>');
			$filter = $('<div class="col-md-8 col-md-offset-2"><input id="share-box-filter" type="text" placeholder="Filter users"></div>');
			$user_list = $('<div class="col-md-8 col-md-offset-2"><ul id="share-box-users"></ul></div>');
			$share_list = $('<ul id="share-box-share"></ul>');
			$btn_share = $('<button id="share-box-button" class="btn btn-primary">Share</button>');
			$btn_close = $('<button id="share-box-cancel" class="btn btn-default">Close</button>');

			$box.data({'resource_id': id});

			$box.append($search, $user_list, $share_list, $btn_share, $btn_close);

			return $box
		}
	}

	/**
	 * Remove the share box from the DOM and clean up.
	 */
	var destroyShareBox() {
		$('#share-box').remove();
	}


	var updateUserPrivileges = function(resource_id, $share_list) {
		$share_list.each(function(index, element) {
			var data = element.data();
			console.log(data.username + " can now view resource " + resource_id);
		});
	}




	/* Share box event handlers */

	$('body').on('click', 'button#project-share, button#experiment-share', function(e) {
		e.stopPropagation();
		e.preventDefault();
		createShareBox();
		return false;
	});

	// Filter the list as the user types
	$('body').on('change', '#share-box-filter', function(e) {
		var $target, pattern, $users;
		e.stopPropagation();
		e.preventDefault();
		pattern = $target.val();
		$users = $('#share-box-users').children();
		$users.each(function(index, element) {
			var re, data;
			re = new RegExp(pattern, 'i');
			data = element.data();

			if (re.test(data.username)
			    || re.test(data.firstname)
			    || re.test(data.lastname)
				|| re.test(data.email)
			) {
				element.show();
			}
			else {
				element.hide();
			}
		});
		return false;
	});

	// Save the sharing permissions of each selected user
	$('body').on('click', '#share-box-button', function(e) {
		var data, resource_id, $share_list;
		e.stopPropagation();
		e.preventDefault();
		data = $("#share-box").data()
		if (data.hasOwnProperty('resource_id')) {
			resource_id = .resource_id;
			$share_list = $("#share_list").children();
			updateUserPrivileges(resource_id, $share_list);
		}
		else {
			console.log("Error: unknown resource");
		}
		return false;
	});

	// Close the share box
	$('body').on('click', '#share-box-close', function(e) {
		e.stopPropagation();
		e.preventDefault();
		destroyShareBox();
		return false;
	});

	// Select a user to share with
	$('body').on('click', '.share-box-users-item', function(e) {
		var $target;
		e.stopPropagation();
		e.preventDefault();
		$target = $(e.target);
		$target.remove();
		$('#share-box-share').prepend($target);
		$target.addClass('.share-box-share-item');
		$target.removeClass('.share-box-users-item');
		return false;
	});

	// Remove a user from the share list
	$('body').on('click', '.share-box-share-item', function(e) {
		e.stopPropagation();
		e.preventDefault();
		$target = $(e.target);
		$target.remove();
		$('#share-box-users').prepend($target);
		$target.addClass('.share-box-users-item');
		$target.removeClass('.share-box-share-item');
		return false;
	});
});
