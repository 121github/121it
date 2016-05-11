// JavaScript Document

var loader_url = "";

var refresh_intval = 60000;

//PANEL SIDE
var data = {
    init: function() {
		$(document).on("click", '.dataleft-check-btn', function(e) {
			data.checkDataLeftPanel();
		});

		data.checkDataLeftPanel();
		setInterval(function(){
			data.checkDataLeftPanel(); // this will run after every 30 seconds
		}, refresh_intval);

		$(document).on("click", '.outcomes-check-btn', function(e) {
			data.checkOutcomesPanel();
		});

		data.checkOutcomesPanel();
		setInterval(function(){
			data.checkOutcomesPanel(); // this will run after every 30 seconds
		}, refresh_intval);


        $('.daterange').daterangepicker({
                opens: "left",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                    'Any Time': ["01/01/2014", moment()]
                },
                format: 'DD/MM/YYYY',
                minDate: "02/07/2014",
                maxDate: moment(),
                startDate: moment(),
                endDate: moment()
            },
            function(start, end, element) {
                var $btn = this.element;
                $btn.find('.date-text').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
                $btn.closest('form').find('input[name="date_from"]').val(start.format('YYYY-MM-DD'));
                $btn.closest('form').find('input[name="date_to"]').val(end.format('YYYY-MM-DD'));

                data.checkOutcomesPanel();
            });

        $('.campaign-select').selectpicker({
            style: 'btn-info btn-xs'
        });

        $(document).on("click", '.campaign-select', function(e) {
            data.checkOutcomesPanel();
        });

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
	},

    checkOutcomesPanel: function() {
        var url = "outcomes/check";
        var status = "";

        var $table = $('.outcomes');
        $table.empty();
        $table.append("<tr style='text-align: center'><td colspan='5'><img src='"+loader_url+"' width='50px;' ></td></tr>")
        $.ajax({
            url: url,
            type: "POST",
            data: $('.outcomes-form').serialize(),
            dataType: "JSON"
        }).done(function (response) {
            $table.empty();
            if (response.success) {
                if (response.columns.length) {

                    var thead = "<thead>";

                    thead += "<tr><th>Outcomes</th>";
                    $.each(response.columns, function(i, column) {
                        thead += "<th colspan='2' style='text-align: center'>"+column+"</th>"
                    });
                    thead += "</tr>";

                    thead += "<tr><th></th>";
                    $.each(response.columns, function(i, column) {
                        thead += "<th>V</th>"
                        thead += "<th>%</th>"
                    });
                    thead += "</tr>";

                    thead += "</thead>";

                    var tbody = "<tbody>"
                    $.each(response.data.outcomes, function(outcome, val) {
                        tbody += "<tr><td>"+outcome+"</td>";
                        $.each(response.columns, function(i, column) {
                            var volume = (typeof val[column] != "undefined"?val[column]:0);
                            var percentage = (typeof val[column] != "undefined"?Math.round((val[column]*100)/response.data.total[column]):0);
                            var text_color = (typeof val[column] != "undefined"?"black":"grey");
                            tbody += "<td style='color: "+text_color+"'>"+volume+"</td>";
                            tbody += "<td style='color: "+text_color+"'>"+percentage+"%</td>";
                        });
                        tbody += "</tr>";
                    });

                    //TOTAL
                    tbody += "<tr style='font-weight: bold; font-size: large'><td>TOTAL</td>";
                    $.each(response.columns, function(i, column) {
                        var volume = (typeof response.data.total[column] != "undefined"?response.data.total[column]:0);
                        tbody += "<td colspan='2' style='text-align: center;'>"+volume+"</td>";
                    });
                    tbody += "</tr>";

                    tbody += "</tbody>";

                    $table.append(thead+tbody);
                }
            }
            else {
                $table
                    .append("<tr><td>"+response.data+"</td></tr>");
            }
        });
    }
}