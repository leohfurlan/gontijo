<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gestaofinanceira extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // Carregando todos os models que o dashboard precisará
        $this->load->model('dashboard_model');
        $this->load->model('endividamento_model'); // Assumindo que você tem este model
        $this->load->model('ativosgado_model');   // Assumindo que você tem este model
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['title'] = _l('gf_menu_dashboard');

        // --- KPIs Financeiros Principais ---
        $data['saldo_caixa']        = $this->dashboard_model->get_saldo_total_caixa();
        $data['total_pagar']        = $this->dashboard_model->get_total_a_pagar();
        $data['total_receber']      = $this->dashboard_model->get_total_a_receber();
        $data['resultado_periodo']  = $this->dashboard_model->get_resultado_periodo(30); // Novo: Lucro/Prejuízo dos últimos 30 dias

        // --- KPI de Endividamento ---
        $data['endividamento_total'] = $this->endividamento_model->get_saldo_devedor_total(); // Novo: Saldo devedor total

        // --- KPIs de Ativos (Gado) ---
        $data['total_rebanho']      = $this->ativosgado_model->get_total_rebanho(); // Novo: Total de animais
        $data['valor_ativo_gado']   = $this->ativosgado_model->get_valor_total_ativo(); // Novo: Valor total do rebanho
        $data['custo_medio_animal'] = $this->ativosgado_model->get_custo_medio_por_animal(); // Novo: Custo médio por cabeça

        // --- Dados para Gráficos ---
        $data['receitas_despesas_chart'] = $this->dashboard_model->get_receitas_despesas_ultimos_meses(6);
        $data['custos_categoria_chart']  = $this->dashboard_model->get_custos_por_categoria(); // Novo: Custos agrupados por categoria

        // --- Alertas e Vencimentos ---
        $data['alertas_vencimento'] = $this->dashboard_model->get_alertas_vencimento(7);

        $this->load->view('admin/gestaofinanceira/dashboard', $data);
    }
}
