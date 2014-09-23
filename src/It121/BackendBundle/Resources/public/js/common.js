$(document).ready(function(){
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
});