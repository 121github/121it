// JavaScript Document

var server = {
	//Create server
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
    
    //Edit server 
	edit: function(server_id, url) {
		var form_id = '#edit_form_'+server_id; 
		var modal = '#edit_'+server_id;
		var show_modal = '#show_'+server_id;
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
    
    //Delete Server
	remove: function(server_id, url) {
		var form_id = '#delete_form_'+server_id; 
		var modal = '#delete_'+server_id;
		var show_modal = '#show_'+server_id;
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