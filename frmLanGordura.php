<?php
    $sqlGordura = $PDOMysql->prepare("
    SELECT * FROM gorlan WHERE gor_cod = ?
    ");
    $sqlGordura->execute(array($gorCod));
    $dadosGordura = $sqlGordura->fetch();
?>

<div class="row">
    <div class="col-md-4">

        <div class="row text-secondary">
            <div class="col">
                <p class="fs-4 fw-normal">Editar Lançamento da Gordura</p>
            </div>
        </div>

        <form action="business.php" method="post" autocomplete="off">
            <input type="hidden" name="exec" value="edtLanGordura">
            <input type="hidden" name="gorCod" value="<?=$gorCod?>">

            <div class="row mb-3">
                <div class="col">
                    <label for="txtDataLancamento" class="form-label">Data Lançamento</label>
                    <input type="date" class="form-control" id="txtDataLancamento" name="txtDataLancamento" required="required">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="selectLinha" class="form-label">Linha</label>
                    <select class="form-select" id="selectLinha" name="selectLinha">
                    <?php
                        foreach (getGor() as $key => $gordura)
                        {
                            ?>
                        <option value="<?=$key?>"><?=$gordura?></option>
                            <?php
                        }
                    ?>
                    </select>
                </div>   
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="txtPesoInicial" class="form-label">Peso Inicial</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="txtPesoInicial" name="txtPesoInicial" placeholder="0,000">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
                <div class="col-6">
                    <label for="txtPesoFinal" class="form-label">Peso Final</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="txtPesoFinal" name="txtPesoFinal" placeholder="0,000">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
            </div>
                
            <div class="row mb-3">
                <div class="col">
                    <label for="txtConsumoKg" class="form-label">Consumo</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="txtConsumoKg" name="txtConsumoKg" placeholder="0,000" tabindex="-1">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
            </div>

            <!-- Botões Finais -->

            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-square"></i> Confirmar</button>
                    <a class="btn btn-danger" href="index.php?p=lanGordura"><i class="bi bi-x-square"></i> Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function pesoTotal() {
        var pesoInicial = $("#txtPesoInicial").val().replaceAll(".", "").replaceAll(",", ".");
        pesoInicial = pesoInicial.length > 0 ? parseFloat(pesoInicial) : 0;
        var pesoFinal = $("#txtPesoFinal").val().replaceAll(".", "").replaceAll(",", ".");
        pesoFinal = pesoFinal.length > 0 ? parseFloat(pesoFinal) : 0;
        var pesoTotal = pesoInicial - pesoFinal;

        $("#txtConsumoKg").val(pesoTotal.toLocaleString("pt-br", {minimumFractionDigits: 3}));
    }

    $("#txtPesoInicial").on("blur", pesoTotal);
    $("#txtPesoFinal").on("blur", pesoTotal);

    // Carrega Valores
    $("#selectLinha").val("<?=$dadosGordura["GOR_LIN"]?>");
    $("#txtDataLancamento").val("<?=$dadosGordura["GOR_DAT"]?>");
    $("#txtPesoInicial").val("<?=floatToStr($dadosGordura["GOR_PES_INI"], 3)?>");
    $("#txtPesoFinal").val("<?=floatToStr($dadosGordura["GOR_PES_FIM"], 3)?>");
            
    pesoTotal();

    $(document).ready(function(){
        // Máscaras
        $("#txtPesoInicial").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtPesoFinal").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
    });
    
</script>