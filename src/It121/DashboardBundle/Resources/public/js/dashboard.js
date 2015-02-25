// JavaScript Document

var loader_url = "";

//PANEL SIDE
var side_panel = {
    init: function() {
		$(document).on("click", '.side-panel-expand-btn', function(e) {
			$('.side-panel-expand-btn').hide();
			$('.side-panel-collect-btn').show();
			$('.side-panel').find('#collapseServer').collapse('show');
			$('.side-panel').find('#collapseDeployment').collapse('show');
			$('.side-panel').find('#collapseUsers').collapse('show');
			$('.side-panel').find('#collapseCallLog').collapse('show');
		});

		$(document).on("click", '.side-panel-collect-btn', function(e) {
			$('.side-panel-expand-btn').show();
			$('.side-panel-collect-btn').hide();
			$('.side-panel').find('#collapseServer').collapse('hide');
			$('.side-panel').find('#collapseDeployment').collapse('hide');
			$('.side-panel').find('#collapseUsers').collapse('hide');
			$('.side-panel').find('#collapseCallLog').collapse('hide');
		});

		$(document).on("click", '.server-check-btn', function(e) {
			side_panel.checkServerPanel();
		});

		$(document).on("click", '.deployment-check-btn', function(e) {
			side_panel.checkDeploymentPanel();
		});

		$(document).on("click", '.call-log-check-btn', function(e) {
			side_panel.checkCallLogPanel();
		});

        $(document).on("click", '.call-today-check-btn', function(e) {
            side_panel.checkTodayCallLogPanel();
        });

		side_panel.checkCallLogPanel();
        setInterval(function(){
            side_panel.checkCallLogPanel(); // this will run after every 30 seconds
        }, 30000);

        side_panel.checkTodayCallLogPanel();
        setInterval(function(){
            side_panel.checkTodayCallLogPanel(); // this will run after every 30 seconds
        }, 30000);

		side_panel.checkServerPanel();
		setInterval(function(){
			side_panel.checkServerPanel(); // this will run after every 30 seconds
		}, 30000);

		side_panel.checkDeploymentPanel();
		setInterval(function(){
			side_panel.checkDeploymentPanel(); // this will run after every 30 seconds
		}, 30000);

    },
	setLoaderUrl: function(url) {
		loader_url = url;
	},

	checkServerPanel: function() {
		var url = "servers/check";
		var status = "";
		var icon_status = "";
		var icon = "";

		var $tbody = $('.servers').find('tbody');
		$tbody.empty();
		$tbody.append("<td style='text-align: center'><img src='"+loader_url+"' width='50px;' ></td>")
		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON"
		}).done(function (response) {
			$tbody.empty();
			if (response.success) {
				$.each(response.data, function(i, server) {
					if (response.data.length) {
						if (server.status.name == 'Ok') {
							status = "success";
							icon_status = "fa-check";
						}
						else if (server.status.name == 'Error') {
							status = "danger";
							icon_status = "fa-remove";
						}
						else if (server.status.name == 'Warning') {
							status = "warning";
							icon_status = "fa-eye";
						}
						if (server.type == 'Service') {
							icon = "fa-laptop";
						}
						else if (server.type == 'Website') {
							icon = "fa-folder";
						}

						$tbody
							.append("<tr class='pop-left "+status+"' data-trigger='hover' data-container='body' data-toggle='popover' data-placement='bottom' data-content='<div>Status "+server.status.name+"</div><div>Last online "+server.last_online+"</div><div>Last check "+server.last_check+"</div><div>Latency "+server.latency+"</div>' data-html='true'>"
										+ "<td><span class='fa "+icon+" fa-1x'></span></td>"
										+ "<td>"+server.name+"</td>"
										+ "<td><span class='fa "+icon_status+" fa-1x'></span></td>"
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
	checkDeploymentPanel: function() {
		var url = "deployment/check";
		var status = "";
		var icon_status = "";
		var icon = "";

		var $tbody = $('.deployments').find('tbody');
		$tbody.empty();
		$tbody.append("<td style='text-align: center'><img src='"+loader_url+"' width='50px;' ></td>")
		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON"
		}).done(function (response) {
			$tbody.empty();
			if (response.success) {
				$.each(response.data, function(i, project) {
					if (response.data.length) {
						if (project.status == 'stable') {
							status = "success";
							icon_status = "fa-check";
						}
						else if (project.status.indexOf('broken') > -1) {
							status = "danger";
							icon_status = "fa-remove";
						}
						else if (project.status == 'back to normal') {
							status = "warning";
							icon_status = "fa-eye";
						}
						else if(project.status.indexOf('?') > -1) {
							status = "info";
						}

						$tbody
							.append("<tr class='pop-left "+status+"' data-trigger='hover' data-container='body' data-toggle='popover' data-placement='bottom' data-content='<div>Status "+project.status+"</div><div>Published "+project.published+"</div><div>Updated "+project.updated+"</div>' data-html='true'>"
								+ "<td><span class='fa fa-gears fa-1x'></span></td>"
								+ "<td><a href='"+project.link+"' target='_blank'>#"+project.version+"</a></td>"
								+ "<td>"+project.title+"</a></td>"
								+ "<td>"
								+ (status == "info"?"<img src='"+loader_url+"' width='25px;'>":"<span class='fa "+icon_status+" fa-1x'></span>")
								+ "</td>"
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
	checkCallLogPanel: function() {
		var url = "calllog/check";

		var $tbody = $('.call-logs').find('tbody');
		$tbody.empty();
		$tbody.append("<td style='text-align: center'><img src='"+loader_url+"' width='50px;' ></td>")
		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON"
		}).done(function (response) {
			$tbody.empty();
			if (response.success) {
				$.each(response.data, function(i, call) {
					if (response.data.length) {
						var callDate = new Date(call.call_date);
						var callDuration = new Date(call.duration);
						var hours = ((callDuration.getHours()+1) > 0?(callDuration.getHours()+1)+'h':'');
						var minutes = (callDuration.getMinutes() > 0?callDuration.getMinutes()+'m':'');
						var seconds = callDuration.getSeconds()+'s';
						var callInbound = (call.inbound?"<span class='glyphicon glyphicon-arrow-up' style='color:green'></span>":"<span class='glyphicon glyphicon-arrow-down' style='color:red'></span>");
						$tbody
							.append("<tr>"
							+ "<td>"+callInbound+"</td>"
							+ "<td>"+call.name_from+"</td>"

							+ "<td>"+callDate.toLocaleTimeString()+"</td>"
							+ "<td style='text-align: right'>"+callDuration.toLocaleTimeString()+" </td>"
							+ "<td>"+call.file.unit+"</td>"
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
    checkTodayCallLogPanel: function() {
        var url = "todaycalllog/check";

        var $body = $('#today-calls');
        $('#today-calls').html("")
        $body.append("<span style='text-align: center'><img src='"+loader_url+"' width='50px;' ></span>")
        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON"
        }).done(function (response) {
            $('#today-calls').html("")
            var s1 = [];
            var s2 = [];
            var ticks = [];
            if (response.success) {
                $.each(response.data, function(i, data) {
                    if (response.data.length) {
                        if (!data.inbound) {
                            s1.push(parseInt(data.num));
                        }
                        else {
                            s2.push(parseInt(data.num));
                        }
                        if($.inArray("Unit "+data.unit, ticks) === -1) {
                            ticks.push("Unit "+data.unit);
                        }
                    }
                });
            }
            else {
                s1.push(0,0);
                s1.push(0,0);
            }


            var plot = $.jqplot('today-calls', [s1, s2], {
                // Tell the plot to stack the bars.
                stackSeries: true,
                captureRightClick: true,
                seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,
                    rendererOptions: {
                        // Put a 30 pixel margin between bars.
                        barMargin: 30,
                        // Highlight bars when mouse button pressed.
                        // Disables default highlighting on mouse over.
                        highlightMouseDown: true
                    },
                    pointLabels: {show: true}
                },
                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        ticks: ticks
                    },
                    yaxis: {
                        // Don't pad out the bottom of the data range.  By default,
                        // axes scaled as if data extended 10% above and below the
                        // actual range to prevent data points right on grid boundaries.
                        // Don't want to do that here.
                        padMin: 0
                    }
                },
                legend: {
                    show: false,
                    location: 'e',
                    placement: 'outside'
                }
            });
        });
    }
}