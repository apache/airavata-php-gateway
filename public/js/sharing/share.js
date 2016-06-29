/**
 * Utilities for sharing projects and experiments between users
 *
 * @author Jeff Kinnison <jkinniso@nd.edu>
 */

var access_enum = {
	NONE: 0,
	VIEW: 1,
	RUN: 2,
	EDIT: 3,
	ADMIN: 4
};

var dummy_data = [
	{
		username: 'testuser1',
		firstname: 'Jane',
		lastname: 'Doe',
		email: 'jadoe@institution.edu',
		access: access_enum.RUN
	},
	{
		username: 'testuser2',
		firstname: 'Ego',
		lastname: 'Id',
		email: 'freud@institution.gov',
		access: access_enum.VIEW
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

 var createThumbnail = function(username, firstname, lastname, email, access=access_enum.NONE, img="#") {
	 var $thumbnail, data, options;

	 data = {
		 username: username,
		 firstname: firstname,
		 lastname: lastname,
		 email: email,
		 access: access
	 };

	 options = '';
	 options += '<option value="' + access_enum.NONE + '"' + (access === access_enum.NONE ? "selected" : "") + '>Not Shared</option>';
	 options += '<option value="' + access_enum.VIEW + '"' + (access === access_enum.VIEW ? "selected" : "") + '>Can View</option>';
	 options += '<option value="' + access_enum.RUN + '"' + (access === access_enum.RUN ? "selected" : "") + '>Can Run</option>';
	 options += '<option value="' + access_enum.EDIT + '"' + (access === access_enum.EDIT ? "selected" : "") + '>Can Edit</option>';
	 options += '<option value="' + access_enum.ADMIN + '"' + (access === access_enum.ADMIN ? "selected" : "") + '>All Privileges</option>';

	 $thumbnail = $('<div class="user-thumbnail col-md-6"> \
	 	<div class="thumbnail"> \
			<div class="col-md-6"> \
				<img class="user-thumbnail-image" src="' + img + '" alt="' + username + '" /> \
			</div> \
			<div class="col-md-6"> \
				<h5 class="user-thumbnail-name">' + firstname + ' ' + lastname + '</h5> \
				<p class="user-thumbnail-email">' + email + '</p> \
				<select class="user-thumbnail-access"> \
				' + options + ' \
				</select> \
			</div> \
		</div>');

		$thumbnail.data(data);

		return $thumbnail;
 }

 var user_sorter = function(a, b) {
	 var $a, $b;
	 $a = $(a).data();
	 $b = $(b).data();

	 if ($a.lastname < $b.lastname) {
		 return -1;
	 }
	 else if ($a.lastname > $b.lastname) {
		 return 1;
	 }
	 else {
		 if ($a.firstname < $b.firstname) {
			 return -1;
		 }
		 else if ($a.firstname > $b.firstname) {
			 return 1;
		 }
		 else {
			 return 0;
		 }
	 }
 }

$(function() {
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
			                <h4 class="modal-title">Share this project</h4> \
			            </div> \
			            <div class="modal-body"> \
			                <p>Click on the users you would like to share with.</p> \
			                <input id="share-box-filter" class="form-control" type="text" placeholder="Filter the user list" /> \
			                <ul id="share-box-users" class="form-control"></ul> \
							<hr /> \
			                <p>Set permissions with the drop-down menu on each user, or click the x to cancel sharing.</p> \
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
		var $users, $share, $user, data;

		$users = $('#share-box-users');
		$share = $('#share-box-share');

		for (var user in dummy_data) {
			if (dummy_data.hasOwnProperty(user)) {
				data = dummy_data[user];
				$user = createThumbnail(data.username, data.firstname, data.lastname, data.email, data.access);
				if (data.access === access_enum.NONE) {
					$user.addClass('share-box-users-item');
					$user.find('select').prop("disabled", true);
					$users.append($user);
				}
				else {
					$user.addClass('share-box-share-item');
					$share.append($user);
				}
			}
		}
	}





	/* Share box event handlers */

	// Create, populate, and show the share box
	$('body').on('click', 'button#project-share, button#experiment-share', function(e) {
		e.stopPropagation();
		e.preventDefault();
		if ($('#share-box').length === 0) {
			$('body').append(createShareBox());
			createTestData();
		}
		$('#share-box').animate({top: "1%"})
		return false;
	});

	// Filter the list as the user types
	$('body').on('keyup', '#share-box-filter', function(e) {
		var $target, pattern, re, $users;
		e.stopPropagation();
		e.preventDefault();
		$target = $(e.target);
		pattern = $target.val();
		if (pattern && pattern !== '') {
			re = new RegExp(pattern, 'i');
		}
		else {
			re = new RegExp(/.+/);
		}
		$users = $('#share-box-users').children();
		console.log("Users: " + $users);
		$users.each(function(index, element) {
			var data;
			data = $(element).data();
			console.log(data);
			if (re.test(data.username)
			    || re.test(data.firstname)
			    || re.test(data.lastname)
				|| re.test(data.email)
			) {
				console.log("Showing the user");
				$(element).show();
			}
			else {
				console.log("Hiding the user");
				$(element).hide();
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
		$share_list = $("#share-box-share").children();
		if (data.hasOwnProperty('resource_id')) {
			resource_id = data.resource_id;
			updateUserPrivileges(resource_id, $share_list);
		}
		else {
			$share_list.each(function() {});
		}
		return false;
	});

	// Close the share box
	$('body').on('click', '#share-box-close, #share-box-x', function(e) {
		e.stopPropagation();
		e.preventDefault();
		$('#share-box').animate({top: "100%"});
		return false;
	});

	// Handle sharing and unsharing
	$('body').on('click', '.user-thumbnail', function(e) {
		var $target;
		e.stopPropagation();
		e.preventDefault();
		$target = $(e.target).closest('.user-thumbnail');
		console.log($target);
		// If the user has sharing privileges, revoke them
		if ($target.hasClass('share-box-users-item')) {
			console.log("Sharing");
			$target.find('select').prop("disabled", false);
			$target.detach().prependTo('#share-box-share').show();
		}
		// Otherwise move to the shared list
		else if ($target.hasClass('share-box-share-item')) {
			console.log("Revoking share");
			$target.find('select').val('0');
			$target.find('select').prop("disabled", true);
			$target.detach().appendTo('#share-box-users');
			$('#share-box-filter').trigger('change');
		}
		$target.toggleClass('share-box-users-item share-box-share-item');
		return false;
	});

	$('body').on('click', '.user-thumbnail-access', function(e) {
		e.stopPropagation();
		return false;
	});
});
