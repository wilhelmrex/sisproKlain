<?php
    $sqlProd = $PDOMysql->prepare("
    SELECT * FROM prdlan INNER JOIN prdcad on (prdlan.prd_cod = prdcad.prd_cod) ORDER BY lan_dat DESC
    ");
    $sqlProd->execute();
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal">Utilização de Temperos</p>
    </div>
</div>

<table id="tblProd" class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>Data</th>
            <th>Linha</th>
            <th>Sabor</th>
            <th>Peso Inicial</th>
            <th>Peso Final</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    <?php
    /*
        foreach ($sqlProd->fetchAll() as $prod)
        {
            $sitColor = "success";
            $sitText = "Concluído";

            if ($prod["LAN_FIM"] == "00:00:00")
            {
                $sitColor = "warning";
                $sitText = "Em Aberto";
            }

            ?>
            <tr>
                <td><?=date("d/m/Y", strtotime($prod["LAN_DAT"]))?></td>
                <td><a href="index.php?p=lan&lanCod=<?=$prod["LAN_COD"]?>"><?=$prod["PRD_DES"]?></a></td>
                <td><span class="badge text-bg-<?=$sitColor?>"><?=$sitText?></span></td>
            </tr>
            <?php
        }
            */
    ?>
    </tbody>
</table>

<script>
    // new DataTable('#tblProd', {
    //     order: [],
    //     pageLength: 25,
    //     language: {
    //         url: '//<?=$_SERVER["HTTP_HOST"]?>/js/DataTables/pt-BR.json',
    //     },
    // });
</script>