<?php
    $txtDataInicial = isset($txtDataInicial) ? $txtDataInicial : "";
    $txtDataFinal = isset($txtDataFinal) ? $txtDataFinal : "";
    $txtRegua = isset($txtRegua) ? $txtRegua : "";
?>

<div class="row mb-3 text-secondary">
    <div class="col">
        <div class="fs-4 fw-normal">Tempo Médio</div>
    </div>
</div>

<div class="row">
    <div class="col-3">
        <ul class="list-group">
            <?php
                $sqlPrd = $PDOMysql->prepare("
                select a.prd_cod, b.prd_des, count(*) as tot_prd
                from prdlan a
                inner join prdcad b on (a.prd_cod = b.prd_cod)
                group by a.prd_cod, b.prd_des
                order by prd_des
                ");
                $sqlPrd->execute(array());
                foreach ($sqlPrd->fetchAll() as $prod)
                {
                    ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="<?=$_SERVER["REQUEST_URI"]?>&prdCod=<?=$prod["PRD_COD"]?>"><?=$prod["PRD_DES"]?></a>
            <span class="badge text-bg-secondary rounded-pill"><?=$prod["TOT_PRD"]?></span>
            </li>
                    <?php                    
                }
            ?>
        </ul>
    </div>
    <?php
    if (isset($prdCod))
    {
        ?>
    <div class="col-9">
        <h3 class="text-secondary">
        <?php
            $prod = getProd($prdCod);
            echo $prod["PRD_DES"];
        ?>
        </h3>
        <div class="row">
            <div class="col">
                <form name="frmFiltro" action="<?=$_SERVER["PHP_SELF"]?>" method="get">
                    <?php
                        // Preserva a URL
                        foreach ($_GET as $field => $value)
                        {
                            ?>
                    <input type="hidden" name="<?=$field?>" value="<?=$value?>">
                            <?php
                        }
                    ?>
                    <div class="row my-3">
                        <div class="col-sm-2"><input class="form-control" type="date" name="txtDataInicial" value="<?=$txtDataInicial ?? $txtDataInicial?>"></div>
                        <div class="col-sm-2"><input class="form-control" type="date" name="txtDataFinal" value="<?=$txtDataFinal ?? $txtDataFinal?>"></div>
                        <div class="col-sm-1"><input class="form-control" type="text" name="txtRegua" value="<?=$txtRegua ?? $txtRegua?>" placeholder="Régua"></div>
                        <div class="col"><input class="btn btn-primary" type="submit" value="Filtrar"></div>
                    </div>
                </form>
            </div>
        </div>
        <div id="divNoDataMsg" class="alert alert-secondary" role="alert">Sem Dados</div>
        <div id="chart_div" style="width: 100%; height: 640px;"></div>
        <div class="row" id="divChartButtons">
            <div class="col text-center">
                <button type="button" class="btn btn-secondary btn-sm" id="btnLimpar">Limpar</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnHora">Bat/Un p/ Hora</button>
                <button type="button" class="btn btn-warning btn-sm" id="btnTmpMed">Tempo Médio Bat/Un</button>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>

<?php
if (isset($prdCod))
{
    if ($txtRegua)
    {
        $sqlLan = $PDOMysql->prepare("
        SELECT * FROM prdlan WHERE prd_cod = ? AND lan_fim <> '00:00:00' AND lan_dat BETWEEN ? AND ? AND lan_reg = ? ORDER BY lan_dat DESC LIMIT 15
        ");
        $sqlLan->execute(array($prdCod, $txtDataInicial ? $txtDataInicial : "0001-01-01", $txtDataFinal ? $txtDataFinal : "9999-12-31", $txtRegua));
    }
    else
    {
        $sqlLan = $PDOMysql->prepare("
        SELECT * FROM prdlan WHERE prd_cod = ? AND lan_fim <> '00:00:00' AND lan_dat BETWEEN ? AND ? ORDER BY lan_dat DESC LIMIT 15
        ");
        $sqlLan->execute(array($prdCod, $txtDataInicial ? $txtDataInicial : "0001-01-01", $txtDataFinal ? $txtDataFinal : "9999-12-31"));
    }

    $arrayDados = [];
    foreach ($sqlLan->fetchAll() as $dadosLanc)
    {
        $horaIni = new DateTime($dadosLanc["LAN_INI"]);
        $horaFim = new DateTime($dadosLanc["LAN_FIM"]);
        $diff = $horaFim->diff($horaIni);

        $massadasHora = $dadosLanc["LAN_UN"] / (timeToFloat($diff->format("%H:%I")) / 60);
        $tempoMassada = timeToFloat($diff->format("%H:%I")) / $dadosLanc["LAN_UN"];

        $data = date("d/m/Y", strtotime($dadosLanc["LAN_DAT"]));

        array_unshift($arrayDados, array($data, $dadosLanc["LAN_UN"], $massadasHora, number_format($massadasHora, 3, ",", "."), $tempoMassada, floatToTime($tempoMassada), $dadosLanc["LAN_COD"]));
    }

    if ($arrayDados)
    {
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        $("#divNoDataMsg").hide();
        
        google.charts.load('current', {'packages':['corechart'], 'language': 'pt-BR'});
        google.charts.setOnLoadCallback(drawVisualization);

        function drawVisualization() {
            var data = google.visualization.arrayToDataTable([
            ['Data', 'Bateladas/Unidades', {role: 'annotation'}, 'Bat/Un p/ Hora', {role: 'annotation'}, 'Tempo Médio Bat/Un', {role: 'annotation'}],
            <?php
            foreach ($arrayDados as $dados)
            {
                ?>
                ['<?=$dados[0]?>', <?=$dados[1]?>, <?=$dados[1]?>, <?=$dados[2]?>, '<?=$dados[3]?>', <?=$dados[4]?>, '<?=$dados[5]?>'],
                <?php
            }
            ?>
            ]);

            var options = {
                //title: 'Company Performance',
                legend: 'top',
                pointSize: 5,
                tooltip: { trigger: 'selection' },
                series: {
                    0: {targetAxisIndex: 0, type: 'bars', color: '#0d6efd'},
                    1: {targetAxisIndex: 1, type: 'line', color: '#dc3545'},
                    2: {targetAxisIndex: 1, type: 'line', color: '#fd7e14'},
                },
                vAxes: {
                    0: {title: 'Tempo Médio'},
                    1: {title: 'Bateladas/Unidades'}
                },
                hAxis: {title: 'Data'},
            };

            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));

            chart.setAction({
                id: 'sample',
                text: 'Abrir lançamento',
                action: function() {
                    selection = chart.getSelection();
                    switch (selection[0].row) {
                        <?php
                            foreach ($arrayDados as $key => $dados)
                            {
                            ?>
                            case <?=$key?>: window.open("index.php?p=lan&lanCod=<?=$dados[6]?>", "blank"); break;
                            <?php
                            }
                        ?>
                    }
                }
            });

            chart.draw(data, options);

            var limpar = document.getElementById("btnLimpar");
            limpar.onclick = function()
            {
                view = new google.visualization.DataView(data);
                options.series[1].color = '#dc3545';
                options.series[2].color = '#fd7e14';
                chart.draw(view, options);
            }
            var onlyHora = document.getElementById("btnHora");
            onlyHora.onclick = function()
            {
                view = new google.visualization.DataView(data);
                view.hideColumns([5]);
                view.hideColumns([6]);
                options.series[1].color = '#dc3545';
                chart.draw(view, options);
            }
            var onlyTempo = document.getElementById("btnTmpMed");
            onlyTempo.onclick = function()
            {
                view = new google.visualization.DataView(data);
                view.hideColumns([3]);
                view.hideColumns([4]);
                options.series[1].color = '#fd7e14';
                chart.draw(view, options);
            }
        }
    </script>
    <?php
    }
    else
    {
        ?>
        
        <script>$("#divNoDataMsg").show(); $("#divChartButtons").hide();</script>
        <?php
    }
}
?>