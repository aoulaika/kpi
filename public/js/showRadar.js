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
        "maximum": 100,
        "axisAlpha": 0.15
    }],
    "graphs": [ {
        "balloonText": "[[value]]% Target",
        "bullet": "round",
        "valueField": "target"
    },{
        "balloonText": "[[value]]% Agent Usage",
        "bullet": "round",
        "valueField": "agent"
    } ]
} );
}