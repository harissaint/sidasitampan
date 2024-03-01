$(function () {
    "use strict";

    var chartContainer2 = document.getElementById("chart2");
    var homeRoute2 = chartContainer2.getAttribute("data-url");

    // chart 2
    var urutan = ["Urusan", "Program", "Kegiatan", "Sub Kegiatan"];
    // Create the chart
    Highcharts.chart("chart2", {
        chart: {
            type: "pie",
            events: {
                load: function () {
                    const series = this.series[0];
                    const totalY = series.points.reduce(
                        (sum, point) => sum + point.y,
                        0
                    );

                    series.points.forEach((point) => {
                        const percentage = (point.y / totalY) * 100;
                        point.update({
                            dataLabels: {
                                format: `{point.name}: ${percentage.toFixed(
                                    2
                                )}%`,
                            },
                        });
                    });
                },
            },
        },
        title: {
            text: "Perbandingan Anggaran",
            align: "left",
        },
        accessibility: {
            announceNewData: {
                enabled: true,
            },
            point: {
                valueSuffix: "%",
            },
        },
        plotOptions: {
            series: {
                cursor: "pointer",
                events: {
                    click: function (event) {
                        var code = this.data[event.point.index].code;
                        var level = this.data[event.point.index].toLevel;
                        var skpd = this.data[event.point.index].skpd;
                        var tahap = this.data[event.point.index].tahap;
                        window.location.href =
                            homeRoute2 + "?tahap="+tahap+"&skpd="+skpd+"&level=" + level + "&code=" + code;
                    },
                },
                borderRadius: 5,
                dataLabels: {
                    enabled: true,
                    format: "{point.name}: {point.percentage:.2f}% of Total", // Menggunakan token percentage
                },
            },
        },
        tooltip: {
            headerFormat:
                '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat:
                '<span style="color:{point.color}">{point.name}</span>: <b>Rp.{point.y:f}</b> of total<br/>',
        },
        series: [
            {
                name: "Anggaran Urusan",
                colorByPoint: true,
                data: urusanData,
            },
        ],
    });
});
