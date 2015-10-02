// JavaScript Document

var address = {
    //Init
    init: function(url) {
        $(document).on("change", '#import_form_file', function(e) {
            address.getImportedPaf(url);
        });
        $(document).on("click", '.btn-save', function(e) {
            address.importPaf(url);
        });
    },
	//Create user
    importPaf: function(url) {
        console.log(url);
        var form_id = "#import_form";
        var modal = "#updatePaf";
        //$(form_id).submit( function() {
            $.ajax({
                type: "POST",
                url: url,
                data: $(form_id).serialize(),
                success: function(response) {
                    if (response.success) {
                        //$(modal).modal('hide');
                        //$('tbody').empty();
                        //document.location.reload(true);
                    }
                    else {
                        //$(form_id).empty();
                        //$(form_id).append(response);
                    }
                }
            });

            return false;
        //);
    },
    getImportedPaf: function(url) {
        console.log(url);
    }

}