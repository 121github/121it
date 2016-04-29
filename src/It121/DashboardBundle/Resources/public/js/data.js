// JavaScript Document

var loader_url = "";

//PANEL SIDE
var data = {
    init: function() {
		$(document).on("click", '.dataleft-check-btn', function(e) {
			data.checkDataLeftPanel();
		});

		data.checkDataLeftPanel();
		setInterval(function(){
			data.checkDataLeftPanel(); // this will run after every 30 seconds
		}, 30000);
    },
	setLoaderUrl: function(url) {
		loader_url = url;
	},

	checkDataLeftPanel: function() {
		var url = "dataleft/check";
		var status = "";

		var $tbody = $('.dataleft').find('tbody');
		$tbody.empty();
		$tbody.append("<tr style='text-align: center'><td colspan='5'><img src='"+loader_url+"' width='50px;' ></td></tr>")
		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON"
		}).done(function (response) {
			$tbody.empty();
			if (response.success) {
				$.each(response.data, function(i, dataleft) {
					if (response.data.length) {
						if (dataleft.mins_remain > 60) {
							status = "success";
						}
                        else if (dataleft.mins_remain <= 60 && dataleft.mins_remain > 20) {
                            status = "warning";
                        }
                        else {
                            status = "danger";
                        }

                        $tbody
							.append("<tr class='"+status+"'>"
										+ "<td>"+dataleft.campaign+"</td>"
										+ "<td>"+dataleft.callbacks+"</td>"
                                        + "<td>"+dataleft.average_dials+"</td>"
                                        + "<td>"+dataleft.agents+"</td>"
                                        + "<td>"+dataleft.mins_remain+"</td>"
									+ "</tr>");
					}
				});
			}
			else {
				$tbody
					.append("<tr><td>"+response.data+"</td></tr>");
			}
		});
	}
}