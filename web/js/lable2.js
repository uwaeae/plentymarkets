$(document).ready(function(){
    var A6Index = 0;


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
    }) .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a><div class='auto_name'> " + item.name2 + "</div><div class='auto_name2'>" +  item.name+"</div><div class='auto_code'>"+item.code + "</div></a>" )
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
                            label:  item.articlecode + ' ' + item.name +' '+item.name2 ,
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
    }) .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a><div class='auto_name'> " + item.code + "</div><div class='auto_name2'>" +  item.name+"</div><div class='auto_code'>"+item.name2 + "</div></a>" )
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

    $('#addDinA6').click(function(event){
        event.preventDefault();
        var item =  $('<div class="a6-print-item" data-a6item="'+A6Index+'"></div>')
        var name = $('form.LableForm input[id="name"]').val();
        var name2 = $('form.LableForm input[id="name2"]').val();
        var description = $('form.LableForm textarea[id="description"]').val();
        var picurl = $('form.LableForm input[id="picurl"]').val();
        item.append($('<img class="a6-print-item-pic" src="' +  picurl + '" />'));
        item.append($('<p><strong>Etikett '+A6Index+'</strong></p>'));
        item.append($('<p><strong>'+name+'</strong></p><p>'+name2+'</p>'));
        item.append($('<p>'+description+'</p>'));


        $('form.LableForm input,textarea').each(function(){
            //console.log($(this).val());
            var id = $(this).attr('id');

            var obj = $('<input type=text type="hidden" ' +
                'name="A6Lable['+A6Index+']['+id+']"'+
                'id="A6_'+A6Index+'_'+ id + '"'+

                '/>').val($(this).val()).hide();
            //$('#labelsToPrint').append(obj);
            item.append(obj);


        });




        var remove = $('<button data-a6item="'+A6Index+'">Remove</button>').click(function(event){
            event.preventDefault();
            var itemid = $(this).data('a6item');
            $('#labelsToPrint div[data-a6item="'+itemid+'"]').remove();

        });
        //$('#labelsToPrint').append(remove);
        item.append(remove);
        $('#labelsToPrint').append(item);
        A6Index ++;


        //form-dinA6

    });
    $('.LableForm').keypress(function(event){
        if( event.keyCode == 13 ){
            // Bei ENTER
            event.preventDefault();
        }
    });

    $('#descriptionShort').keypress(countChars);
    countChars();
});


var countChars = function(){

    var text =  $('#descriptionShort ').val();

    var felder = text.split(" ");
    var line = '';
    var lines = 0;
    var max = 58;
    var length = 0;
    for (var i=0; i<felder.length; i++) {
        length =  (line + ' ' + felder[i]).length;
        if(length <= max ){
            line +=  ' ' + felder[i] ;
        }
        else {
            lines++;
            line = felder[i];
        }

    }
    var result = (max * 5 ) - ((lines * max) + length);
    console.log('lines '+lines+' length ' +length);
    $('#available').val(result);
    if(result < 0)   $('#available').css( "color", "red" );
    else  $('#available').css( "color", "green" );

};

var selected = function( event, ui ) {
    console.log(ui);
    var data = ui.item.data;
    $('#name ').val(data.name);
    $('#name2 ').val(data.name2);
    $('#description ').val(data.description);
    $('#descriptionShort ').val(data.descriptionShort);
    $('#articlecode ').val(data.articlecode);
    $('#articleid ').val(data.articleid);
    $('#picurl').val(data.picurl);
    $('.article_pic').attr('src',data.picurl);
    countChars();
    return false;
}
