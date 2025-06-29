<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row _buttons">
                            <div class="col-md-4">
                                <select class="selectpicker" id="filtro_centro_custo" name="filtro_centro_custo" data-width="100%" data-none-selected-text="Todos os Centros de Custo">
                                    <option value="">Consolidado</option> <?php foreach ($centros_custo as $centro) { ?>
                                        <option value="<?php echo $centro['id']; ?>"><?php echo $centro['nome']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <div id="dashboard-widgets" class="row">
                            <p class="text-center">Carregando dados...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    $(function() {
        // Guarda uma referência ao container dos widgets
        var widgetsContainer = $('#dashboard-widgets');
        
        // Define a URL para a chamada AJAX
        var ajaxUrl = "<?php echo admin_url('gestaofinanceira/ajax_get_dashboard_data'); ?>";

        /**
         * NOVA FUNÇÃO: Formata um número para o padrão de moeda brasileiro (R$)
         * @param {number} number O número a ser formatado
         * @returns {string} O valor formatado como R$ 1.234,56
         */
        function formatarParaBRL(number) {
            if (isNaN(number)) {
                return 'R$ 0,00';
            }
            // Usa a API de internacionalização do navegador para a formatação correta
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(number);
        }

        // Função para carregar e atualizar os dados do dashboard
        function updateDashboard(centroCustoId) {
            widgetsContainer.html('<p class="text-center">Carregando dados...</p>');
            
            $.post(ajaxUrl, {
                centro_custo_id: centroCustoId,
                [csrfData.token_name]: csrfData.hash
            }).done(function(response) {
                var data = JSON.parse(response);

                // *** MUDANÇA AQUI: Usando a nova função formatarParaBRL ***
                var aPagar = formatarParaBRL(data.a_pagar_semana);
                var aReceber = formatarParaBRL(data.a_receber_semana);
                var resultadoMes = formatarParaBRL(data.resultado_mes);

                var resultadoClass = data.resultado_mes >= 0 ? 'text-success' : 'text-danger';

                var widgetsHtml = `
                    <div class="col-md-4 col-sm-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h3 class="bold">${resultadoMes}</h3>
                                <span class="${resultadoClass}">Resultado do Mês (Realizado)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h3 class="bold">${aPagar}</h3>
                                <span class="text-danger">A Pagar (Próximos 7 dias)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h3 class="bold">${aReceber}</h3>
                                <span class="text-success">A Receber (Próximos 7 dias)</span>
                            </div>
                        </div>
                    </div>
                `;

                widgetsContainer.html(widgetsHtml);
            });
        }

        $('#filtro_centro_custo').on('change', function() {
            var selectedId = $(this).val();
            updateDashboard(selectedId);
        });

        updateDashboard(''); 
    });
</script>

</body>
</html>