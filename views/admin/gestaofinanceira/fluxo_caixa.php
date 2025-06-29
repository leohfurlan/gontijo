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

                        <!-- Filtros -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <form method="GET" action="<?php echo admin_url('gestaofinanceira/fluxo_caixa'); ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_data_inicio'); ?></label>
                                                        <input type="date" name="data_inicio" class="form-control" 
                                                               value="<?php echo $filtros['data_inicio']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_data_fim'); ?></label>
                                                        <input type="date" name="data_fim" class="form-control" 
                                                               value="<?php echo $filtros['data_fim']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo _l('gf_filtro_centro_custo'); ?></label>
                                                        <select name="centro_custo" class="form-control">
                                                            <option value="">Todos</option>
                                                            <?php foreach ($centros_custo as $centro): ?>
                                                            <option value="<?php echo $centro['id']; ?>" 
                                                                    <?php echo $filtros['centro_custo'] == $centro['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $centro['nome']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i> Filtrar
                                                        </button>
                                                        <button type="button" class="btn btn-success" onclick="exportarFluxoCaixa()">
                                                            <i class="fa fa-file-excel-o"></i> <?php echo _l('gf_btn_exportar'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumo do Fluxo de Caixa -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-line-chart"></i> Fluxo de Caixa - 
                                            <?php echo _d($filtros['data_inicio']); ?> a <?php echo _d($filtros['data_fim']); ?>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        $saldo_inicial = 0;
                                        $total_entradas = 0;
                                        $total_saidas = 0;
                                        
                                        foreach ($fluxo_caixa as $movimento) {
                                            if ($movimento['tipo_conta'] == 'Receita') {
                                                $total_entradas += $movimento['valor'];
                                            } else {
                                                $total_saidas += $movimento['valor'];
                                            }
                                        }
                                        
                                        $saldo_final = $saldo_inicial + $total_entradas - $total_saidas;
                                        ?>
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-info">Saldo Inicial</h4>
                                                    <h3 class="text-info">R$ <?php echo number_format($saldo_inicial, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-success">Total Entradas</h4>
                                                    <h3 class="text-success">R$ <?php echo number_format($total_entradas, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-danger">Total Saídas</h4>
                                                    <h3 class="text-danger">R$ <?php echo number_format($total_saidas, 2, ',', '.'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="<?php echo $saldo_final >= 0 ? 'text-primary' : 'text-warning'; ?>">Saldo Final</h4>
                                                    <h3 class="<?php echo $saldo_final >= 0 ? 'text-primary' : 'text-warning'; ?>">
                                                        R$ <?php echo number_format($saldo_final, 2, ',', '.'); ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalhamento do Fluxo -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5>Detalhamento dos Movimentos</h5>
                                        <div class="table-responsive">
                                            <table class="table table-striped dt-table" id="tabelaFluxoCaixa">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo _l('gf_lancamentos_data_liquidacao'); ?></th>
                                                        <th><?php echo _l('gf_lancamentos_descricao'); ?></th>
                                                        <th><?php echo _l('gf_plano_contas_nome'); ?></th>
                                                        <th><?php echo _l('gf_centros_custo_nome'); ?></th>
                                                        <th><?php echo _l('gf_contas_bancarias_title'); ?></th>
                                                        <th class="text-right">Entradas</th>
                                                        <th class="text-right">Saídas</th>
                                                        <th class="text-right">Saldo Acumulado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $saldo_acumulado = $saldo_inicial;
                                                    foreach ($fluxo_caixa as $movimento): 
                                                        $valor_entrada = $movimento['tipo_conta'] == 'Receita' ? $movimento['valor'] : 0;
                                                        $valor_saida = $movimento['tipo_conta'] == 'Despesa' ? $movimento['valor'] : 0;
                                                        $saldo_acumulado += $valor_entrada - $valor_saida;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo _d($movimento['data_liquidacao']); ?></td>
                                                        <td><?php echo $movimento['descricao']; ?></td>
                                                        <td><?php echo $movimento['nome_conta']; ?></td>
                                                        <td><?php echo $movimento['centro_custo_nome']; ?></td>
                                                        <td>
                                                            <?php if ($movimento['banco']): ?>
                                                                <?php echo $movimento['banco'] . ' - ' . $movimento['agencia'] . '/' . $movimento['conta']; ?>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-right text-success">
                                                            <?php echo $valor_entrada > 0 ? 'R$ ' . number_format($valor_entrada, 2, ',', '.') : '-'; ?>
                                                        </td>
                                                        <td class="text-right text-danger">
                                                            <?php echo $valor_saida > 0 ? 'R$ ' . number_format($valor_saida, 2, ',', '.') : '-'; ?>
                                                        </td>
                                                        <td class="text-right <?php echo $saldo_acumulado >= 0 ? 'text-primary' : 'text-warning'; ?>">
                                                            R$ <?php echo number_format($saldo_acumulado, 2, ',', '.'); ?>
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

                        <!-- Gráfico do Fluxo de Caixa -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5>Evolução do Saldo</h5>
                                        <canvas id="chartFluxoCaixa" height="400"></canvas>
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

<script>
$(document).ready(function() {
    $('#tabelaFluxoCaixa').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "asc"]],
        "pageLength": 50
    });

    // Gráfico do Fluxo de Caixa
    var ctx = document.getElementById('chartFluxoCaixa').getContext('2d');
    var fluxoData = <?php echo json_encode($fluxo_caixa); ?>;
    
    var labels = [];
    var saldos = [];
    var saldoAcumulado = <?php echo $saldo_inicial; ?>;
    
    // Agrupar por data
    var dadosAgrupados = {};
    fluxoData.forEach(function(item) {
        var data = item.data_liquidacao;
        if (!dadosAgrupados[data]) {
            dadosAgrupados[data] = 0;
        }
        if (item.tipo_conta === 'Receita') {
            dadosAgrupados[data] += parseFloat(item.valor);
        } else {
            dadosAgrupados[data] -= parseFloat(item.valor);
        }
    });
    
    // Ordenar por data e calcular saldo acumulado
    Object.keys(dadosAgrupados).sort().forEach(function(data) {
        saldoAcumulado += dadosAgrupados[data];
        labels.push(data);
        saldos.push(saldoAcumulado);
    });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Saldo Acumulado',
                data: saldos,
                borderColor: 'rgba(0, 123, 255, 1)',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Saldo: R$ ' + context.parsed.y.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
});

function exportarFluxoCaixa() {
    var params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.open('<?php echo admin_url("gestaofinanceira/fluxo_caixa"); ?>?' + params.toString());
}
</script>

<?php init_tail(); ?>

