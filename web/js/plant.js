


function toggleedit(id,url) {

    if( $('div .plantshow'.id).empty() )
        $('div .plantshow'.id).load(url);
    else
        $('div .plantshow'.id).hide();
}

$("input.search_code").keyup(function () {
    $("div.results").load('/data/plant/search/code/' + $(this).val() + ' div.results');


});

$("input.search_name").keyup(function () {
    //$("div.results").load('/data/plant/search/name/' + $(this).val() + ' div.results');
    $.get('/data/plant/search/name/' + $(this).val() , function(data) {
        var content = $(data).find("div.results")
        $("div.results").html(content);
    });

});

$("input.search_latein").keyup(function () {
   // $("div.results").load('/data/plant/search/latein/' + $(this).val() + ' div.results');

    $.get('/data/plant/search/latein/' + $(this).val() , function(data) {
        var content = $(data).find("div.results")
        $("div.results").html(content);
    });

});

$(function() {
    function log( message ) {
        $( "<div/>" ).text( message ).prependTo( "#log" );
        $( "#log" ).scrollTop( 0 );
    }

    $( "#latein" ).autocomplete({
        source: "search.php",
        minLength: 2,
        select: function( event, ui ) {
            log( ui.item ?
                "Selected: " + ui.item.value + " aka " + ui.item.id :
                "Nothing selected, input was " + this.value );
        }
    });
});