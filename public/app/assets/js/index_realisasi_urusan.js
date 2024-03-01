$(function () {
    "use strict";

    var chartContainer = document.getElementById("chart4");
    var homeRoute = chartContainer.getAttribute("data-url");

    Highcharts.setOptions({
        lang: {
            numericSymbols: [ ' ribu' , ' Juta' , ' Milyar' , ' Triliun' , ' P' , 'E']
        }
    });

    Highcharts.chart("chart4", {
        chart: {
            type: "column",    
        },
        title: {
            text: "Perbandingan Realisasi - Anggaran",
        },
        xAxis: {
            categories: category,
            crosshair: true,
        },
        yAxis: {
            min: 0,
            title: {
                text: "Anggaran (Rp)",
            },
        },
        tooltip: {
            headerFormat:
                '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat:
                '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: "</table>",
            shared: true,
            useHTML: true,
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
            },
            series: {
                cursor: "pointer",
                events: {
                    click: function (event) {
                        var code = this.data[event.point.index].code;
                        var level = this.data[event.point.index].toLevel;
                        var skpd = this.data[event.point.index].skpd;
                        var tahun = this.data[event.point.index].tahun;
                        var tahapan_id = this.data[event.point.index].tahapan_id;
                        window.location.href =
                            homeRoute + "?tahun="+tahun+"&tahapan_id="+tahapan_id+"&skpd="+skpd+"&level=" + level + "&code=" + code;
                    },
                },
                // borderRadius: 5,
                dataLabels: {
                    enabled: true,
                    format: "{point.y}",
                },
            },
        },
        series: [
            {
                name: "Anggaran",
                data: perbandinganData2
            },
            {
                name: "Realisasi",
                data: perbandinganData
            },
        ],
    });
});
