function showRadar (data) {
    var chart = AmCharts.makeChart( "chart-container", 
    {
      "type": "radar",
      "theme": "light",
      "dataProvider": data,
      "startDuration": 2,
      "categoryField": "name",
      "valueAxes": [
      {
        "axisTitleOffset": 20,
        "minimum": 0,
        "axisAlpha": 0.15
    }],
    "graphs": [ {
        "balloonText": "[[value]]% Average Usage",
        "bullet": "round",
        "valueField": "average"
    },{
        "balloonText": "[[value]]% Agent Usage",
        "bullet": "round",
        "valueField": "agent"
    } ]
} );
}