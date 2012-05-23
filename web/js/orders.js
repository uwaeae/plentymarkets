
$(document).ready(function(){

    $("div#content input:checkbox")
        .attr("checked","checked");


    $(".order_items_view").click(function() {

        $(this).parent().find(".order_items").toggle();
        return false;

    });


});



