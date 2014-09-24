// JavaScript Document

var user = {
    //Create user 
    create: function(url) {
    	var form_id = "#new_form"; 
    	var modal = "#new";
        $(form_id).submit( function() {
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                    	$(modal).modal('hide');
                    	$('tbody').empty();
                    	 document.location.reload(true);
                    }
                    else {
                    	$(form_id).empty();
                        $(form_id).append(response);
                    }
                }
            });

            return false; 
        });
    },

	//Edit user 
	edit: function(user_id, url) {
		var form_id = '#edit_form_'+user_id; 
		var modal = '#edit_'+user_id;
		var show_modal = '#show_'+user_id;
		$(show_modal).modal('hide');
	    $(form_id).submit( function() {
	        $.ajax({
	            type: "POST",
	            url: url,
	            data: $(this).serialize(),
	            success: function(response) {
	                if (response.success) {
	                	$(modal).modal('hide');
	                	$('tbody').empty();
	                	 document.location.reload(true);
	                }
	                else {
	                	$(form_id).empty();
	                    $(form_id).append(response);
	                }
	            }
	        });

	        return false; 
	    });
	},
	
	//Change user password 
	change_password: function(user_id, url) {
		var form_id = '#password_form_'+user_id; 
		var modal = '#password_'+user_id;
		var show_modal = '#show_'+user_id;
		$(show_modal).modal('hide');
	    $(form_id).submit( function() {
	        $.ajax({
	            type: "POST",
	            url: url,
	            data: $(this).serialize(),
	            success: function(response) {
	                if (response.success) {
	                	$(modal).modal('hide');
	                	$('tbody').empty();
	                	 document.location.reload(true);
	                }
	                else {
	                	$(form_id).empty();
	                    $(form_id).append(response);
	                }
	            }
	        });

	        return false; 
	    });
	},
	
	//Delete User
	remove: function(user_id, url) {
		var form_id = '#delete_form_'+user_id; 
		var modal = '#delete_'+user_id;
		var show_modal = '#show_'+user_id;
		$(show_modal).modal('hide');
	    $(form_id).submit( function() {
	        $.ajax({
	            type: "DELETE",
	            url: url,
	            data: $(this).serialize(),
	            success: function(response) {
	            	$(modal).modal('hide');
                	$('tbody').empty();
                	document.location.reload(true);
	            }
	        });

	        return false; 
	    });
	}
}