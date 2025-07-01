<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'lancamento-form']); ?>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php $value = (isset($lancamento) ? $lancamento['descricao'] : ''); ?>
                        <?php echo render_input('descricao', 'Descrição do Lançamento', $value); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php $value = (isset($lancamento) ? $lancamento['valor'] : ''); ?>
                                <?php echo render_input('valor', 'Valor', $value, 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $value = (isset($lancamento) ? $lancamento['data_vencimento'] : date('Y-m-d')); ?>
                                <?php echo render_date_input('data_vencimento', 'Data de Vencimento', _d($value)); ?>
                            </div>
                        </div>

                        <?php $selected = (isset($lancamento) ? $lancamento['id_plano_contas'] : ''); ?>
                        <?php echo render_select('id_plano_contas', $contas, ['id', 'nome_conta'], 'Categoria (Plano de Contas)', $selected); ?>

                        <?php $selected = (isset($lancamento) ? $lancamento['id_centro_custo'] : ''); ?>
                        <?php echo render_select('id_centro_custo', $centros_custo, ['id', 'nome'], 'Centro de Custo', $selected); ?>

                        <?php $selected = (isset($lancamento) ? $lancamento['id_entidade'] : ''); ?>
                        <?php echo render_select('id_entidade', $entidades, ['id', 'nome_razao_social'], 'Entidade (Cliente/Fornecedor)', $selected, [], [], '', 'true'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $status_options = [
                                    ['id' => 'A Pagar', 'name' => 'A Pagar'],
                                    ['id' => 'Pago', 'name' => 'Pago'],
                                    ['id' => 'A Receber', 'name' => 'A Receber'],
                                    ['id' => 'Recebido', 'name' => 'Recebido'],
                                    ['id' => 'Cancelado', 'name' => 'Cancelado'],
                                ];
                                $selected = (isset($lancamento) ? $lancamento['status'] : 'A Pagar');
                                echo render_select('status', $status_options, ['id', 'name'], 'Status', $selected);
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php $value = (isset($lancamento) ? $lancamento['data_liquidacao'] : ''); ?>
                                <?php echo render_date_input('data_liquidacao', 'Data de Liquidação (Pagamento)', _d($value)); ?>
                            </div>
                        </div>
                        
                        <?php $value = (isset($lancamento) ? $lancamento['observacoes'] : ''); ?>
                        <?php echo render_textarea('observacoes', 'Observações', $value); ?>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar Lançamento</button>
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
        appValidateForm($('#lancamento-form'), {
            descricao: 'required',
            valor: { required: true, number: true },
            data_vencimento: 'required',
            id_plano_contas: 'required',
            id_centro_custo: 'required',
            status: 'required'
        });
    });
</script>
</body>
</html>
