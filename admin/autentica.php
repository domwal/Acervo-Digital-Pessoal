<?php
include '../config.php';

$error = 0;
if (isset($_POST['usuario']) && isset($_POST['senha'])) {
    if ($_POST['usuario'] === LOGIN && md5($_POST['senha']) === PASS) {
        $_SESSION["autenticado"] = 1;
        header("location: ./");
        exit();
    }
    else {
        $error = 1;
    }
}

$_SESSION["autenticado"] = 0;
header("location: login.php?error={$error}");


