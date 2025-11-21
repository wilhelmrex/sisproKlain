<script>
    const myModal = new bootstrap.Modal('#modalCarregando');
    myModal.show();

</script>
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

        if ($dadosLanc["LAN_UN"] > 0)
            $tempoMassada = floatToTime(timeToFloat($lanTempo->format("%H:%I")) / $dadosLanc["LAN_UN"]);

        $massadaMin = number_format(round($dadosLanc["LAN_UN"] / (timeToFloat($lanTempo->format("%H:%I"))), 2), 2, ",", ".");

        $massadasHora = number_format(round($dadosLanc["LAN_UN"] / (timeToFloat($lanTempo->format("%H:%I")) / 60), 2), 2, ",", ".");

        $kgHora = number_format(round($dadosLanc["LAN_KG"] / (timeToFloat($lanTempo->format("%H:%I")) / 60), 2), 2, ",", ".");
    }
?>

<div class="row text-secondary">
    <div class="col">
        <div class="text-secondary"><small>Lançamento: #<?=$lanCod?></small></div>
        <div class="fs-4 fw-normal">Produção: <?=$dadosProd["PRD_DES"]?> em <?=date("d/m/Y", strtotime($dadosLanc["LAN_DAT"]))?></div>
        <div class="text-secondary">Atualizado em: <?=isset($dadosLanc["LAN_ATU"]) ? date("d/m/Y H:i:s", strtotime($dadosLanc["LAN_ATU"])) : ""?></div>
    </div>
</div>

<form name="frmLanc" method="post" action="business.php" method="post">
<input type="hidden" name="exec" value="edtLan">
<input type="hidden" name="lanCod" value="<?=$lanCod?>">

<div class="row">
    <div class="col">
        <ul class="nav nav-tabs" id="tabLan">
            <li class="nav-item">
                <button class="nav-link active" id="tabDadosGerais" data-bs-toggle="tab" data-bs-target="#divDadosGerais" type="button">Dados Gerais</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tabIntervalos" data-bs-toggle="tab" data-bs-target="#divIntervalos" type="button">Intervalos</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tabPesagens" data-bs-toggle="tab" data-bs-target="#divPesagens" type="button">Pesagens</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tabTemperaturas" data-bs-toggle="tab" data-bs-target="#divTemperaturas" type="button">Temperaturas</button>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            
            <!-- Dados Gerais -->
            
            <div class="tab-pane fade show active" id="divDadosGerais" tabindex="0">
                <div class="row mt-3 mb-3">
                    <div class="col-4">
                        <p class="border-bottom text-danger">Resumo</p>

                        <!-- Data Produção -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtData" class="col-form-label">Data Produção</label>
                            </div>
                            <div class="col-6">
                                <input type="date" id="txtData" name="txtData" class="form-control form-control-sm" required="required" value="<?=$dadosLanc["LAN_DAT"]?>">
                            </div>
                        </div>

                        <!-- Data Produção -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtRegua" class="col-form-label">Régua</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtRegua" name="txtRegua" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_REG"]?>">
                            </div>
                        </div>

                        <!-- Início Produção -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtLanIni" class="col-form-label">Início Produção</label>
                            </div>
                            <div class="col-6">
                                <input type="time" id="txtLanIni" name="txtLanIni" class="form-control form-control-sm" required="required" value="<?=$dadosLanc["LAN_INI"]?>">
                            </div>
                        </div>
                        
                        <!-- Fim Produção -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtLanFim" class="col-form-label">Fim Produção</label>
                            </div>
                            <div class="col-6">
                                <input type="time" id="txtLanFim" name="txtLanFim" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_FIM"]?>">
                            </div>
                        </div>

                        <!-- Tempo Total -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTempoTotal" class="col-form-label">Tempo Total</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtTempoTotal" class="form-control form-control-sm bg-light" readonly="readonly" value="<?=$tempoTotal?>">
                            </div>
                        </div>

                        <!-- Total Unidades / Massadas -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTotUn" class="col-form-label"><strong>Total <?=$prdUnidade?></strong></label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtTotUn" name="txtTotUn" class="form-control form-control-sm border-dark" value="<?=number_format(floatval($dadosLanc["LAN_UN"]), 3, ",", ".")?>" placeholder="0,000">
                            </div>
                        </div>

                        <!-- Peso Total Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTotKg" class="col-form-label">Peso Total Kg</label>
                            </div>
                            <?php
                                // Valida se o peso total está registrado corretamente na tabela
                                // Recalcula novamente com as pesagens atuais
                                $col = 6;
                                $totalKgRecalc = (floatval($dadosLanc["LAN_UN"]) * floatval($dadosProd["PRD_PES_UN_KG"])) + floatval($dadosLanc["LAN_FORN_AD_MASS_KG"]) + floatval($dadosLanc["LAN_FORN_AD_RET_KG"]) + floatval($dadosLanc["LAN_SALG_AD_AMID_KG"]) + floatval($dadosLanc["LAN_SALG_AD_FAR_KG"]) + floatval($dadosLanc["LAN_SALG_AD_REP_KG"]);
                                if (round($totalKgRecalc, 3) != round(floatval($dadosLanc["LAN_KG"]), 3)) // Para evitar comparação de todas as casas
                                    $col = 3;
                            ?>
                            <div class="col-<?=$col?>">
                                <input type="text" id="txtTotKg" name="txtTotKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_KG"]), 3, ",", ".")?>">
                            </div>
                            <?php
                                if ($col == 3)
                                {
                                    ?>
                            <div class="col-3">
                                <input type="text" class="form-control form-control-sm border-danger text-danger bg-light" value="<?=number_format($totalKgRecalc, 3, ",", ".")?>">
                            </div>
                                    <?php
                                }
                            ?>
                        </div>

                        <!-- Tempo 1 Unidade / Massada -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTempoUnidade" class="col-form-label">Tempo / <?=$prdUnidade?></label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtTempoUnidade" class="form-control form-control-sm bg-light" readonly="readonly" value="<?=$tempoMassada?>">
                            </div>
                        </div>

                        <!-- Unidade / Massada P/ Hora -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTempoUnidadeHora" class="col-form-label"><?=$prdUnidade?> / Hora</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtTempoUnidadeHora" class="form-control form-control-sm bg-light" readonly="readonly" value="<?=$massadasHora?>">
                            </div>
                        </div>

                        <!-- Unidade / Massada P/ Min -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTempoUnidadeMin" class="col-form-label"><?=$prdUnidade?> / Min</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtTempoUnidadeMin" class="form-control form-control-sm bg-light" readonly="readonly" value="<?=$massadaMin?>">
                            </div>
                        </div>

                        <!-- Kg / Hora -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtKgHora" class="col-form-label">Kg / Hora</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtKgHora" class="form-control form-control-sm bg-light" readonly="readonly" value="<?=$kgHora?>">
                            </div>
                        </div>

                        <!-- Núm Pessoas -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtNumPess" class="col-form-label">Núm Pessoas</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtNumPess" name="txtNumPess" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_NUM_PESS"]?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <p class="border-bottom text-danger">Lote e Validade</p>
            
                        <!-- Lote -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtLote" class="col-form-label">Lote</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtLote" name="txtLote" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_LOTE"]?>">
                            </div>
                        </div>

                        <!-- Validade -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtValidade" class="col-form-label">Validade</label>
                            </div>
                            <div class="col-6">
                                <input type="date" id="txtValidade" name="txtValidade" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_VAL"]?>">
                            </div>
                        </div>

                        <p class="border-bottom text-danger mt-3">Sobras</p>

                        <!-- Reprocesso Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtReprocessoKg" class="col-form-label">Reprocesso Kg</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtReprocessoKg" name="txtReprocessoKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_REP_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Descarte Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtDescarteKg" class="col-form-label">Descarte Kg</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtDescarteKg" name="txtDescarteKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_DES_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Sobra Massa Forno Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtSobraMassaKg" class="col-form-label">Sobra Massa Cilindro Kg</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtSobraMassaKg" name="txtSobraMassaKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_FORN_SOBR_MASS_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Perda Embalagem Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtPerdaEmbKg" class="col-form-label">Perda Embalagem Kg</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtPerdaEmbKg" name="txtPerdaEmbKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_PERD_EMB_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <p class="border-bottom text-danger mt-3">Informações Adicionais</p>

                        <!-- Potes -->
                        <div class="row">
                            <div class="col-2">
                                <label for="txtPotes540" class="col-form-label">Potes 540g</label>
                            </div>
                            <div class="col-2">
                                <input type="number" id="txtPotes540" name="txtPotes540" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_PAC_540"]?>" min="0" max="99999" step="1">
                            </div>
                            <div class="col-2">
                                <input type="number" id="totalPotes540" class="form-control form-control-sm bg-light" value="<?=intval($dadosLanc["LAN_PAC_540"]) * 40?>" readonly="readonly">
                            </div>
                            <div class="col-2">
                                <label for="txtPotes196" class="col-form-label">Potes 196g</label>
                            </div>
                            <div class="col-2">
                                <input type="number" id="txtPotes196" name="txtPotes196" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_PAC_196"]?>" min="0" max="99999" step="1">
                            </div>
                            <div class="col-2">
                                <input type="number" id="totalPotes196" class="form-control form-control-sm bg-light" value="<?=intval($dadosLanc["LAN_PAC_196"]) * 14?>" readonly="readonly">
                            </div>
                            <div class="col-2">
                                <label for="txtUnidLoja" class="col-form-label">Un. Loja</label>
                            </div>
                            <div class="col-2">
                                <input type="number" id="txtUnidLoja" name="txtUnidLoja" class="form-control form-control-sm" value="<?=$dadosLanc["LAN_PAC_UN_LOJA"]?>" min="0" max="99999" step="1">
                            </div>
                        </div>

                    </div>
        
                    <div class="col-4">
                        <p class="border-bottom text-danger">Adicionados</p>

                        <!-- Massa Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtAdMassaKg" class="col-form-label">Massa Kg (Forno)</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtAdMassaKg" name="txtAdMassaKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_FORN_AD_MASS_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Retalhos Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtAdRetKg" class="col-form-label">Retalhos Kg (Forno)</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtAdRetKg" name="txtAdRetKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_FORN_AD_RET_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Amido Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtAdAmidoKg" class="col-form-label">Amido Kg (Salgados)</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtAdAmidoKg" name="txtAdAmidoKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_SALG_AD_AMID_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Farinha Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtAdFarinhaKg" class="col-form-label">Farinha Kg (Salgados)</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtAdFarinhaKg" name="txtAdFarinhaKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_SALG_AD_FAR_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Farinha Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtAdReprocessoKg" class="col-form-label">Reprocesso Kg (Salgados)</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtAdReprocessoKg" name="txtAdReprocessoKg" class="form-control form-control-sm" value="<?=number_format(floatval($dadosLanc["LAN_SALG_AD_REP_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <!-- Total Kg -->
                        <div class="row">
                            <div class="col-6">
                                <label for="txtTotalAdKg" class="col-form-label">Total Kg</label>
                            </div>
                            <div class="col-6">
                                <input type="text" id="txtTotalAdKg" class="form-control form-control-sm bg-light" readonly="readonly" value="<?=number_format(floatval($dadosLanc["LAN_FORN_AD_MASS_KG"])+floatval($dadosLanc["LAN_FORN_AD_RET_KG"])+floatval($dadosLanc["LAN_SALG_AD_AMID_KG"])+floatval($dadosLanc["LAN_SALG_AD_FAR_KG"])+floatval($dadosLanc["LAN_SALG_AD_REP_KG"]), 3, ",", ".")?>">
                            </div>
                        </div>

                        <p class="border-bottom text-danger mt-3">Observações</p>

                        <!-- Observações -->

                        <div class="row">
                            <div class="col">
                                <textarea class="form-control" id="txtObs" name="txtObs" rows="4"><?=$dadosLanc["LAN_OBS"]?></textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        
            <!-- Intervalos -->
            <div class="tab-pane fade" id="divIntervalos" tabindex="0">
                <div class="row">
                    <div class="col">
                        <div class="row mb-3 mt-3">
                            <div class="col-1">Considerar</div>
                            <div class="col-1">Hora Início</div>
                            <div class="col-1">Hora Fim</div>
                            <div class="col-1">Parada</div>
                            <div class="col">Motivo</div>
                        </div>        
                        <?php
                            $baseDateTime = new DateTime('@0'); // Unix epoch
                            $baseConsDateTime = new DateTime('@0'); // Unix epoch

                            $dadosInt = getInt($lanCod);
                            for ($i = 0; $i < 10; $i++)
                            {
                                unset($diff);
                                $checked = "";
                                if ( isset($dadosInt[$i]["INT_INI"]) && isset($dadosInt[$i]["INT_FIM"]) )
                                {
                                    $iniTime = new DateTime($dadosInt[$i]["INT_INI"]);
                                    $endTime = new DateTime($dadosInt[$i]["INT_FIM"]);
                                    $diff = $endTime->diff($iniTime);
                                    $baseDateTime->add($diff);

                                    if ( isset($dadosInt[$i]["INT_CONS"]) && ($dadosInt[$i]["INT_CONS"] == "S") )
                                    {
                                        $baseConsDateTime->add($diff);
                                        $checked = "checked";
                                    }
                                }
                                ?>
                                <div class="row mb-2">
                                    <div class="col-1"><input type="checkbox" class="form-check-input" id="txtIntConsidera<?=$i?>" name="txtIntConsidera<?=$i?>" value="S" <?=$checked?> data-bs-toggle="tooltip" data-bs-title="Marque se é pra descontar do tempo de produção"></div>
                                    <div class="col-1"><input type="time" class="form-control form-control-sm" id="txtIntHoraIni<?=$i?>" name="txtIntHoraIni<?=$i?>" value="<?=isset($dadosInt[$i]["INT_INI"]) ? $dadosInt[$i]["INT_INI"] : ""?>"></div>
                                    <div class="col-1"><input type="time" class="form-control form-control-sm" id="txtIntHoraFim<?=$i?>" name="txtIntHoraFim<?=$i?>" value="<?=isset($dadosInt[$i]["INT_FIM"]) ? $dadosInt[$i]["INT_FIM"] : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm bg-light" id="txtIntDiff<?=$i?>" value="<?=isset($diff) ? $diff->format("%H:%I:%S") : ""?>" readonly="readonly"></div>
                                    <div class="col"><input type="text" class="form-control form-control-sm" id="txtIntMot<?=$i?>" name="txtIntMot<?=$i?>" value="<?=isset($dadosInt[$i]["INT_MOT"]) ? $dadosInt[$i]["INT_MOT"] : ""?>"></div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col">
                        <?php
                            $summedInterval = $baseDateTime->diff(new DateTime('@0'));
                            $summedConsInterval = $baseConsDateTime->diff(new DateTime('@0'));
                        ?>
                        Tempo Total de Parada: <?=isset($summedInterval) ? $summedInterval->format("%H:%I:%S") : ""?> / 
                        Tempo Considerado de Parada: <?=isset($summedConsInterval) ? $summedConsInterval->format("%H:%I:%S") : ""?>
                    </div>
                </div>
            </div>

            <!-- Pesagens -->
            <div class="tab-pane fade" id="divPesagens" tabindex="0">
                <div class="row">
                    <div class="col">
                        <div class="row mb-3 mt-3">
                            <div class="col-1">Hora</div>
                            <div class="col-1">C. P. M.</div>
                            <div class="col-1">Peso Cru</div>
                            <div class="col-1">Peso Assado</div>
                            <div class="col-1">% Evaporação</div>
                            <div class="col-1">Peso C/ Cob</div>
                            <div class="col-1">Peso S/ Cob</div>
                            <div class="col-1">Dif Peso</div>
                            <div class="col">Observações</div>
                        </div>        
                        <?php
                            $dadosPes = getPes($lanCod);
                            for ($i = 0; $i < 10; $i++)
                            {
								$percEvap = 0;
								if (isset($dadosPes[$i]["PES_FORN_CRU"]) && isset($dadosPes[$i]["PES_FORN_ASS"]))
									if (floatval($dadosPes[$i]["PES_FORN_CRU"]) > 0 && floatval($dadosPes[$i]["PES_FORN_ASS"]) > 0)
										$percEvap = (($dadosPes[$i]["PES_FORN_CRU"] - $dadosPes[$i]["PES_FORN_ASS"]) / $dadosPes[$i]["PES_FORN_CRU"]) * 100;

                                $difPeso = 0;
                                if (isset($dadosPes[$i]["PES_S_COB"]) && isset($dadosPes[$i]["PES_C_COB"]))
                                    $difPeso = floatval($dadosPes[$i]["PES_C_COB"]) - floatval($dadosPes[$i]["PES_S_COB"]);

                                ?>
                                <div class="row mb-2">
                                    <div class="col-1"><input type="time" class="form-control form-control-sm" id="txtPesHor<?=$i?>" name="txtPesHor<?=$i?>" value="<?=isset($dadosPes[$i]["LAN_HOR"]) ? $dadosPes[$i]["LAN_HOR"] : ""?>"></div>
                                    <div class="col-1"><input type="number" class="form-control form-control-sm" id="txtPesCPM<?=$i?>" name="txtPesCPM<?=$i?>" value="<?=isset($dadosPes[$i]["PES_FORN_CPM"]) ? $dadosPes[$i]["PES_FORN_CPM"] : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtPesCru<?=$i?>" name="txtPesCru<?=$i?>" value="<?=isset($dadosPes[$i]["PES_FORN_CRU"]) ? number_format($dadosPes[$i]["PES_FORN_CRU"], 3, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtPesAss<?=$i?>" name="txtPesAss<?=$i?>" value="<?=isset($dadosPes[$i]["PES_FORN_ASS"]) ? number_format($dadosPes[$i]["PES_FORN_ASS"], 3, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm bg-light" value="<?=floatToStr($percEvap)?>" readonly="readonly"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtPesSCob<?=$i?>" name="txtPesSCob<?=$i?>" value="<?=isset($dadosPes[$i]["PES_S_COB"]) ? number_format($dadosPes[$i]["PES_S_COB"], 3, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtPesCCob<?=$i?>" name="txtPesCCob<?=$i?>" value="<?=isset($dadosPes[$i]["PES_C_COB"]) ? number_format($dadosPes[$i]["PES_C_COB"], 3, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm bg-light" value="<?=number_format($difPeso, 3, ",", ".")?>" readonly="readonly"></div>
                                    <div class="col"><input type="text" class="form-control form-control-sm" id="txtPesObs<?=$i?>" name="txtPesObs<?=$i?>" value="<?=isset($dadosPes[$i]["LAN_OBS"]) ? $dadosPes[$i]["LAN_OBS"] : ""?>"></div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Temperaturas -->
            <div class="tab-pane fade" id="divTemperaturas" tabindex="0">
                <div class="row">
                    <div class="col">
                        <div class="row mb-3 mt-3">
                            <div class="col-1">Hora</div>
                            <div class="col-1">Tmp. Massa</div>
                            <div class="col-1">Tmp. Z1</div>
                            <div class="col-1">Tmp. Z2</div>
                            <div class="col-1">Tmp. Z3</div>
                            <div class="col">Observações</div>
                        </div>        
                        <?php
                            $dadosTmp = getTmp($lanCod);
                            for ($i = 0; $i < 10; $i++)
                            {
                                ?>
                                <div class="row mb-2">
                                    <div class="col-1"><input type="time" class="form-control form-control-sm" id="txtTmpHor<?=$i?>" name="txtTmpHor<?=$i?>" value="<?=isset($dadosTmp[$i]["LAN_HOR"]) ? $dadosTmp[$i]["LAN_HOR"] : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtTmpMassa<?=$i?>" name="txtTmpMassa<?=$i?>" value="<?=isset($dadosTmp[$i]["FORN_TMP_MASS"]) ? number_format($dadosTmp[$i]["FORN_TMP_MASS"], 1, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtTmpZ1<?=$i?>" name="txtTmpZ1<?=$i?>" value="<?=isset($dadosTmp[$i]["FORN_TMP_Z1"]) ? number_format($dadosTmp[$i]["FORN_TMP_Z1"], 1, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtTmpZ2<?=$i?>" name="txtTmpZ2<?=$i?>" value="<?=isset($dadosTmp[$i]["FORN_TMP_Z2"]) ? number_format($dadosTmp[$i]["FORN_TMP_Z2"], 1, ",", ".") : ""?>"></div>
                                    <div class="col-1"><input type="text" class="form-control form-control-sm" id="txtTmpZ3<?=$i?>" name="txtTmpZ3<?=$i?>" value="<?=isset($dadosTmp[$i]["FORN_TMP_Z3"]) ? number_format($dadosTmp[$i]["FORN_TMP_Z3"], 1, ",", ".") : ""?>"></div>
                                    <div class="col"><input type="text" class="form-control form-control-sm" id="txtTmpObs<?=$i?>" name="txtTmpObs<?=$i?>" value="<?=isset($dadosTmp[$i]["LAN_OBS"]) ? $dadosTmp[$i]["LAN_OBS"] : ""?>"></div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">    
    <div class="col border-top p-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="checkSalvar">
            <label class="form-check-label" for="checkSalvar">Confirma alteração?</label>
        </div>
        <button type="submit" id="btnSubmit" class="btn btn-primary" disabled="disabled"><i class="bi bi-check-square"></i> Salvar</button>
    </div>
</div>

</form>

<script>
    $(document).ready(function() {
        $("#txtTotUn").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtTotKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtReprocessoKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtDescarteKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtSobraMassaKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtPerdaEmbKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtAdMassaKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtAdRetKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtAdAmidoKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtAdFarinhaKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtAdReprocessoKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtTotalAdKg").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});

        <?php
            for ($i = 0; $i < 10; $i++)
            {
            ?>
        $("#txtPesCru<?=$i?>").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtPesAss<?=$i?>").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtPesCCob<?=$i?>").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtPesSCob<?=$i?>").mask("#.##0,000", {reverse: true, clearIfNotMatch: true});
        $("#txtTmpMassa<?=$i?>").mask("#0,0", {reverse: true, clearIfNotMatch: true});
        $("#txtTmpZ1<?=$i?>").mask("#0,0", {reverse: true, clearIfNotMatch: true});
        $("#txtTmpZ2<?=$i?>").mask("#0,0", {reverse: true, clearIfNotMatch: true});
        $("#txtTmpZ3<?=$i?>").mask("#0,0", {reverse: true, clearIfNotMatch: true});
            <?php
            }
        ?>

        $("#checkSalvar").click(function() {
            $("#btnSubmit").attr("disabled", !this.checked);
        });

        myModal.hide();

    });
</script>