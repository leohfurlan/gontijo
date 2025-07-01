<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'ativo-gado-form']); ?>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <div class="row">
                            <div class="col-md-8">
                                <?php echo render_input('descricao_lote', 'Descrição do Lote', (isset($ativo_gado) ? $ativo_gado['descricao_lote'] : ''), 'text', [], [], '', 'placeholder="Ex: Lote de Garrotes Nelore"'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php 
                                // CORREÇÃO: O array de categorias foi reestruturado para o formato correto
                                // que a função render_select espera (um array de arrays).
                                $categorias = [
                                    ['id' => 'Garrotes', 'name' => 'Garrotes'],
                                    ['id' => 'Novilhas', 'name' => 'Novilhas'],
                                    ['id' => 'Bezerros', 'name' => 'Bezerros'],
                                    ['id' => 'Vacas', 'name' => 'Vacas'],
                                    ['id' => 'Touros', 'name' => 'Touros'],
                                ];
                                // O terceiro parâmetro foi ajustado para ['id', 'name'] para corresponder à nova estrutura.
                                echo render_select('categoria', $categorias, ['id', 'name'], 'Categoria', (isset($ativo_gado) ? $ativo_gado['categoria'] : ''));
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('quantidade_cabecas', 'Quantidade de Cabeças', (isset($ativo_gado) ? $ativo_gado['quantidade_cabecas'] : ''), 'number'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('peso_medio_entrada', 'Peso Médio de Entrada (kg)', (isset($ativo_gado) ? $ativo_gado['peso_medio_entrada'] : ''), 'number'); ?>
                            </div>
                             <div class="col-md-4">
                                <?php echo render_input('custo_total_aquisicao', 'Custo Total de Aquisição', (isset($ativo_gado) ? $ativo_gado['custo_total_aquisicao'] : ''), 'number', ['step' => '0.01']); ?>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6">
                                <?php echo render_select('id_centro_custo', $centros_custo, ['id', 'nome'], 'Centro de Custo', (isset($ativo_gado) ? $ativo_gado['id_centro_custo'] : '')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_date_input('data_entrada', 'Data de Entrada', _d(isset($ativo_gado) ? $ativo_gado['data_entrada'] : date('Y-m-d'))); ?>
                            </div>
                        </div>
                        
                        <?php echo render_textarea('observacoes', 'Observações', (isset($ativo_gado) ? $ativo_gado['observacoes'] : '')); ?>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        // Validação do formulário
        appValidateForm($('#ativo-gado-form'), {
            descricao_lote: 'required',
            categoria: 'required',
            quantidade_cabecas: { required: true, digits: true },
            custo_total_aquisicao: { required: true, number: true },
            id_centro_custo: 'required',
            data_entrada: 'required'
        });
    });
</script>
</body>
</html>
