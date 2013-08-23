$(document).ready(function(){
    $( "#datepicker" ).datepicker({
        onSelect: function(date) {
            //console.log(date);
            var URL=$('#datepicker').data('url');
            URL = URL.replace("now",date);
            $('#result').empty().load(URL+' #result',function(){
                init();
            });
            var URL2=$('#report').attr('href');
            URL2 = URL2.split('/');
            URL2.pop();
            URL2.push(date);
            $('#report').attr('href',URL2.join('/'));

        },
        dateFormat: "dd-mm-yy"
    });
    init();



});

function init(){
    //$('.article_items').hide();

    $('.article_button').click(function(){
        $('.article_button').show();
        $(this).hide();
        $('.article_items').slideUp();
        $('.selected').removeClass('selected');
        $(this).parents("tr").addClass('selected');
        $(this).parents("tr").find('.article_items').slideToggle()

    });
    $('.bon').click(function(){
        $.post($(this).attr( 'href' ),function(data) {

            w=window.open();
            w.document.write(data);
            w.print();
            w.close();
        });
    })


}