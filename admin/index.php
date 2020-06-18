<?php
include '../config.php';
auth();

$allowPages = [
    'home',
    'gerenciar_disciplina',
    'gerenciar_conteudo',
    'apagar_todo_conteudo'
];

if (!isset($_GET['f'])) {
    $page = 'home';
}
else {
    $page = $_GET['f'];

    if (!in_array($page, $allowPages) || !is_file($page . '.php')) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <title><?= TITULO ?> - Admin </title>

    <link rel="stylesheet" type="text/css" href="../css/candy-box.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css" />

    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/jquery.md5.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/upload.js"></script>
    <script src="../js/admin.js"></script>
</head>
<body>

    <div class="container">

        <!-- Navbar -->
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1><img src="../img/logo.png"> Admin</h1>
                </div>
                <div class="navbar navbar-inverse">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse navbar-inverse-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="./">Home</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Gerenciar Conteúdo<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="./?f=gerenciar_disciplina">Gerenciar Disciplinas</a></li>
                                    <li><a href="./?f=gerenciar_conteudo">Gerenciar Conteúdo</a></li>
                                    <li class="divider"></li>
                                    <li><a href="./?f=apagar_todo_conteudo">Apagar Todo Conteúdo</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="./autentica.php">Sair</a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

       


        <!-- Containers -->
        <?php include ("{$page}.php") ?>


    </div>

</body>
</html>