<?php
    $sqlTempero = $PDOMysql->prepare("
    SELECT * FROM temlan t INNER JOIN temcad t2 ON t.tem_cod = t2.tem_cod LEFT JOIN temsob t3 ON t.lan_dat = t3.sob_dat WHERE t.lan_cod = ?
    ");
    $sqlTempero->execute(array($lanCod));
    $dadosTempero = $sqlTempero->fetch();

    // Distinct Linhas
    $sqlDistinctLinhas = $PDOMysql->prepare("SELECT DISTINCT tem_lin FROM temcad ORDER BY tem_lin");
    $sqlDistinctLinhas->execute();
?>

<div class="row">
    <div class="col-md-4 border-end">

        <div class="row text-secondary">
            <div class="col">
                <p class="fs-4 fw-normal">Editar Lançamento do Tempero</p>
            </div>
        </div>

        <form action="business.php" method="post" autocomplete="off">
            <input type="hidden" name="exec" value="edtLanTempero">
            <input type="hidden" name="lanCod" value="<?=$lanCod?>">

            <div class="row mb-3">
                <div class="col">
                    <label for="txtDataProducao" class="form-label">Data Produção</label>
                    <input type="date" class="form-control" id="txtDataProducao" name="txtDataProducao" required="required">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="selectLinha" class="form-label">Linha</label>
                    <select class="form-select" id="selectLinha" name="selectLinha">
                    <?php
                        $sqlLinhas = $PDOMysql->prepare("SELECT DISTINCT tem_lin FROM temcad ORDER BY tem_lin");
                        $sqlLinhas->execute();
                        foreach ($sqlLinhas->fetchAll() as $linha)
                        {
                            ?>
                        <option value="<?=$linha["TEM_LIN"]?>"><?=$linha["TEM_LIN"]?></option>
                            <?php
                        }
                    ?>
                    </select>
                </div>   
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="selectSabor" class="form-label">Sabor</label>
                    <select class="form-select" id="selectSabor" name="selectSabor">
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
                    <label for="txtPesoTotal" class="form-label">Peso Total</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light" id="txtPesoTotal" name="txtPesoTotal" placeholder="0,000" tabindex="-1">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
            </div>

            <!-- Botões Finais -->

            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-square"></i> Confirmar</button>
                    <a class="btn btn-danger" href="index.php?p=lanTemperos"><i class="bi bi-x-square"></i> Cancelar</a>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-3">
        <div class="row text-secondary">
            <div class="col">
                <p class="fs-4 fw-normal">Editar Sobras do Dia <?=date("d/m/Y", strtotime($dadosTempero["LAN_DAT"]))?></p>
            </div>
        </div>

        <form action="business.php" method="post" autocomplete="off">
            <input type="hidden" name="exec" value="edtLanTemperoSobras">
            <input type="hidden" name="lanDat" value="<?=$dadosTempero["LAN_DAT"]?>">

            <div class="row mb-3">
                <div class="col">
                    <label for="txtReprocesso" class="form-label">Reprocesso</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="txtReprocesso" name="txtReprocesso" placeholder="0,000">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="txtDescarte" class="form-label">Descarte</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="txtDescarte" name="txtDescarte" placeholder="0,000">
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="txtObs" class="form-label">Observações</label>
                    <textarea class="form-control" id="txtObs" name="txtObs" rows="3"></textarea>
                </div>
            </div>

            <!-- Botões Finais -->

            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-square"></i> Confirmar</button>
                    <a class="btn btn-danger" href="index.php?p=lanTemperos"><i class="bi bi-x-square"></i> Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $("#selectLinha").on("change", () => {
        $.ajax({
            method: "GET",
            dataType: 'json',
            url: "ajax.temperoSabor.php",
            data: {selectLinha: $("#selectLinha").val()}
        }).done(function(json) {
            $("#selectSabor").empty();
            $.each(json, function(key, value) {
                $("#selectSabor").append(new Option(value, key));
            });
        });
    });

    function pesoTotal() {
        var pesoInicial = $("#txtPesoInicial").val().replaceAll(".", "").replaceAll(",", ".");
        pesoInicial = pesoInicial.length > 0 ? parseFloat(pesoInicial) : 0;
        var pesoFinal = $("#txtPesoFinal").val().replaceAll(".", "").replaceAll(",", ".");
        pesoFinal = pesoFinal.length > 0 ? parseFloat(pesoFinal) : 0;
        var pesoTotal = pesoInicial - pesoFinal;

        $("#txtPesoTotal").val(pesoTotal.toLocaleString("pt-br", {minimumFractionDigits: 3}));
    }

    $("#txtPesoInicial").on("blur", pesoTotal);
    $("#txtPesoFinal").on("blur", pesoTotal);

    // Carrega Valores
    $("#selectLinha").val("<?=$dadosTempero["TEM_LIN"]?>");
    $("#selectLinha").trigger("change");

    $("#txtDataProducao").val("<?=$dadosTempero["LAN_DAT"]?>");
    $("#txtPesoInicial").val("<?=floatToStr($dadosTempero["LAN_PES_INI"], 3)?>");
    $("#txtPesoFinal").val("<?=floatToStr($dadosTempero["LAN_PES_FIM"], 3)?>");
    $("#txtReprocesso").val("<?=floatToStr($dadosTempero["SOB_REP_KG"], 3)?>");
    $("#txtDescarte").val("<?=floatToStr($dadosTempero["SOB_DES_KG"], 3)?>");
            
    pesoTotal();

    // Estava com problemas para carregar o valor padrão no segundo select
    // carregado a partir do ajax. Só consegui fazer assim, talvez tenha algum
    // jeito melhor. Tentei com o evento window load é o último a ser chamado, depois do ready
    // Achei este ajaxComplete, parece ter funcionado
    $(document).on("ajaxComplete", function() {
        $("#selectSabor").val("<?=$dadosTempero["TEM_COD"]?>");
    });

    $(document).ready(function(){
        $("#selectLinha").trigger("change");

        // Máscaras
        $("#txtPesoInicial").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtPesoFinal").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtReprocesso").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtDescarte").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
    });
    
</script>