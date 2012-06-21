
function toggleedit(id,url) {

    if( $('div .plantshow'.id).empty() )
        $('div .plantshow'.id).load(url);
    else
        $('div .plantshow'.id).hide();
}

$(document).ready(function(){
        $("input.search_code").keyup(function () {
            //$("tbody.results").load('/data/plant/search/code/' + $(this).val() + 'tbody.results');
            $.get('/data/plant/search/code/' + $(this).val() , function(data) {
                var content = $(data).find(" tbody.results ").children();
                $("tbody.results").empty().html(content);
            });

        });

        $("input.search_name").keyup(function () {
            //$("div.results").load('/data/plant/search/name/' + $(this).val() + ' div.results');
            $.get('/data/plant/search/name/' + $(this).val() , function(data) {
                var content = $(data).find(" tbody.results ").children();
                $("tbody.results").empty().html(content);
            });
        });

        $("input.search_latein").keyup(function () {
           // $("div.results").load('/data/plant/search/latein/' + $(this).val() + ' div.results');

            $.get('/data/plant/search/latein/' + $(this).val() , function(data) {
                var content = $(data).find("table tbody.results").children();
                $("tbody.results").html(content);
            });

        });
});
