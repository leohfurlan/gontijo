<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <!-- Cartão Fluxo de Caixa -->
                            <div class="col-md-4">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <i class="fa fa-line-chart fa-3x" aria-hidden="true"></i>
                                        <h4 class="mtop20">Fluxo de Caixa</h4>
                                        <p>Analise as entradas e saídas de caixa num período específico.</p>
                                        <a href="<?php echo admin_url('gestaofinanceira/relatorios/fluxo_caixa'); ?>" class="btn btn-info">Acessar Relatório</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Cartão DRE -->
                            <div class="col-md-4">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <i class="fa fa-pie-chart fa-3x" aria-hidden="true"></i>
                                        <h4 class="mtop20">DRE - Demonstrativo de Resultado</h4>
                                        <p>Visualize o confronto entre receitas, custos e despesas, apurando o lucro ou prejuízo.</p>
                                        <a href="<?php echo admin_url('gestaofinanceira/relatorios/dre'); ?>" class="btn btn-info">Acessar Relatório</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Cartão Endividamento -->
                            <div class="col-md-4">
                                <div class="panel_s">
                                    <div class="panel-body text-center">
                                        <i class="fa fa-credit-card fa-3x" aria-hidden="true"></i>
                                        <h4 class="mtop20">Endividamento</h4>
                                        <p>Acompanhe os seus contratos de dívida e a evolução dos pagamentos.</p>
                                        <a href="<?php echo admin_url('gestaofinanceira/relatorios/endividamento'); ?>" class="btn btn-info">Acessar Relatório</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
