function getSummary() {

    var sum = 0;
    $('.shopinglist table tbody tr').each(function () {
        sum += $(this).data("sum");
        //console.log($(this).data("sum"));
    });

    $('.iteminput').keypress(function (event) {
        if (event.keyCode > 112 && event.keyCode < 122) event.preventDefault();
        if (event.keyCode == 13) {

            //$.post('/cashbox/'+$('.inputkeyboard').data('cashbox')+
            //    '/checkout/'+$('.inputkeyboard').data('checkout')+
            //    '/itemaction',
            //    {id: $(this).data('id'), quantity: $(this).val(),action: 'quantity'},buildtable);
            addQuery('/cashbox/' + $('.inputkeyboard').data('cashbox') +
                '/checkout/' + $('.inputkeyboard').data('checkout') +
                '/itemaction',
                {id: $(this).data('id'), quantity: $(this).val(), action: 'quantity'});
            return false;
        }
    });
    $('.itemprice').keypress(function (event) {
        if (event.keyCode > 112 && event.keyCode < 122) event.preventDefault();
        if (event.keyCode == 13) {
            itemFocus = '.inputkeyboard';
            //$.post('/cashbox/'+$('.inputkeyboard').data('cashbox')+
            //    '/checkout/'+$('.inputkeyboard').data('checkout')+
            //    '/itemaction',
            //    {id: $(this).data('id'), price: $(this).val(),action: 'price'},buildtable);
            addQuery('/cashbox/' + $('.inputkeyboard').data('cashbox') +
                '/checkout/' + $('.inputkeyboard').data('checkout') +
                '/itemaction',
                {id: $(this).data('id'), price: $(this).val(), action: 'price'});
            return false;
        }
    });


    $('.co_toPay').html(sum.toFixed(2) + '&euro;');
    $('.itemEdit a').click(function () {

//        $.post('/cashbox/'+$('.inputkeyboard').data('cashbox')+'/checkout/'+$('.inputkeyboard').data('checkout')+'/itemaction',
//            { id: $(this).data('id'),action:$(this).data('action')},buildtable);
        addQuery('/cashbox/' + $('.inputkeyboard').data('cashbox') + '/checkout/' + $('.inputkeyboard').data('checkout') + '/itemaction',
            { id: $(this).data('id'), action: $(this).data('action')});


    });


}
var itemFocus = '.itemprice:last';
// Artikel Tabelle aus JSON daten Bauen
var buildtable = function buildTable(data) {

    /* if (!(typeof(data) == 'object')) {
     var savedata = data;
     $.get('/login', function (data) {

     //console.log($('div#login'));
     if ($('div#login')) {

     $('.ym-column').fadeTo("fast", 0.33);

     $('.ym-wrapper').append($(data).find('div#login'));

     $('div#login form').submit(function (event) {
     event.preventDefault();
     //console.log($(this).attr('action'))

     $.post($(this).attr('action'), $(this).serialize(), function (data) {
     console.log(data);
     $('.ym-column').fadeTo("fast", 1);
     $('div#login').remove();

     return false;

     });

     })
     }


     });


     }

     else {*/


    //$('.shopinglist').empty().html($(data).find('.shopinglist table'));
    $('.co_items').empty();
    var index = 1;
    $.each(data, function (key, val) {

        var items = [];
        items.push('<td>' + index + '</td>');
        // items.push('<td>' + val['code'] + '</td>');
        items.push('<td><input class="iteminput" value="' + val['quantity'] + '" data-id="' + val['id'] + '"></td>'
            /*'<div class="itemEdit">'+
             '<a href="#" data-action="minus" data-id="'+ val['id'] +'">' +
             '<img src="/images/icons/arrow-down.png" alt="- Menge" /></a>'+
             '<a href="#" data-action="plus" data-id="'+ val['id'] +'">'+
             ' <img src="/images/icons/arrow-up.png" alt="+ Menge" /></a>'*/
        );
        items.push('<td>' + val['description'] + '</td>');
        items.push('<td>' + val['VAT'] + '%</td>');
        items.push('<td>' +
            '<input class="itemprice" value="' + parseFloat(val['price']).toFixed(2) + '" data-id="' + val['id'] + '">' +
            '</td>'
        );
        // items.push('<td>' + val['pa'] + '</td>');
        items.push('<td>' + parseFloat(val['sum']).toFixed(2) + ' &euro;' +
            '<div class="itemEdit">' +
            '<a href="#" data-action="delete" data-id="' + val['id'] + '">' +
            '<img src="/images/icons/remove2.png" alt="Löschen" /></a>' +
            '</div></td>');

        $('<tr>', {
            'id': key,
            'data-sum': val['sum'],
            html: items.join('')

        }).appendTo('.co_items');
        index++;
    });
    getSummary();
    $('.inputkeyboard').val('');
    $(itemFocus).val('').focus();

    //    }

};

var query = new Array();
var running = false;
function addQuery(url, data) {
    var item = new Object();
    item['URL'] = url;
    item['DATA'] = data;
    query.push(item);
}

var runQuery = function () {
    if (!running) {

        running = true;
        while (query.length != 0) {
            item = query[0];
            $.post(item.URL, item.DATA, buildtable);

            query.shift();
            //console.log(item);
        }
        running = false;
    }
}


$(document).ready(function () {
    init();

});

var init = function () {


    self.setInterval(runQuery, 200);
// Quittung Drucken
    $('.bontext').submit(function (event) {
        event.preventDefault();
        for (instance in CKEDITOR.instances)
            CKEDITOR.instances[instance].updateElement();
        var text = $(this).find('textarea').val();
        if (text.length = 0) {
            console.log(CKEDITOR.instances.editor1.getData());
            if (CKEDITOR.instances.editor1.getData() != '') text = CKEDITOR.instances.editor1.getData();
        }


        text.replace(/\r\n|\r|\n/g, "<br />");
        console.log(text);
        $.post($(this).attr('action'), {bontext: text}, function (data) {
            // console.log(data);
            // $('#printPage').empty().append(data);
            w = window.open();
            w.document.write(data);


            w.print(false);
            w.close();

        });
    });


// Rechungsdialog
    $('.orderdialog').dialog({
        autoOpen: false,
        minWidth: 400 });
    $(".order").click(function () {
        $(".orderdialog").dialog("open");
    });

// Bezahl Dialog
    $('.toPaydialog').dialog({
        autoOpen: false,
        modal: true,
        minWidth: 600,
        minHeight: 400});
    $(".toPay").click(function () {
        $(".toPaydialog").dialog("open");
    });


    // Standart Artikel
    $('.input_buttons div').click(function () {
        var code = $(this).data('code');
        var input = $('.inputkeyboard');
        var id = input.data('cashbox');
        var checkout = input.data('checkout');
        var quantity = $('.inputkeyboard').val();
        if (quantity.length > 0) {
            itemFocus = '.itemprice:last';
            //$.post('/cashbox/'+id+'/checkout/'+checkout+'/add',{ code: code,quantity: quantity},buildtable);
            addQuery('/cashbox/' + id + '/checkout/' + checkout + '/add', { name: $(this).html(),code: code, quantity: quantity});
        }


    });


    $(document).keypress(function (event) {
        // console.log(event.keyCode);
        var id = $('#inputkeyboard').data('cashbox');
        var id_checkout = $('#inputkeyboard').data('checkout');
        if (event.keyCode > 112 && event.keyCode < 122) {
            event.preventDefault();
            // Funtkionstasten
            var item = $('.checkout-box[data-keyboard=' + event.keyCode + ']');

            console.log(item);
            var code = item.data('code');

            $('.checkout-box[data-keyboard=' + event.keyCode + ']').animate({
                backgroundColor: '#ddC8dd'}, 100).animate({
                    backgroundColor: '#00c800'}, 800);
            ;
            var quantity = $('.inputkeyboard').val();
            if (quantity.length == 0) quantity = 1;
            itemFocus = '.itemprice:last';
            //$.post('/cashbox/'+id+'/checkout/'+id_checkout+'/add',{ code: code,quantity: quantity},buildtable);
            addQuery('/cashbox/' + id + '/checkout/' + id_checkout + '/add', { name: item.html(), code: code, quantity: quantity});

        }
        if (event.keyCode == 13) {
            // Bei ENTER
            event.preventDefault();
            var button = $('.input_buttons div:first');
            var quantity = $('.inputkeyboard').val();
            if (quantity.length == 0) quantity = 1;
            itemFocus = '.itemprice:last';
            // $.post('/cashbox/'+id+'/checkout/'+id_checkout+'/add',{ code: button.data('code'),quantity: quantity},buildtable);
            addQuery('/cashbox/' + id + '/checkout/' + id_checkout + '/add', { name: button.html(), code: button.data('code'), quantity: quantity});


        }

    }).focus();


    // Eingabefeld Tasten aktionen
    $('#inputkeyboard').keypress(function (event) {
        //console.log(event.keyCode);
        var id = $(this).data('cashbox');
        var id_checkout = $('#inputkeyboard').data('checkout');
        /* if(event.keyCode > 112 && event.keyCode < 120){
         event.preventDefault();
         // Funtkionstasten

         var code = $('.checkout-box[data-keyboard='+event.keyCode +']').data('code');
         $('.checkout-box[data-keyboard='+event.keyCode +']').animate({
         backgroundColor: '#ddC8dd'}, 100).animate({
         backgroundColor: '#00c800'}, 800);
         ;
         var quantity = $('.inputkeyboard').val();
         if(quantity.length > 0){
         itemFocus = '.itemprice:last';
         $.post('/cashbox/'+id+'/checkout/add',{ code: code,quantity: quantity},buildtable);
         }

         return false;
         }*/


        if (event.keyCode == 9) {
            // Bei TAB
            var code = $(this).val();
            var input = $(this);
            itemFocus = '.inputkeyboard';
            addQuery('/cashbox/' + id + '/checkout/' + id_checkout + '/add', { code: code, quantity: 1});

            //$.post('/cashbox/'+id+'/checkout/'+id_checkout+'/add',{ code: code},buildtable);
            return false;
        }
        /*
         if( event.keyCode == 13 ){

         // Bei ENTER
         var button = $('.input_buttons div:first');
         var quantity = $('.inputkeyboard').val();
         if(quantity.length == 0) quantity = 1;
         itemFocus = '.itemprice:last';
         $.post('/cashbox/'+id+'/checkout/'+id_checkout+'/add',{ code: button.data('code'),quantity: quantity},buildtable);

         return false;
         }

         */
    }).focus();

    // Abrechnung
    $('input.co_payed').keypress(function (event) {


        if (event.charCode > 47 && event.charCode < 58 || event.charCode == 46 || event.charCode == 44 || event.keyCode == 8) {


            return true;
        }
        if (event.keyCode == 13) {

            var getback = parseFloat($(this).val().replace(',', '.')) - parseFloat($('.co_toPay').html());
            $(".co_return").html(getback.toFixed(2) + ' &euro;')
            //$(".return_focus").focus();
            return true;
        }


        return false;

    });

    // Artikel liste Bearbeiten


    $("#form_lastname").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/customer/search/" + request.term,
                dataType: "json",

                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.FirstName + ' ' + item.Surname + ', ' + item.Street + ' ' + item.HouseNo + ', ' + item.ZIP + ' ' + item.City,
                            value: item.Surname,
                            data: item
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            console.log(ui);
            var data = ui.item.data;
            $('#form_customerno ').val(data.CustomerID);
            $('#form_lastname ').val(data.Surname);
            $('#form_firstname ').val(data.FirstName);
            $('#form_street ').val(data.Street);
            $('#form_HouseNo ').val(data.HouseNo);
            $('#form_company ').val(data.Surname);
            $('#form_zip ').val(data.ZIP);
            $('#form_city ').val(data.City);
            $('#form_country ').val(data.Country);
            $('#form_email ').val(data.Email);

            return false;
        }

    });
    getSummary();


};
