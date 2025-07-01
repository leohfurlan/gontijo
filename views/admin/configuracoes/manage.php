<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <?php echo form_open($this->uri->uri_string(), ['id' => 'configuracoes-form']); ?>
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo $title; ?>
                        </h4>
                        <hr class="hr-panel-heading" />

                        <h4>Configurações Gerais</h4>
                        <p class="text-muted">Ajustes gerais do módulo de gestão financeira.</p>

                        <?php
                        // Moeda Padrão
                        $value = (isset($configuracoes['moeda_padrao']) ? $configuracoes['moeda_padrao'] : 'BRL');
                        echo render_input('moeda_padrao', 'Moeda Padrão (Ex: BRL, USD)', $value, 'text');
                        
                        // Formato de Data
                        $value = (isset($configuracoes['formato_data']) ? $configuracoes['formato_data'] : 'd/m/Y');
                        echo render_input('formato_data', 'Formato de Data (Ex: d/m/Y)', $value, 'text');
                        ?>
                        
                        <hr />
                        
                        <h4>Configurações de Rateio</h4>
                        <p class="text-muted">Percentuais para rateio de despesas administrativas entre os centros de custo.</p>

                        <?php
                        // Percentual Rateio Jacamim
                        $value = (isset($configuracoes['percentual_rateio_jacamim']) ? $configuracoes['percentual_rateio_jacamim'] : '50');
                        echo render_input('percentual_rateio_jacamim', 'Percentual de Rateio - Fazenda Jacamim (%)', $value, 'number', ['min' => 0, 'max' => 100, 'step' => '0.01']);
                        
                        // Percentual Rateio Marape
                        $value = (isset($configuracoes['percentual_rateio_marape']) ? $configuracoes['percentual_rateio_marape'] : '50');
                        echo render_input('percentual_rateio_marape', 'Percentual de Rateio - Fazenda Marape (%)', $value, 'number', ['min' => 0, 'max' => 100, 'step' => '0.01']);
                        ?>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Salvar Configurações</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        // Validação do formulário
        appValidateForm($('#configuracoes-form'), {
            moeda_padrao: 'required',
            formato_data: 'required',
            percentual_rateio_jacamim: { required: true, number: true, min: 0, max: 100 },
            percentual_rateio_marape: { required: true, number: true, min: 0, max: 100 }
        });
    });
</script>
</body>
</html>
