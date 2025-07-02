<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'contrato-form']); ?>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo render_select('id_credor', $credores, ['id', 'nome_razao_social'], 'Credor', $contrato['id_credor'] ?? ''); ?>
                        <?php echo render_input('numero_contrato', 'Número do Contrato', $contrato['numero_contrato'] ?? ''); ?>
                        <?php echo render_input('descricao', 'Descrição', $contrato['descricao'] ?? ''); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('valor_contrato', 'Valor do Contrato', $contrato['valor_contrato'] ?? '', 'number', ['step' => '0.01']); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('taxa_juros_aa', 'Taxa de Juros (% a.a.)', $contrato['taxa_juros_aa'] ?? '', 'number', ['step' => '0.00001']); ?>
                            </div>
                        </div>

                        <?php echo render_date_input('data_contratacao', 'Data da Contratação', _d($contrato['data_contratacao'] ?? date('Y-m-d'))); ?>
                        
                        <?php echo render_select('status', [['id'=>'Ativo','name'=>'Ativo'],['id'=>'Liquidado','name'=>'Liquidado'],['id'=>'Cancelado','name'=>'Cancelado']], ['id','name'], 'Status', $contrato['status'] ?? 'Ativo'); ?>

                        <?php echo render_textarea('observacoes', 'Observações', $contrato['observacoes'] ?? ''); ?>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar Contrato</button>
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
        appValidateForm($('#contrato-form'), {
            id_credor: 'required',
            numero_contrato: 'required',
            valor_contrato: { required: true, number: true },
            data_contratacao: 'required'
        });
    });
</script>
</body>
</html>
