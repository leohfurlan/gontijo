<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'entidade-form']); ?>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php // CORREÇÃO: Usando a variável '$entidade' (singular) para preencher os valores. ?>
                        <?php echo render_input('nome_razao_social', 'Nome / Razão Social', (isset($entidade) ? $entidade['nome_razao_social'] : '')); ?>
                        <?php echo render_input('cpf_cnpj', 'CPF / CNPJ', (isset($entidade) ? $entidade['cpf_cnpj'] : '')); ?>

                        <?php 
                        $tipos = [
                            ['id' => 'Cliente', 'name' => 'Cliente'],
                            ['id' => 'Fornecedor', 'name' => 'Fornecedor'],
                            ['id' => 'Credor', 'name' => 'Credor'],
                            ['id' => 'Outro', 'name' => 'Outro'],
                        ];
                        echo render_select('tipo_entidade', $tipos, ['id', 'name'], 'Tipo de Entidade', (isset($entidade) ? $entidade['tipo_entidade'] : 'Cliente'));
                        ?>

                        <hr />

                        <?php echo render_input('contato_principal', 'Contato Principal', (isset($entidade) ? $entidade['contato_principal'] : '')); ?>
                        <?php echo render_input('telefone', 'Telefone', (isset($entidade) ? $entidade['telefone'] : '')); ?>
                        <?php echo render_input('email', 'E-mail', (isset($entidade) ? $entidade['email'] : ''), 'email'); ?>
                        <?php echo render_textarea('endereco', 'Endereço', (isset($entidade) ? $entidade['endereco'] : '')); ?>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar Entidade</button>
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
        appValidateForm($('#entidade-form'), {
            nome_razao_social: 'required',
            tipo_entidade: 'required'
        });
    });
</script>
</body>
</html>
