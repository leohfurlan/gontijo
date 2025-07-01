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
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <!-- Resumo do Rebanho -->
                        <div class="row">
                            <?php 
                                $cores = ['Garrotes' => 'primary', 'Novilhas' => 'success', 'Bezerros' => 'info', 'Vacas' => 'warning', 'Touros' => 'danger'];
                                foreach ($summary['categorias'] as $cat) { ?>
                                <div class="col-md-2">
                                    <div class="panel panel-<?php echo $cores[$cat['categoria']] ?? 'default'; ?>">
                                        <div class="panel-body text-center">
                                            <h3><?php echo $cat['total_cabecas']; ?></h3>
                                            <p class="text-muted"><?php echo $cat['categoria']; ?></p>
                                            <small><?php echo format_currency_br($cat['valor_total']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                             <div class="col-md-2">
                                <div class="panel panel-default">
                                    <div class="panel-body text-center">
                                        <h3 class="text-primary"><?php echo $summary['total_geral_cabecas']; ?></h3>
                                        <p class="text-muted">Total de Cabeças</p>
                                        <small class="text-success"><?php echo format_currency_br($summary['total_geral_valor']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="hr-panel-heading" />
                        
                        <!-- Tabela de Ativos (Renderizada no PHP) -->
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
                                <?php foreach($ativos_gado as $ativo) { ?>
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
                                        <td><?php echo format_currency_br($ativo['custo_total_aquisicao']); ?></td>
                                        <td><?php echo get_status_badge($ativo['status_lote']); ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/ativosgado/ativo/' . $ativo['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/ativosgado/delete/' . $ativo['id'], 'remove', 'btn-danger _delete'); ?>
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
<?php init_tail(); ?>
<script>
    $(function() {
        // Inicializa a tabela com os dados já na página
        $('#tabela-ativos-gado').DataTable({
            "language": app.options.datatables_lang,
            "order": [[0, "desc"]]
        });
    });
</script>
</body>
</html>
