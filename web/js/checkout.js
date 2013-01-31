$(document).ready(function(){

    $('.orderdialog').dialog({
        autoOpen: false,
        minWidth: 400 });
    $( ".order" ).click(function() {
        $( ".orderdialog" ).dialog( "open" );
    });



   $('.inputkeyboard').keypress(function(event){

       if(event.keyCode == 9 || event.keyCode == 13 ){
           var code =  $(this).val()
           var input = $(this);

           $.getJSON('/checkout/add/'+ code,function(data){
                //$('.shopinglist').empty().html($(data).find('.shopinglist table'));
               $('.co_items').empty();

               $.each(data, function(key, val) {

                   var items = [];

                   items.push('<td>' + val['code'] + '</td>');
                   items.push('<td>' + val['quantity'] + '</td>');
                   items.push('<td>' + val['description'] + '</td>');
                   items.push('<td>' + parseFloat(val['price']).toFixed(2) + ' &euro;</td>');
                   items.push('<td>' + val['pa'] + '</td>');
                   items.push('<td>' + parseFloat(val['sum']).toFixed(2) + ' &euro;</td>');

                   $('<tr>',{
                        'id': key,
                       'data-sum':val['sum'],
                       html: items.join('')

                   }).appendTo('.co_items');

                  });

               });



               getSummary();
           }
       });

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