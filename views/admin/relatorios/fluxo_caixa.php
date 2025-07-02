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
                        <?php echo form_open($this->uri->uri_string(), ['method' => 'get']); ?>
                        <div class="row">
                            <div class="col-md-3"><?php echo render_date_input('data_inicio', 'Data de Início', $filtros['data_inicio']); ?></div>
                            <div class="col-md-3"><?php echo render_date_input('data_fim', 'Data de Fim', $filtros['data_fim']); ?></div>
                            <div class="col-md-2"><?php echo render_select('centro_custo', $centros_custo, ['id', 'nome'], 'Centro de Custo', $filtros['centro_custo']); ?></div>
                            <div class="col-md-2"><?php echo render_select('agrupamento', [['id'=>'monthly','name'=>'Mensal'],['id'=>'daily','name'=>'Diário']], ['id','name'], 'Agrupar por', $filtros['agrupamento']); ?></div>
                            <div class="col-md-2"><label>&nbsp;</label><br><button type="submit" class="btn btn-info">Filtrar</button></div>
                        </div>
                        <?php echo form_close(); ?>
                        <hr />

                        <!-- Tabela de Fluxo de Caixa -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="fluxo-caixa-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-left sticky-col">Descrição</th>
                                        <?php foreach($periodos as $periodo): ?>
                                            <th colspan="2" class="text-center"><?php echo ($filtros['agrupamento'] == 'daily') ? _d($periodo) : strftime('%b/%y', strtotime($periodo)); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <?php foreach($periodos as $periodo): ?>
                                            <th class="text-center">Orçado</th>
                                            <th class="text-center">Realizado</th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Pre-calcula os totais para as linhas de resumo
                                    $totais_entradas = [];
                                    $totais_saidas = [];
                                    foreach($plano_contas as $conta_pai) {
                                        if ($conta_pai['tipo_conta'] == 'Receita') {
                                            if (isset($conta_pai['children'])) {
                                                foreach($conta_pai['children'] as $conta_filha) {
                                                    foreach($periodos as $p) {
                                                        $totais_entradas[$p]['orcado'] = ($totais_entradas[$p]['orcado'] ?? 0) + ($matrix_dados[$conta_filha['id']][$p]['orcado'] ?? 0);
                                                        $totais_entradas[$p]['realizado'] = ($totais_entradas[$p]['realizado'] ?? 0) + ($matrix_dados[$conta_filha['id']][$p]['realizado'] ?? 0);
                                                    }
                                                }
                                            }
                                        } else {
                                            if (isset($conta_pai['children'])) {
                                                foreach($conta_pai['children'] as $conta_filha) {
                                                     foreach($periodos as $p) {
                                                        $totais_saidas[$p]['orcado'] = ($totais_saidas[$p]['orcado'] ?? 0) + ($matrix_dados[$conta_filha['id']][$p]['orcado'] ?? 0);
                                                        $totais_saidas[$p]['realizado'] = ($totais_saidas[$p]['realizado'] ?? 0) + ($matrix_dados[$conta_filha['id']][$p]['realizado'] ?? 0);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>

                                    <!-- Linha Saldo Inicial -->
                                    <tr class="info">
                                        <td class="sticky-col"><strong>SALDO INICIAL</strong></td>
                                        <?php
                                        $saldo_acumulado_orcado = $saldo_inicial;
                                        $saldo_acumulado_realizado = $saldo_inicial;
                                        foreach($periodos as $p) {
                                            echo '<td class="text-right"><strong>' . app_format_money($saldo_acumulado_orcado, get_base_currency()) . '</strong></td>';
                                            echo '<td class="text-right"><strong>' . app_format_money($saldo_acumulado_realizado, get_base_currency()) . '</strong></td>';
                                            // Atualiza o saldo para o próximo período
                                            $mov_orcado = ($totais_entradas[$p]['orcado'] ?? 0) - ($totais_saidas[$p]['orcado'] ?? 0);
                                            $mov_realizado = ($totais_entradas[$p]['realizado'] ?? 0) - ($totais_saidas[$p]['realizado'] ?? 0);
                                            $saldo_acumulado_orcado += $mov_orcado;
                                            $saldo_acumulado_realizado += $mov_realizado;
                                        }
                                        ?>
                                    </tr>

                                    <!-- Linha Entradas de Caixa -->
                                    <tr class="success parent-row" data-id="entradas">
                                        <td class="sticky-col"><strong><i class="fa fa-plus-square-o toggle-icon"></i> (+) ENTRADAS DE CAIXA</strong></td>
                                        <?php foreach($periodos as $p): ?>
                                            <td class="text-right"><strong><?php echo app_format_money($totais_entradas[$p]['orcado'] ?? 0, get_base_currency()); ?></strong></td>
                                            <td class="text-right"><strong><?php echo app_format_money($totais_entradas[$p]['realizado'] ?? 0, get_base_currency()); ?></strong></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php
                                    // Função recursiva para renderizar as linhas
                                    function render_fluxo_row($contas, $matrix_dados, $periodos, $level = 0, $parent_id = 'root') {
                                        foreach ($contas as $conta) {
                                            $is_pai = isset($conta['children']);
                                            $row_class = 'child-of-'.$parent_id;
                                            if($is_pai) $row_class .= ' parent-row';

                                            $icon = $is_pai ? '<i class="fa fa-plus-square-o toggle-icon"></i> ' : '';
                                            
                                            echo '<tr class="' . $row_class . '" data-id="' . $conta['id'] . '" data-parent="' . $parent_id . '">';
                                            echo '<td class="sticky-col" style="padding-left:' . (20 + ($level * 20)) . 'px;">' . $icon . $conta['nome_conta'] . '</td>';
                                            
                                            foreach ($periodos as $periodo) {
                                                $orcado = $matrix_dados[$conta['id']][$periodo]['orcado'] ?? 0;
                                                $realizado = $matrix_dados[$conta['id']][$periodo]['realizado'] ?? 0;
                                                echo '<td class="text-right">' . app_format_money($orcado, get_base_currency()) . '</td>';
                                                echo '<td class="text-right">' . app_format_money($realizado, get_base_currency()) . '</td>';
                                            }
                                            echo '</tr>';

                                            if ($is_pai) {
                                                render_fluxo_row($conta['children'], $matrix_dados, $periodos, $level + 1, $conta['id']);
                                            }
                                        }
                                    }
                                    
                                    // Renderiza apenas as Receitas
                                    foreach($plano_contas as $conta) { if($conta['tipo_conta'] == 'Receita') render_fluxo_row([$conta], $matrix_dados, $periodos, 0, 'entradas'); }
                                    ?>

                                    <!-- Linha Saídas de Caixa -->
                                    <tr class="danger parent-row" data-id="saidas">
                                        <td class="sticky-col"><strong><i class="fa fa-plus-square-o toggle-icon"></i> (-) SAÍDAS DE CAIXA</strong></td>
                                        <?php foreach($periodos as $p): ?>
                                            <td class="text-right"><strong><?php echo app_format_money($totais_saidas[$p]['orcado'] ?? 0, get_base_currency()); ?></strong></td>
                                            <td class="text-right"><strong><?php echo app_format_money($totais_saidas[$p]['realizado'] ?? 0, get_base_currency()); ?></strong></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php
                                    // Renderiza apenas as Despesas
                                    foreach($plano_contas as $conta) { if($conta['tipo_conta'] == 'Despesa') render_fluxo_row([$conta], $matrix_dados, $periodos, 0, 'saidas'); }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <!-- Linha Saldo do Movimento -->
                                    <tr class="info">
                                        <th class="sticky-col">SALDO DO MOVIMENTO</th>
                                        <?php
                                        $saldo_acumulado_orcado = $saldo_inicial;
                                        $saldo_acumulado_realizado = $saldo_inicial;
                                        foreach($periodos as $p):
                                            $mov_orcado = ($totais_entradas[$p]['orcado'] ?? 0) - ($totais_saidas[$p]['orcado'] ?? 0);
                                            $mov_realizado = ($totais_entradas[$p]['realizado'] ?? 0) - ($totais_saidas[$p]['realizado'] ?? 0);
                                            $saldo_acumulado_orcado += $mov_orcado;
                                            $saldo_acumulado_realizado += $mov_realizado;
                                        ?>
                                            <th class="text-right"><?php echo app_format_money($mov_orcado, get_base_currency()); ?></th>
                                            <th class="text-right"><?php echo app_format_money($mov_realizado, get_base_currency()); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                    <!-- Linha Saldo Acumulado -->
                                    <tr class="info">
                                        <th class="sticky-col">SALDO ACUMULADO</th>
                                        <?php
                                        $saldo_acumulado_orcado = $saldo_inicial;
                                        $saldo_acumulado_realizado = $saldo_inicial;
                                        foreach($periodos as $p):
                                            $mov_orcado = ($totais_entradas[$p]['orcado'] ?? 0) - ($totais_saidas[$p]['orcado'] ?? 0);
                                            $mov_realizado = ($totais_entradas[$p]['realizado'] ?? 0) - ($totais_saidas[$p]['realizado'] ?? 0);
                                            $saldo_acumulado_orcado += $mov_orcado;
                                            $saldo_acumulado_realizado += $mov_realizado;
                                        ?>
                                            <th class="text-right"><?php echo app_format_money($saldo_acumulado_orcado, get_base_currency()); ?></th>
                                            <th class="text-right"><?php echo app_format_money($saldo_acumulado_realizado, get_base_currency()); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<style>
    /* Adiciona a coluna fixa */
    .sticky-col {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        background-color: #f9f9f9;
        z-index: 2;
    }
    #fluxo-caixa-table thead tr:first-child th.sticky-col {
        z-index: 3;
    }
    /* Adiciona um cursor de ponteiro para indicar que a linha é clicável */
    .parent-row {
        cursor: pointer;
    }
    .toggle-icon {
        margin-right: 5px;
        color: #555;
        font-size: 1.2em;
    }
</style>
<script>
    $(function() {
        // Esconde todas as linhas filhas inicialmente
        $('#fluxo-caixa-table tbody tr[class*="child-of-"]').hide();

        // Adiciona o evento de clique nas linhas pai
        $('.parent-row').on('click', function() {
            var parentId = $(this).data('id');
            // Alterna a visibilidade das linhas filhas diretas
            $('tr.child-of-' + parentId).toggle();
            // Alterna o ícone de expandir/recolher
            $(this).find('.toggle-icon').toggleClass('fa-plus-square-o fa-minus-square-o');
        });
    });
</script>
</body>
</html>
