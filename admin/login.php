<!DOCTYPE html>
<html>
<head>
    <title>Admin - Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="../css/candy-box.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css" />
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/admin.js"></script>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1><img src="../img/logo.png"> Admin</h1>
                </div>
            </div>
        </div>


 







        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-6">
                <div class="login">
                    <div class="login-classic">
                        <form class="form-login" name="form_admin_login" id="form_admin_login" method="post" action="autentica.php">
                            <h2>Nome de usuário e Senha</h2>
                            <span class="error-msg-login">
                                <?= (isset($_GET['error']) && $_GET['error'] == 1) ? "* Nome de usuário/senha inválido(s)" : '' ?>
                            </span>

                            <fieldset class="usuario">
                                <input type="text" name="usuario" placeholder="Nome de Usuário">
                            </fieldset>

                            <fieldset class="senha">
                                <input type="password" name="senha" placeholder="Senha">
                            </fieldset>

                            <div>
                                <button class="btn btn-primary btn-lg">
                                    Continuar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
            </div>
        </div>

    </div>

</body>
</html>



