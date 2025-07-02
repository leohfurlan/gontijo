<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/ativosgado/ativo'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Novo Lote de Gado
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
                                        <a href="<?php echo admin_url('gestaofinanceira/ativosgado/download_sample'); ?>">
                                            Baixar Ficheiro de Exemplo
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- FIM DOS BOTÕES -->

                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <!-- Resumo do Rebanho -->
                        <div class="row">
                            <?php 
                                $cores = ['Garrotes' => 'primary', 'Novilhas' => 'success', 'Bezerros' => 'info', 'Vacas' => 'warning', 'Touros' => 'danger'];
                                if(isset($summary) && is_array($summary['categorias'])) {
                                    foreach ($summary['categorias'] as $cat) { ?>
                                    <div class="col-md-2">
                                        <div class="panel panel-<?php echo $cores[$cat['categoria']] ?? 'default'; ?>">
                                            <div class="panel-body text-center">
                                                <h3><?php echo $cat['total_cabecas']; ?></h3>
                                                <p class="text-muted"><?php echo $cat['categoria']; ?></p>
                                                <small><?php echo app_format_money($cat['valor_total'], get_base_currency()); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php } } ?>
                             <div class="col-md-2">
                                <div class="panel panel-default">
                                    <div class="panel-body text-center">
                                        <h3 class="text-primary"><?php echo $summary['total_geral_cabecas'] ?? 0; ?></h3>
                                        <p class="text-muted">Total de Cabeças</p>
                                        <small class="text-success"><?php echo app_format_money($summary['total_geral_valor'] ?? 0, get_base_currency()); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="hr-panel-heading" />
                        
                        <!-- Tabela de Ativos -->
                        <table class="table dt-table" id="tabela-ativos-gado">
                            <thead>
                                <th>ID</th>
                                <th>Descrição do Lote</th>
                                <th>Data de Entrada</th>
                                <th>Categoria</th>
                                <th>Qtd. Cabeças</th>
                                <th>Custo Total</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                                <?php if(isset($ativos_gado)) { foreach($ativos_gado as $ativo) { ?>
                                    <tr>
                                        <td><?php echo $ativo['id']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('gestaofinanceira/ativosgado/ativo/' . $ativo['id']); ?>">
                                                <?php echo $ativo['descricao_lote']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo _d($ativo['data_entrada']); ?></td>
                                        <td><?php echo $ativo['categoria']; ?></td>
                                        <td><?php echo $ativo['quantidade_cabecas']; ?></td>
                                        <td><?php echo app_format_money($ativo['custo_total_aquisicao'], get_base_currency()); ?></td>
                                        <td><?php echo get_status_badge($ativo['status_lote']); ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/ativosgado/ativo/' . $ativo['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/ativosgado/delete/' . $ativo['id'], 'remove', 'btn-danger _delete'); ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
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
                <h4 class="modal-title">Importar Lotes de Gado (.csv, .xls, .xlsx)</h4>
            </div>
            <?php echo form_open_multipart(admin_url('gestaofinanceira/ativosgado/upload'), ['id' => 'import-form']); ?>
            <div class="modal-body">
                <p>Para garantir uma importação correta, o seu ficheiro deve ter as colunas na ordem correta. Use o ficheiro de exemplo como guia.</p>
                <hr />
                <?php echo render_input('arquivo_ativos_gado', 'Selecione o Ficheiro', '', 'file'); ?>
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
        $('#tabela-ativos-gado').DataTable({
            "language": app.options.datatables_lang,
            "order": [[0, "desc"]]
        });
    });
</script>
</body>
</html>
