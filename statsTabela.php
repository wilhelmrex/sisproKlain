<div class="row mb-3 text-secondary">
    <div class="col">
        <div class="fs-4 fw-normal">Tabela de Dados</div>
    </div>
</div>

<?php
    $sqlLan = $PDOMysql->prepare("
    select * from prdlan p inner join prdcad p2 on p.prd_cod = p2.prd_cod ;
    ");
    $sqlLan->execute(); //array($prdCod, $txtDataInicial ? $txtDataInicial : "0001-01-01", $txtDataFinal ? $txtDataFinal : "9999-12-31", $txtRegua));
?>

<table id="tblLan" class="table table-sm table-striped table-hover nowrap">
    <thead>
        <tr>
            <th>Edit</th>
            <th>ID</th>
            <th>Produto</th>
            <th>Data</th>
            <th>Un/Bat Kg</th>
            <th>Kg</th>
            <th>Kg/Hora</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Tempo Total</th>
            <th>Intervalo Total</th>
            <th>Intervalo Considerado</th>
            <th>Adicionado Massa Kg</th>
            <th>Adicionado Reprocesso Kg</th>
            <th>Reprocesso Kg (Sobra Fim do Dia)</th>
            <th>Descarte Kg</th>
            <th>Sobra Massa Kg</th>
            <th>Adicionado Amido Salgados Kg</th>
            <th>Adicionado Farinha Salgados Kg</th>
            <th>Adicionado Reprocesso Salgados Kg</th>
            <th>Número Pessoas</th>
            <th>Perda Embalagem</th>
            <th>Pote 540</th>
            <th>Pote 196</th>
            <th>Unidade Loja</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($sqlLan->fetchAll() as $dadosLanc)
            {
            // tempo de produção (fim - inicio)
            if (($dadosLanc["LAN_INI"] != "00:00:00") && ($dadosLanc["LAN_FIM"] != "00:00:00"))
            {
                $lanIni = new DateTime($dadosLanc["LAN_INI"]);
                $lanFim = new DateTime($dadosLanc["LAN_FIM"]);       
                $lanTempo = $lanIni->diff($lanFim);
                
                $tempoTotal = $lanTempo->format("%H:%I:%S") . " (" . timeToFloat($lanTempo->format("%H:%I:%S"))  . " min)";

                // Converter para horas decimais
                $minutos = timeToFloat($lanTempo->format("%H:%I:%S"));
                $horasDecimais = $minutos / 60;
                $tempoTotalDecimais = number_format($horasDecimais, 2, ',', '.');
            }
            // tempo de intervalos
            $lancod = $dadosLanc["LAN_COD"];
            $sqlInt = $PDOMysql->prepare("select * from prdint p where p.lan_cod = $lancod;");
            $sqlInt->execute();
            $intervalos = $sqlInt->fetchAll();
            if (count($intervalos) == 0)
            {
                $intervalos = "00:00:00 (0 min)";
            } else 
            {
                $totalIntervalo = 0;
                $totalintervaloCons = 0;
                foreach ($intervalos as $int)
                {
                    $totalIntervalo += timeToFloat($int["INT_FIM"]) - timeToFloat($int["INT_INI"]);
                    if($int["INT_CONS"] == 'S'){
                        $totalintervaloCons += timeToFloat($int["INT_FIM"]) - timeToFloat($int["INT_INI"]);
                    }
                }
                $intervalos = floatToTime($totalIntervalo) . " (" . $totalIntervalo . " min)";
                $intervalosConss = floatToTime($totalintervaloCons) . " (" . $totalintervaloCons . " min)";
            }

            $kghora = ($dadosLanc["LAN_KG"] + $dadosLanc["LAN_REP_KG"] +$dadosLanc["LAN_FORN_AD_RET_KG"]) / $horasDecimais;
            
            ?>
            <tr>

                <td><a class="dropdown-item" data-bs-title="Verificar Lançamento" href="index.php?p=lan&lanCod=<?=$dadosLanc["LAN_COD"]?>"><i class="bi bi-pencil-square"></i></a></td>
                <td><?=$dadosLanc["LAN_COD"]?></td>
                <td><?=$dadosLanc["PRD_DES"]?></td>
                <td data-order="<?=$dadosLanc["LAN_DAT"]?>"><?=date("d/m/Y", strtotime($dadosLanc["LAN_DAT"]))?></td>
                <td><?=floatToStr($dadosLanc["LAN_UN"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_KG"], 3)?></td>
                <td><?=floatToStr($kghora)?></td>
                <td><?=$dadosLanc["LAN_INI"]?></td>
                <td><?=$dadosLanc["LAN_FIM"]?></td>
                <td><?=$tempoTotal?></td>
                <td><?=$intervalos?></td>
                <td><?=$intervalosConss?></td>
                <td><?=floatToStr($dadosLanc["LAN_FORN_AD_MASS_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_FORN_AD_RET_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_REP_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_DES_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_FORN_SOBR_MASS_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_SALG_AD_AMID_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_SALG_AD_REP_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_SALG_AD_FAR_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_NUM_PESS"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_PERD_EMB_KG"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_PAC_540"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_PAC_196"], 3)?></td>
                <td><?=floatToStr($dadosLanc["LAN_PAC_UN_LOJA"], 3)?></td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>

<script>
new DataTable('#tblLan', {
    select:true,
    scrollX: true,
    colReorder: true,

    layout: {
        top2: { buttons: ['colvis'] },
        top1: { searchPanes: { initCollapsed: true } }
    },

    columnDefs: [
        {
            targets: [1, 2, 3],      // colunas que terão filtros
            searchPanes: { show: true }
        },
        {
            targets: '_all',           // todas as outras ficam fora do SearchPanes
            searchPanes: { show: false }
        }
    ],

    order: [[3, "desc"], [1,"desc"]],
    pageLength: 25,

    language: {
        url: '//<?=$_SERVER["HTTP_HOST"]?>/js/DataTables/pt-BR.json',
    }
});
</script>
