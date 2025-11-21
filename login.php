<?php
    session_start();
    session_destroy();

    extract($_REQUEST);
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Produção</title>
    <link rel="icon" type="image/png" href="icon.png">
    <link href="bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap-icons-1.13.1/bootstrap-icons.min.css" rel="stylesheet">
    <script src="bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #045ABF;
            background-position-y: -16em;
            background-image: url("bg.jpg");
            background-size: 65%;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
<div class="container-fluid">
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
?>

<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5 mt-5">
        <div class="col-lg-7 text-center text-lg-start">        
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form name="frmLogin" action="business.php" method="post" class="p-4 p-md-5 border rounded-3 bg-body-tertiary" autocomplete="off">
                <input type="hidden" name="exec" value="login">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="txtUserAD" name="txtUserAD" placeholder="Usuário" autofocus="autofocus" maxlength="50" required="required">
                    <label for="txtUserAD">Usuário</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="txtPassAD" name="txtPassAD" placeholder="Senha" required="required">
                    <label for="txtPassAD">Senha</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Acessar</button>
                <hr class="my-4" />
                <small class="text-body-secondary">Utilize seu usuário e senha de acesso ao computador.</small>
            </form>
        </div>
    </div>
</div>

</body>
</html>