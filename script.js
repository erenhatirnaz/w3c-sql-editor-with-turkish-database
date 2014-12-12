/**
 * Created by Eren on 12.12.2014.
 */

$(function () {
    $.post("get-tables.php", function(r){
        r = JSON.parse(r);

        for (var item in r) {
            $("#tables").append("<li class='list-group-item'><span class='badge'>" + r[item] + "</span><a href='#' id='tbl"+item+"'>"+ item +"</a></li>");
        }

        $("#tables li a").click(function(){
            var tableName = this.id.replace('tbl','');

            $("#txtSqlQuery").val("SELECT * FROM "+tableName);
        });
    });
});