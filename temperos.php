<?php
    $sqlTemperos = $PDOMysql->prepare("
    SELECT * FROM temcad
    ");
    $sqlTemperos->execute();
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal">Temperos</p>
    </div>
    <div class="col text-end">
        <a href="index.php?p=frmTempero" class="btn btn-sm btn-primary">+ Adicionar</a>
    </div>
</div>

<table id="tblTempero" class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Linha</th>
            <th>Sabor</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($sqlTemperos->fetchAll() as $tempero)
        {
            $bgColor = $tempero["TEM_ATIVO"] == "S" ? "" : "table-danger";
            ?>
            <tr class="<?=$bgColor?>">
                <td><?=$tempero["TEM_COD"]?></td>
                <td><a href="index.php?p=frmTempero&temCod=<?=$tempero["TEM_COD"]?>"><?=$tempero["TEM_LIN"]?></a></td>
                <td><a href="index.php?p=frmTempero&temCod=<?=$tempero["TEM_COD"]?>"><?=$tempero["TEM_SAB"]?></a></td>
                <td><?=$tempero["TEM_ATIVO"] == "S" ? "Ativo" : "Inativo"?></td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>

<script>
    new DataTable('#tblTempero', {
        order: [],
        pageLength: 10,
        language: {
            url: '//<?=$_SERVER["HTTP_HOST"]?>/js/DataTables/pt-BR.json',
        },
    });
</script>