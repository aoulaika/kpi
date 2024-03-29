
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
            plotOptions: {
                series: {
                    pointWidth: 15
                }
            },
            series: [{
                name: graphName,
                showInLegend: false,
                data: data
            }]
        });
    }

    function draw(data) {
        /*var ticketsData1 = [];
        for (var i = 0; i < data.length; i++) {
            ticketsData1.push({
                date: new Date(data[i].CreatedYear, data[i].CreatedMonth - 1, data[i].CreatedDay, data[i].CreatedHour, data[i].CreatedMinute, data[i].CreatedSecond, 0),
                visits: data[i].count
            });
};*/
var ticketsData1 = [];
var deb = new Date($('#datedeb').html());
deb.setHours(0, 0, 0);
var fin = new Date($('#datefin').html());
fin.setHours(0, 0, 0);
var i = 0;
while (deb <= fin) {
    try {
        current = new Date(data[i].CreatedYear, data[i].CreatedMonth - 1, data[i].CreatedDay, data[i].CreatedHour, 0);
    }
    catch (err) {
        current = new Date(0);
    }
    ticketsData1.push({
        date: new Date(deb.getTime()),
        visits: (current.getTime() == deb.getTime()) ? data[i].count : 0
    });
    if (current.getTime() == deb.getTime())
        i++;
    deb.setHours(deb.getHours() + 1);
}
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
}

function csiTrack(id, data) {
    var csiData = [];
    for (var i = 0; i < data.length; i++) {
        csiData.push({
            date: new Date(data[i].date),
            visits: parseFloat(data[i].value)
        });
    };
    var chart = AmCharts.makeChart(id, {
        "type": "serial",
        "theme": "light",
        "marginRight": 40,
        "autoMarginOffset": 20,
        "marginTop": 1,
        "dataProvider": csiData,
        "valueAxes": [{
            "axisAlpha": 0.2,
            "dashLength": 2,
            "position": "left",
            "minimum": 0,
            "guides": [{
                "dashLength": 2,
                "inside": true,
                "label": "average",
                "lineAlpha": 2,
                "value": 4.25
            }]
        }],
        "mouseWheelZoomEnabled": true,
        "graphs": [{
            "id": "csiTrack",
            "balloonText": "[[category]]<br/><b><span style='font-size:14px;'>value: [[value]]</span></b>",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 3,
            "hideBulletsCount": 50,
            "title": "red line",
            "bulletBorderColor": "#FFFFFF",
            "hideBulletsCount": 50,
            "valueField": "visits",
            "lineThickness": 2,
            "useLineColorForBulletBorder": true
        }],
        "chartScrollbar": {
            "autoGridCount": true,
            "graph": "csiTrack",
            "oppositeAxis":true,
            "graphFillAlpha": 0,
            "graphLineAlpha": 0.5,
            "selectedGraphFillAlpha": 0,
            "selectedGraphLineAlpha": 1,
            "autoGridCount":true,
            "scrollbarHeight": 20
        },
        "chartCursor": {
            "categoryBalloonDateFormat": "YYYY-MM-DD",
            "cursorPosition": "mouse",
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true
        },
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true,
            "axisAlpha": 0,
            "minHorizontalGap": 60
        },
        "export": {
            "enabled": true
        }
    });

chart.pathToImages = '/kpi/public/img/';

}

function reloadTop (data) {
    var str='';
    for (var key in data) {
       if (data.hasOwnProperty(key)) {
        str+="<li class='item'>";
        str+="<div class='row'>";
            str+="<div class='col-lg-1'>";
                str+="<span class='rank'>"+data[key].number+"</span>";
            str+="</div>";
            str+="<div class='col-lg-1'>";
                str+="<img src='../../../kpi/public"+data[key].picture+"' class='rank-img' alt='Product Image' />";
            str+="</div>";
            str+="<div class='col-lg-8'>";
                str+="<a class='product-title'>"+data[key].name+"</a>";
                str+="<span class='product-description' style='font-size:0.9em'>";
                    str+="<span class='rank-titles' >Current CSI :</span> <span class='rank-titles' > rate </span>"+data[key].csi+" <span class='rank-titles' >For </span>"+data[key].csi_surveys+" Surveys<br>";
                    str+="<span class='rank-titles' >CSI With Scrub :</span> <span class='rank-titles' > rate </span>"+data[key].csi_scrub+" <span class='rank-titles' >For </span>"+data[key].csi_scrub_surveys+" Surveys";
                str+="</span>";
            str+="</div>";

            str+="<div class='col-lg-2'>";
            if (data[key].number==1) {
                str+="<span class='pull-right' style='color:#FFCC00'><i class='fa fa-3x fa-trophy'></i></span>";        
            } else if(data[key].number==2){
                str+="<span class='pull-right' style='color:#E2E2E2'><i class='fa fa-3x fa-trophy'></i></span>";        
            } else if(data[key].number==3){
                str+="<span class='pull-right' style='color:#E29A2C'><i class='fa fa-3x fa-trophy'></i></span>";        
            }
            str+="</div>";
        str+="</div>";
        str+="</li>";
        }
    }
    $('#top-agent').html(str);
}