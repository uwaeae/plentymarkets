
var buildtable = function buildTable(data){
    //$('.shopinglist').empty().html($(data).find('.shopinglist table'));
    $('.co_items').empty();
    var index = 1;
    $.each(data, function(key, val) {

        var items = [];
        items.push('<td>' + index + '</td>');
        items.push('<td>' + val['code'] + '</td>');
        items.push('<td>' + val['quantity'] + '' +
            '<div class="itemEdit">'+
            '<a href="#" data-action="minus" data-id="'+ val['id'] +'">' +
            '<img src="/images/icons/arrow-down.png" alt="- Menge" /></a>'+
            '<a href="#" data-action="plus" data-id="'+ val['id'] +'">'+
            ' <img src="/images/icons/arrow-up.png" alt="+ Menge" /></a></div></td>');
        items.push('<td>' + val['description'] + '</td>');
        items.push('<td>' + parseFloat(val['price']).toFixed(2) + ' &euro;</td>');
       // items.push('<td>' + val['pa'] + '</td>');
        items.push('<td>' + parseFloat(val['sum']).toFixed(2) + ' &euro;' +
            '<div class="itemEdit">'+
            '<a href="#" data-action="delete" data-id="'+ val['id'] +'">' +
            '<img src="/images/icons/remove2.png" alt="Löschen" /></a>'+
            '</div></td>');

        $('<tr>',{
            'id': key,
            'data-sum':val['sum'],
            html: items.join('')

        }).appendTo('.co_items');
        index ++;
    });
    getSummary();
    $('.inputkeyboard').val('').focus();
};
$(document).ready(function(){
    $(".btnPrint").printPage();
    // Dialog Wiget initalisieren

    $('.orderdialog').dialog({
        autoOpen: false,
        minWidth: 400 });
    $( ".order" ).click(function() {
        $( ".orderdialog" ).dialog( "open" );
    });


    $('.toPaydialog').dialog({
        autoOpen: false,
        modal: true,
        minWidth: 400 });
    $( ".toPay" ).click(function() {
        $( ".toPaydialog" ).dialog( "open" );
    });

    // Artikel Tabelle aus JSON daten Bauen


    // Standart Artikel
    $('.input_buttons div').click(function(){
        var code = $(this).data('code');
        var input =  $('.inputkeyboard');
        var id =  input.data('cashbox');
        var price = input.val();
        if(price.length > 0)
        $.post('/cashbox/'+id+'/checkout/add',{ code: code,price: price},buildtable);


    });

    // Eingabefeld Tasten aktionen
   $('.inputkeyboard').keypress(function(event){
        console.log(event.keyCode);
        var id = $(this).data('cashbox');
       if(event.keyCode > 112 && event.keyCode < 120){
           var items = $('.co_items tr td');
           var code = items[event.keyCode - 112 ].data('code');
           var price = $('.inputkeyboard').val();
           if(price.length > 0)
               $.post('/cashbox/'+id+'/checkout/add',{ code: code,price: price},buildtable);
           return false;
       }

       if(event.keyCode == 9 || event.keyCode == 13 ){
           var code =  $(this).val()
           var input = $(this);

           $.post('/cashbox/'+id+'/checkout/add',{ code: code},buildtable);
            return false;
           }

       }).focus();

    // Abrechnung
   $('input.co_payed').keypress(function(event){


     if(event.charCode > 47 && event.charCode < 58 || event.charCode == 46|| event.charCode == 44 || event.keyCode == 8 ){



         return true;
     }
     if(event.keyCode == 13  ){

         var getback =   parseFloat($(this).val().replace(',','.')) - parseFloat($('.co_toPay').html());
         $(".co_return").html(getback.toFixed(2) + ' &euro;')
         return true;
     }



        return false;

   });

    // Artikel liste Bearbeiten


    $( "#form_lastname" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/customer/search/"+request.term,
                dataType: "json",

                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.FirstName + ' ' + item.Surname+', '+item.Street +' '+item.HouseNo +', '+item.ZIP +' '+item.City,
                            value: item.Surname,
                            data: item
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: function( event, ui ) {
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
});




function getSummary(){

    var sum = 0;
    $('.shopinglist table tbody tr').each(function(){
        sum += $(this).data("sum");
        //console.log($(this).data("sum"));
    });

    $('.co_toPay').html(sum.toFixed(2) + '&euro;');
    $('.itemEdit a').click(function(){
        ;
        $.post('/cashbox/'+$('.inputkeyboard').data('cashbox')+'/checkout/itemaction',{ id: $(this).data('id'),action:$(this).data('action')},buildtable);
    });
}