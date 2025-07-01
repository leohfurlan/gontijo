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
