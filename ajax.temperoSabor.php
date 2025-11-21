<?php
    // Funções úteis
    require_once("util.php");

    // Transforma REQUEST em variáveis
    extract($_REQUEST);

    $arraySabor = [];
    $sqlSabores = $PDOMysql->prepare("SELECT * FROM temcad WHERE tem_lin = ? AND tem_ativo = ? ORDER BY tem_sab");
    $sqlSabores->execute(array($selectLinha, "S"));
    foreach ($sqlSabores->fetchAll() as $sabor)
        $arraySabor[$sabor["TEM_COD"]] = $sabor["TEM_SAB"];
    
    echo json_encode($arraySabor);
?>