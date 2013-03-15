$(document).ready(function(){
    $('.edit_quickbutton').click(function(){
        var url;
        url = $(this).attr('href');
        $.get(url, function(data) {
            $('.edit_area').empty().html(data);

        });
       return false;
    });
});