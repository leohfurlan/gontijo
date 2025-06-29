<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
<?php $this->load->view('admin/gestaofinanceira/_nav'); ?>
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <!-- KPIs Principais -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-money fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">R$ <?php echo number_format($kpis['saldo_total'], 0, ',', '.'); ?></div>
                                                <div>Saldo Total em Caixa</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/contas_bancarias'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Detalhes</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-success">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-line-chart fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">R$ <?php echo number_format($kpis['receitas_mes'], 0, ',', '.'); ?></div>
                                                <div>Receitas do Mês</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/dre'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver DRE</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-warning">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-bar-chart fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">R$ <?php echo number_format($kpis['despesas_mes'], 0, ',', '.'); ?></div>
                                                <div>Despesas do Mês</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/lancamentos'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Lançamentos</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-<?php echo $kpis['resultado_mes'] >= 0 ? 'success' : 'danger'; ?>">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-<?php echo $kpis['resultado_mes'] >= 0 ? 'thumbs-up' : 'thumbs-down'; ?> fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">R$ <?php echo number_format($kpis['resultado_mes'], 0, ',', '.'); ?></div>
                                                <div>Resultado do Mês</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/dre'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Análise</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda Linha de KPIs -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-paw fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo number_format($kpis['total_animais'], 0, ',', '.'); ?></div>
                                                <div>Total de Animais</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/ativos_gado'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Rebanho</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-dollar fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">R$ <?php echo number_format($kpis['valor_rebanho'], 0, ',', '.'); ?></div>
                                                <div>Valor do Rebanho</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/ativos_gado'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Ativos</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-danger">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-exclamation-triangle fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $kpis['contas_vencidas']; ?></div>
                                                <div>Contas Vencidas</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/endividamento'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Pendências</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-warning">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-credit-card fa-3x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">R$ <?php echo number_format($kpis['total_endividamento'], 0, ',', '.'); ?></div>
                                                <div>Total Endividamento</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo admin_url('gestaofinanceira/endividamento'); ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver Contratos</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos -->
                        <div class="row">
                            <!-- Fluxo de Caixa -->
                            <div class="col-md-8">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-line-chart"></i> Fluxo de Caixa - Últimos 12 Meses
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="graficoFluxoCaixa" height="300"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Distribuição de Despesas -->
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-pie-chart"></i> Despesas por Categoria
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="graficoDespesas" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda linha de gráficos -->
                        <div class="row">
                            <!-- Evolução do Rebanho -->
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-area-chart"></i> Evolução do Rebanho
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="graficoRebanho" height="250"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Rentabilidade por Centro de Custo -->
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-bar-chart"></i> Rentabilidade por Fazenda
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="graficoRentabilidade" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alertas e Notificações -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-warning"></i> Alertas Importantes
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php if (!empty($alertas)): ?>
                                            <?php foreach ($alertas as $alerta): ?>
                                            <div class="alert alert-<?php echo $alerta['tipo']; ?> alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                <strong><?php echo $alerta['titulo']; ?>:</strong> <?php echo $alerta['mensagem']; ?>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted">Nenhum alerta no momento.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-calendar"></i> Próximos Vencimentos
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php if (!empty($proximos_vencimentos)): ?>
                                            <div class="table-responsive">
                                                <table class="table table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th>Descrição</th>
                                                            <th>Valor</th>
                                                            <th>Vencimento</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($proximos_vencimentos as $vencimento): ?>
                                                        <tr>
                                                            <td><?php echo $vencimento['descricao']; ?></td>
                                                            <td>R$ <?php echo number_format($vencimento['valor'], 2, ',', '.'); ?></td>
                                                            <td><?php echo _d($vencimento['data_vencimento']); ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted">Nenhum vencimento próximo.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumo por Centro de Custo -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-building"></i> Resumo por Centro de Custo (Mês Atual)
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Centro de Custo</th>
                                                        <th class="text-right">Receitas</th>
                                                        <th class="text-right">Despesas</th>
                                                        <th class="text-right">Resultado</th>
                                                        <th class="text-right">Margem</th>
                                                        <th class="text-center">Animais</th>
                                                        <th class="text-right">Valor Rebanho</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($resumo_centros as $centro): ?>
                                                    <?php 
                                                    $resultado = $centro['receitas'] - $centro['despesas'];
                                                    $margem = $centro['receitas'] > 0 ? ($resultado / $centro['receitas']) * 100 : 0;
                                                    ?>
                                                    <tr>
                                                        <td><strong><?php echo $centro['nome']; ?></strong></td>
                                                        <td class="text-right text-success">
                                                            R$ <?php echo number_format($centro['receitas'], 2, ',', '.'); ?>
                                                        </td>
                                                        <td class="text-right text-danger">
                                                            R$ <?php echo number_format($centro['despesas'], 2, ',', '.'); ?>
                                                        </td>
                                                        <td class="text-right <?php echo $resultado >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                            <strong>R$ <?php echo number_format($resultado, 2, ',', '.'); ?></strong>
                                                        </td>
                                                        <td class="text-right <?php echo $margem >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                            <?php echo number_format($margem, 1, ',', '.'); ?>%
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-primary"><?php echo $centro['total_animais']; ?></span>
                                                        </td>
                                                        <td class="text-right text-info">
                                                            R$ <?php echo number_format($centro['valor_rebanho'], 2, ',', '.'); ?>
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
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    initDashboardCharts();
});

function initDashboardCharts() {
    // Gráfico de Fluxo de Caixa
    var ctx1 = document.getElementById('graficoFluxoCaixa').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($fluxo_caixa_12m, 'mes')); ?>,
            datasets: [{
                label: 'Entradas',
                data: <?php echo json_encode(array_column($fluxo_caixa_12m, 'entradas')); ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: false
            }, {
                label: 'Saídas',
                data: <?php echo json_encode(array_column($fluxo_caixa_12m, 'saidas')); ?>,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: false
            }, {
                label: 'Saldo',
                data: <?php echo json_encode(array_column($fluxo_caixa_12m, 'saldo')); ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Despesas por Categoria
    var ctx2 = document.getElementById('graficoDespesas').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($despesas_categoria)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($despesas_categoria)); ?>,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Gráfico de Evolução do Rebanho
    var ctx3 = document.getElementById('graficoRebanho').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($evolucao_rebanho)); ?>,
            datasets: [{
                label: 'Quantidade de Animais',
                data: <?php echo json_encode(array_values($evolucao_rebanho)); ?>,
                backgroundColor: '#17a2b8'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Rentabilidade por Centro de Custo
    var ctx4 = document.getElementById('graficoRentabilidade').getContext('2d');
    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($resumo_centros, 'nome')); ?>,
            datasets: [{
                label: 'Receitas',
                data: <?php echo json_encode(array_column($resumo_centros, 'receitas')); ?>,
                backgroundColor: '#28a745'
            }, {
                label: 'Despesas',
                data: <?php echo json_encode(array_column($resumo_centros, 'despesas')); ?>,
                backgroundColor: '#dc3545'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
</script>

<?php init_tail(); ?>

