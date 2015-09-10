@extends('managerViews/layout')
@section('title', ' Global Dashboard')
@endsection
@section('content')
    <div id="weeks"></div>
@section('script')
    <!-- Compare Weeks -->
    <script src="{{ asset('/js/amcharts.js') }}"></script>
    <script src="{{ asset('/js/serial.js') }}" type="text/javascript"></script>
    <script>
        var values= JSON.parse('<?php echo json_encode($values); ?>');
        drawChart(values);
        function addDays(date, days) {
            var result = new Date(date);
            result.setDate(result.getDate() + days);
            return result;
        }
        function drawChart(values){
            var chartData=[];
            var dates=[];
            var iterator =[];
            for(var i=0;i<values.length;i++){
                dates.push(new Date(values[i][0].CreatedYear, values[i][0].CreatedMonth-1, values[i][0].CreatedDay, 0, 0));
                iterator.push(0);
            }
            console.log(dates);
            var datefin=dates[0];
            datefin=addDays(dates[0],1);
            while(dates[0]<datefin) {
                var temp=[];
                for(var i=0;i<values.length;i++){
                    try{
                        temp[i] = new Date(values[i][iterator[i]].CreatedYear, values[i][iterator[i]].CreatedMonth-1, values[i][iterator[i]].CreatedDay, values[i][iterator[i]].CreatedHour, 0);
                    }
                    catch(err){
                        temp[i] =new Date(0);

                    }
                }
                var tempo ={};
                tempo["date"] = new Date(dates[0].getTime());
                for(var i=0;i<values.length;i++) {
                    tempo[""+i] = (temp[i].getTime() == dates[i].getTime())? values[i][iterator[i]].count:0;
                }
                chartData.push(tempo);
                for(var i=0;i<values.length;i++) {
                    if(temp[i].getTime() == dates[i].getTime())
                        iterator[i]++;
                }
                for(var i=0;i<values.length;i++)
                    dates[i].setHours(dates[i].getHours()+1);
            }
            console.log(chartData);
            var graphData = [];
            for(var i=0;i<values.length;i++){
                var obj = {
                    "id": ""+i,
                    "balloonText": "[[category]]<br/><b><span style='font-size:14px;'>value: [[value]]</span></b>",
                    "bullet": "round",
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "hideBulletsCount": 50,
                    "title": "week<br>" ,
                    "valueField": ""+i,
                    "useLineColorForBulletBorder": true,
                    "lineThickness": 2
                };
                graphData.push(obj);
            }
            var weekday=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            function formatLabel(value, date, categoryAxis){
                if (date.getHours()==0){
                    return weekday[date.getDay()];}
                else {return String(date.getHours())+":"+String(date.getMinutes())};
            }
            var chart = AmCharts.makeChart("weeks", {
                "type": "serial",
                "theme": "light",
                "legend": {
                    "useGraphSettings": true
                },
                "marginRight": 80,
                "autoMarginOffset": 20,
                "marginTop": 7,
                "dataProvider": chartData,
                "valueAxes": [{
                    "axisAlpha": 0.2,
                    "dashLength": 1,
                    "position": "left"
                }],
                "mouseWheelZoomEnabled": true,
                "graphs": graphData,
                "chartScrollbar": {
                    "autoGridCount": true,
                    "graph": "0",
                    "scrollbarHeight": 40
                },
                "chartCursor": {
                    "categoryBalloonDateFormat": "JJ h",
                    "cursorPosition": "mouse"
                },
                "categoryField": "date",
                "categoryAxis": {
                    "parseDates": true,
                    "labelFunction":formatLabel,
                    "minPeriod": "mm",
                    "axisColor": "#DADADA",
                    "dashLength": 1,
                    "minorGridEnabled": true
                },
                "export": {
                    "enabled": true
                }
            });
            chart.addListener("rendered", zoomChart);
            zoomChart();
            // this method is called when chart is first inited as we listen for "dataUpdated" event
            function zoomChart() {
                // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
            }
        }
    </script>
    <!-- End Compare Weeks -->
@endsection
@endsection