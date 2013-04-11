$(document).ready(function(){
    $( "#datepicker" ).datepicker({
        onSelect: function(date) {
            //console.log(date);
            var URL=$('#datepicker').data('url');
            URL = URL.replace("now",date);
            $('#result').empty().load(URL+' #result');
            var URL2=$('#report').attr('href');
            URL2 = URL2.split('/');
            URL2.pop();
            URL2.push(date);
            $('#report').attr('href',URL2.join('/'));
        },
        dateFormat: "dd-mm-yy"
    });
});