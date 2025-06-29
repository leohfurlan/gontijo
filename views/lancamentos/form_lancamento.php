<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <?php 
                            // Define a URL do formulário: se estiver editando, inclui o ID, senão, aponta para 'create'
                            $form_action = isset($lancamento) 
                                ? admin_url('gestaofinanceira/lancamentos/edit/' . $lancamento->id) 
                                : admin_url('gestaofinanceira/lancamentos/create');
                            echo form_open($form_action); 
                        ?>

                        <div class="form-group">
                            <label for="tipo" class="control-label">Tipo</label>
                            <select id="tipo" name="tipo" class="selectpicker" data-width="100%">
                                <?php
                                    // Define o valor selecionado se estiver editando
                                    $tipo_selecionado = isset($lancamento) ? $lancamento->tipo : '';
                                ?>
                                <option value="despesa" <?php if($tipo_selecionado == 'despesa'){echo 'selected';} ?>>Despesa</option>
                                <option value="receita" <?php if($tipo_selecionado == 'receita'){echo 'selected';} ?>>Receita</option>
                            </select>
                        </div>

                        <?php 
                            // Define o valor para os campos de texto se estiver editando
                            $descricao = isset($lancamento) ? $lancamento->descricao : '';
                            echo render_input('descricao', 'Descrição', $descricao); 
                            
                            $valor = isset($lancamento) ? number_format($lancamento->valor, 2, ',', '.') : '';
                            echo render_input('valor', 'Valor', $valor, 'text', ['data-type' => 'currency']); 
                            
                            $data_vencimento = isset($lancamento) ? _d($lancamento->data_vencimento) : '';
                            echo render_date_input('data_vencimento', 'Data de Vencimento', $data_vencimento);
                        ?>
                        
                        <?php 
                            // Define o valor selecionado para os dropdowns
                            $categoria_selecionada = isset($lancamento) ? $lancamento->categoria_id : '';
                            echo render_select('categoria_id', $categorias, ['id', 'nome'], 'Categoria', $categoria_selecionada); 
                            
                            $centro_custo_selecionado = isset($lancamento) ? $lancamento->centro_custo_id : '';
                            echo render_select('centro_custo_id', $centros_custo, ['id', 'nome'], 'Centro de Custo', $centro_custo_selecionado);
                        ?>

                        <?php 
                            // Verifica o status para marcar o checkbox e exibir a data de pagamento
                            $is_pago = isset($lancamento) && $lancamento->status == 'pago_recebido';
                        ?>
                        <div class="checkbox">
                            <input type="checkbox" id="marcar_pago" name="marcar_pago" value="1" <?php if($is_pago){echo 'checked';} ?>>
                            <label for="marcar_pago">Marcar como Pago/Recebido?</label>
                        </div>
                        
                        <div id="data_pagamento_wrapper" class="<?php if(!$is_pago){echo 'hidden';} ?>">
                             <?php 
                                $data_pagamento = isset($lancamento) ? _d($lancamento->data_pagamento) : '';
                                echo render_date_input('data_pagamento', 'Data de Pagamento', $data_pagamento); 
                             ?>
                        </div>

                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info">Salvar Lançamento</button>
                        </div>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {
        // Mostra/esconde o campo de data de pagamento
        $('#marcar_pago').on('change', function() {
            $('#data_pagamento_wrapper').toggleClass('hidden', !this.checked);
        });
    });
</script>
</body>
</html>