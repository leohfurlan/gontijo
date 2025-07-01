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
