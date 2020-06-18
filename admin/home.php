<?php
if (!isset($allowPages)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}


$sqlQueryStr = "SELECT a.nome,COUNT(b.id) AS total FROM disciplina a LEFT JOIN conteudo b ON b.id_disciplina=a.id GROUP BY a.id";
$result = $db->rawQuery($sqlQueryStr);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="jumbotron">

            <?php if ($db->count) : ?>
            <p>Listagem das disciplinas cadastradas</p>

            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-group">
                        <?php foreach ($result as $key => $row) { ?>
                            <li class="list-group-item"><span class="badge"><?=$row['total']?></span> <?=$row['nome']?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>


            <?php if (!$db->count) : ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-dismissable alert-warning">
                            <h4>Nenhum Registro Encontrado</h4>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>


