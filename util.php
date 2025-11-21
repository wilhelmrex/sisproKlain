<?php
    // Conexão Mysql
    $PDOMysql = new PDO("mysql:host=172.16.10.237;dbname=intranet", "intranet", "intranet");
    $PDOMysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDOMysql->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $PDOMysql->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);

    // Conexão Firebird
    $PDOFirebird = new PDO("firebird:dbname=172.16.10.15:/syspro/bd/sysdb.fbd", "SYSDBA", "S760651y");
    $PDOFirebird->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDOFirebird->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $PDOFirebird->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);

    // Converte p/ UTF-8
    /*function _($dbContent)
    {
        return mb_convert_encoding($dbContent, "UTF-8", "ISO-8859-1");
    }*/

    // Floats
    function strToFloat($value)
    {
        return floatval(str_replace(array(".", ","), array("", "."), $value));
    }

    function floatToStr($float, $decimal = 2)
    {
        return number_format(floatval($float), $decimal, ",", ".");
    }

    function getTiposProducao($tipo, $setor, $ativo = "S")
    {
        global $PDOMysql;

        $sqlProd = $PDOMysql->prepare("SELECT * FROM prdcad WHERE prd_tip = ? AND prd_set = ? AND prd_ativo = ? ORDER BY prd_des");
        $sqlProd->execute(array($tipo, $setor, $ativo));
        return $sqlProd->fetchAll();
    }

    function getLancDia($tipo, $setor, $ativo, $dia)
    {
        global $PDOMysql;

        $sqlProd = $PDOMysql->prepare("SELECT * FROM prdlan WHERE prd_cod IN (SELECT prd_cod FROM prdcad WHERE prd_tip = ? AND prd_set = ? AND prd_ativo = ?) AND lan_dat = ?");
        $sqlProd->execute(array($tipo, $setor, $ativo, $dia));
        return $sqlProd->fetchAll();
    }

    function getProd($prdCod)
    {
        global $PDOMysql;

        $sqlProd = $PDOMysql->prepare("SELECT * FROM prdcad WHERE prd_cod = ?");
        $sqlProd->execute(array($prdCod));
        return $sqlProd->fetch();
    }

    function getLanc($lanCod)
    {
        global $PDOMysql;

        $sqlLanc = $PDOMysql->prepare("SELECT * FROM prdlan WHERE lan_cod = ?");
        $sqlLanc->execute(array($lanCod));
        return $sqlLanc->fetch();
    }

    function getInt($lanCod)
    {
        global $PDOMysql;

        $sqlInt = $PDOMysql->prepare("SELECT * FROM prdint WHERE lan_cod = ? ORDER BY int_ini");
        $sqlInt->execute(array($lanCod));
        return $sqlInt->fetchAll();
    }

    function getPes($lanCod)
    {
        global $PDOMysql;

        $sqlPes = $PDOMysql->prepare("SELECT * FROM prdpes WHERE lan_cod = ? ORDER BY lan_hor");
        $sqlPes->execute(array($lanCod));
        return $sqlPes->fetchAll();
    }

    function getTmp($lanCod)
    {
        global $PDOMysql;

        $sqlPes = $PDOMysql->prepare("SELECT * FROM prdtmp WHERE lan_cod = ? ORDER BY lan_hor");
        $sqlPes->execute(array($lanCod));
        return $sqlPes->fetchAll();
    }

    function getGor()
    {
        // Basta incluir no array abaixo os valores desejados que o sistema se atualiza
        return ["A" => "Aperitivos/Bitz", "B" => "Bolinhas", "P" => "Palitinhos", "S" => "Salgadão", "T" => "Tortilhos"];
    }

    function getSet()
    {
        // Basta incluir no array abaixo os valores desejados que o sistema se atualiza
        return ["A" => "Alfajor", "P" => "Paçoca", "F" => "Forno", "S" => "Salgados"];
    }

    function timeToFloat($time)
    {
        $exp = explode(":", $time);
        $horas = intval($exp[0]);
        $min = intval($exp[1]);

        return $horas * 60 + $min;
    }

    function floatToTime($float)
    {
        $totalSeconds = intval($float * 60);

        // Calculate hours
        $horas = floor($totalSeconds / 3600);

        // Calculate remaining seconds after extracting hours
        $remainingSeconds = $totalSeconds % 3600;

        // Calculate minutes from remaining seconds
        $min = floor($remainingSeconds / 60);

        // Calculate remaining seconds after extracting minutes
        $seg = round($remainingSeconds % 60);

        return str_pad($horas, 2, "0", STR_PAD_LEFT) . ":" . str_pad($min, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seg, 2, "0", STR_PAD_LEFT);
    }

?>