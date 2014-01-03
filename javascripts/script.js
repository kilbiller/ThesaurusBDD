$(".rectractable").click(function () {
    var icon = $(this);
    if (icon.hasClass("glyphicon-chevron-right")) {
        icon.removeClass("glyphicon-chevron-right");
        icon.addClass("glyphicon-chevron-up");
    } else {
        icon.removeClass("glyphicon-chevron-up");
        icon.addClass("glyphicon-chevron-right");
    }
    $(this).next().toggle("fast");
});


$("#add").click(function() {
    $("#synonymes").append('<input type="text" class="form-control" name="terme_synonyme[]" placeholder="Entrez un synonyme">');
});
