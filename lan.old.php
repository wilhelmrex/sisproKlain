<?php
    $dadosLanc = getLanc($lanCod);
    $dadosProd = getProd($dadosLanc["PRD_COD"]);

    // Unidade de Medida
    $prdUnidade = $dadosProd["PRD_UN"] == "U" ? "Unidades" : "Massadas";

    // Cálculos
    // Tempo
    $tempoTotal = "00:00:00";
    $tempoMassada = "00:00:00";
    $massadaMin = 0;
    $massadasHora = 0;
    $kgHora = 0;
    
    if ( ($dadosLanc["LAN_INI"] != "00:00:00") && ($dadosLanc["LAN_FIM"] != "00:00:00") )
    {
        $lanIni = new DateTime($dadosLanc["LAN_INI"]);
        $lanFim = new DateTime($dadosLanc["LAN_FIM"]);       
        $lanTempo = $lanIni->diff($lanFim);
        
        $tempoTotal = $lanTempo->format("%H:%I:%S") . " (" . timeToFloat($lanTempo->format("%H:%I:%S"))  . " min)";

        $tempoMassada = floatToTime(timeToFloat($lanTempo->format("%H:%I")) / $dadosLanc["LAN_UN"]);

        $massadaMin = number_format(round($dadosLanc["LAN_UN"] / (timeToFloat($lanTempo->format("%H:%I"))), 2), 2, ",", ".");

        $massadasHora = number_format(round($dadosLanc["LAN_UN"] / (timeToFloat($lanTempo->format("%H:%I")) / 60), 2), 2, ",", ".");

        $kgHora = number_format(round($dadosLanc["LAN_KG"] / (timeToFloat($lanTempo->format("%H:%I")) / 60), 2), 2, ",", ".");
    }
?>

<div class="row text-secondary">
    <div class="col">
        <p class="fs-4 fw-normal">Produção de <?=$dadosProd["PRD_DES"]?> em <?=date("d/m/Y", strtotime($dadosLanc["LAN_DAT"]))?></p>
    </div>
</div>

<div class="row mb-3 text-secondary">
    <div class="col-4">
        <p class="fs-5 border-bottom text-danger">Dados Gerais</p>
        <table class="table table-sm table-striped table-hover">
        <tbody>
            <tr><th scope="col">Início Produção</th><td><?=$dadosLanc["LAN_INI"]?></td></tr>
            <tr><th scope="col">Fim Produção</th><td><?=$dadosLanc["LAN_FIM"]?></td></tr>
            <tr><th scope="col">Tempo Total</th><td><?=$tempoTotal?></td></tr>
            <tr><th scope="col">Total <?=$prdUnidade?></th><td><?=$dadosLanc["LAN_UN"]?></td></tr>
            <tr><th scope="col">Peso Total Kg</th><td><?=number_format(floatval($dadosLanc["LAN_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Tempo 1 <?=$prdUnidade?></th><td><?=$tempoMassada?></td></tr>
            <tr><th scope="col"><?=$prdUnidade?> / Hora</th><td><?=$massadasHora?></td></tr>
            <tr><th scope="col"><?=$prdUnidade?> / Min</th><td><?=$massadaMin?></td></tr>
            <tr><th scope="col">Kg / Hora</th><td><?=$kgHora?></td></tr>
            <tr><th scope="col">Núm Pessoas</th><td><?=$dadosLanc["LAN_NUM_PESS"]?></td></tr>
            <tr><th scope="col">Lote</th><td><?=$dadosLanc["LAN_LOTE"]?></td></tr>
            <tr><th scope="col">Validade</th><td><?=isset($dadosLanc["LAN_VAL"]) ? date("d/m/Y", strtotime($dadosLanc["LAN_VAL"])) : ""?></td></tr>
        </tbody>
        </table>
    </div>
    <div class="col-4">
        <p class="fs-5 border-bottom text-danger">Adicionados</p>
        <table class="table table-sm table-striped table-hover">
        <tbody>
            <tr><th scope="col">Massa Kg</th><td><?=number_format(floatval($dadosLanc["LAN_FORN_AD_MASS_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Retalhos Kg</th><td><?=number_format(floatval($dadosLanc["LAN_FORN_AD_RET_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Amido Kg</th><td><?=number_format(floatval($dadosLanc["LAN_SALG_AD_AMID_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Farinha Kg</th><td><?=number_format(floatval($dadosLanc["LAN_SALG_AD_FAR_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Reprocesso Kg</th><td><?=number_format(floatval($dadosLanc["LAN_SALG_AD_REP_KG"]), 3, ",", ".")?></td></tr>

            <tr><th scope="col">Total Kg</th><td><?=number_format(floatval($dadosLanc["LAN_FORN_AD_MASS_KG"])+floatval($dadosLanc["LAN_FORN_AD_RET_KG"])+floatval($dadosLanc["LAN_SALG_AD_AMID_KG"])+floatval($dadosLanc["LAN_SALG_AD_FAR_KG"])+floatval($dadosLanc["LAN_SALG_AD_REP_KG"]), 3, ",", ".")?></td></tr>
        </tbody>
        </table>
        <p class="fs-5 border-bottom text-danger">Sobras</p>
        <table class="table table-sm table-striped table-hover">
        <tbody>
            <tr><th scope="col">Massa Kg</th><td><?=number_format(floatval($dadosLanc["LAN_FORN_SOBR_MASS_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Reprocesso Kg</th><td><?=number_format(floatval($dadosLanc["LAN_REP_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Descarte Kg</th><td><?=number_format(floatval($dadosLanc["LAN_DES_KG"]), 3, ",", ".")?></td></tr>
            <tr><th scope="col">Perda Embalagem Kg</th><td><?=number_format(floatval($dadosLanc["LAN_PERD_EMB_KG"]), 3, ",", ".")?></td></tr>
        </tbody>
        </table>
    </div>
    <div class="col-4">
        
    </div>
</div>


<div class="row mb-3 text-secondary">
    <div class="col-4 border-bottom">
        <p class="fs-5 border-bottom text-danger">Intervalos</p>
        <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">Início</th>
            <th scope="col">Fim</th>
            <th scope="col">Motivo</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach (getInt($lanCod) as $int)
            {
                ?>
                    <tr>
                    <td><?=$int["INT_INI"]?></td>
                    <td><?=$int["INT_FIM"]?></td>
                    <td><?=$int["INT_MOT"]?></td>
                    </tr>
                <?php
            }
        ?>
        </tbody>
        </table>
    </div>

    <div class="col-4 border-bottom">
        <p class="fs-5 border-bottom text-danger">Pesagens</p>
        <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">Hora</th>
            <th scope="col">C. P. M.</th>
            <th scope="col">Peso Cru</th>
            <th scope="col">Peso Assado</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach (getPes($lanCod) as $pes)
            {
                ?>
                    <tr>
                    <td><?=$pes["LAN_HOR"]?></td>
                    <td><?=$pes["PES_FORN_CPM"]?></td>
                    <td><?=number_format(floatval($pes["PES_FORN_CRU"]), 1, ",", ".")?></td>
                    <td><?=number_format(floatval($pes["PES_FORN_ASS"]), 1, ",", ".")?></td>
                    </tr>
                <?php
            }
        ?>
        </tbody>
        </table>
    </div>
    
    <div class="col-4 border-bottom">
        <p class="fs-5 border-bottom text-danger">Temperaturas</p>
        <table class="table table-sm table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">Hora</th>
            <th scope="col">Tmp Massa</th>
            <th scope="col">Tmp Z1</th>
            <th scope="col">Tmp Z2</th>
            <th scope="col">Tmp Z3</th>
            <th scope="col">Obs</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach (getTmp($lanCod) as $tmp)
            {
                ?>
                    <tr>
                    <td><?=$tmp["LAN_HOR"]?></td>
                    <td><?=number_format(floatval($tmp["FORN_TMP_MASS"]), 1, ",", ".")?></td>
                    <td><?=number_format(floatval($tmp["FORN_TMP_Z1"]), 1, ",", ".")?></td>
                    <td><?=number_format(floatval($tmp["FORN_TMP_Z2"]), 1, ",", ".")?></td>
                    <td><?=number_format(floatval($tmp["FORN_TMP_Z3"]), 1, ",", ".")?></td>
                    <td><?=$tmp["LAN_OBS"]?></td>
                    </tr>
                <?php
            }
        ?>
        </tbody>
        </table>
    </div>
</div>