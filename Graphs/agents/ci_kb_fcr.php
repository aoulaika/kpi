<?php
$sql_ci_notnull = "SELECT COUNT(*) as count FROM fact f,ci_dim ci WHERE f.fk_ci=ci.id AND ci.name!='NULL'";
$sql_total = "SELECT COUNT(*) as count FROM fact";
$db=new PDO('mysql:host=localhost;dbname=ticketdb;charset=utf8', 'root', '');
$ci_notnull = $db->query($sql_ci_notnull);
$total = $db->query($sql_total);
$kb_notnull=$db->query("select count(f.fk_kb) as count from fact f, kb_dim k where k.Id=f.fk_kb and k.EKMS_knowledge_Id is not null and k.EKMS_knowledge_Id like '%https://knowledge%'");
$fcr_yes=$db->query("select count(f.fk_ticket) as count from fact f,tickets_dim t where f.fk_ticket=t.Id AND t.fcr_resolved!=0");
$ci = $ci_notnull->fetch();
$all = $total->fetch();
$kb=$kb_notnull->fetch();
$fcr=$fcr_yes->fetch();
$data=($ci['count']/$all['count'])*100;
$data2=($kb['count']/$all['count'])*100;
$data3=($fcr['count']/$all['count'])*100;

$users_ci=$db->query("SELECT ag.Id, COUNT(*) AS count FROM fact f, agent_dim ag, ci_dim ci WHERE f.fk_ci = ci.id AND f.fk_agent = ag.Id GROUP BY ag.Id");

$users_ci_notnull=$db->query("SELECT ag.Id, COUNT(*) AS count FROM fact f, agent_dim ag, ci_dim ci WHERE f.fk_ci = ci.id AND f.fk_agent = ag.Id AND ci.name IS NOT NULL GROUP BY ag.Id");
$users_kb_notnull=$db->query("SELECT ag.Id, COUNT(f.fk_kb) AS count FROM fact f, agent_dim ag, kb_dim k WHERE k.Id = f.fk_kb AND f.fk_agent = ag.Id AND k.EKMS_knowledge_Id IS NOT NULL AND k.EKMS_knowledge_Id LIKE '%https://knowledge%' GROUP BY ag.Id");
$users_fcr_true=$db->query("SELECT ag.Id, COUNT(f.fk_ticket) AS count FROM fact f, agent_dim ag, tickets_dim t WHERE f.fk_ticket = t.Id AND f.fk_agent = ag.Id AND t.fcr_resolved != 0 GROUP BY ag.Id");
$ci=array();
$kb=array();
$fcr=array();
while ($row=$users_ci->fetch(PDO::FETCH_OBJ)) {
    array_push($ci,array("id"=>(int)$row->Id, "count"=>(float)(100/$row->count)));
    array_push($kb,array("id"=>(int)$row->Id, "count"=>(float)(100/$row->count)));
    array_push($fcr,array("id"=>(int)$row->Id, "count"=>(float)(100/$row->count)));
}
$i=1;
while ($row=$users_ci_notnull->fetch(PDO::FETCH_OBJ)) {
    if ($i==$row->Id) {
        $ci[$i-1]['count']=$ci[$i-1]['count']*$row->count;
    } else {
        $ci[$i-1]['count']=$ci[$i-1]['count']*0;
    }
    $i=$row->Id+1;
}
while ($i<=32) {
    $ci[$i-1]['count']=$ci[$i-1]['count']*0;
    $i++;
}
$i=1;
while ($row=$users_kb_notnull->fetch(PDO::FETCH_OBJ)) {
    if ($i==$row->Id) {
        $kb[$i-1]['count']=$kb[$i-1]['count']*$row->count;
    } else {
        $kb[$i-1]['count']=$kb[$i-1]['count']*0;
    }
    $i=$row->Id+1;
}
while ($i<=32) {
    $kb[$i-1]['count']=$kb[$i-1]['count']*0;
    $i++;
}
$i=1;
while ($row=$users_fcr_true->fetch(PDO::FETCH_OBJ)) {
    if ($i==$row->Id) {
        $fcr[$i-1]['count']=$fcr[$i-1]['count']*$row->count;
    } else {
        $fcr[$i-1]['count']=$fcr[$i-1]['count']*0;
    }
    $i=$row->Id+1;
}
while ($i<=32) {
    $fcr[$i-1]['count']=$fcr[$i-1]['count']*0;
    $i++;
}
$users=$db->query("select * from agent_dim");
?>
<!DOCTYPE html>
<head>
    <meta HTTP-EQUIV="X-UA-COMPATIBLE" CONTENT="IE=EmulateIE9" >
    <script type="text/javascript" src="d3.min.js"></script>
    <script type="text/javascript" src="radialProgress.js"></script>
    <link type="text/css" rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        button{
            width: 220px;
        }
        #test .arc2 {
            stroke-weight:0.1;
            fill: #3660b0;
        }
        #outer {
            background:#FFFFFF;
            border-radius: 5px;
            color: #000;
        }
        #div1, #div2, #div3, #div4 {
            width: 33%;
            height: 200px;
            box-sizing: border-box;
            float: left;
        }
        #div2 .arc {
            stroke-weight: 0.1;
            fill: #f0a417;
        }
        #div2 .arc2 {
            stroke-weight: 0.1;
            fill: #b00d08;
        }
        #div3 .arc {
            stroke-weight: 0.1;
            fill: #1d871b;
        }
        .selectedRadial {
            border-radius: 3px;
            background: #f4f4f4;
            color: #000;
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            -moz-box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            border: 1px solid rgba(200,200,200,0.85);
        }
        .radial {
            border-radius: 3px;
            background: #FFFFFF;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-sm-3">
        <div style="height:400px;width:260px;line-height:2em;overflow:auto;padding:5px;">
                <button id="0" class="btn btn-default tailee">All</button>
                <?php while ($row=$users->fetch()) { ?>
                <button id="<?php echo $row['Id'] ?>" type="button" class="btn btn-default taille" name="button"><?php echo $row['Name'] ?></button><br>
                <?php } ?>
            </div>
        </div>
        <div class="col-sm-9">
          <center>
              <h3 id="agent">All Agents</h3>
              <div id='outer'>
                <div id="main" style="margin: 0px auto; ">
                    <div id="div1"></div>
                    <div id="div2"></div>
                    <div id="div3"></div>       
                </div>
            </center>
        </div>
    </div>

    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script language="JavaScript">
        var ci=<?php echo $data;?>;
        var kb=<?php echo $data2;?>;
        var fcr=<?php echo $data3;?>;
        var ci_users=JSON.parse( '<?php echo json_encode($ci); ?>' );
        var kb_users=JSON.parse( '<?php echo json_encode($kb); ?>' );
        var fcr_users=JSON.parse( '<?php echo json_encode($fcr); ?>' );
        var div1=d3.select(document.getElementById('div1'));
        var div2=d3.select(document.getElementById('div1'));
        var div3=d3.select(document.getElementById('div1'));
        start();
        $("button").on("click", function(){
            var i=parseInt($(this).attr('id'));
            if (i==0) {
                $('#agent').text('All Agents');
                ci=<?php echo $data;?>;
                kb=<?php echo $data2;?>;
                fcr=<?php echo $data3;?>;
            } else{
                $('#agent').text($(this).text())
                ci=ci_users[i-1].count;
                kb=kb_users[i-1].count;
                fcr=fcr_users[i-1].count;
            }

            start();
        });
        function start() {
            var rp1 = radialProgress(document.getElementById('div1'))
            .label("Total CI Usage")
            .diameter(200)
            .value(ci)
            .render();
            var rp2 = radialProgress(document.getElementById('div2'))
            .label("Total KB Usage")
            .diameter(200)
            .value(kb)
            .render();                
            var rp2 = radialProgress(document.getElementById('div3'))
            .label("Total FCR")
            .diameter(200)
            .value(fcr)
            .render();
        }
    </script>
</body>
</html>


