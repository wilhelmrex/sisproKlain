<?php
    $sqlGor = $PDOMysql->prepare("
    SELECT * FROM gorlan ORDER BY gor_dat DESC
    ");
    $sqlGor->execute();
    $dadosGordura = $sqlGor->fetchAll();
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal">Utilização de Gordura</p>
    </div>
</div>

<table id="tblGor" class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>Data</th>
            <th>Linha</th>
            <th>Peso Inicial Kg</th>
            <th>Peso Final Kg</th>
            <th>Consumo Kg</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($dadosGordura as $gordura)
        {
            $lanData = date("d/m/Y", strtotime($gordura["GOR_DAT"]));
            ?>
            <tr>
                <td><?=$lanData?></td>
                <td><a href="index.php?p=frmLanGordura&gorCod=<?=$gordura["GOR_COD"]?>"><?=getGor()[$gordura["GOR_LIN"]]?></a></td>
                <td><?=number_format(floatval($gordura["GOR_PES_INI"]), 3, ",", ".")?></td>
                <td><?=number_format(floatval($gordura["GOR_PES_FIM"]), 3, ",", ".")?></td>
                <td><?=number_format(floatval($gordura["GOR_PES_INI"]-$gordura["GOR_PES_FIM"]), 3, ",", ".")?></td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>

<script>
    new DataTable('#tblTemp', {
        order: [],
        pageLength: 25,
        language: {
            url: '//<?=$_SERVER["HTTP_HOST"]?>/js/DataTables/pt-BR.json',
        },
        rowGroup: {
            dataSrc: 0
        }        
    });
</script>