<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-success" onclick="exportarRelatorios()">
                                    <i class="fa fa-file-excel-o"></i> <?php echo _l('gf_btn_exportar'); ?>
                                </button>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Filtros -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <form method="GET" action="<?php echo admin_url('gestaofinanceira/relatorios_operacionais'); ?>">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Período</label>
                                                        <select name="periodo" class="form-control" id="periodo">
                                                            <option value="mes_atual" <?php echo $this->input->get('periodo') == 'mes_atual' ? 'selected' : ''; ?>>Mês Atual</option>
                                                            <option value="mes_anterior" <?php echo $this->input->get('periodo') == 'mes_anterior' ? 'selected' : ''; ?>>Mês Anterior</option>
                                                            <option value="trimestre" <?php echo $this->input->get('periodo') == 'trimestre' ? 'selected' : ''; ?>>Último Trimestre</option>
                                                            <option value="semestre" <?php echo $this->input->get('periodo') == 'semestre' ? 'selected' : ''; ?>>Último Semestre</option>
                                                            <option value="ano" <?php echo $this->input->get('periodo') == 'ano' ? 'selected' : ''; ?>>Ano Atual</option>
                                                            <option value="personalizado" <?php echo $this->input->get('periodo') == 'personalizado' ? 'selected' : ''; ?>>Personalizado</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="data_inicio_div" style="display: none;">
                                                    <div class="form-group">
                                                        <label>Data Início</label>
                                                        <input type="date" name="data_inicio" class="form-control" 
                                                               value="<?php echo $this->input->get('data_inicio'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="data_fim_div" style="display: none;">
                                                    <div class="form-group">
                                                        <label>Data Fim</label>
                                                        <input type="date" name="data_fim" class="form-control" 
                                                               value="<?php echo $this->input->get('data_fim'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Centro de Custo</label>
                                                        <select name="centro_custo" class="form-control">
                                                            <option value="">Todos</option>
                                                            <?php foreach ($centros_custo as $centro): ?>
                                                            <option value="<?php echo $centro['id']; ?>" 
                                                                    <?php echo $this->input->get('centro_custo') == $centro['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $centro['nome']; ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i> Filtrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs dos Relatórios -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#compras-gado" aria-controls="compras-gado" role="tab" data-toggle="tab">
                                                    <i class="fa fa-shopping-cart"></i> Compras de Gado
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#vendas-gado" aria-controls="vendas-gado" role="tab" data-toggle="tab">
                                                    <i class="fa fa-money"></i> Vendas de Gado
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#custos-categoria" aria-controls="custos-categoria" role="tab" data-toggle="tab">
                                                    <i class="fa fa-pie-chart"></i> Custos por Categoria
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#evolucao-rebanho" aria-controls="evolucao-rebanho" role="tab" data-toggle="tab">
                                                    <i class="fa fa-line-chart"></i> Evolução do Rebanho
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#rentabilidade" aria-controls="rentabilidade" role="tab" data-toggle="tab">
                                                    <i class="fa fa-bar-chart"></i> Rentabilidade
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <!-- Tab Compras de Gado -->
                                            <div role="tabpanel" class="tab-pane active" id="compras-gado">
                                                <div class="row" style="margin-top: 20px;">
                                                    <div class="col-md-12">
                                                        <h4>Histórico de Compras de Gado</h4>
                                                        
                                                        <!-- Resumo das Compras -->
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="panel panel-primary">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo $resumo_compras['total_animais']; ?></h3>
                                                                        <p>Animais Comprados</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-success">
                                                                    <div class="panel-body text-center">
                                                                        <h3>R$ <?php echo number_format($resumo_compras['valor_total'], 2, ',', '.'); ?></h3>
                                                                        <p>Valor Total Investido</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-info">
                                                                    <div class="panel-body text-center">
                                                                        <h3>R$ <?php echo number_format($resumo_compras['preco_medio'], 2, ',', '.'); ?></h3>
                                                                        <p>Preço Médio por Animal</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo $resumo_compras['total_lotes']; ?></h3>
                                                                        <p>Lotes Adquiridos</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Tabela de Compras -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped" id="tabelaCompras">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Data</th>
                                                                        <th>Descrição</th>
                                                                        <th>Categoria</th>
                                                                        <th>Quantidade</th>
                                                                        <th>Valor Unitário</th>
                                                                        <th>Valor Total</th>
                                                                        <th>Fornecedor</th>
                                                                        <th>Centro de Custo</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($compras_gado as $compra): ?>
                                                                    <tr>
                                                                        <td><?php echo _d($compra['data_aquisicao']); ?></td>
                                                                        <td><?php echo $compra['descricao']; ?></td>
                                                                        <td>
                                                                            <span class="label label-primary"><?php echo $compra['categoria']; ?></span>
                                                                        </td>
                                                                        <td class="text-center"><?php echo $compra['quantidade']; ?></td>
                                                                        <td class="text-right">R$ <?php echo number_format($compra['valor_unitario'], 2, ',', '.'); ?></td>
                                                                        <td class="text-right text-success">
                                                                            <strong>R$ <?php echo number_format($compra['valor_total'], 2, ',', '.'); ?></strong>
                                                                        </td>
                                                                        <td><?php echo $compra['fornecedor']; ?></td>
                                                                        <td><?php echo $compra['centro_custo']; ?></td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab Vendas de Gado -->
                                            <div role="tabpanel" class="tab-pane" id="vendas-gado">
                                                <div class="row" style="margin-top: 20px;">
                                                    <div class="col-md-12">
                                                        <h4>Histórico de Vendas de Gado</h4>
                                                        
                                                        <!-- Resumo das Vendas -->
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="panel panel-primary">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo $resumo_vendas['total_animais']; ?></h3>
                                                                        <p>Animais Vendidos</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-success">
                                                                    <div class="panel-body text-center">
                                                                        <h3>R$ <?php echo number_format($resumo_vendas['valor_total'], 2, ',', '.'); ?></h3>
                                                                        <p>Receita Total</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-info">
                                                                    <div class="panel-body text-center">
                                                                        <h3>R$ <?php echo number_format($resumo_vendas['preco_medio'], 2, ',', '.'); ?></h3>
                                                                        <p>Preço Médio por Animal</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo number_format($resumo_vendas['margem_media'], 1, ',', '.'); ?>%</h3>
                                                                        <p>Margem Média</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Tabela de Vendas -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped" id="tabelaVendas">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Data</th>
                                                                        <th>Cliente</th>
                                                                        <th>Categoria</th>
                                                                        <th>Quantidade</th>
                                                                        <th>Valor Unitário</th>
                                                                        <th>Valor Total</th>
                                                                        <th>Custo Unitário</th>
                                                                        <th>Margem</th>
                                                                        <th>Centro de Custo</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($vendas_gado as $venda): ?>
                                                                    <?php 
                                                                    $margem = (($venda['valor_unitario'] - $venda['custo_unitario']) / $venda['valor_unitario']) * 100;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo _d($venda['data_venda']); ?></td>
                                                                        <td><?php echo $venda['cliente']; ?></td>
                                                                        <td>
                                                                            <span class="label label-success"><?php echo $venda['categoria']; ?></span>
                                                                        </td>
                                                                        <td class="text-center"><?php echo $venda['quantidade']; ?></td>
                                                                        <td class="text-right">R$ <?php echo number_format($venda['valor_unitario'], 2, ',', '.'); ?></td>
                                                                        <td class="text-right text-success">
                                                                            <strong>R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></strong>
                                                                        </td>
                                                                        <td class="text-right">R$ <?php echo number_format($venda['custo_unitario'], 2, ',', '.'); ?></td>
                                                                        <td class="text-right <?php echo $margem >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                                            <?php echo number_format($margem, 1, ',', '.'); ?>%
                                                                        </td>
                                                                        <td><?php echo $venda['centro_custo']; ?></td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab Custos por Categoria -->
                                            <div role="tabpanel" class="tab-pane" id="custos-categoria">
                                                <div class="row" style="margin-top: 20px;">
                                                    <div class="col-md-12">
                                                        <h4>Análise de Custos por Categoria</h4>
                                                        
                                                        <!-- Gráfico de Custos -->
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body">
                                                                        <canvas id="graficoCustomCategoria" height="300"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4>Resumo por Categoria</h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <?php foreach ($custos_categoria as $categoria => $dados): ?>
                                                                        <div class="row" style="margin-bottom: 10px;">
                                                                            <div class="col-md-8">
                                                                                <strong><?php echo $categoria; ?></strong>
                                                                            </div>
                                                                            <div class="col-md-4 text-right">
                                                                                R$ <?php echo number_format($dados['total'], 0, ',', '.'); ?>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab Evolução do Rebanho -->
                                            <div role="tabpanel" class="tab-pane" id="evolucao-rebanho">
                                                <div class="row" style="margin-top: 20px;">
                                                    <div class="col-md-12">
                                                        <h4>Evolução do Rebanho</h4>
                                                        
                                                        <!-- Gráfico de Evolução -->
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <canvas id="graficoEvolucaoRebanho" height="300"></canvas>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Tabela de Movimentação -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Mês/Ano</th>
                                                                        <th>Estoque Inicial</th>
                                                                        <th>Compras</th>
                                                                        <th>Vendas</th>
                                                                        <th>Nascimentos</th>
                                                                        <th>Mortes</th>
                                                                        <th>Estoque Final</th>
                                                                        <th>Variação</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($evolucao_rebanho as $periodo): ?>
                                                                    <?php 
                                                                    $variacao = $periodo['estoque_final'] - $periodo['estoque_inicial'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $periodo['periodo']; ?></td>
                                                                        <td class="text-center"><?php echo $periodo['estoque_inicial']; ?></td>
                                                                        <td class="text-center text-success">+<?php echo $periodo['compras']; ?></td>
                                                                        <td class="text-center text-danger">-<?php echo $periodo['vendas']; ?></td>
                                                                        <td class="text-center text-success">+<?php echo $periodo['nascimentos']; ?></td>
                                                                        <td class="text-center text-danger">-<?php echo $periodo['mortes']; ?></td>
                                                                        <td class="text-center"><strong><?php echo $periodo['estoque_final']; ?></strong></td>
                                                                        <td class="text-center <?php echo $variacao >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                                            <?php echo ($variacao >= 0 ? '+' : '') . $variacao; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab Rentabilidade -->
                                            <div role="tabpanel" class="tab-pane" id="rentabilidade">
                                                <div class="row" style="margin-top: 20px;">
                                                    <div class="col-md-12">
                                                        <h4>Análise de Rentabilidade</h4>
                                                        
                                                        <!-- KPIs de Rentabilidade -->
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="panel panel-success">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo number_format($kpis_rentabilidade['margem_bruta'], 1, ',', '.'); ?>%</h3>
                                                                        <p>Margem Bruta</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-info">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo number_format($kpis_rentabilidade['roi'], 1, ',', '.'); ?>%</h3>
                                                                        <p>ROI</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-body text-center">
                                                                        <h3>R$ <?php echo number_format($kpis_rentabilidade['custo_por_animal'], 2, ',', '.'); ?></h3>
                                                                        <p>Custo por Animal/Mês</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="panel panel-primary">
                                                                    <div class="panel-body text-center">
                                                                        <h3><?php echo number_format($kpis_rentabilidade['giro_estoque'], 1, ',', '.'); ?>x</h3>
                                                                        <p>Giro do Estoque</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Gráfico de Rentabilidade -->
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <canvas id="graficoRentabilidade" height="300"></canvas>
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
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Controle de exibição dos campos de data
    $('#periodo').change(function() {
        if ($(this).val() == 'personalizado') {
            $('#data_inicio_div, #data_fim_div').show();
        } else {
            $('#data_inicio_div, #data_fim_div').hide();
        }
    });

    // Inicializar gráficos
    initGraficos();
});

function initGraficos() {
    // Gráfico de Custos por Categoria
    var ctx1 = document.getElementById('graficoCustomCategoria').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($custos_categoria)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values(array_column($custos_categoria, 'total'))); ?>,
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
    var ctx2 = document.getElementById('graficoEvolucaoRebanho').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($evolucao_rebanho, 'periodo')); ?>,
            datasets: [{
                label: 'Estoque de Animais',
                data: <?php echo json_encode(array_column($evolucao_rebanho, 'estoque_final')); ?>,
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
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

    // Gráfico de Rentabilidade
    var ctx3 = document.getElementById('graficoRentabilidade').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($rentabilidade_mensal, 'mes')); ?>,
            datasets: [{
                label: 'Receitas',
                data: <?php echo json_encode(array_column($rentabilidade_mensal, 'receitas')); ?>,
                backgroundColor: '#4BC0C0'
            }, {
                label: 'Custos',
                data: <?php echo json_encode(array_column($rentabilidade_mensal, 'custos')); ?>,
                backgroundColor: '#FF6384'
            }, {
                label: 'Lucro',
                data: <?php echo json_encode(array_column($rentabilidade_mensal, 'lucro')); ?>,
                backgroundColor: '#36A2EB'
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

function exportarRelatorios() {
    var params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.open('<?php echo admin_url("gestaofinanceira/relatorios_operacionais"); ?>?' + params.toString());
}
</script>

<?php init_tail(); ?>

