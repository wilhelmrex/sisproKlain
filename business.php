<?php
    session_start();

    // Funções úteis
    require_once("util.php");

    // Transforma REQUEST em variáveis
    extract($_REQUEST);


    /**********************************
     * Login
     */
    if ($_REQUEST["exec"] == "login")
    {
        $ldap_host = "172.16.0.10";
        $ldap_dn = "dc=biscoitosklain,dc=local"; // Base DN for your directory
        $ldap_user = "sistemas@biscoitosklain.local"; // Bind DN (user with search permissions)
        $ldap_password = "Klain5000$"; // Password for the bind DN

        // Connect to LDAP server
        $ldap_conn = ldap_connect($ldap_host);

        if ($ldap_conn)
        {
            // Set LDAP options (optional, but recommended for better behavior)
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

            // Bind to the LDAP directory
            $ldap_bind = ldap_bind($ldap_conn, "$txtUserAD@biscoitosklain.local", $txtPassAD);

            if ($ldap_bind)
            {
                ldap_close($ldap_conn);
                $_SESSION["userAD"] = $txtUserAD;
                header("Location: index.php");
            }
            else
            {
                ldap_close($ldap_conn);
                header("Location: login.php?alertMsg=" . urlencode("Usuário ou Senha Incorretos!") . "&alertType=danger");
                exit;
            }
        }
        else
        {
            header("Location: login.php?alertMsg=" . urlencode("Falha ao consultar o AD!") . "&alertType=danger");
            exit;
        }
    }

    /**********************************
     * Editar Lançamento
     */
    if ($_REQUEST["exec"] == "edtLan")
    {
        // Faz um backup dos valores atuais
        $arrayBackup = [];

        $arrayBackup["LANCAMENTO"] = getLanc($lanCod);
        $arrayBackup["INTERVALOS"] = getInt($lanCod);
        $arrayBackup["PESAGENS"] = getPes($lanCod);
        $arrayBackup["TEMPERATURAS"] = getTmp($lanCod);

        $arrayBackup["NOVO"] = $_REQUEST;

        $sqlInsertLog = $PDOMysql->prepare("
        INSERT INTO prdlog (log_dat, log_usr, log) VALUES (?, ?, ?)
        ");
        $sqlInsertLog->execute(array(date("Y-m-d H:i:s"), $_SESSION["userAD"], var_export($arrayBackup, true)));

        // Atualiza
        $sqlUpdateLanc = $PDOMysql->prepare("
        UPDATE prdlan SET lan_dat = ?, lan_ini = ?, lan_fim = ?, lan_un = ?, lan_kg = ?, lan_lote = ?, lan_val = ?, lan_rep_kg = ?, lan_des_kg = ?, lan_num_pess = ?, lan_perd_emb_kg = ?, lan_obs = ?, lan_pac_540 = ?, lan_pac_196 = ?,lan_pac_un_loja = ?, lan_forn_ad_mass_kg = ?, lan_forn_ad_ret_kg = ?, lan_forn_sobr_mass_kg = ?, lan_salg_ad_amid_kg = ?, lan_salg_ad_rep_kg = ?, lan_salg_ad_far_kg = ?, lan_atu = ?, lan_reg = ? WHERE lan_cod = ?
        ");
        $sqlUpdateLanc->execute(array($txtData, $txtLanIni, $txtLanFim, strToFloat($txtTotUn), strToFloat($txtTotKg), $txtLote, strlen($txtValidade) > 0 ? $txtValidade : null, strToFloat($txtReprocessoKg), strToFloat($txtDescarteKg), intval($txtNumPess), strToFloat($txtPerdaEmbKg), $txtObs, intval($txtPotes540), intval($txtPotes196), intval($txtUnidLoja), strToFloat($txtAdMassaKg), strToFloat($txtAdRetKg), strToFloat($txtSobraMassaKg), strToFloat($txtAdAmidoKg), strToFloat($txtAdReprocessoKg), strToFloat($txtAdFarinhaKg), date("Y-m-d H:i:s"), $txtRegua, $lanCod));

        // Remove Intervalos, Pesagens e Temperaturas
        $sqlDelete = $PDOMysql->prepare("
        DELETE FROM prdint WHERE lan_cod = ?
        ");
        $sqlDelete->execute(array($lanCod));

        $sqlInsertInt = $PDOMysql->prepare("
        INSERT INTO prdint (lan_cod, int_ini, int_fim, int_mot, int_cons)
        VALUES (?, ?, ?, ?, ?)
        ");

        $sqlDelete = $PDOMysql->prepare("
        DELETE FROM prdpes WHERE lan_cod = ?
        ");
        $sqlDelete->execute(array($lanCod));

        $sqlInsertPes = $PDOMysql->prepare("
        INSERT INTO prdpes (lan_cod, lan_hor, lan_obs, pes_s_cob, pes_c_cob, pes_forn_cru, pes_forn_ass, pes_forn_cpm)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");        
        
        $sqlDelete = $PDOMysql->prepare("
        DELETE FROM prdtmp WHERE lan_cod = ?
        ");
        $sqlDelete->execute(array($lanCod));

        $sqlInsertTmp = $PDOMysql->prepare("
        INSERT INTO prdtmp (lan_cod, lan_hor, forn_tmp_mass, forn_tmp_z1, forn_tmp_z2, forn_tmp_z3, lan_obs)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ");        

        for ($i = 0; $i <= 9; $i++)
        {
            // Intervalos
            if (strlen($_REQUEST["txtIntHoraIni$i"]) > 0)
            {
                $sqlInsertInt->execute(array($lanCod, $_REQUEST["txtIntHoraIni$i"], $_REQUEST["txtIntHoraFim$i"], $_REQUEST["txtIntMot$i"], $_REQUEST["txtIntConsidera$i"] ?? $_REQUEST["txtIntConsidera$i"]));
            }

            // Pesagens
            if (strlen($_REQUEST["txtPesHor$i"]) > 0)
            {
                $sqlInsertPes->execute(array($lanCod, $_REQUEST["txtPesHor$i"], $_REQUEST["txtPesObs$i"], strToFloat($_REQUEST["txtPesSCob$i"]), strToFloat($_REQUEST["txtPesCCob$i"]), strToFloat($_REQUEST["txtPesCru$i"]), strToFloat($_REQUEST["txtPesAss$i"]), intval($_REQUEST["txtPesCPM$i"])));
            }

            // Temperaturas
            if (strlen($_REQUEST["txtTmpHor$i"]) > 0)
            {
                $sqlInsertTmp->execute(array($lanCod, $_REQUEST["txtTmpHor$i"], strToFloat($_REQUEST["txtTmpMassa$i"]), strToFloat($_REQUEST["txtTmpZ1$i"]), strToFloat($_REQUEST["txtTmpZ2$i"]), strToFloat($_REQUEST["txtTmpZ3$i"]), $_REQUEST["txtTmpObs$i"]));
            }
        }

        header("Location: index.php?p=lan&lanCod=$lanCod");
        exit;
    }

    /**********************************
     * Add Tempero
     */
    if ($_REQUEST["exec"] == "addTem")
    {
        $sqlAddTem = $PDOMysql->prepare("
        INSERT INTO temcad (tem_lin, tem_sab, tem_ativo) VALUES (?, ?, ?)
        ");
        $sqlAddTem->execute(array($txtLinha, $txtSabor, $selectAtivo));

        header("Location: index.php?p=temperos");
        exit;
    }

    /**********************************
     * Edt Tempero
     */
    if ($_REQUEST["exec"] == "edtTem")
    {
        $sqlEdtTem = $PDOMysql->prepare("
        UPDATE temcad SET tem_lin = ?, tem_sab = ?, tem_ativo = ? WHERE tem_cod = ?
        ");
        $sqlEdtTem->execute(array($txtLinha, $txtSabor, $selectAtivo, $temCod));

        header("Location: index.php?p=temperos");
        exit;
    }

    /**********************************
     * Edt Lançamento Tempero
     */
    if ($_REQUEST["exec"] == "edtLanTempero")
    {
        // Faz backup dos dados atuais
        $sqlTempero = $PDOMysql->prepare("
        SELECT * FROM temlan t INNER JOIN temcad t2 ON t.tem_cod = t2.tem_cod LEFT JOIN temsob t3 ON t.lan_dat = t3.sob_dat WHERE t.lan_cod = ?
        ");
        $sqlTempero->execute(array($lanCod));

        $sqlInsertLog = $PDOMysql->prepare("
        INSERT INTO prdlog (log_dat, log_usr, log) VALUES (?, ?, ?)
        ");
        $sqlInsertLog->execute(array(date("Y-m-d H:i:s"), $_SESSION["userAD"], var_export($sqlTempero->fetch(), true)));

        // Atualiza o lançamento
        $sqlUpdate = $PDOMysql->prepare("
        UPDATE temlan SET lan_dat = ?, tem_cod = ?, lan_pes_ini = ?, lan_pes_fim = ? WHERE lan_cod = ?
        ");
        $sqlUpdate->execute(array($txtDataProducao, $selectSabor, strToFloat($txtPesoInicial), strToFloat($txtPesoFinal), $lanCod));

        header("Location: index.php?p=lanTemperos");
        exit;
    }

    /**********************************
     * Edt Lançamento Tempero Sobras
     */
    if ($_REQUEST["exec"] == "edtLanTemperoSobras")
    {
        // Verifica se já existe lançamento do dia
        $sqlLancDia = $PDOMysql->prepare("SELECT * FROM temsob WHERE sob_dat = ?");
        $sqlLancDia->execute(array($lanDat));
        $dados = $sqlLancDia->fetch();

        if ($dados)
        {
            // Faz backup tbm
            $sqlInsertLog = $PDOMysql->prepare("
            INSERT INTO prdlog (log_dat, log_usr, log) VALUES (?, ?, ?)
            ");
            $sqlInsertLog->execute(array(date("Y-m-d H:i:s"), $_SESSION["userAD"], var_export($dados, true)));           

            $sqlUpdateDia = $PDOMysql->prepare("UPDATE temsob SET sob_rep_kg = ?, sob_des_kg = ?, sob_obs = ? WHERE sob_dat = ?");
            $sqlUpdateDia->execute(array(strToFloat($txtReprocesso), strToFloat($txtDescarte), $txtObs, $lanDat));            
        }
        else
        {
            $sqlInsertDia = $PDOMysql->prepare("INSERT INTO temsob (sob_dat, sob_rep_kg, sob_des_kg, sob_obs) VALUES (?, ?, ?, ?)");
            $sqlInsertDia->execute(array($lanDat, strToFloat($txtReprocesso), strToFloat($txtDescarte), $txtObs));            
        }        

        header("Location: index.php?p=lanTemperos");
        exit;
    }

    /**********************************
     * Edt Lançamento Gordura
     */
    if ($_REQUEST["exec"] == "edtLanGordura")
    {
        // Faz backup
        $sqlGor = $PDOMysql->prepare("SELECT * FROM gorlan WHERE gor_cod = ?");
        $sqlGor->execute(array($gorCod));
        $dadosGor = $sqlGor->fetch();

        $sqlInsertLog = $PDOMysql->prepare("
        INSERT INTO prdlog (log_dat, log_usr, log) VALUES (?, ?, ?)
        ");
        $sqlInsertLog->execute(array(date("Y-m-d H:i:s"), $_SESSION["userAD"], var_export($dadosGor, true)));           

        // Atualiza
        $sqlUpdate = $PDOMysql->prepare("UPDATE gorlan SET gor_dat = ?, gor_lin = ?, gor_pes_ini = ?, gor_pes_fim = ? WHERE gor_cod = ?");
        $sqlUpdate->execute(array($txtDataLancamento, $selectLinha, strToFloat($txtPesoInicial), strToFloat($txtPesoFinal), $gorCod));            

        header("Location: index.php?p=lanGordura");
        exit;
    }    

    /**********************************
     * Add Motivo
     */
    if ($_REQUEST["exec"] == "addMot")
    {
        $sqlAddMot = $PDOMysql->prepare("
        INSERT INTO motcad (mot_set, mot_des, mot_ativo) VALUES (?, ?, ?)
        ");
        $sqlAddMot->execute(array($selectSetor, $txtDescricao, $selectAtivo));

        header("Location: index.php?p=motIntervalos");
        exit;
    }

    /**********************************
     * Edt Motivo
     */
    if ($_REQUEST["exec"] == "edtMot")
    {
        $sqlEdtMot = $PDOMysql->prepare("
        UPDATE motcad SET mot_set = ?, mot_des = ?, mot_ativo = ? WHERE mot_cod = ?
        ");
        $sqlEdtMot->execute(array($selectSetor, $txtDescricao, $selectAtivo, $motCod));

        header("Location: index.php?p=motIntervalos");
        exit;
    }
?>