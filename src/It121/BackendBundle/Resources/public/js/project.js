// JavaScript Document

var project = {
	//Create project
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
    
    //Edit project 
	edit: function(project_id, url) {
		var form_id = '#edit_form_'+project_id; 
		var modal = '#edit_'+project_id;
		var show_modal = '#show_'+project_id;
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
    
    //Delete Project
	remove: function(project_id, url) {
		var form_id = '#delete_form_'+project_id; 
		var modal = '#delete_'+project_id;
		var show_modal = '#show_'+project_id;
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