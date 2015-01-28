/**
 * Created by ErenHatirnaz on 12.12.2014.
 */

function getAllDatas(tableName) {
    tableName = tableName.replace('tbl', '');

    $("#txtSqlQuery").val("SELECT * FROM " + tableName);
    $("#btnRun").click();
}

function parseColumnNames(data) {
    var columnNames = [];

    for (var columnName in data) {
        if (data.hasOwnProperty(columnName)) {
            columnNames.push(columnName);
        }
    }

    return columnNames;
}

$(function () {
    $("#btnCreateDatabase").click(function () {
        $('#databaseNotFoundModal').modal('hide');
        $("#runRandomDatabaseGenerator").click();
    });

    $("#runRandomDatabaseGenerator").click(function () {
        $("#randomDatabaseGeneratorModal").modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        $('#page').attr('src', './tools/generator.php')
            .load(function () {
                $('[role=progressbar]').addClass('progress-bar-success').removeClass('progress-bar-striped').removeClass('active');
                $('#btnSuccessfully').removeAttr('disabled');
            });
    });

    $("#txtSqlQuery").autosize()
        .bind('keydown', function (e) {
            if (e.ctrlKey && e.keyCode === 13) {
                $("#btnRun").click();
            }
        });

    $.getJSON("get-tables.php", function (tables) {
        for (var table in tables) {
            if(tables.hasOwnProperty(table)) {
                var tableHtmlData = "<li class='list-group-item'>" +
                    "<span class='badge'>" + tables[table] + "</span>" +
                    "<a href='#' onclick='getAllDatas(this.id)' id='tbl" + table + "'>" + table + "</a>" +
                    "</li>";
                $("#tables").append(tableHtmlData);
            }
        }
    }).fail(function () {
        $("#databaseNotFoundModal").modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
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

            $("#result").removeClass("panel-default").removeClass("panel-danger").addClass("panel-success");

            var result = "<h4>Çalıştırdığınız Sorgu</h4>" +
                "<pre><code class='sql'>" + $("#txtSqlQuery").val() + "</code></pre>" +
                "<br/><h3>Sonuç</h3>";

            if (typeof datas[0] === 'undefined') {
                result += "<strong>Bu tabloda hiç veri bulunmamaktadır!</strong>";
            } else {
                result += "<strong>Bu tabloda <span style='color: red; '>" + datas.length + "</span> adet kayıt bulunmaktadır.</strong>" +
                "<table class='table table-striped'>" +
                "<thead>" +
                "<tr>";

                for (var columnName in columnNames) {
                    if(columnNames.hasOwnProperty(columnName)) {
                        result += "<td><strong>" + columnNames[columnName] + "</strong></td>";
                    }
                }

                result += "</tr>" +
                "</thead>" +
                "<tbody>";

                for (var row in datas) {
                    result += "<tr>";

                    if(datas.hasOwnProperty(row)) {
                        for (var column in datas[row]) {
                            if(datas[row].hasOwnProperty(column)) {
                                result += "<td>" + datas[row][column] + "</td>";
                            }
                        }
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
        }).fail(function (a) {
            var errorMessage = JSON.parse(a.responseText);

            $("#result").removeClass("panel-default").addClass("panel-danger");
            $(".panel-body").html("Bir hata ile karşılaşıldı: <strong>" + errorMessage["error"] + "</strong>");
        });
    });
});