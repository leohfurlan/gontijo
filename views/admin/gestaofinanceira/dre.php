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

                        <!-- Filtros -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <form method="GET" action="<?php echo admin_url('gestaofinanceira/dre'); ?>">
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
                                                        <button type="button" class="btn btn-success" onclick="exportarDRE()">
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

                        <!-- DRE Estruturado -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-pie-chart"></i> Demonstrativo de Resultado do Exercício (DRE) - 
                                            <?php echo _d($filtros['data_inicio']); ?> a <?php echo _d($filtros['data_fim']); ?>
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        // Agrupar dados por grupo DRE
                                        $grupos_dre = [];
                                        $total_receitas = 0;
                                        $total_custos_variaveis = 0;
                                        $total_custos_fixos = 0;
                                        
                                        foreach ($dre as $item) {
                                            $grupo = $item['grupo_dre'] ?: 'Outros';
                                            if (!isset($grupos_dre[$grupo])) {
                                                $grupos_dre[$grupo] = [];
                                            }
                                            $grupos_dre[$grupo][] = $item;
                                            
                                            if ($item['tipo_conta'] == 'Receita') {
                                                $total_receitas += $item['valor'];
                                            } elseif ($grupo == 'Custo Variável') {
                                                $total_custos_variaveis += $item['valor'];
                                            } elseif ($grupo == 'Custo Fixo') {
                                                $total_custos_fixos += $item['valor'];
                                            }
                                        }
                                        
                                        $margem_contribuicao = $total_receitas - $total_custos_variaveis;
                                        $resultado_operacional = $margem_contribuicao - $total_custos_fixos;
                                        ?>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">Descrição</th>
                                                        <th width="20%" class="text-right">Valor (R$)</th>
                                                        <th width="20%" class="text-right">% Receita</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- RECEITAS -->
                                                    <tr class="success">
                                                        <td><strong>RECEITA OPERACIONAL</strong></td>
                                                        <td class="text-right"><strong>R$ <?php echo number_format($total_receitas, 2, ',', '.'); ?></strong></td>
                                                        <td class="text-right"><strong>100,00%</strong></td>
                                                    </tr>
                                                    <?php if (isset($grupos_dre['Receita Operacional'])): ?>
                                                        <?php foreach ($grupos_dre['Receita Operacional'] as $item): ?>
                                                        <tr>
                                                            <td style="padding-left: 30px;"><?php echo $item['nome_conta']; ?></td>
                                                            <td class="text-right">R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
                                                            <td class="text-right">
                                                                <?php echo $total_receitas > 0 ? number_format(($item['valor'] / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                    
                                                    <!-- CUSTOS VARIÁVEIS -->
                                                    <tr class="warning">
                                                        <td><strong>(-) CUSTOS VARIÁVEIS</strong></td>
                                                        <td class="text-right"><strong>R$ <?php echo number_format($total_custos_variaveis, 2, ',', '.'); ?></strong></td>
                                                        <td class="text-right"><strong>
                                                            <?php echo $total_receitas > 0 ? number_format(($total_custos_variaveis / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                        </strong></td>
                                                    </tr>
                                                    <?php if (isset($grupos_dre['Custo Variável'])): ?>
                                                        <?php foreach ($grupos_dre['Custo Variável'] as $item): ?>
                                                        <tr>
                                                            <td style="padding-left: 30px;"><?php echo $item['nome_conta']; ?></td>
                                                            <td class="text-right">R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
                                                            <td class="text-right">
                                                                <?php echo $total_receitas > 0 ? number_format(($item['valor'] / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                    
                                                    <!-- MARGEM DE CONTRIBUIÇÃO -->
                                                    <tr class="<?php echo $margem_contribuicao >= 0 ? 'info' : 'danger'; ?>">
                                                        <td><strong>= MARGEM DE CONTRIBUIÇÃO</strong></td>
                                                        <td class="text-right"><strong>R$ <?php echo number_format($margem_contribuicao, 2, ',', '.'); ?></strong></td>
                                                        <td class="text-right"><strong>
                                                            <?php echo $total_receitas > 0 ? number_format(($margem_contribuicao / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                        </strong></td>
                                                    </tr>
                                                    
                                                    <!-- CUSTOS FIXOS -->
                                                    <tr class="warning">
                                                        <td><strong>(-) CUSTOS FIXOS</strong></td>
                                                        <td class="text-right"><strong>R$ <?php echo number_format($total_custos_fixos, 2, ',', '.'); ?></strong></td>
                                                        <td class="text-right"><strong>
                                                            <?php echo $total_receitas > 0 ? number_format(($total_custos_fixos / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                        </strong></td>
                                                    </tr>
                                                    <?php if (isset($grupos_dre['Custo Fixo'])): ?>
                                                        <?php foreach ($grupos_dre['Custo Fixo'] as $item): ?>
                                                        <tr>
                                                            <td style="padding-left: 30px;"><?php echo $item['nome_conta']; ?></td>
                                                            <td class="text-right">R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
                                                            <td class="text-right">
                                                                <?php echo $total_receitas > 0 ? number_format(($item['valor'] / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                    
                                                    <!-- RESULTADO OPERACIONAL -->
                                                    <tr class="<?php echo $resultado_operacional >= 0 ? 'success' : 'danger'; ?>">
                                                        <td><strong>= RESULTADO OPERACIONAL</strong></td>
                                                        <td class="text-right"><strong>R$ <?php echo number_format($resultado_operacional, 2, ',', '.'); ?></strong></td>
                                                        <td class="text-right"><strong>
                                                            <?php echo $total_receitas > 0 ? number_format(($resultado_operacional / $total_receitas) * 100, 2, ',', '.') : '0,00'; ?>%
                                                        </strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Indicadores Chave -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="panel panel-success">
                                    <div class="panel-body text-center">
                                        <h4>Margem de Contribuição</h4>
                                        <h3 class="<?php echo $margem_contribuicao >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo $total_receitas > 0 ? number_format(($margem_contribuicao / $total_receitas) * 100, 1, ',', '.') : '0,0'; ?>%
                                        </h3>
                                        <p class="text-muted">R$ <?php echo number_format($margem_contribuicao, 2, ',', '.'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-info">
                                    <div class="panel-body text-center">
                                        <h4>Margem Operacional</h4>
                                        <h3 class="<?php echo $resultado_operacional >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo $total_receitas > 0 ? number_format(($resultado_operacional / $total_receitas) * 100, 1, ',', '.') : '0,0'; ?>%
                                        </h3>
                                        <p class="text-muted">R$ <?php echo number_format($resultado_operacional, 2, ',', '.'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-warning">
                                    <div class="panel-body text-center">
                                        <h4>Custos Variáveis</h4>
                                        <h3 class="text-warning">
                                            <?php echo $total_receitas > 0 ? number_format(($total_custos_variaveis / $total_receitas) * 100, 1, ',', '.') : '0,0'; ?>%
                                        </h3>
                                        <p class="text-muted">R$ <?php echo number_format($total_custos_variaveis, 2, ',', '.'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-danger">
                                    <div class="panel-body text-center">
                                        <h4>Custos Fixos</h4>
                                        <h3 class="text-danger">
                                            <?php echo $total_receitas > 0 ? number_format(($total_custos_fixos / $total_receitas) * 100, 1, ',', '.') : '0,0'; ?>%
                                        </h3>
                                        <p class="text-muted">R$ <?php echo number_format($total_custos_fixos, 2, ',', '.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico da DRE -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5>Composição da Receita</h5>
                                        <canvas id="chartComposicaoReceita" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5>Estrutura de Custos</h5>
                                        <canvas id="chartEstruturaCustos" height="300"></canvas>
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
    // Gráfico de Composição da Receita
    var ctxReceita = document.getElementById('chartComposicaoReceita').getContext('2d');
    var receitaData = <?php echo json_encode(isset($grupos_dre['Receita Operacional']) ? $grupos_dre['Receita Operacional'] : []); ?>;
    
    var labelsReceita = receitaData.map(item => item.nome_conta);
    var valoresReceita = receitaData.map(item => parseFloat(item.valor));
    var coresReceita = [
        '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1',
        '#fd7e14', '#20c997', '#6610f2', '#e83e8c', '#6c757d'
    ];

    new Chart(ctxReceita, {
        type: 'doughnut',
        data: {
            labels: labelsReceita,
            datasets: [{
                data: valoresReceita,
                backgroundColor: coresReceita.slice(0, labelsReceita.length),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': R$ ' + context.parsed.toLocaleString('pt-BR') + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Estrutura de Custos
    var ctxCustos = document.getElementById('chartEstruturaCustos').getContext('2d');
    
    new Chart(ctxCustos, {
        type: 'bar',
        data: {
            labels: ['Custos Variáveis', 'Custos Fixos'],
            datasets: [{
                label: 'Valores',
                data: [<?php echo $total_custos_variaveis; ?>, <?php echo $total_custos_fixos; ?>],
                backgroundColor: ['#ffc107', '#dc3545'],
                borderColor: ['#ffc107', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'R$ ' + context.parsed.y.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
});

function exportarDRE() {
    var params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.open('<?php echo admin_url("gestaofinanceira/dre"); ?>?' + params.toString());
}
</script>

<?php init_tail(); ?>

