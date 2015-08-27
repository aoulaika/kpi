function showRadar () {
    $('#chart-container').highcharts({
        chart: {
            polar: true,
            type: 'line'
        },
        title: {
            text: '',
            x: -80
        },
        pane: {
            size: '75%'
        },
        xAxis: {
            categories: ['FCR', 'FCR Resolvable', 'FCR Resolvable Missed', 'EKMS Usage', 'CI Usage'],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },
        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            min: 0
        },
        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
        },
        series: [{
            name: 'Average',
            data: [43000, 19000, 60000, 35000, 17000],
            pointPlacement: 'on'
        }, {
            name: 'Agent',
            data: [50000, 39000, 42000, 31000, 26000],
            pointPlacement: 'on'
        }]
    });
}