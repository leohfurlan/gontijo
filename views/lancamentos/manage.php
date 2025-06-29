<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/lancamentos/create'); ?>" class="btn btn-info pull-left display-block">
                                Novo Lançamento
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="table-responsive">
                            <table class="table table-striped" id="tabela-lancamentos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Categoria</th>
                                        <th>Centro de Custo</th>
                                        <th>Status</th>
                                        <th>Ações</th> </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lancamentos as $lancamento) : ?>
                                        <tr>
                                            <td><?php echo $lancamento['id']; ?></td>
                                            <td><?php echo ($lancamento['tipo'] == 'receita') ? '<span class="label label-success">Receita</span>' : '<span class="label label-danger">Despesa</span>'; ?></td>
                                            <td><?php echo $lancamento['descricao']; ?></td>
                                            <td><?php echo app_format_money($lancamento['valor'], get_base_currency()); ?></td>
                                            <td><?php echo _d($lancamento['data_vencimento']); ?></td>
                                            <td><?php echo $lancamento['categoria_nome']; ?></td>
                                            <td><?php echo $lancamento['centro_custo_nome']; ?></td>
                                            <td><?php echo ($lancamento['status'] == 'pago_recebido') ? '<span class="label label-info">Pago/Recebido</span>' : '<span class="label label-warning">A Pagar/Receber</span>'; ?></td>
                                            
                                            <td>
                                                <a href="<?php echo admin_url('gestaofinanceira/lancamentos/edit/' . $lancamento['id']); ?>" class="btn btn-default btn-icon" title="Editar Lançamento">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('gestaofinanceira/lancamentos/delete/' . $lancamento['id']); ?>" class="btn btn-danger btn-icon _delete" title="Excluir Lançamento">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(function() {
        $('#tabela-lancamentos').DataTable({
            "language": { "url": "<?php echo base_url('assets/plugins/datatables/i18n/' . get_datatables_locale() . '.json'); ?>" },
            "order": [[0, "desc"]],
            "columnDefs": [ { "targets": -1, "orderable": false } ] // Garante que a coluna de ações não seja ordenável
        });
    });
</script>
</body>
</html>