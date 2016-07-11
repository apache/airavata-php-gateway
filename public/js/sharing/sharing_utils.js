var createThumbnail = function(username, firstname, lastname, email, access = access_enum.NONE, share = true) {
  var $thumbnail, data, select, options;

  data = {
      username: username,
      firstname: firstname,
      lastname: lastname,
      email: email,
      access: {
          read: false,
          write: false
      },
      currentaccess: {
          read: false,
          write: false
      }
  };

  if (access === access_enum.READ) {
      data.access.read = true;
      data.currentaccess.read = true;
  }
  else if (access === access_enum.WRITE) {
      data.access.read = true;
      data.access.write = true;
      data.currentaccess.read = true;
      data.currentaccess.write = true;
  }

  select = '';

  if (share) {
      select = '<select class="sharing-thumbnail-access" style="display: none;" disabled>';

      options = '';
      options += '<option value="' + access_enum.NONE + '"' + (access === access_enum.NONE ? "selected" : "") + ' style="display: none;">No Permissions</option>';
      options += '<option value="' + access_enum.READ + '"' + (access === access_enum.VIEW ? "selected" : "") + '>Can Read</option>';
      options += '<option value="' + access_enum.WRITE + '"' + (access === access_enum.RUN ? "selected" : "") + '>Can Write</option>';

      select += options;
      select += '</select>';
   }

   $thumbnail = $('<div class="sharing-thumbnail col-md-6"> \
                     <div class="thumbnail"> \
                        <button type="button" class="sharing-thumbnail-unshare close" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
                        <div class="col-md-11"> \
                        <h5>' + username + '</h5>\
                        </div> \
                        <div class="col-md-4"> \
                           <img class="sharing-thumbnail-image" src="' + $('.baseimage').prop('src') + '" alt="' + username + '" /> \
                        </div> \
                         <div class="col-md-7"> \
                              <h5 class="sharing-thumbnail-name">' + firstname + ' ' + lastname + '</h5> \
                              <p class="sharing-thumbnail-email">' + email + '</p> \
                              ' + select + ' \
                          </div> \
                      </div>');

   $thumbnail.find('.baseimage').show();
   $thumbnail.data(data);

   return $thumbnail;
}

var changeShareState = function($target) {
    var data;
    data = $target.data();
    // If the user has sharing privileges, revoke them
    if ($target.hasClass('share-box-users-item')) {
        console.log("Sharing");
        $target.find('.sharing-thumbnail-access').val('1').prop("disabled", false).show();
        data.currentaccess.read = true;
        $target.data(data);
        $target.find('.sharing-thumbnail-unshare').show();
        $target.detach().prependTo('#share-box-share').show();
    }
    // Otherwise move to the shared list
    else if ($target.hasClass('share-box-share-item')) {
        console.log("Revoking share");
        $target.find('select').val('0').prop("disabled", true).hide();
        data.currentaccess.read = true;
        data.currentaccess.write = true;
        $target.data(data);
        $target.find('.sharing-thumbnail-unshare').hide();
        $target.detach().appendTo('#share-box-users');
        $('#share-box-filter').trigger('keydown');
        $(".order-results-selector").trigger('change');
    }
    $target.toggleClass('share-box-users-item share-box-share-item');
}

var usernameComparator = function(a, b) {
   var $a, $b;
   console.log("Sorting by username");
   $a = $(a).data();
   $b = $(b).data();

   if ($a.username < $b.username) {
       return -1;
   } else if ($a.username > $b.username) {
       return 1;
   } else {
       return 0;
   }
}

var firstLastComparator = function(a, b) {
   var $a, $b;
   $a = $(a).data();
   $b = $(b).data();

   if ($a.firstname < $b.firstname) {
       return -1;
   } else if ($a.firstname > $b.firstname) {
       return 1;
   } else {
       if ($a.lastname < $b.lastname) {
           return -1;
       } else if ($a.lastname > $b.lastname) {
           return 1;
       } else {
           return 0;
       }
   }
}

var lastFirstComparator = function(a, b) {
   var $a, $b;
   $a = $(a).data();
   $b = $(b).data();

   if ($a.lastname < $b.lastname) {
       return -1;
   } else if ($a.lastname > $b.lastname) {
       return 1;
   } else {
       if ($a.firstname < $b.firstname) {
           return -1;
       } else if ($a.firstname > $b.firstname) {
           return 1;
       } else {
           return 0;
       }
   }
}

var emailComparator = function(a, b) {
   var $a, $b;
   $a = $(a).data();
   $b = $(b).data();

   if ($a.email < $b.email) {
       return -1;
   } else if ($a.email > $b.email) {
       return 1;
   } else {
       return 0;
   }
}

var userFilter = function(users, pattern) {
   re = new RegExp(pattern, 'i');
   $(users).each(function(index, element) {
       var data;
       data = $(element).data();
       if (re.test(data.username.toLowerCase()) ||
           re.test(data.firstname.toLowerCase()) ||
           re.test(data.lastname.toLowerCase()) ||
           re.test(data.email.toLowerCase())
       ) {
           console.log("Showing the user");
           $(element).show();
       } else {
           console.log("Hiding the user");
           $(element).hide();
       }
   });
}
