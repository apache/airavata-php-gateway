var access_enum = {
    NONE: 0,
    READ: 1,
    WRITE: 2
};

var access_text = [
  'Cannot access',
  'Can read',
  'can write'
];

var createThumbnail = function(username, firstname, lastname, email, access = access_enum.NONE, share = true) {
  var $thumbnail, data, select, options, access_text_current;

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

  access_text_current = access_text[access];

  if (access !== access_enum.NONE) {
      data.access.read = true;
      data.currentaccess.read = true;
  }

  if (access === access_enum.WRITE) {
      data.access.write = true;
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
                              <label class="sharing-thumbnail-access-text">' + access_text_current + '</label> \
                          </div> \
                      </div>');

   $thumbnail.find('.baseimage').show();
   $thumbnail.data(data);

   return $thumbnail;
}

var changeShareState = function($target) {
    var data;
    data = $target.data();
    if ($target.hasClass('share-box-users-item')) {
        $target.find('.sharing-thumbnail-access').val('1').prop("disabled", false).show();
        $target.find('.sharing-thumbnail-access-text').val(access_text[access_enum.READ]).hide();
        data.currentaccess.read = true;
        $target.data(data);
        $target.find('.sharing-thumbnail-unshare').show();
        $target.detach().prependTo('#share-box-share').show();
    }
    else if ($target.hasClass('share-box-share-item')) {
        $target.find('.sharing-thumbnail-access').val('0').prop("disabled", true).hide();
        $target.find('.sharing-thumbnail-access-text').val(access_text[access_enum.NONE]).show();
        data.currentaccess.read = false;
        data.currentaccess.write = false;
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
           $(element).show();
       } else {
           $(element).hide();
       }
   });
}
