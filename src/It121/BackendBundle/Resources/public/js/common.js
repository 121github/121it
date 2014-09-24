$(document).ready(function(){
	
	//POPOVER
	
    $(".pop-top").popover({
        placement : 'top',
    }).click(function(e) { 
        e.preventDefault(); 
        $(this).focus(); 
    });
    $(".pop-right").popover({
        placement : 'right',
    }).click(function(e) { 
        e.preventDefault(); 
        $(this).focus(); 
    });
    
    $(".pop-bottom").popover({
        placement : 'bottom', 
    }).click(function(e) { 
        e.preventDefault(); 
        $(this).focus(); 
    });
    $(".pop-left").popover({
        placement : 'left',
    }).click(function(e) { 
        e.preventDefault(); 
        $(this).focus(); 
    });
    
    
    //MESAGGES
    
    //timing the alert box to close after 5 seconds
    window.setTimeout(function () {
	     $(".message").fadeTo(500, 0).slideUp(500, function () {
	         $(this).remove();
	     });
	 }, 5000);
	 //Adding a click event to the 'x' button to close immediately
	 $('.message_info .close').on("click", function (e) {
	     $(this).parent().fadeTo(500, 0).slideUp(500);
	 });
});