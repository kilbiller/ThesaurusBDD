$(".concept").click(function() {
    var icon = $(this).find(':first-child');
    if(icon.hasClass( "glyphicon-chevron-right" ))
    {
        icon.removeClass( "glyphicon-chevron-right" )
        icon.addClass( "glyphicon-chevron-up" )
    }
    else
    {
        icon.removeClass( "glyphicon-chevron-up" )
        icon.addClass( "glyphicon-chevron-right" )
    }
    $( this ).next().toggle( "fast" );
});