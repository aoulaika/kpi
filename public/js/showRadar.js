function showRadar (data) {
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
            size: '90%'
        },
        xAxis: {
            categories: ['FCR', 'FCR Resolvable', 'EKMS Usage', 'CI Usage'],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },
        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            max: 100
        },
        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>{series.name}'
        },
        series: data
    });
}