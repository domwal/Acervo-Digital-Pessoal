<?php

/**
 * corta texto
 * @param string $text
 * @param int $nWords
 * @param string $type f or r f=forward and r=rewind
 * @return string
 */
function cutText($text, $nWords=50, $type='f') {
    $array = explode(' ', $text);
    $total = count($array);
    $newArray = [];

    if ($type == 'f') {
        $count = 0;
        foreach ($array as $value) {
            $newArray[] = $value;
            if ($count++ >= $nWords) {
                if ($count <= $total) {
                    $newArray[] = ' ...';
                }
                break;
            }
        }
    }
    elseif ($type == 'r') {
        $array = array_reverse($array);
        $count = 0;
        foreach ($array as $value) {
            $newArray[] = $value;
            if ($count++ >= $nWords) {
                if ($count <= $total) {
                    $newArray[] = ' ...';
                }
                break;
            }
        }

        $newArray = array_reverse($newArray);
    }

    return implode(' ', $newArray);
}

/**
 * retorna os termos da pesquisa em Array
 * @param string $searchString
 * @return array $termsArray
 */
function extractSearchTerms($searchString='') {
    // tres ou mais letras, até duas serao ignoradas nos termos de pesquisa, exceto uma frase entre aspas 
    $stopWordsArray = ['das', 'dos', 'que'];
    $matches = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", $searchString, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

    $termsArray = [];
    if (count(explode(' ', $searchString))> 1) {
        $termsArray[] = trim(preg_replace("/[^\p{L}\p{N} \-]+/u", '', $searchString));
    }
    foreach ($matches as $value) {
        $value = trim(preg_replace("/[^\p{L}\p{N} \-]+/u", '', $value));

        // algumas palavras serão ignorados
        if (strlen($value) >= 3 && !in_array($value, $stopWordsArray) && !in_array($value, $termsArray)) {
            $termsArray[] = $value;
        }
    }

    return $termsArray;
}

/**
 * retorna o trecho do texto de acordo com o termos
 * @param array $termsArray
 * @param string $text
 * @return array $resultsArray
 */
function resultByTerms($termsArray, $text) {
    $resultsArray = [];
    foreach ($termsArray as $termo) {
        $termo = removeAccents($termo);
        $termo = preg_quote($termo);
        $textNoAccent = removeAccents($text);
        $resultArray = [];
        if (preg_match_all("/(\b{$termo}\b)/ui", $textNoAccent, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $key => $value) {
                $resultsArray[] = cutText(mb_substr(mb_substr($text, 0, $value[1]), -1000), 100, 'r') . cutText(mb_substr($text, $value[1], 1000), 100);
            }
        }
    }

    return $resultsArray;
}

/**
 * converte o texto, remove os acentos das palavras
 * @param string $text
 * @return array $resultsArray
 */
function removeAccents($text) {
    $lwac = ['á','é','í','ó','ú','â','ê','î','ô','û','ã','õ','à','ç','Á','É','Í','Ó','Ú','Â','Ê','Î','Ô','Û','Ã','Õ','À','Ç'];
    $lnac = ['a','e','i','o','u','a','e','i','o','u','a','o','a','c','A','E','I','O','U','A','E','I','O','U','A','O','A','C'];

    return str_replace($lwac, $lnac, $text);
}

/**
 * verifica se está logado
 */
function auth() {
    if (!$_SESSION["autenticado"]) {
        header("location: login.php");
        exit();
    }
}

/**
 * remove caracteres indesejados
 * @param string $text
 * @return string
 */
function filterHash($text) {
    return preg_replace('/[^A-Za-z0-9]/', '', $text);
}

/**
 * remove caracteres indesejados
 * @param string $text
 * @return string
 */
function filterUploadedFileName($text) {
    $text = removeAccents($text);
    $text = preg_replace('/[^A-Za-z0-9 \-_\.]/', '', $text);
    $text = strtolower(substr($text, -150));

    return $text;
}

/**
 * remove arquivos temporarios
 * @param string $dir
 * @param array $listType lista de tipos que serao removidos
 * @param int $time segundos de idade que foi criado
 */
function removeOldFiles($dir='./tmp/', $listType=['.pdf'], $time=86400) {
    $listType = array_map('strtolower', $listType);
    foreach (glob($dir."*") as $file) {
        if (time() - filectime($file) > $time && in_array(strtolower(substr($file, -4)), $listType)) {
            @unlink($file);
        }
    }
}

/**
 * converte o arquivo pdf para o texto formatado sem os caracteres indesejados
 * @param string $arquivo
 * @param string $path
 * @return string $text
 */
function pdfToFullTextContent($arquivo, $path) {

    $text = '';
    if (stripos(PHP_OS, 'linux') === 0 && ENABLE_PDFTOTEXT == 1) {
        // code for Linux
        shell_exec("echo '' > '{$path}output.txt'");
        shell_exec("/usr/bin/pdftotext -layout -enc Latin1 '{$path}{$arquivo}' '{$path}output.txt'");
        $text = file_get_contents($path . 'output.txt');
        $text = trim($text);
    }


    if (stripos(PHP_OS, 'win') === 0 || stripos(PHP_OS, 'darwin') === 0 || !$text) {
        // code for windows || code for OS X

        // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($path . $arquivo);
        $text = $pdf->getText();
    }
    
    $text = \ForceUTF8\Encoding::toUTF8($text);
    $text = preg_replace("/[^\p{L}\p{N} \:\-\.\,\?\=\(\)\+\*\&\%\$\#\@\!\º\_]+/u", ' ', $text);
    // remove multiple spaces
    $text = preg_replace('!\s+!', ' ', $text);

    return $text;
}

/**
 * apaga todo o conteudo indexado nesse site (arquivos pdf e os dados do Banco de Dados)
 */
function removeAllContent() {
    global $db;

    $sqlStr = [
        "SET FOREIGN_KEY_CHECKS = 0",
        "TRUNCATE `disciplina`",
        "TRUNCATE `conteudo`",
        "SET FOREIGN_KEY_CHECKS = 1",
    ];

    try {
        foreach ($sqlStr as $value) {
            $result = $db->rawQuery($value);
        }
    } catch (Exception $e) {
        return 0;
    }
    return 1;
}


