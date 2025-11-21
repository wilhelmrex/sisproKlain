<?php
    $sqlMotivos = $PDOMysql->prepare("
    SELECT * FROM motcad
    ");
    $sqlMotivos->execute();
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal">Motivos de Intervalo</p>
    </div>
    <div class="col text-end">
        <a href="index.php?p=frmMotIntervalos" class="btn btn-sm btn-primary">+ Adicionar</a>
    </div>
</div>

<table id="tblMotivos" class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Descrição</th>
            <th>Setor</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($sqlMotivos->fetchAll() as $motivo)
        {
            $bgColor = $motivo["MOT_ATIVO"] == "S" ? "" : "table-danger";
            ?>
            <tr class="<?=$bgColor?>">
                <td><?=$motivo["MOT_COD"]?></td>
                <td><a href="index.php?p=frmMotIntervalos&motCod=<?=$motivo["MOT_COD"]?>"><?=$motivo["MOT_DES"]?></a></td>
                <td><?=getSet()[$motivo["MOT_SET"]]?></td>
                <td><?=$motivo["MOT_ATIVO"] == "S" ? "Ativo" : "Inativo"?></td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>

<script>
    new DataTable('#tblMotivos', {
        order: [],
        pageLength: 10,
        language: {
            url: '//<?=$_SERVER["HTTP_HOST"]?>/js/DataTables/pt-BR.json',
        },
    });
</script>