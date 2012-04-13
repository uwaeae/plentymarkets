


function toggleedit(id,url) {

    if( $('div .plantshow'.id).empty() )
        $('div .plantshow'.id).load(url);
    else
        $('div .plantshow'.id).hide();
}