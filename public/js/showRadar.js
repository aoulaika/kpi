function showRadar (data) {
  var chart = AmCharts.makeChart( "chart-container", 
  {
    "type": "radar",
    "theme": "light",
    "dataProvider": data,
    "startDuration": 0.5,
    "startEffect": "easeOutSine",
    "categoryField": "name",
    "valueAxes": [
    {
      "axisTitleOffset": 20,
      "minimum": 0,
      "maximum": 100,
      "axisAlpha": 0.15
    }],
    "guides": [
    {
      "fillAlpha": 0.10,
      "value": 0,
      "toValue": 50
    }],
    "colors": ['#2685BF', '#24B273', '#FF5B44'],
    "legend": {
      "useGraphSettings": false,
      "align": "center",
      "data": [{"title": "Target", "color": "#2685BF"},{"title": "Average", "color": "#24B273"},{"title": "Agent", "color": "#FF5B44"}]
    },
    "graphs": [
    {
      "balloonText": "[[value]]% Target",
      "bullet": "round",
      "valueField": "target"
    },
    {
      "balloonText": "[[value]]% Average Usage",
      "bullet": "round",
      "valueField": "average"
    },
    {
      "balloonText": "[[value]]% Agent Usage",
      "bullet": "round",
      "valueField": "agent"
    }],
    "balloon": {
      "adjustBorderColor": true,
      "color": "#000000",
      "cornerRadius": 5,
      "fillColor": "#FFFFFF"
    }
  });
}