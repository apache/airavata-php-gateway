/**
 * Utilities for sharing projects and experiments between users
 *
 * @author Jeff Kinnison <jkinniso@nd.edu>
 */

 var createThumbnail = function(username, firstname, lastname, email, img) {
	 var $thumbnail, data;

	 data = {
		 username: username,
		 firstname: firstname,
		 lastname: lastname,
		 email: email
	 };

	 $thumbnail = $('<div class="col-md-6"> \
	 	<div class="user-thumbnail thumbnail"> \
			<div class="col-md-6"> \
				<img src="' + img + '" alt="' + username + '" /> \
			</div> \
			<div class="col-md-6"> \
				<h5>' + firstname + ' ' + lastname + '</h5> \
				<p>' + email + '</p> \
			</div> \
		</div>');

		$thumbnail.data(data);

		return $thumbnail;
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
			                <input id="share-box-filter" type="text" placeholder="Filter the user list" /> \
			                <ul id="share-box-users"></ul> \
			                <p>Set permissions with the drop-down menu on each user, or click the x to cancel sharing.</p> \
							<ul id="share-box-share"></ul> \
			            </div> \
			            <div class="modal-footer"> \
							<button type="button" id="share-box-button" class="btn btn-primary">Share</button> \
			                <button type="button" id="share-box-close" class="btn btn-default" data-dismiss="modal">Close</button> \
			            </div> \
			        </div> \
			    </div> \
			</div>');
			$share_box.data({'resource_id': resource_id});
		}
		return $share_box;
	}





	/* Share box event handlers */

	$('body').on('click', 'button#project-share, button#experiment-share', function(e) {
		e.stopPropagation();
		e.preventDefault();
		$('body').append(createShareBox());
		$('#share-box').animate({top: "1%"})
		return false;
	});

	// Filter the list as the user types
	$('body').on('change', '#share-box-filter', function(e) {
		var $target, pattern, re $users;
		e.stopPropagation();
		e.preventDefault();
		pattern = $target.val();
		if (pattern !== '') {
			re = new RegExp(pattern, 'i');
		}
		else {
			re = new RegExp(/.+/);
		}
		$users = $('#share-box-users').children();
		$users.each(function(index, element) {
			var data;
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
			resource_id = data.resource_id;
			$share_list = $("#share_list").children();
			updateUserPrivileges(resource_id, $share_list);
		}
		else {
			console.log("Error: unknown resource");
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
