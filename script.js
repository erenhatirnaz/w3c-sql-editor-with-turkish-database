/**
 * Created by Eren Hatırnaz on 12.12.2014.
 */

function getAllDatas(tableName) {
    tableName = tableName.replace('tbl', '');

    $("#txtSqlQuery").val("SELECT * FROM " + tableName);
    $("#btnRun").click();
}

function parseColumnNames(data) {
    var columnNames = [];

    for (var columnName in data) {
        columnNames.push(columnName);
    }

    return columnNames;
}
$(function () {
    $("#txtSqlQuery").autosize();

    $.getJSON("get-tables.php", function (tables) {
        for (var table in tables) {
            var tableHtmlData = "<li class='list-group-item'>" +
                "<span class='badge'>" + tables[table] + "</span>" +
                "<a href='#' onclick='getAllDatas(this.id)' id='tbl" + table + "'>" + table + "</a>" +
                "</li>";

            $("#tables").append(tableHtmlData);
        }
    });

    $("#btnRun").click(function () {
        var queryData = {queryString: $("#txtSqlQuery").val()};

        $.ajax({
            type: "POST",
            url: "query-executer.php",
            data: queryData,
            dataType: "json"
        }).done(function (response) {
            var datas = response,
                columnNames = parseColumnNames(datas[0]);

            var result = "<h4>Çalıştırdığınız Sorgu</h4>" +
                "<pre><code class='sql'>" + $("#txtSqlQuery").val() + "</code></pre>" +
                "<br/><h3>Sonuç</h3>" +
                "<strong>Bu tabloda <font color='red'>"+datas.length+"</font> adet kayıt bulunmaktadır.</strong>";

            if(typeof datas[0] === 'undefined') {
                result += "<strong>Bu tabloda hiç veri bulunmamaktadır!</strong>";
            } else {
                result += "<table class='table table-striped'>" +
                "<thead>" +
                "<tr>";

                for (var columnName in columnNames) {
                    result += "<td><strong>" + columnNames[columnName] + "</strong></td>";
                }

                result += "</tr>" +
                "</thead>" +
                "<tbody>";

                for (var row in datas) {
                    result += "<tr>";

                    for (var column in datas[row]) {
                        result += "<td>" + datas[row][column] + "</td>";
                    }

                    result += "</tr>";
                }

                result += "</tbody>" +
                "</table>";
            }

            $('.panel-body').html(result);

            $('pre code').each(function (i, block) {
                hljs.highlightBlock(block);
            });
        });
    });
});