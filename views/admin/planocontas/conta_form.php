<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'plano-conta-form']); ?>
            <div class="col-md-6 col-md-offset-3">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo render_input('codigo_conta', 'Código da Conta', $conta['codigo_conta'] ?? ''); ?>
                        <?php echo render_input('nome_conta', 'Nome da Conta', $conta['nome_conta'] ?? ''); ?>
                        
                        <?php echo render_select('id_pai', $contas_pai, ['id', 'nome_conta'], 'Conta Pai (Sintética)', $conta['id_pai'] ?? ''); ?>

                        <?php echo render_select('tipo_conta', [['id'=>'Receita','name'=>'Receita'],['id'=>'Despesa','name'=>'Despesa']], ['id','name'], 'Tipo de Conta', $conta['tipo_conta'] ?? ''); ?>
                        
                        <?php echo render_input('grupo_dre', 'Grupo DRE', $conta['grupo_dre'] ?? ''); ?>
                        
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="aceita_lancamento" id="aceita_lancamento" <?php if(isset($conta) && $conta['aceita_lancamento'] == 1 || !isset($conta)){echo 'checked';} ?>>
                            <label for="aceita_lancamento">Aceita Lançamentos (Analítica)</label>
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
        appValidateForm($('#plano-conta-form'), {
            codigo_conta: 'required',
            nome_conta: 'required',
            tipo_conta: 'required'
        });
    });
</script>
</body>
</html>
