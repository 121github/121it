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
        
        server.select_environments(modal);
    },
    
    //Edit server 
	edit: function(server_id, url) {
		var form_id = '#edit_form_'+server_id; 
		var modal = '#edit_'+server_id;
		var show_modal = '#show_'+server_id;
		server.select_environments();
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
	    
	    server.select_environments(modal);
	    
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
	},
	
	select_subtypes: function(url) {
		$(document).on('change',"#it121_serverbundle_server_type",function(){
		    var data = {
		        type_id: $(this).val()
		    };
		 
		    $.ajax({
		        type: 'post',
		        url: url,
		        data: data,
		        success: function(data) {
		        	var $subtype_selector = $('#it121_serverbundle_server_subtype');
		 
		            $subtype_selector.html('<option>Select a subtype</option>');
		 
		            for (var i=0, total = data.length; i < total; i++) {
			            $subtype_selector.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
		            }
		        }
		    });
		});
	},
	
	select_environments: function(modal) {
		if ($(modal+" #it121_serverbundle_server_subtype").find(":selected").text() == "Development") {
			$(modal+" #environment").show();
		}
	    $(document).on('change',modal+" #it121_serverbundle_server_subtype",function(){
		    var data = {
		    	subtype: $(this).find(":selected").text()
		    };
		    
		    if (data.subtype == 'Development') {
		    	$(modal+" #environment").show();
		    }
		    else {
		    	$(modal+" #environment").hide();
		    	$(modal+' #it121_serverbundle_server_environment').val("");
		    }
		});
	    $(document).on('change',modal+" #it121_serverbundle_server_type",function(){
		    var data = {
		    	subtype: $(this).find(":selected").text()
		    };
		    
		    if (data.subtype == 'Development') {
		    	$(modal+" #environment").show();
		    }
		    else {
		    	$(modal+" #environment").hide();
		    	$(modal+' #it121_serverbundle_server_environment').val("");
		    }
		});
	}
}