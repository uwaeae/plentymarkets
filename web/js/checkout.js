$(document).ready(function(){

    $('.orderdialog').dialog({
        autoOpen: false,
        minWidth: 400 });
    $( ".order" ).click(function() {
        $( ".orderdialog" ).dialog( "open" );
    });



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
                    '<a href="#" class="itemMinus" data-id="'+ val['id'] +'">' +
                    '<img src="/images/icons/arrow-down.png" alt="- Menge" /></a>'+
                    '<a href="#" class="itemPlus" data-id="'+ val['id'] +'">'+
                    ' <img src="/images/icons/arrow-up.png" alt="+ Menge" /></a></div></td>');
                items.push('<td>' + val['description'] + '</td>');
                items.push('<td>' + parseFloat(val['price']).toFixed(2) + ' &euro;</td>');
                items.push('<td>' + val['pa'] + '</td>');
                items.push('<td>' + parseFloat(val['sum']).toFixed(2) + ' &euro;' +
                    '<div class="itemEdit">'+
                    '<a href="#" class="itemMinus" data-id="'+ val['id'] +'">' +
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


    $('.input_buttons div').click(function(){
        var code = $(this).data('code');


        var price = $('.inputkeyboard').val();
        if(price.length > 0)
        $.post('/checkout/add',{ code: code,price: price},buildtable);


    });


   $('.inputkeyboard').keypress(function(event){
        console.log(event.keyCode);

       if(event.keyCode > 112 && event.keyCode < 120){
           var items = $('.co_items tr td');
           var code = items[event.keyCode - 112 ].data('code');
           var price = $('.inputkeyboard').val();
           if(price.length > 0)
               $.post('/checkout/add',{ code: code,price: price},buildtable);
           return false;
       }

       if(event.keyCode == 9 || event.keyCode == 13 ){
           var code =  $(this).val()
           var input = $(this);

           $.post('/checkout/add',{ code: code},buildtable);
            return false;
           }

       }).focus();

   $('.co_payed').keypress(function(event){


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

   getSummary();
});



function getSummary(){

    var sum = 0;
    $('.shopinglist table tbody tr').each(function(){
        sum += $(this).data("sum");
        //console.log($(this).data("sum"));
    });

    $('.co_toPay').html(sum.toFixed(2) + '&euro;');
}