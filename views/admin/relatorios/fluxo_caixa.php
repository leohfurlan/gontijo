<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <!-- Filtros do Relatório -->
                        <?php echo form_open($this->uri->uri_string(), ['method' => 'get']); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?php echo render_date_input('data_inicio', 'Data de Início', _d($filtros['data_inicio'])); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_date_input('data_fim', 'Data de Fim', _d($filtros['data_fim'])); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('centro_custo', $centros_custo, ['id', 'nome'], 'Centro de Custo', $filtros['centro_custo']); ?>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-info">Filtrar</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <hr />

                        <!-- Tabela de Resultados -->
                        <table class="table dt-table" id="tabela-fluxo-caixa">
                            <thead>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Entradas</th>
                                <th>Saídas</th>
                            </thead>
                            <tbody>
                                <?php
                                $total_entradas = 0;
                                $total_saidas = 0;
                                foreach ($fluxo_caixa as $lancamento) {
                                    $entrada = 0;
                                    $saida = 0;
                                    if ($lancamento['tipo_conta'] == 'Receita') {
                                        $entrada = $lancamento['valor'];
                                        $total_entradas += $entrada;
                                    } else {
                                        $saida = $lancamento['valor'];
                                        $total_saidas += $saida;
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo _d($lancamento['data_liquidacao']); ?></td>
                                        <td><?php echo $lancamento['descricao']; ?></td>
                                        <td><?php echo $lancamento['nome_conta']; ?></td>
                                        <td class="text-success"><?php echo ($entrada > 0) ? format_money($entrada, get_base_currency()->symbol) : ''; ?></td>
                                        <td class="text-danger"><?php echo ($saida > 0) ? format_money($saida, get_base_currency()->symbol) : ''; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Totais:</th>
                                    <th class="text-success"><?php echo format_money($total_entradas, get_base_currency()->symbol); ?></th>
                                    <th class="text-danger"><?php echo format_money($total_saidas, get_base_currency()->symbol); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Saldo do Período:</th>
                                    <th colspan="2" class="<?php echo ($total_entradas - $total_saidas >= 0) ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo format_money($total_entradas - $total_saidas, get_base_currency()->symbol); ?>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
