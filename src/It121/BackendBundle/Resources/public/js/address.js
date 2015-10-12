// JavaScript Document

var loader_url = "";

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

var show = {
    //Init
    init: function() {
        $(document).on("click", '.refresh-postcodeio-btn', function(e) {
            e.preventDefault();
            var postcode = $(this).attr('data-postcode');
            var pafPostcodeId = $(this).attr('data-pafPostcodeId');
            show.getPostcodeIoDetails(postcode, pafPostcodeId);
        });
    },
    setLoaderUrl: function(url) {
        loader_url = url;
    },
    getPostcodeIoDetails: function(postcode, pafPostcodeId) {
        var url = "getpostcodeio";

        var $tbody = $('.postcodeIo-details').find('tbody');
        var $panel = $('.postcodeIo-panel');
        $tbody.empty();
        $panel.find('.update-date').empty().append("<img src='"+loader_url+"' width='50px;' >")
        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            data: {"postcode": postcode, "pafPostcodeId": pafPostcodeId}
        }).done(function (response) {
            $tbody.empty();
            $panel.find('.update-date').empty();
            if (response.success) {
                console.log(response.data);
                $.each(response.data, function(key, value) {
                    var postcodeIoDate = new Date(value.postcode_io.createdDate);
                    $panel.find('.update-date').append(value.postcodeIo.createdDate);
                    //$tbody
                    //    .append("<tr class='pop-left "+status+"' data-trigger='hover' data-container='body' data-toggle='popover' data-placement='bottom' data-content='<div>Status "+server.status.name+"</div><div>Last online "+server.last_online+"</div><div>Last check "+server.last_check+"</div><div>Latency "+server.latency+"</div>' data-html='true'>"
                    //    + "<td><span class='fa "+icon+" fa-1x'></span></td>"
                    //    + "<td>"+server.name+"</td>"
                    //    + "<td><span class='fa "+icon_status+" fa-1x'></span></td>"
                    //    + "</tr>");
                });
            }
            else {
                $tbody
                    .append("<tr><td>"+response.data+"</td></tr>");
            }
        });
    },
}