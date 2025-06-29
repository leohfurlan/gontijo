<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gestaofinanceira_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) {
            access_denied('gestao_fazendas');
        }

        $data['title'] = _l('gf_dashboard_title');
        $data['saldo_caixa'] = $this->gestaofinanceira_model->get_saldo_total_caixa();
        $data['total_pagar'] = $this->gestaofinanceira_model->get_total_a_pagar();
        $data['total_receber'] = $this->gestaofinanceira_model->get_total_a_receber();
        $data['receitas_despesas'] = $this->gestaofinanceira_model->get_receitas_despesas_ultimos_meses(6);
        $data['evolucao_fluxo'] = $this->gestaofinanceira_model->get_evolucao_fluxo_caixa(12);
        $data['alertas_vencimento'] = $this->gestaofinanceira_model->get_alertas_vencimento(7);

        $this->load->view('admin/gestaofinanceira/dashboard', $data);
    }
}
