$(document).ready(function(){
    $('.edit_quickbutton').click(function(){
        var url;
        url = $(this).attr('href');
        $.get(url, function(data) {
            $('.edit_area').empty().html(data);
            $('#acme_bscheckoutbundle_quickbuttontype_quickkey').keypress(function(event){
                $(this).val(event.keyCode);
                return false;
            })
        });
       return false;
    });

    $('#acme_bscheckoutbundle_quickbuttontype_quickkey').keypress(function(event){
        $(this).val(event.keyCode);
        return false;
    });

});

config.toolbar_Basic =
    [
        ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
    ];