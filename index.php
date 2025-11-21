<?php
    session_start();

    if (!isset($_SESSION["userAD"]))
    {
        header("Location: login.php");
        exit;
    }

    extract($_REQUEST);

    // Funções úteis
    require_once("util.php");
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="<?=isset($refresh) ? $refresh : ""?>">
    <title>Sistema de Produção</title>
    <link rel="icon" type="image/png" href="icon.png">
    <link href="bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap-icons-1.13.1/bootstrap-icons.min.css" rel="stylesheet">
    <link href="js/DataTables/datatables.min.css?t=<?=time()?>" rel="stylesheet">
    <script src="bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/DataTables/datatables.min.js"></script>    
</head>
<body>
<nav class="navbar sticky-top bg-body-tertiary navbar-expand-lg shadow mb-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?refresh=60">
            <img src="logo.png" alt="Logo" width="64" class="d-inline-block align-text-middle"> Sistema de Produção
        </a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php?refresh=60">Início</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Cadastros</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php?p=temperos">Temperos</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Lançamentos</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php?p=home">Produção</a></li>
                    <li><a class="dropdown-item" href="index.php?p=lanGordura">Gordura</a></li>
                    <li><a class="dropdown-item" href="index.php?p=lanTemperos">Temperos</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Estatísticas</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php?p=statsTabela">Tabela de Dados</a></li>
                    <li><a class="dropdown-item" href="index.php?p=statsTmpMedio">Tempo Médio</a></li>
                </ul>
            </li>
        </ul>
        <div class="d-flex">
            <span class="navbar-text me-2"><i class="bi bi-person"></i> <?=$_SESSION["userAD"]?></span>
            &nbsp;
            <span class="me-2"><a href="login.php" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-title="Sair do Sistema"><i class="bi bi-box-arrow-right" ></i></a></span>
        </div>
    </div>
</nav>

<div class="container-fluid">

<!-- Modal Carregando -->
<div class="modal" id="modalCarregando" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="spinner-border text-primary"></div>
            </div>
        </div>
    </div>
</div>

<?php
    // Área reservada para mostrar uma mensagem
    if (isset($_REQUEST["alertMsg"]))
    {
        ?>
        <div class="row mt-3 mb-3">
            <div class="col">
                <div class="alert alert-<?=$_REQUEST["alertType"]?>"><?=$_REQUEST["alertMsg"]?></div>
            </div>   
        </div>
        <?php
    }

    // Conteúdo
    $pagina = isset($_REQUEST["p"]) ? $_REQUEST["p"] : "home";
    require_once("$pagina.php");
?>
</div>

<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

// Light or Dark Mode
// if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
//     $("html").attr("data-bs-theme", "dark");
// }
</script>

</body>
</html>