<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/planocontas/conta'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Nova Conta
                            </a>
                            
                            <!-- BOTÕES DE IMPORTAÇÃO E DOWNLOAD ADICIONADOS -->
                            <div class="btn-group pull-left mleft5">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-upload"></i> Importar <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#import_modal">
                                            Importar de Ficheiro
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo admin_url('gestaofinanceira/planocontas/download_sample'); ?>">
                                            Baixar Ficheiro de Exemplo
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- FIM DOS BOTÕES -->
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <?php // CORREÇÃO: Usando uma linha de cabeçalho com divs para alinhar com a lista. ?>
                        <div class="row" style="font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="col-md-5">Código e Nome da Conta</div>
                            <div class="col-md-2">Tipo</div>
                            <div class="col-md-2">Aceita Lançamentos</div>
                            <div class="col-md-3 text-right">Ações</div>
                        </div>

                        <div class="tree-plano-contas">
                            <ul>
                                <?php
                                // Inicia a renderização recursiva da árvore
                                $this->load->view('admin/planocontas/_plano_contas_row', ['contas' => $contas]);
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JANELA MODAL DE IMPORTAÇÃO -->
<div class="modal fade" id="import_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Importar Plano de Contas</h4>
            </div>
            <?php echo form_open_multipart(admin_url('gestaofinanceira/planocontas/upload'), ['id' => 'import-form']); ?>
            <div class="modal-body">
                <p>Para garantir uma importação correta, o seu ficheiro deve ter as colunas na ordem correta. Use o ficheiro de exemplo como guia.</p>
                <hr />
                <?php echo render_input('arquivo_plano_contas', 'Selecione o Ficheiro', '', 'file'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-info">Iniciar Importação</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- FIM DA JANELA MODAL -->
 
<?php init_tail(); ?>
<style>
/* Estilos para a árvore do plano de contas */
.tree-plano-contas ul {
    list-style-type: none;
    padding-left: 20px;
    margin-left: 0;
}
.tree-plano-contas li .row {
    border-bottom: 1px solid #f0f0f0;
    padding: 8px 0;
    margin: 0;
}
.tree-plano-contas li .row:hover {
    background-color: #f5f5f5;
}
</style>
</body>
</html>
