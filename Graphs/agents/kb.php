<?php
$db=new PDO('mysql:host=localhost;dbname=ticketsdw;charset=utf8', 'root', '');
$sql_valide = "SELECT agent_dim.Id, agent_dim.Name, count(agent_dim.Id) AS totale FROM agent_dim, fact, kb_dim WHERE fact.fk_agent=agent_dim.Id AND fact.fk_kb=kb_dim.Id AND kb_dim.EKMS_knowledge_Id LIKE '%https://knowledge.rf.lilly.com%' AND kb_dim.EKMS_knowledge_Id IS NOT NULL GROUP BY agent_dim.Id";
$sql_totale = "SELECT a.Name,count(a.Id) AS totale FROM fact f, kb_dim k, agent_dim a WHERE k.Id=f.fk_kb AND a.Id=f.fk_agent group by a.Id";
$agent_totale=$db->query($sql_totale);
$agent_valide=$db->query($sql_valide);
$name=array();
$count=array();
while ($row=$agent_valide->fetch(PDO::FETCH_OBJ)) {
  array_push($name, $row->Name);
  array_push($count, $row->totale*100);
}
while ($row=$agent_totale->fetch(PDO::FETCH_OBJ)) {
  if(in_array($row->Name, $name)){
      $count[array_search($row->Name, $name)]=number_format((float)($count[array_search($row->Name, $name)]/$row->totale), 2, '.', '');
    }else{
      array_push($name, $row->Name);
      array_push($count, 0);
    }
}
$series=array(
  "label"=>"",
  "values"=>$count
);
$data=(object)array(
  "labels" => $name,
  "series" => [(object)$series]
);
?>
<!DOCTYPE html>
<meta charset="utf-8">
<html>
<style>

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
  fill: black;
  font: 11px sans-serif;
  text-anchor: end;
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
</style>
<body>
  <center>
    <svg class="chart"></svg>
  </center>
  <script src="d3.min.js"></script>
  <script>

  var data = JSON.parse( '<?php echo json_encode($data); ?>' );
  console.log(data);

  var chartWidth = 700,
  barHeight = 20,
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

  // Color scale
  var color = d3.scale.category20();
  var chartHeight = barHeight * zippedData.length + gapBetweenGroups * data.labels.length;

  var x = d3.scale.linear()
  .domain([0, d3.max(zippedData)*0.5])
  .range([0, d3.max(zippedData)*3]);

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
  .attr("height", barHeight +2);

  // Add text label in bar
  bar.append("text")
  .attr("x", function(d) { return x(d) +38; })
  .attr("y", barHeight / 2)
  .attr("fill", "blue")
  .attr("dy", ".35em")
  .text(function(d) { return d; });

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
