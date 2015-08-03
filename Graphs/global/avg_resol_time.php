<?php 
$db=new PDO('mysql:host=localhost;dbname=ticketdb;charset=utf8', 'root', '');
$sql_valide = "SELECT avg (`ResolutionTime`) as ResolutionTime FROM `time_dim`, `fact` WHERE `time_dim`.`Id`=`fact`.`fk_time`";
$sql_valid= "SELECT avg (`ResolutionTime`) as ResolutionTimeCat , Category as Cat FROM `time_dim`, `fact`, `tickets_dim` WHERE `time_dim`.`Id`=`fact`.`fk_time` and `fact`.`fk_ticket`=`tickets_dim`.`id` group by `tickets_dim`.`Category`";
$agent_valide=$db->query($sql_valide);
$agent_valid=$db->query($sql_valid);
$Categorie=array();
$ResolutionTimeCat=array();
$tab=array();
while ($row=$agent_valide->fetch(PDO::FETCH_OBJ)) {
  $avgResolution=$row->ResolutionTime;
  
}
while ($row=$agent_valid->fetch(PDO::FETCH_OBJ)) {
  array_push($Categorie, $row->Cat);
  array_push($ResolutionTimeCat, number_format((float)($row->ResolutionTimeCat), 0, '.', '')/(60*60*24));
}

  $series=array(
  "label"=>"",
  "values"=>$ResolutionTimeCat
);
$data=(object)array(
  "labels" => $Categorie,
  "series" => [(object)$series]
);


?>
<!DOCTYPE html>
<meta charset="utf-8">
<html>
<style>



.d3-tip {
  line-height: 1;
  font-weight: bold;
  padding: 12px;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  border-radius: 2px;
}

/* Creates a small triangle extender for the tooltip */
.d3-tip:after {
  box-sizing: border-box;
  display: inline;
  font-size: 10px;
  width: 100%;
  line-height: 1;
  color: rgba(0, 0, 0, 0.8);
  content: "\25BC";
  position: absolute;
  text-align: center;
}

/* Style northward tooltips differently */
.d3-tip.n:after {
  margin: -1px 0 0 0;
  top: 100%;
  left: 0;
}
.chart .legend {
  fill: black;
  font: 14px sans-serif;
  text-anchor: start;
  font-size: 12px;
}

.chart text {
  fill: black;
  font: 12px sans-serif;
  text-anchor: end;
}

.chart .label {
  fill: grey;
  font: 11px sans-serif;
  text-anchor: end;
  font-size:xx-small;
}

.bar:hover {
  fill: #00A1DD;
}

.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}
.chart{
  
  padding-top: 50px;
}
</style>
<body>
  <center>
    <svg class="chart"></svg>
  </center>
  <script src="../../public/js/d3.min.js"></script>
  <script src="d3.tip.v0.6.3.js"></script>
  <script>

  var data = JSON.parse( '<?php echo json_encode($data); ?>' );
  console.log(data);

  var chartWidth = 1000,
  barHeight = 15,
  groupHeight = barHeight*data.series.length,
  gapBetweenGroups = 6,
  spaceForLabels = 140,
  spaceForLegend = 140;

  // Zip the series data together (first values, second values, etc.)
  var zippedData = [];
  for (var i=0; i<data.labels.length; i++) {
    for (var j=0; j<data.series.length; j++) {
      zippedData.push(data.series[j].values[i]);
    }
  }

var tip = d3.tip()
  .attr('class', 'd3-tip')
  .offset([-10, 0])
   .style("left", (x+10) + "px")   
   .style("top", (y+10) + "px")
  .html(function(d) {
    return "<strong>Avg Resolution Time:</strong> <span style='color:red'>" + parseInt(d)+ "  j "+ parseInt((d-parseInt(d))*24) +"   h   "+ parseInt((((d-parseInt(d))*24)-parseInt((d-parseInt(d))*24))*60) + "  min</span>";
  })

  // Color scale
  var color = d3.scale.category20();
  var chartHeight = barHeight * zippedData.length + gapBetweenGroups * data.labels.length;

  var x = d3.scale.linear()
  .domain([0, d3.max(zippedData)*0.067])
  .range([0, d3.max(zippedData)*4]);
   console.log(d3.max(zippedData));
  var y = d3.scale.linear()
  .range([chartHeight + gapBetweenGroups, 0]);

  var yAxis = d3.svg.axis()
  .scale(y)
  .tickFormat('')
  .tickSize(0)
  .orient("left");

  // Specify the chart area and dimensions
  var chart = d3.select(".chart")
  .attr("width", spaceForLabels + chartWidth + spaceForLegend)
  .attr("height", chartHeight);
  chart.call(tip);

  // Create bars
  var bar = chart.selectAll("g")
  .data(zippedData)
  .enter().append("g")
  .attr("transform", function(d, i) {
    return "translate(" + spaceForLabels + "," + (i * barHeight + gapBetweenGroups * (0.4 + Math.floor(i/data.series.length))) + ")";
  });

  // Create rectangles of the correct width
  bar.append("rect")
  .attr("fill", function(d,i) { return color(i % data.series.length); })
  .attr("class", "bar")
  .attr("width", x)
  .attr("height", barHeight +2)
  .on('mouseover', tip.show)
  .on('mouseout', tip.hide);



  // Add text label in bar
 /* bar.append("text")
  .attr("x", function(d) { return x(d) +38; })
  .attr("y", barHeight / 2)
  .attr("fill", "blue")
  .attr("dy", ".35em")
  .text(function(d) { return d; });*/

  // Draw labels
  bar.append("text")
  .attr("class", "label")
  .attr("x", function(d) { return -3; })
  .attr("y", groupHeight / 2)
  .attr("dy", ".35em")
  .text(function(d,i) {
    if (i % data.series.length === 0)
    return data.labels[Math.floor(i/data.series.length)];
    else
    return ""});

    chart.append("g")
    .attr("class", "y axis")
    .attr("transform", "translate(" + spaceForLabels + ", " + -gapBetweenGroups/2 + ")")
    .call(yAxis);

    </script>
  </body>
  </html>
