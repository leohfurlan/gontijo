<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'centro-custo-form']); ?>
            <div class="col-md-6 col-md-offset-3">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo render_input('nome', 'Nome do Centro de Custo', $centro_custo['nome'] ?? ''); ?>
                        
                        <?php 
                        $tipos = [
                            ['id' => 'Operacional', 'name' => 'Operacional'],
                            ['id' => 'Administrativo', 'name' => 'Administrativo'],
                        ];
                        echo render_select('tipo', $tipos, ['id', 'name'], 'Tipo', $centro_custo['tipo'] ?? 'Operacional');
                        ?>

                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="ativo" id="ativo" <?php if(isset($centro_custo) && $centro_custo['ativo'] == 1 || !isset($centro_custo)){echo 'checked';} ?>>
                            <label for="ativo">Ativo</label>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar Centro de Custo</button>
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
        appValidateForm($('#centro-custo-form'), {
            nome: 'required',
            tipo: 'required'
        });
    });
</script>
</body>
</html>
