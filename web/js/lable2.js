$(document).ready(function(){
    $( "#name" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/data/product/sjson/name/"+request.term,
                dataType: "json",

                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label:  item.name + ' ' + item.name2+' '+item.articlecode ,
                            name: item.name,
                            name2: item.name2,
                            code: item.articlecode,
                            value: item.name,
                            data: item
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: selected
    })  .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a><div class='auto_name'> " + item.name + "</div><div class='auto_name2'>" +  item.name2+"</div><div class='auto_code'>"+item.code + "</div></a>" )
            .appendTo( ul );
    };

    $( "#name2" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/data/product/sjson/name2/"+request.term,
                dataType: "json",

                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label:  item.name2 + ' ' + item.name+' '+item.articlecode ,
                            value: item.name2,
                            data: item
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: selected
    }) .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a><div class='auto_name'> " + item.name + "</div><div class='auto_name2'>" +  item.name2+"</div><div class='auto_code'>"+item.code + "</div></a>" )
            .appendTo( ul );
    };
    $( "#articlecode" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/data/product/sjson/code/"+request.term,
                dataType: "json",

                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label:  item.articlecode + ' ' + item.name2+' '+item.name ,
                            value: item.articlecode,
                            data: item
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: selected
    }) .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a><div class='auto_name'> " + item.name + "</div><div class='auto_name2'>" +  item.name2+"</div><div class='auto_code'>"+item.code + "</div></a>" )
            .appendTo( ul );
    };


    $('form.LableForm').submit(function(event){
        event.preventDefault();


        var data = $(this).serializeArray();
        $.post($(this).attr('action'), data)
            .done(function(data) {
                $('.PdfOut').attr('data',data['pdfurl']);
            });

    });


});

var selected = function( event, ui ) {
    console.log(ui);
    var data = ui.item.data;
    $('#name ').val(data.name);
    $('#name2 ').val(data.name2);
    $('#description ').val(data.description);
    $('#articlecode ').val(data.articlecode);
    $('#articleid ').val(data.articleid);
    return false;
}
