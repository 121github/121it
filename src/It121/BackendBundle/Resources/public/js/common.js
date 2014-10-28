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
    
    
    //MENU
	$(".dropdown-menu > li > a.trigger").on("click",function(e){
		var current=$(this).next();
		var grandparent=$(this).parent().parent();
		if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
			$(this).toggleClass('right-caret left-caret');
		grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
		grandparent.find(".sub-menu:visible").not(current).hide();
		current.toggle();
		e.stopPropagation();
	});
	$(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
		var root=$(this).closest('.dropdown');
		root.find('.left-caret').toggleClass('right-caret left-caret');
		root.find('.sub-menu:visible').hide();
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