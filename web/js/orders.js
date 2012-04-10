
function selectAll(){

            $("input:checkbox")
                .attr("checked","checked");
    }
function unselectAll(){

    $("input:checkbox")
        .attr("checked","")
        .val("0");
}
