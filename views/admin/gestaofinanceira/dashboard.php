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

                        <!-- Linha de KPIs Principais -->
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s kpi-card" style="border: 1px solid #28a745;">
                                    <div class="panel-body" style="padding: 15px;">
                                        <h4 class="mtop0 text-success">Resultado do Período</h4>
                                        <h3 class="bold text-success"><?php echo app_format_money(isset($resultado_periodo) ? $resultado_periodo : 0, get_base_currency()); ?></h3>
                                        <small class="text-muted">Lucro/Prejuízo (Últimos 30 dias)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s kpi-card" style="border: 1px solid #17a2b8;">
                                    <div class="panel-body" style="padding: 15px;">
                                        <h4 class="mtop0 text-info">Saldo em Caixa</h4>
                                        <h3 class="bold text-info"><?php echo app_format_money($saldo_caixa, get_base_currency()); ?></h3>
                                        <small class="text-muted">Soma de todas as contas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s kpi-card" style="border: 1px solid #ffc107;">
                                    <div class="panel-body" style="padding: 15px;">
                                        <h4 class="mtop0 text-warning">Total a Pagar</h4>
                                        <h3 class="bold text-warning"><?php echo app_format_money($total_pagar, get_base_currency()); ?></h3>
                                        <small class="text-muted">Contas e despesas em aberto</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="panel_s kpi-card" style="border: 1px solid #dc3545;">
                                    <div class="panel-body" style="padding: 15px;">
                                        <h4 class="mtop0 text-danger">Endividamento Total</h4>
                                        <h3 class="bold text-danger"><?php echo app_format_money(isset($endividamento_total) ? $endividamento_total : 0, get_base_currency()); ?></h3>
                                        <small class="text-muted">Saldo devedor de contratos</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="hr-panel-heading" />

                        <!-- Linha de Gráficos -->
                        <div class="row">
                            <div class="col-md-7">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h4 class="no-margin">Fluxo de Caixa (Últimos 6 Meses)</h4>
                                        <hr class="hr-panel-heading" />
                                        <div style="height: 300px;">
                                            <canvas id="graficoFluxoCaixa"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h4 class="no-margin">Composição de Custos</h4>
                                        <hr class="hr-panel-heading" />
                                        <div style="height: 300px;">
                                            <canvas id="graficoCustosCategoria"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Linha de Alertas e Ativos -->
                        <div class="row">
                            <div class="col-md-7">
                                <div class="panel_s">
                                    <div class="panel-heading">
                                        <i class="fa fa-warning"></i> Alertas e Vencimentos
                                    </div>
                                    <div class="panel-body">
                                        <?php if (isset($alertas_vencimento) && !empty($alertas_vencimento)) : ?>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo _l('description'); ?></th>
                                                            <th><?php echo _l('date'); ?></th>
                                                            <th class="text-right"><?php echo _l('amount'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($alertas_vencimento as $alerta): ?>
                                                        <tr class="text-<?php echo $alerta['tipo_conta'] == 'Despesa' ? 'danger' : 'success'; ?>">
                                                            <td>
                                                                <?php echo $alerta['descricao']; ?><br>
                                                                <small class="text-muted"><?php echo $alerta['entidade_nome']; ?></small>
                                                            </td>
                                                            <td><?php echo _d($alerta['data_vencimento']); ?></td>
                                                            <td class="text-right"><?php echo app_format_money($alerta['valor'], get_base_currency()); ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else : ?>
                                            <p class="text-success"><i class="fa fa-check-circle"></i> Nenhum alerta de vencimento para os próximos 7 dias.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="panel_s">
                                    <div class="panel-heading">
                                        <i class="fa fa-paw"></i> Resumo de Ativos (Gado)
                                    </div>
                                    <div class="panel-body">
                                        <h4 class="text-center">Total de Animais: <strong class="text-info"><?php echo isset($total_rebanho) ? $total_rebanho : 0; ?></strong></h4>
                                        <hr>
                                        <p><strong>Valor do Ativo:</strong> <?php echo app_format_money(isset($valor_ativo_gado) ? $valor_ativo_gado : 0, get_base_currency()); ?></p>
                                        <p><strong>Custo Médio / Cabeça:</strong> <?php echo app_format_money(isset($custo_medio_animal) ? $custo_medio_animal : 0, get_base_currency()); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function() {
    // Processa dados para o gráfico de Fluxo de Caixa
    var receitasDespesasData = <?php echo json_encode($receitas_despesas_chart); ?>;
    var labels = [];
    var receitas = [];
    var despesas = [];

    var temp_data = {};
    receitasDespesasData.forEach(function(item) {
        if (!temp_data[item.mes]) {
            temp_data[item.mes] = { Receita: 0, Despesa: 0 };
            labels.push(item.mes);
        }
        temp_data[item.mes][item.tipo_conta] = parseFloat(item.total);
    });

    labels.sort();
    labels.forEach(function(label) {
        receitas.push(temp_data[label].Receita);
        despesas.push(temp_data[label].Despesa);
    });

    var ctxFluxo = document.getElementById('graficoFluxoCaixa').getContext('2d');
    new Chart(ctxFluxo, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Receitas',
                data: receitas,
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }, {
                label: 'Despesas',
                data: despesas,
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Processa dados para o gráfico de Custos por Categoria
    var custosData = <?php echo json_encode($custos_categoria_chart); ?>;
    var custoLabels = custosData.map(function(item) { return item.nome_conta; });
    var custoValores = custosData.map(function(item) { return item.total; });

    var ctxCustos = document.getElementById('graficoCustosCategoria').getContext('2d');
    new Chart(ctxCustos, {
        type: 'doughnut',
        data: {
            labels: custoLabels,
            datasets: [{
                label: 'Custos por Categoria',
                data: custoValores,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
});
</script>
<style>
    .kpi-card {
        min-height: 135px;
    }
</style>
</body>
</html>
