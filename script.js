/**
 * Created by Eren on 12.12.2014.
 */

$(function () {
    $("#txtSqlQuery").autosize();

    $.post("get-tables.php", function(r){
        r = JSON.parse(r);

        for (var item in r) {
            $("#tables").append("<li class='list-group-item'><span class='badge'>" + r[item] + "</span><a href='#' id='tbl"+item+"'>"+ item +"</a></li>");
        }

        $("#tables li a").click(function(){
            var tableName = this.id.replace('tbl','');

            $("#txtSqlQuery").val("SELECT * FROM "+tableName);
            $("#btnRun").click();
        });
    });

    $("#btnRun").click(function(){
        $.post("query-executer.php",{queryString: $("#txtSqlQuery").val()}, function(resp){
            try
            {
                resp = JSON.parse(resp);
                console.log(resp);

                $("#result").removeClass("panel-default").removeClass("panel-danger").addClass("panel-success");

                var result = "<h4>Çalıştırdığınız Sorgu</h4>" +
                    "<pre><code class='sql'>"+$("#txtSqlQuery").val()+"</code></pre>" +
                    "<br/><h3>Sonuç</h3>" +
                    "<table class='table table-striped'>" +
                    "<thead>" +
                    "<tr>";
                for(var columnName in resp.columnNames){
                    result += "<td><strong>"+resp.columnNames[columnName]+"</strong></td>";
                }
                result += "</tr>" +
                "</thead>" +
                "<tbody>";

                for(var data in resp.datas) {
                    result += "<tr>";

                    for(var column in resp.datas[data]) {
                        result += "<td>"+resp.datas[data][column]+"</td>";
                    }

                    result += "</tr>";
                }

                result += "</tbody>" +
                "</table>";

                $(".panel-body").html(result);

                $('pre code').each(function(i, block) {
                    hljs.highlightBlock(block);
                });
            } catch (err){
                $("#result").removeClass("panel-default").addClass("panel-danger");
                $(".panel-body").html("<strong>Bir hata ile karşılaşıldı!</strong>");
            }
        });

        $("#txtSqlQuery").autosize();
    });
});