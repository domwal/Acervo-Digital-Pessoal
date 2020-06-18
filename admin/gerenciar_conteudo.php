<?php
if (!isset($allowPages)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

removeOldFiles(ADM_TMP_PATH, ['.pdf'], 86400);

$sqlQueryStr = "SELECT * FROM conteudo";
$result = $db->rawQuery($sqlQueryStr);

$listaMateriaArray = $db->get('disciplina', 100);

$maxUploadSize = (int)(str_replace('M', '', ini_get('upload_max_filesize'))* 1024 * 1024);
$maxPostSize = (int)(str_replace('M', '', ini_get('post_max_size')) * 1024 * 1024);

$serverUploadMaxSize = $maxUploadSize < $maxPostSize ? $maxUploadSize : $maxPostSize;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="jumbotron">
            <p>
                <span>Listagem dos conteudos cadastrados</span>
                <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#myModal" id="btnAdicionarNovo">Adicionar Novo</button>
            </p>


            


            <!-- Tables -->
            <div class="row">
                <div class="col-lg-12">

                    <?php if (count($result)) { ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Título</th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php foreach ($result as $key => $row) { ?>
                             <tr>
                                <td style="width: 5%"><?=$row['id']?></td>
                                <td><?=$row['titulo']?></td>
                                <td style="width: 15%">
                                    <button type="button" class="btn btn-primary btn-xs btnVisualizar" data-toggle="modal" data-target="#viewModal" data-codigo="<?=$row['id']?>" data-tipo="retornar-conteudo">Visualizar</button> 
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
                <h4 class="modal-title">Conteudo</h4>
            </div>
            <div class="modal-body">
                Confirma remover essa registro e o anexo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-codigo="0" data-tipo="deletar-conteudo" id="btnConfirmarExcluir">Confirmar</button>
            </div>
        </div>
    </div>
</div>


<div id="viewModal" class="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><div id="imprime-titulo" class="data-show-content">Conteudo</div></h4>
            </div>
            <div class="modal-body">



                <div class="row">
                    <div class="col-lg-12">
                        <div class="well">
                            <div id="imprime-texto" class="data-show-content">Conteudo aqui</div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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
                <h4 class="modal-title">Conteudo</h4>
            </div>
            <div class="modal-body">



                <div class="row">
                    <div class="col-lg-12">
                        <div class="well">
                            <div class="row" id="help-block"></div>
                            <form class="form-horizontal" name="formConteudo" id="formConteudo">
                                <input type="hidden" name="inputHash" id="inputHash" value="<?= md5(microtime(true)) ?>">
                                <input type="hidden" name="serverUploadMaxSize" id="serverUploadMaxSize" value="<?= $serverUploadMaxSize ?>">
                                <input type="hidden" name="a" value="salvar-conteudo">
                                <fieldset>
                                    <legend>* Campos obrigatórios</legend>

                                    <div class="form-group" id="disciplina-group">
                                      <label class="col-lg-2 control-label" for="selectDisciplina">Disciplina</label>
                                      <div class="col-md-4">
                                        <select name="selectDisciplina" class="form-control">
                                          <option value="0"> -- ESCOLHA --</option>
                                          <?php if (count($listaMateriaArray)) { ?>
                                              <?php foreach ($listaMateriaArray as $value) { ?>
                                                  <option value="<?=$value['id']?>"><?=$value['nome']?></option>
                                              <?php } ?>
                                          <?php } ?>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="form-group" id="file-group">
                                      <label class="col-lg-2 control-label" for="fileArquivo">Arquivo</label>
                                      <div class="col-md-4">
                                        <input name="fileArquivo[]" id="fileArquivo" class="input-file" type="file" accept=".pdf" multiple>
                                      </div>
                                    </div>

                                </fieldset>

                                <div id="progress-upload-group">
                                </div>
                            </form>


                        </div>
                    </div>
                </div>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onblur="location.reload();">Fechar</button>
                <button type="button" class="btn btn-primary" id="btnSaveConteudo">Salvar</button>
            </div>
        </div>
    </div>
</div>
