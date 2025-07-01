<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'conta-bancaria-form']); ?>
            <div class="col-md-6 col-md-offset-3">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo render_input('banco', 'Nome do Banco', $conta_bancaria['banco'] ?? ''); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('agencia', 'Agência', $conta_bancaria['agencia'] ?? ''); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('conta', 'Conta Corrente', $conta_bancaria['conta'] ?? ''); ?>
                            </div>
                        </div>

                        <?php echo render_select('id_centro_custo', $centros_custo, ['id', 'nome'], 'Centro de Custo', $conta_bancaria['id_centro_custo'] ?? ''); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('saldo_inicial', 'Saldo Inicial', $conta_bancaria['saldo_inicial'] ?? '0.00', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_date_input('data_saldo_inicial', 'Data do Saldo Inicial', _d($conta_bancaria['data_saldo_inicial'] ?? date('Y-m-d'))); ?>
                            </div>
                        </div>

                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="ativo" id="ativo" <?php if(isset($conta_bancaria) && $conta_bancaria['ativo'] == 1 || !isset($conta_bancaria)){echo 'checked';} ?>>
                            <label for="ativo">Ativo</label>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar Conta</button>
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
        appValidateForm($('#conta-bancaria-form'), {
            banco: 'required',
            agencia: 'required',
            conta: 'required',
            id_centro_custo: 'required',
            data_saldo_inicial: 'required'
        });
    });
</script>
</body>
</html>
