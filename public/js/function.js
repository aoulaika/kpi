
function c_avg (tab) {
    var sum=0;
    for (var i = 0; i < tab.length; i++) {
        sum+=parseInt(tab[i].count);
    }
    return sum/tab.length;
}
function c_max (tab) {
    var max=parseInt(tab[0].count);
    for (var i = 1; i < tab.length; i++) {
        if(max<parseInt(tab[i].count)){
            max=parseInt(tab[i].count);
        }
    }
    return max;
}
function c_min (tab) {
    var min=parseInt(tab[0].count);
    for (var i = 1; i < tab.length; i++) {
        if(min>parseInt(tab[i].count)){
            min=parseInt(tab[i].count);
        }
    }
    return min;
}

/* Bar chart (Max Min Avg) function */
function bar(id,suffix,label,name,value,max,avg,min) {
    var val=parseInt(value)
    var mx=parseInt(max);
    var av=parseInt(avg);
    var mn=parseInt(min);
    $(id).highcharts({
        chart:{
            type:'bar'
        },

        exporting: { enabled: false },

        tooltip: {
            valueSuffix: suffix
        },

        title: {
            text: ' '
        },

        credits: {
            enabled: false
        },

        xAxis: {
            categories: [name,'','Maximum','Average','Minimum']
        },
        series: [{
            name: label,
            showInLegend: false,
            data: [
            (val<70)?{y:val,color:'red'}:val,
            0,
            {y:mx,color:'#6DD187'},
            {y:av,color:'#86F0A2'},
            {y:mn,color:'#A3FFBC'}
            ]
        }]
    });
}

function doit(agent_name,agent_nbr,ci,kb,fcr,fcr_reso){
    if (ci<50) {
        $("#ci").css('color','red');
    }else{
        $("#ci").css('color','');
    }
    if (kb<50) {
        $("#kb").css('color','red');
    }else{
        $("#kb").css('color','');
    }
    if (fcr<50) {
        $("#fcr").css('color','red');
    }else{
        $("#fcr").css('color','');
    }
    if (fcr_reso<50) {
        $("#fcr_reso").css('color','red');
    }else{
        $("#fcr_reso").css('color','');
    }
    $('#agent_name').html(agent_name);
    $('#ci').html('<span class="count">'+ci+'</span>%');
    $('#kb').html('<span class="count">'+kb+'</span>%');
    $('#fcr').html('<span class="count">'+fcr+'</span>%');
    $('#fcr_reso').html('<span class="count">'+fcr_reso+'</span>%');
    $('#nbr').html(agent_nbr);
    $('.count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
}

function reloadSelect (d,id) {
    var str='<option value="all">All</option>';
    for (var property in d) {
        if (d.hasOwnProperty(property)) {
            str+='<option value='+property+'>'+property+'</option>';
        }
    }
    $(id).html(str);
}

function gauge(id,mi,mx,title,tht,tht_time){
    var gaugeOptions = {
        chart: {
            type: 'solidgauge'
        },
        title: null,
        pane: {
            center: ['50%', '85%'],
            size: '100%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },
        tooltip: {
            enabled: false
        },
            // the value axis
            yAxis: {
                stops: [
                    [0.1, '#A0D9FF'], // blue
                    [0.5, '#7CB5EC'], // yellow
                    [0.9, '#346DA4'] // red
                    ],
                    lineWidth: 0,
                    minorTickInterval: null,
                    tickPixelInterval: 400,
                    tickWidth: 0,
                    title: {
                        y: -70
                    },
                    labels: {
                        y: 16
                    }
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            y: 5,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }
                }
            };
            var gData={
                yAxis: {
                    min: mi,
                    max: mx,
                    title: {
                        text: title
                    }
                },
                exporting: { enabled: false },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Speed',
                    data: tht,
                    dataLabels: {
                        format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                        ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '"></span><br/>' +
                        '<span style="font-size:14px;color:blue;font-family:Open Sans">'+tht_time+'</span></div>'
                    }
                }]
            };
        // The speed gauge
        $(id).highcharts(Highcharts.merge(gaugeOptions, gData));
    }

    function drawBar(id,name,graphName,suffix,data){
        $(id).highcharts({
            chart:{
                type:'bar'
            },
            exporting: { enabled: false },
            tooltip: {
                valueSuffix: suffix
            },
            title: {
                text: ' '
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: name
            },
            series: [{
                name: graphName,
                showInLegend: false,
                data: data
            }]
        });
    }

    function draw(data) {
        var ticketsData1 = [];
        for (var i = 0; i < data.length; i++) {
            ticketsData1.push({
                date: new Date(data[i].CreatedYear, data[i].CreatedMonth - 1, data[i].CreatedDay, data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
                visits: data[i].count
            });
        };

        var chart2 = AmCharts.makeChart("ticketsChart2", {
            "type": "serial",
            "theme": "light",
            "marginRight": 80,
            "autoMarginOffset": 20,
            "marginTop": 7,
            "dataProvider": ticketsData1,
            "valueAxes": [{
                "axisAlpha": 0.2,
                "dashLength": 1,
                "position": "left"
            }],
            "mouseWheelZoomEnabled": true,
            "graphs": [{
                "id": "ticketsChart2",
                "balloonText": "[[category]]<br/><b><span style='font-size:14px;'>value: [[value]]</span></b>",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "hideBulletsCount": 50,
                "title": "red line",
                "valueField": "visits",
                "useLineColorForBulletBorder": true
            }],
            "chartScrollbar": {
                "autoGridCount": true,
                "graph": "ticketsChart2",
                "scrollbarHeight": 40
            },
            "chartCursor": {
                "categoryBalloonDateFormat": "JJ h, DD MMMM",
                "cursorPosition": "mouse"
            },
            "categoryField": "date",
            "categoryAxis": {
                "parseDates": true,
                "minPeriod": "mm",
                "axisColor": "#DADADA",
                "dashLength": 1,
                "minorGridEnabled": true
            },
            "export": {
                "enabled": true
            }
        });
chart2.pathToImages = '/kpi/public/img/';
chart2.addListener("rendered", zoomChart);
zoomChart();

        // this method is called when chart is first inited as we listen for "dataUpdated" event
        function zoomChart() {
            // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
            chart2.zoomToIndexes(ticketsData1.length - 40, ticketsData1.length - 1);
        }
    }