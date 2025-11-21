<?php
    $sqlTemp = $PDOMysql->prepare("
    SELECT * FROM temlan t INNER JOIN temcad t2 ON t.tem_cod = t2.tem_cod LEFT JOIN temsob t3 ON t.lan_dat = t3.sob_dat ORDER BY lan_dat DESC
    ");
    $sqlTemp->execute();
    $dadosTempero = $sqlTemp->fetchAll();
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal">Utilização de Temperos</p>
    </div>
</div>

<table id="tblTemp" class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>Data</th>
            <th>Linha</th>
            <th>Sabor</th>
            <th>Peso Inicial Kg</th>
            <th>Peso Final Kg</th>
            <th>Utilizado Kg</th>
            <th>Reprocesso Kg</th>
            <th>Descarte Kg</th>
            <th>Obs</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $data = false;
        $show = false;
        $arrayModal = [];
        foreach ($dadosTempero as $tempero)
        {
            $lanData = date("d/m/Y", strtotime($tempero["LAN_DAT"]));
            if ($data != $lanData)
            {
                $data = $lanData;
                $show = true;
            }
            ?>
            <tr>
                <td><?=$lanData?></td>
                <td><a href="index.php?p=frmLanTemperos&lanCod=<?=$tempero["LAN_COD"]?>"><?=$tempero["TEM_LIN"]?></a></td>
                <td><a href="index.php?p=frmLanTemperos&lanCod=<?=$tempero["LAN_COD"]?>"><?=$tempero["TEM_SAB"]?></a></td>
                <td><?=number_format(floatval($tempero["LAN_PES_INI"]), 3, ",", ".")?></td>
                <td><?=number_format(floatval($tempero["LAN_PES_FIM"]), 3, ",", ".")?></td>
                <td><?=number_format(floatval($tempero["LAN_PES_INI"]) - floatval($tempero["LAN_PES_FIM"]), 3, ",", ".")?></td>
                <td><?=$show == true ? number_format(floatval($tempero["SOB_REP_KG"]), 3, ",", ".") : ""?></td>
                <td><?=$show == true ? number_format(floatval($tempero["SOB_DES_KG"]), 3, ",", ".") : ""?></td>
                <td class="text-center">
                    <?php
                    if ($show)
                    {
                        if ($tempero["SOB_OBS"])
                        {
                            $arrayModal[$tempero["LAN_DAT"]] = $tempero["SOB_OBS"];
                            ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal<?=$tempero["LAN_DAT"]?>"><i class="bi bi-info-circle"></i></button>
                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php
            $show = false;
        }
    ?>
    </tbody>
</table>

<?php
    // Constroi os modals com as observações das sobras
    foreach ($arrayModal as $key => $obs)
    {
?>
<div class="modal fade" id="modal<?=$key?>" tabindex="-1">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Observação</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <?=$obs?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
    </div>
</div>
</div>
<?php
    }
?>

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