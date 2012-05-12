




$("input.search_code").keyup(function () {
    $("div.results").load('/data/product/search/code/' + $(this).val() + ' div.results table');


});

$("input.search_name").keyup(function () {
  //  $("div.results").load('/data/product/search/name/' + $(this).val() + ' div.results');

    $.get('/data/product/search/name/' + $(this).val() , function(data) {
        var content = $(data).find("div.results table")
        $("div.results").html(content);
    });


});


$("input.search_latein").keyup(function () {
    //  $("div.results").load('/data/product/search/name/' + $(this).val() + ' div.results');

    $.get('/data/product/search/latein/' + $(this).val() , function(data) {

        var content = $(data).find("div.results table")
        $("div.results").html(content);

    });


});
