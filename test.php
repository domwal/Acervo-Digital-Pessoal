<?php
include 'config.php';
$error = 0;

if (!function_exists('mb_substr')) {
    echo('<li>Ative a extensao: mbstring no php.ini ou instale: sudo apt-get install php7.3-mbstring</li>');
    $error = 1;
}

chdir('admin');
if (!is_writable(ADM_TMP_PATH)) {
    echo('<li>O diretório : (' . ADM_TMP_PATH . ') não tem permissão de escrita</li>');
    $error = 1;
}

if (!is_writable(ADM_FILE_PATH)) {
    echo('<li>O diretório : (' . ADM_FILE_PATH . ') não tem permissão de escrita</li>');
    $error = 1;
}




if (!$error) {
    echo "---- OK ----";
}