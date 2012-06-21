
$(document).ready(function(){

    $(" input:checkbox")
        .attr("checked","checked");


    $(".order_items_view").click(function() {

        $(this).parent().find(".order_items").toggle();
        return false;

    });


});



