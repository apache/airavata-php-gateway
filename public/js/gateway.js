$( document).ready( function(){
	
		//show options on hovering on a gateway
		$(".panel-title").hover( 
			function(){
				$(this).find(".gateway-options").addClass("in");
			},
			function(){
				$(this).find(".gateway-options").removeClass("in");
			}
		);

		//search Gateway Profiles 
		$('.filterinput').keyup(function() {
	        var a = $(this).val();
	        if (a.length > 0) {
	            children = ($("#accordion1").children());

	            var containing = children.filter(function () {
	                var regex = new RegExp('\\b' + a, 'i');
	                return regex.test($('a', this).text());
	            }).slideDown();
	            children.not(containing).slideUp();
	        } else {
	            children.slideDown();
	        }
	        return false;
	    });

	    //remove Compute Resource
	    $("body").on("click", ".remove-cr", function(){
			$(this).parent().remove();
		});


		$(".add-cr").click( function(){

			$(".add-compute-resource-block").find("#gatewayId").val( $(this).data("gpid"));
			$(this).after( $(".add-compute-resource-block").html() );
		});

		$("body").on("change", ".cr-select", function(){
			crId = $(this).val();
			//This is done as Jquery creates problems when using period(.) in id or class.
			crId = crId.replace(/\./g,"_");
			$(this).parent().parent().find(".pref-space").html( $("#cr-" + crId).html());
		});

		$(".edit-gateway").click( function(){
			$(".edit-gp-name").val( $(this).data("gp-name") );
			$(".edit-gp-desc").val( $(this).data("gp-desc") );
			$(".edit-gpId").val( $(this).data("gp-id") );
		});

		$(".delete-gateway").click( function(){
			$(".delete-gp-name").html( $(this).data("gp-name") );
			$(".delete-gpId").val( $(this).data("gp-id") );
		});

		$(".remove-resource").click( function(){
			$(".remove-cr-name").html( $(this).data("cr-name") );
			$(".remove-crId").val( $(this).data("cr-id") );
			$(".cr-gpId").val( $(this).data("gp-id") );
		});

});