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

                        <!-- Estrutura da DRE -->
                        <table class="table">
                            <tbody>
                                <?php
                                $resultados_por_grupo = [];
                                foreach ($dre as $item) {
                                    $resultados_por_grupo[$item['grupo_dre']][] = $item;
                                }
                                
                                $total_receitas = 0;
                                $total_despesas = 0;
                                ?>

                                <?php if(isset($resultados_por_grupo['Receita Operacional'])): ?>
                                    <tr class="tree-tr">
                                        <td class="tree-td"><strong>(=) RECEITA OPERACIONAL</strong></td>
                                        <td class="text-right"><strong>
                                            <?php
                                            $subtotal_receitas = 0;
                                            foreach($resultados_por_grupo['Receita Operacional'] as $item) {
                                                $subtotal_receitas += $item['total_conta'];
                                            }
                                            echo format_money($subtotal_receitas, get_base_currency()->symbol);
                                            $total_receitas += $subtotal_receitas;
                                            ?>
                                        </strong></td>
                                    </tr>
                                    <?php foreach($resultados_por_grupo['Receita Operacional'] as $item): ?>
                                        <tr>
                                            <td style="padding-left: 30px;"><?php echo $item['nome_conta']; ?></td>
                                            <td class="text-right"><?php echo format_money($item['total_conta'], get_base_currency()->symbol); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php
                                // Função para renderizar grupos de despesas
                                function render_despesa_group($group_name, $label, &$resultados_por_grupo, &$total_despesas) {
                                    if(isset($resultados_por_grupo[$group_name])):
                                        $subtotal_despesas = 0;
                                        foreach($resultados_por_grupo[$group_name] as $item) {
                                            $subtotal_despesas += $item['total_conta'];
                                        }
                                        $total_despesas += $subtotal_despesas;
                                ?>
                                        <tr class="tree-tr">
                                            <td class="tree-td"><strong>(-) <?php echo $label; ?></strong></td>
                                            <td class="text-right"><strong>(<?php echo format_money($subtotal_despesas, get_base_currency()->symbol); ?>)</strong></td>
                                        </tr>
                                        <?php foreach($resultados_por_grupo[$group_name] as $item): ?>
                                            <tr>
                                                <td style="padding-left: 30px;"><?php echo $item['nome_conta']; ?></td>
                                                <td class="text-right">(<?php echo format_money($item['total_conta'], get_base_currency()->symbol); ?>)</td>
                                            </tr>
                                        <?php endforeach; ?>
                                <?php
                                    endif;
                                }
                                
                                render_despesa_group('Custo Variável', 'CUSTOS VARIÁVEIS', $resultados_por_grupo, $total_despesas);
                                render_despesa_group('Custo Fixo', 'CUSTOS FIXOS', $resultados_por_grupo, $total_despesas);
                                ?>

                                <tr class="tree-tr-total">
                                    <td class="tree-td-total"><strong>(=) RESULTADO LÍQUIDO</strong></td>
                                    <td class="text-right"><strong><?php echo format_money($total_receitas - $total_despesas, get_base_currency()->symbol); ?></strong></td>
                                </tr>
                            </tbody>
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
