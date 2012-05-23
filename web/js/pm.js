$(document).ready(function(){
    $('.loading').hide();

    initlist();


});

function initlist(){
    $("tr.row_editable").click(function(){

        $(' .loading').show();
        $('div.edit').load($(this).attr('url') + ' form' , function(){


            initedit();
            $('.loading').hide();

        });

    });
}



function initedit(){
    $("form").submit(function() {
        $("tr.row_editable").unbind();
        var $form = $( this ),
            url = $form.attr( 'action' );
        $("div.edit" ).fadeTo('fast',0.1);
        $(' .loading').show();
        $.post( url, $(this).serializeArray(),
            function( data ) {
                var content = $( data ).find('form');
                $("div.edit" ).html( data );
                $("div.edit" ).fadeTo('fast',1);

                $("div.list").fadeTo('fast',0.1);
                $("div.list").load('/data/paymentmethods .list',function(){
                    $("div.list").fadeTo('fast',1);
                   initlist();
                    $('.loading').hide();
                });
            }
        );


        return false;

    });


}