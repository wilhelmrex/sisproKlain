<?php
    // Editar
    if (isset($temCod))
    {
        $sqlTempero = $PDOMysql->prepare("
        SELECT * FROM temcad WHERE tem_cod = ?
        ");
        $sqlTempero->execute(array($temCod));
        $dadosTempero = $sqlTempero->fetch();
    }

    // Distinct Linhas
    $sqlDistinctLinhas = $PDOMysql->prepare("SELECT DISTINCT tem_lin FROM temcad ORDER BY tem_lin");
    $sqlDistinctLinhas->execute();
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal"><?=isset($temCod) ? "Editar" : "Adicionar"?> Tempero</p>
    </div>
</div>

<form action="business.php" method="post" autocomplete="off">
    <input type="hidden" name="exec" value="<?=isset($temCod) ? "edtTem" : "addTem"?>">
    <input type="hidden" name="temCod" value="<?=$temCod ?? ""?>">

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="txtLinha" class="form-label">Linha</label>
            <input type="text" class="form-control" id="txtLinha" name="txtLinha" required="required" autofocus="autofocus" list="temperos-linhas" value="<?=$dadosTempero["TEM_LIN"] ?? ""?>">

            <datalist id="temperos-linhas">
                <?php
                foreach ($sqlDistinctLinhas->fetchAll() as $linha)
                {
                    ?>
                <option value="<?=$linha["TEM_LIN"]?>"></option>
                    <?php
                }
                ?>
            </datalist>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="txtSabor" class="form-label">Sabor</label>
            <input type="text" class="form-control" id="txtSabor" name="txtSabor" required="required" value="<?=$dadosTempero["TEM_SAB"] ?? ""?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="selectAtivo" class="form-label">Ativo</label>
            <select class="form-select" id="selectAtivo" name="selectAtivo">
                <option value="S">Sim</option>
                <option value="N">Não</option>
            </select>
        </div>   
    </div>

    <!-- Botões Finais -->

    <div class="row">
        <div class="col">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-square"></i> Confirmar</button>
            <a class="btn btn-danger" href="index.php?p=temperos"><i class="bi bi-x-square"></i> Cancelar</a>
        </div>
    </div>

</form>

<script>
$(document).ready(function() {
    $("#selectAtivo").val("<?=$dadosTempero["TEM_ATIVO"] ?? ""?>");
});
</script>
