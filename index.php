<?php
include 'config.php';

$listaMateriaArray = $db->get('disciplina', 100);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./favicon.ico" type="image/x-icon" />
    <title><?= TITULO ?></title>

    <link rel="stylesheet" type="text/css" href="css/candy-box.css" />
    <link rel="stylesheet" type="text/css" href="css/autocomplete.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/autocomplete.js"></script>
    <script src="js/jquery.highlight-5.closure.js"></script>
    <script src="js/cookie.js"></script>
    <script src="js/global.js"></script>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <img src="img/logo.png">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="well">
                    <form class="form-horizontal" name="form_busca" id="form_busca" method="get" autocomplete="off">

                        <fieldset>
                        <legend>Busca</legend>

                        <div class="form-group ">
                            <div class="col-lg-12">
                                <input class="form-control" type="text" name="search" id="search" value="<?= isset($_GET['search']) ? str_replace('"', "&quot;", $_GET['search']) : '' ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-4">
                                <strong>Disciplina:</strong>
                                <select name="disciplina" class="form-control" id="disciplina">
                                    <option value="0"> -- TODOS --</option>
                                    <?php if (count($listaMateriaArray)) { ?>
                                        <?php foreach ($listaMateriaArray as $value) { ?>
                                            <?php if (isset($_GET['disciplina']) && $_GET['disciplina'] == $value['id']) { $selected = 'selected'; } else { $selected = ''; } ?>
                                            <option value="<?=$value['id']?>" <?=$selected?>><?=$value['nome']?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">Pesquisar</button>
                                <a href="./">Limpar Campos</a>
                            </div>
                        </div>

                        </fieldset>

                    </form>
                </div>
            </div>
        </div>

        <?php
        if (isset($_GET['search']) && $_GET['search']) {

            $idMateria = (isset($_GET['disciplina'])) ? intval($_GET['disciplina']) : 0;

            $searchText = ($_GET['search']);
            $searchText = preg_replace("/[^\p{L}\p{N} \+\-*\"]+/u", ' ', $searchText);
            $searchText = trim(preg_replace('!\s+!', ' ', $searchText));

            $searchMateria = '';
            if ($idMateria) {
                $searchMateria .= " AND id_disciplina = '$idMateria'";
            }

            // full text search
            $sqlQueryStr = "
                SELECT
                    a.id,
                    a.titulo,
                    a.texto,
                    b.nome AS nome_disciplina 
                FROM
                    conteudo a
                    LEFT JOIN disciplina b ON a.id_disciplina = b.id 
                WHERE
                    MATCH ( a.texto ) AGAINST ( '{$searchText}' IN BOOLEAN MODE ) $searchMateria 
                    LIMIT 0,25
            ";

            // echo '<pre>' . $sqlQueryStr . '</pre>';
            $terms = extractSearchTerms($searchText);
            $result = $db->rawQuery($sqlQueryStr);

            ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-dismissable alert-warning">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Termos:</h4>
                        <p>
                            <li class="termos-busca"><?= implode('</li><li class="termos-busca">', $terms) ?></li>
                        </p>
                    </div>
                </div>
            </div>

            <?php
            if ($db->count) {
                foreach ($result as $key => $row) {
                    $listaResultadoFinal = [];
                    if ($searchText) {

                        $listaResultadoFinal = resultByTerms($terms, $row['texto']);
                        $totalEncontrado = count($listaResultadoFinal);
                    ?>


                    <div class="panel panel-default">
                        <div class="panel-heading">Total: <?= $totalEncontrado ?> | <?= $row["nome_disciplina"] ?> | <a href="<?= 'files/'. md5($row["id"]) . '.pdf'?>" target="_bank"><?=$row['titulo']?></a></div>
                        <div class="panel-body resultado-busca">
                            <?php foreach ($listaResultadoFinal as $key => $value) : ?>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?= $value ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>



                    <?php
                    }
                }
            } else {
                echo "<br><strong>Nenhum Registro Encontrado</strong>";
            }
        }
        ?>
    </div>


</body>
</html>