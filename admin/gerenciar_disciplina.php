<?php
if (!isset($allowPages)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}


$sqlQueryStr = "SELECT * FROM disciplina";
$result = $db->rawQuery($sqlQueryStr);

?>

<div class="row">
    <div class="col-lg-12">
        <div class="jumbotron">
            <p>
                <span>Listagem das disciplinas cadastradas</span>
                <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#myModal" id="btnAdicionarNovo">Adicionar Novo</button>
            </p>


            


            <!-- Tables -->
            <div class="row">
                <div class="col-lg-12">

                    <?php if ($db->count) { ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php foreach ($result as $key => $row) { ?>
                             <tr>
                                <td style="width: 5%"><?=$row['id']?></td>
                                <td><?=$row['nome']?></td>
                                <td style="width: 15%">
                                    <button type="button" class="btn btn-primary btn-xs btnEditar" data-toggle="modal" data-target="#myModal" data-codigo="<?=$row['id']?>" data-tipo="retornar-disciplina">Editar</button> 
                                    <button type="button" class="btn btn-danger btn-xs btnExcluir" data-toggle="modal" data-target="#confirm" data-codigo="<?=$row['id']?>">Excluir</button></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>

                </div>
            </div>

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




<div id="confirm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Disciplina</h4>
            </div>
            <div class="modal-body">
                Confirma remover essa disciplina e todo seu conteúdo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-codigo="0" data-tipo="deletar-disciplina" id="btnConfirmarExcluir">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- tem que ser o ultimo form da pagina -->
<div id="myModal" class="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Disciplina</h4>
            </div>
            <div class="modal-body">



                <div class="row">
                    <div class="col-lg-12">
                        <div class="well">
                            <div class="row" id="help-block"></div>
                            <form class="form-horizontal" name="formDisciplina" id="formDisciplina">
                                <input type="hidden" name="a" value="salvar-disciplina">
                                <input type="hidden" name="inputId" value="0">
                                <fieldset>
                                    <legend>* Campos obrigatórios</legend>
                                    <div class="form-group" id="nome-group">
                                        <label for="inputNome" class="col-lg-2 control-label">Nome *</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" name="inputNome" placeholder="Nome" required="required">
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onblur="location.reload();">Fechar</button>
                <button type="button" class="btn btn-primary" id="btnSaveDisciplina">Salvar</button>
            </div>
        </div>
    </div>
</div>
