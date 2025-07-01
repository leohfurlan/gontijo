<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/contasbancarias/conta'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Nova Conta Bancária
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <table class="table dt-table" id="tabela-contas-bancarias">
                            <thead>
                                <th>ID</th>
                                <th>Banco</th>
                                <th>Agência</th>
                                <th>Conta</th>
                                <th>Centro de Custo</th>
                                <th>Saldo Inicial</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                                <?php foreach($contas_bancarias as $conta) { ?>
                                    <tr>
                                        <td><?php echo $conta['id']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('gestaofinanceira/contasbancarias/conta/' . $conta['id']); ?>">
                                                <?php echo $conta['banco']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $conta['agencia']; ?></td>
                                        <td><?php echo $conta['conta']; ?></td>
                                        <td><?php echo $conta['centro_custo_nome']; ?></td>
                                        <td><?php echo format_currency_br($conta['saldo_inicial']); ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/contasbancarias/conta/' . $conta['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/contasbancarias/delete/' . $conta['id'], 'remove', 'btn-danger _delete'); ?>
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
        $('#tabela-contas-bancarias').DataTable({
            "language": app.options.datatables_lang,
            "order": [[1, "asc"]]
        });
    });
</script>
</body>
</html>
