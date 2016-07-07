var createThumbnail = function(username, firstname, lastname, email, access=access_enum.NONE) {
    var $thumbnail, data, options;

    data = {
        username: username,
        firstname: firstname,
        lastname: lastname,
        email: email,
        access: access
    };

    options = '';
    options += '<option value="' + access_enum.NONE + '"' + (access === access_enum.NONE ? "selected" : "") + ' style="display: none;">Can View</option>';
    options += '<option value="' + access_enum.VIEW + '"' + (access === access_enum.VIEW ? "selected" : "") + '>Can View</option>';
    options += '<option value="' + access_enum.RUN + '"' + (access === access_enum.RUN ? "selected" : "") + '>Can Run</option>';
    options += '<option value="' + access_enum.EDIT + '"' + (access === access_enum.EDIT ? "selected" : "") + '>Can Edit</option>';
    options += '<option value="' + access_enum.ADMIN + '"' + (access === access_enum.ADMIN ? "selected" : "") + '>All Privileges</option>';

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
               <select class="sharing-thumbnail-access" style="display: none;" disabled> \
               ' + options + ' \
               </select> \
           </div> \
       </div>');

       $thumbnail.find('.baseimage').show();
       $thumbnail.data(data);

       return $thumbnail;
}

var usernameComparator = function(a, b) {
   var $a, $b;
   console.log("Sorting by username");
   $a = $(a).data();
   $b = $(b).data();

   if ($a.username < $b.username) {
       return -1;
   }
   else if ($a.username > $b.username) {
       return 1;
   }
   else {
       return 0;
   }
}

var firstLastComparator = function(a, b) {
   var $a, $b;
   $a = $(a).data();
   $b = $(b).data();

   if ($a.firstname < $b.firstname) {
       return -1;
   }
   else if ($a.firstname > $b.firstname) {
       return 1;
   }
   else {
       if ($a.lastname < $b.lastname) {
           return -1;
       }
       else if ($a.lastname > $b.lastname) {
           return 1;
       }
       else {
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

var emailComparator = function(a, b) {
   var $a, $b;
   $a = $(a).data();
   $b = $(b).data();

   if ($a.email < $b.email) {
       return -1;
   }
   else if ($a.email > $b.email) {
       return 1;
   }
   else {
       return 0;
   }
}

var userFilter = function(users, pattern) {
   re = new RegExp(pattern, 'i');
   $(users).each(function(index, element) {
      var data;
      data = $(element).data();
      console.log(data);
      if (re.test(data.username.toLowerCase())
          || re.test(data.firstname.toLowerCase())
          || re.test(data.lastname.toLowerCase())
          || re.test(data.email.toLowerCase())
      ) {
         console.log("Showing the user");
         $(element).show();
      }
      else {
         console.log("Hiding the user");
         $(element).hide();
      }
   });
}
