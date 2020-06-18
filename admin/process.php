<?php
include '../config.php';
include '../classes/Encoding.php';
include '../vendor/autoload.php';

auth();


// **********************************************************************
// ** DISCIPLINA
// **********************************************************************

function salvarDisciplina() {
    global $db;

    $validaCampos = 1;
    $success = 0;
    $message = '';
    $errors = [];

    $id = (int)$_POST['inputId'];
    $nome = preg_replace("/[^\p{L}\p{N} ?!\.\-_:]+/u", '', $_POST['inputNome']);

    // monstar uma array dos dados a ser salvo no BD
    $data = [
        "nome" => $nome
    ];

    // validacao dos dados
    if (!$nome) {
        $validaCampos = 0;
        $errors['nome'] = 'Nome não pode ser em Branco';
    }

    // se estiver tudo certo, insere ou atualiza os dados do BD
    if ($validaCampos) {
        // se tem id, entao atualiza os dados
        if ($id) {
            $db->where ('id', $id);
            if ($db->update ('disciplina', $data)) {
                $success = 1;
                $message = 'Atualizado com sucesso';
            }
        }
        else {
            $id = $db->insert('disciplina', $data);

            if ($id) {
                $success = 1;
                $message = 'Cadastrado com sucesso';
            }
        }
    }

    return json_encode(['message' => $message, 'success' => $success, 'errors' => $errors]);
}

function retornarDisciplina() {
    global $db;

    $success = 0;
    $id = (int)$_POST['codigo'];

    $db->where ("id", $id);
    $result = $db->getOne ("disciplina", "id AS inputId, nome AS inputNome");

    if (count($result)) {
        $success = 1;
    }
    
    return json_encode(['success' => $success, 'ok' => $result]);
}

function retornarConteudo() {
    global $db;

    $success = 0;
    $id = (int)$_POST['codigo'];

    $db->where ("id", $id);
    $result = $db->getOne ("conteudo", "titulo, texto");

    if (count($result)) {
        $success = 1;
    }
    
    return json_encode(['success' => $success, 'ok' => $result]);
}

function deletarDisciplina() {
    global $db;

    $success = 0;
    $message = '';
    $id = (int)$_POST['codigo'];

    $db->where ("id", $id);
    $result = $db->getOne ("disciplina", "id");

    if (count($result)) {

        $cols = ["id"];
        $db->where ("id_disciplina", $id);
        $result = $db->get ("conteudo", null, $cols);

        $listaArquivosParaRemover = [];
        if ($db->count > 0) {
            foreach ($result as $value) {
                $listaArquivosParaRemover[] = $value['id'];
            }
        }

        $db->where('id', $id);
        if($db->delete('disciplina')) {
            $success = 1;
            $message = "Excluido com sucesso";

            // remover os arquivos anexados
            foreach ($listaArquivosParaRemover as $value) {
                $file = ADM_FILE_PATH . md5($value) . '.pdf';
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        } 

        
    }
    
    return json_encode(['success' => $success, 'message' => $message]);
}




// **********************************************************************
// ** CONTEUDO
// **********************************************************************


function uploadConteudo() {
    global $db;

    $fileUploaded = '';
    $success      = 0;
    $message      = '';

    if (isset($_FILES['file']) && isset($_POST['inputHash'])) {
        $myFile = $_FILES['file'];
        $myHash = filterHash($_POST['inputHash']);

        if ($myFile['type'] == 'application/pdf' && strlen($myHash) == 32) {
            $fileUploaded = filterUploadedFileName($myFile["name"]);
            move_uploaded_file($myFile['tmp_name'], ADM_TMP_PATH . $myHash . '_' . $fileUploaded);

            $success = 1;
            $message = 'Arquivo (' . $fileUploaded . ') foi enviado com sucesso!';
        }
    }

    return json_encode(['success' => $success, 'message' => $message]);
}

function salvarConteudo() {
    global $db;

    $validaCampos = 1;
    $success = 0;
    $message = '';
    $errors = [];

    $idDisciplina = isset($_POST['selectDisciplina']) ? (int)$_POST['selectDisciplina'] : 0;
    $myHash = isset($_POST['inputHash']) ? filterHash($_POST['inputHash']) : '';
    $myFile = (isset($_POST['inputListFiles']) && is_array($_POST['inputListFiles'])) ? $_POST['inputListFiles'] : [];

    $countUploadedFiles = count($myFile);
    $listFilesInTempDir = [];

    foreach ($myFile as $value) {
        $fileUploaded = filterUploadedFileName($value);
        if (is_file(ADM_TMP_PATH . $myHash . '_' . $fileUploaded)) {
            $listFilesInTempDir[] = $fileUploaded;
        }
    }

    // validacao dos dados
    if (!$idDisciplina) {
        $validaCampos = 0;
        $errors['disciplina'] = 'Selecione uma Disciplina';
    }
    if (!$countUploadedFiles) {
        $validaCampos = 0;
        $errors['file'] = 'Nenhum arquivo selecionado';
    }
    elseif (!count($listFilesInTempDir)) {
        $validaCampos = 0;
        $errors['file'] = 'Erro no(s) Arquivo(s), tente novamente.';
    }


    // se estiver tudo certo, insere ou atualiza os dados do BD
    if ($validaCampos) {

        foreach ($listFilesInTempDir as $value) {
            // monstar uma array dos dados a ser salvo no BD
            $data = [
                "id_disciplina" => $idDisciplina,
                "arquivo" => $value,
                "titulo" => str_replace('.pdf', '', str_replace('_', ' ', $value)),
                "texto" => pdfToFullTextContent($myHash . '_' . $value, ADM_TMP_PATH),
            ];

            $id = $db->insert('conteudo', $data);
            if ($id) {

                copy(ADM_TMP_PATH . $myHash . '_' . $value, ADM_FILE_PATH . md5($id) . '.pdf');

                $success = 1;
                $message = 'Cadastrado com sucesso';
            }
        }

        

    }

    return json_encode(['message' => $message, 'success' => $success, 'errors' => $errors]);
}

function deletarConteudo() {
    global $db;

    $success = 0;
    $message = '';
    $id = (int)$_POST['codigo'];

    $db->where('id', $id);
    if($db->delete('conteudo')) {
        $success = 1;
        $message = "Excluido com sucesso";

        // remover os arquivo anexado
        $file = ADM_FILE_PATH . md5($id) . '.pdf';
        if (is_file($file)) {
            @unlink($file);
        }
    } 

    

    
    return json_encode(['success' => $success, 'message' => $message]);
}














// **********************************************************************
// ** RESET ALL CONTENT - APAGAR TODO CONTEUDO
// **********************************************************************
function resetAllContent() {
    $success = 0;
    $message = '';

    $result = removeAllContent();

    if ($result) {
        removeOldFiles(ADM_TMP_PATH, ['.pdf'], 1);
        removeOldFiles(ADM_FILE_PATH, ['.pdf'], 1);

        $success = 1;
        $message = 'Todo conteudo foi apagado com sucesso';
    }

    return json_encode(['success' => $success, 'message' => $message]);
}



// **********************************************************************
// ** NAO ALTERAR
// **********************************************************************
$action = isset($_POST['a']) ? $_POST['a'] : '';
$action = preg_replace('/[^a-z\-]/', '', $action);
$actionArray = explode('-', $action);
$newVar = '';
$j = 0;
foreach ($actionArray as $value) {
    if ($j++ == 0) {
        $newVar .= $value;
    }
    else {
        $newVar .= ucfirst($value);
    }
}

$action = $newVar;
if (!function_exists($action)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
else {
    echo call_user_func($action);
}