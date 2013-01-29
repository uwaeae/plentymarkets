$(document).ready(function(){
   $('.inputkeyboard').keypress(function(event){

       if(event.keyCode == 9 || event.keyCode == 13 ){
           var code =  $(this).val()
           var input = $(this);

           $.getJSON('/app_dev.php/checkout/add/'+ code,function(data){
                //$('.shopinglist').empty().html($(data).find('.shopinglist table'));
               $('.co_items').empty();
               var items = [];
               $.each(data, function(key, val) {
                   items.push('<tr id="' + key + '">' + val + '</li>');
               });



               getSummary();
           });
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