<?php
    // Editar
    if (isset($motCod))
    {
        $sqlMotivos = $PDOMysql->prepare("
        SELECT * FROM motcad WHERE mot_cod = ?
        ");
        $sqlMotivos->execute(array($motCod));
        $dadosMotivo = $sqlMotivos->fetch();
    }
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal"><?=isset($temCod) ? "Editar" : "Adicionar"?> Motivo</p>
    </div>
</div>

<form action="business.php" method="post" autocomplete="off">
    <input type="hidden" name="exec" value="<?=isset($motCod) ? "edtMot" : "addMot"?>">
    <input type="hidden" name="motCod" value="<?=$motCod ?? ""?>">

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="txtDescricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="txtDescricao" name="txtDescricao" required="required" value="<?=$dadosMotivo["MOT_DES"] ?? ""?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="selectSetor" class="form-label">Setor</label>
            <select class="form-select" id="selectSetor" name="selectSetor" autofocus="autofocus">
                <?php
                foreach (getSet() as $key => $setor)
                {
                    ?>
                <option value="<?= $key ?>"><?= $setor ?></option>
                    <?php
                }
                ?>
            </select>
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
            <a class="btn btn-danger" href="index.php?p=motIntervalos"><i class="bi bi-x-square"></i> Cancelar</a>
        </div>
    </div>

</form>

<?php
if (isset($motCod))
    {
?>
<script>
$(document).ready(function() {
    $("#selectSetor").val("<?=$dadosMotivo["MOT_SET"] ?? ""?>");
    $("#selectAtivo").val("<?=$dadosMotivo["MOT_ATIVO"] ?? ""?>");
});
</script>
<?php
    }
?>