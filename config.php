<?php
header('Content-Type: text/html; charset=utf-8');


ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
set_time_limit(300);

/*
 habilitar a conversão do pdf usando o aplicativo do linux (0 | 1)
 para instalar:
 # yum install poppler-utils
 # sudo apt-get install poppler-utils
*/
define('ENABLE_PDFTOTEXT', 1);

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'acervo_digital_pessoal');
define('DB_PORT', '3306');

define('TITULO', 'Acervo Digital Pessoal');

define("ADM_TMP_PATH", './tmp/');
define("ADM_FILE_PATH", '../files/');


// /admin credenciais
define('LOGIN', 'admin');
// senha padrao é "admin", se desejar trocar, use o md5
define('PASS', '21232f297a57a5a743894a0e4a801fc3');

session_start();

if (!isset($_SESSION["autenticado"])) {
    $_SESSION["autenticado"] = 0;
}

include 'classes/MysqliDb.php';
include 'classes/PDF2Text.php';

include 'lib.php';

// $db = new MysqliDb ($conn);
$db = new MysqliDb (
    [
        'host'     => DB_HOST,
        'username' => DB_USER,
        'password' => DB_PASS,
        'db'       => DB_NAME,
        'port'     => DB_PORT,
        'prefix'   => '',
        'charset'  => 'utf8'
    ]
);

