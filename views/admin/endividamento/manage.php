<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('gestaofinanceira/endividamento/contrato'); ?>" class="btn btn-info pull-left display-block">
                                <i class="fa fa-plus-circle"></i> Novo Contrato
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <!-- KPIs de Resumo do Endividamento -->
                        <div class="row mbot15">
                            <div class="col-md-3 col-xs-6 border-right">
                                <h3 class="bold"><?php echo $summary['total_contratos']; ?></h3>
                                <span class="text-info">Contratos Ativos</span>
                            </div>
                            <div class="col-md-3 col-xs-6 border-right">
                                <h3 class="bold"><?php echo app_format_money($summary['valor_total_original'], get_base_currency()); ?></h3>
                                <span class="text-primary">Valor Original</span>
                            </div>
                            <div class="col-md-3 col-xs-6 border-right">
                                <h3 class="bold text-success"><?php echo app_format_money($summary['valor_pago'], get_base_currency()); ?></h3>
                                <span class="text-success">Total Pago</span>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <h3 class="bold text-danger"><?php echo app_format_money($summary['saldo_devedor'], get_base_currency()); ?></h3>
                                <span class="text-danger">Saldo Devedor</span>
                            </div>
                        </div>
                        <hr />

                        <h4 class="no-margin">Lista de Contratos</h4>
                        <table class="table dt-table" id="tabela-contratos">
                            <thead>
                                <th>Nº Contrato</th>
                                <th>Credor</th>
                                <th>Descrição</th>
                                <th>Valor Contratado</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </thead>
                            <tbody>
                                <?php foreach($contratos as $contrato) { ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo admin_url('gestaofinanceira/endividamento/contrato/' . $contrato['id']); ?>">
                                                <?php echo $contrato['numero_contrato']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $contrato['credor_nome']; ?></td>
                                        <td><?php echo $contrato['descricao']; ?></td>
                                        <td><?php echo app_format_money($contrato['valor_contrato'], get_base_currency()); ?></td>
                                        <td><?php echo _d($contrato['data_contratacao']); ?></td>
                                        <td><?php echo get_status_badge($contrato['status']); ?></td>
                                        <td>
                                            <?php echo icon_btn('gestaofinanceira/endividamento/contrato/' . $contrato['id'], 'pencil-square-o'); ?>
                                            <?php echo icon_btn('gestaofinanceira/endividamento/delete/' . $contrato['id'], 'remove', 'btn-danger _delete'); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr />
                        <h4 class="no-margin text-danger">Parcelas a Vencer (Próximos 30 dias)</h4>
                        <div class="table-responsive">
                            <table class="table table-striped mtop15">
                                <thead>
                                    <th>Contrato</th>
                                    <th>Credor</th>
                                    <th>Nº Parcela</th>
                                    <th>Vencimento</th>
                                    <th class="text-right">Valor</th>
                                </thead>
                                <tbody>
                                    <?php foreach($parcelas_vencendo as $parcela): ?>
                                    <tr>
                                        <td><?php echo $parcela['contrato_descricao']; ?></td>
                                        <td><?php echo $parcela['credor_nome']; ?></td>
                                        <td><?php echo $parcela['numero_parcela']; ?></td>
                                        <td><?php echo _d($parcela['data_vencimento']); ?></td>
                                        <td class="text-right"><?php echo app_format_money($parcela['valor_parcela'], get_base_currency()); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($parcelas_vencendo)): ?>
                                    <tr><td colspan="5" class="text-center">Nenhuma parcela a vencer no período.</td></tr>
                                    <?php endif; ?>
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
        $('#tabela-contratos').DataTable({
            "language": app.options.datatables_lang,
            "order": [[4, "desc"]]
        });
    });
</script>
</body>
</html>
