<?php
if (!isset($allowPages)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

?>

<div class="row">
    <div class="col-lg-12">
        <div class="jumbotron">
            <p>
                <span>Tem certeza que deseja excluir todo conteúdo?</span>
            </p>

            <p><a class="btn btn-primary btn-lg btn-danger" data-toggle="modal" data-target="#confirm">Apagar Todo Conteudo</a></p>

        </div>
    </div>
</div>




<div id="confirm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tem Certeza?</h4>
            </div>
            <div class="modal-body">
                Confirma apagar todo o conteúdo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-codigo="0" data-tipo="reset-all-content" id="btnConfirmarExcluir">Confirmar</button>
            </div>
        </div>
    </div>
</div>

