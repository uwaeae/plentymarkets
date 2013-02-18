$(document).ready(function(){
        /*
        $("input.search_code").keyup(function () {

            $("div.results").load('/data/product/search/code/' + $(this).val() + ' div.results table');
            });

        $("input.search_name").keyup(function () {
         $.get('/data/product/search/name/' + $(this).val() , function(data) {
         var content = $(data).find("div.results table")
         $("div.results").html(content);
         });

         });

        $("input.search_latein").keyup(function () {

            $.get('/data/product/search/latein/' + $(this).val() , function(data) {

                var content = $(data).find("div.results table")
                $("div.results").html(content);

            });

        });
         */

    $("input.search_name").keyup(function () {


        if($(this).val().length > 2){
            $.get('/data/product/search/name/' + $(this).val() , function(data) {
                var content = $(data).find(" tbody.results ").children();
                console.log(content);
                $("tbody.results").empty().html(content);
            });
        }


    });
    $("input.search_code").keyup(function () {


        if($(this).val().length > 2){
            $.get('/data/product/search/code/' + $(this).val() , function(data) {
                var content = $(data).find(" tbody.results ").children();
                console.log(content);
                $("tbody.results").empty().html(content);
            });
        }


    });

    $("input.search_latein").keyup(function () {


        if($(this).val().length > 2){
            $.get('/data/product/search/latein/' + $(this).val() , function(data) {
                var content = $(data).find(" tbody.results ").children();
                console.log(content);
                $("tbody.results").empty().html(content);
            });
        }


    });






});