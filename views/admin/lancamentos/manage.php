<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/lancamentos/lancamento'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Novo Lançamento
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
                                        <a href="<?php echo admin_url('gestaofinanceira/lancamentos/download_sample'); ?>">
                                            Baixar Ficheiro de Exemplo
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- FIM DOS BOTÕES -->

                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <table class="table dt-table" id="tabela-lancamentos">
                            <thead>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Categoria</th>
                                <th>Centro de Custo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                                <?php foreach($lancamentos as $lancamento) { ?>
                                    <tr class="<?php echo get_overdue_class($lancamento['data_vencimento'], $lancamento['status']); ?>">
                                        <td><?php echo $lancamento['id']; ?></td>
                                        <td>
                                            <?php if ($lancamento['tipo_conta'] == 'Receita') {
                                                echo '<span class="label label-success">Receita</span>';
                                            } else {
                                                echo '<span class="label label-danger">Despesa</span>';
                                            } ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo admin_url('gestaofinanceira/lancamentos/lancamento/' . $lancamento['id']); ?>">
                                                <?php echo $lancamento['descricao']; ?>
                                            </a>
                                            <?php if(!empty($lancamento['entidade_nome'])) {
                                                echo '<div class="row-options">' . $lancamento['entidade_nome'] . '</div>';
                                            } ?>
                                        </td>
                                        <td><?php echo format_currency_br($lancamento['valor']); ?></td>
                                        <td><?php echo _d($lancamento['data_vencimento']); ?></td>
                                        <td><?php echo $lancamento['nome_conta']; ?></td>
                                        <td><?php echo $lancamento['centro_custo_nome']; ?></td>
                                        <td><?php echo get_status_badge($lancamento['status']); ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/lancamentos/lancamento/' . $lancamento['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/lancamentos/delete/' . $lancamento['id'], 'remove', 'btn-danger _delete'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

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
                <h4 class="modal-title">Importar Lançamentos (.csv, .xls, .xlsx)</h4>
            </div>
            <?php echo form_open_multipart(admin_url('gestaofinanceira/lancamentos/upload'), ['id' => 'import-form']); ?>
            <div class="modal-body">
                <p>Para garantir uma importação correta, o seu ficheiro deve ter as colunas na ordem correta. Use o ficheiro de exemplo como guia.</p>
                <hr />
                <?php echo render_input('arquivo_lancamentos', 'Selecione o Ficheiro', '', 'file'); ?>
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
<script>
    $(function() {
        $('#tabela-lancamentos').DataTable({
            "language": app.options.datatables_lang,
            "order": [[4, "desc"]] // Ordenar pela data de vencimento
        });
    });
</script>
</body>
</html>
