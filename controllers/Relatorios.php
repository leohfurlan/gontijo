<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorios extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('relatorios_model');
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }
        $data['title'] = 'Central de Relatórios';
        $this->load->view('admin/relatorios/relatorios_landing', $data);
    }

    public function fluxo_caixa()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['title'] = _l('gf_menu_fluxo_caixa');
        
        // CORREÇÃO: Converte as datas para o formato SQL antes de as usar.
        $data_inicio = $this->input->get('data_inicio') ? to_sql_date($this->input->get('data_inicio')) : date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ? to_sql_date($this->input->get('data_fim')) : date('Y-m-t');
        $centro_custo = $this->input->get('centro_custo');

        $data['fluxo_caixa'] = $this->relatorios_model->get_fluxo_caixa($data_inicio, $data_fim, $centro_custo);
        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        
        // Envia as datas no formato original para preencher os filtros na view
        $data['filtros'] = [
            'data_inicio' => $this->input->get('data_inicio') ?: date(get_current_date_format(true)),
            'data_fim' => $this->input->get('data_fim') ?: date(get_current_date_format(true)),
            'centro_custo' => $centro_custo
        ];
        
        $this->load->view('admin/relatorios/fluxo_caixa', $data);
    }

    public function dre()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }
        
        $data['title'] = _l('gf_menu_dre');

        // CORREÇÃO: Converte as datas para o formato SQL antes de as usar.
        $data_inicio = $this->input->get('data_inicio') ? to_sql_date($this->input->get('data_inicio')) : date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ? to_sql_date($this->input->get('data_fim')) : date('Y-m-t');
        $centro_custo = $this->input->get('centro_custo');

        $data['dre'] = $this->relatorios_model->get_dre($data_inicio, $data_fim, $centro_custo);
        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        
        // Envia as datas no formato original para preencher os filtros na view
        $data['filtros'] = [
            'data_inicio' => $this->input->get('data_inicio') ?: date(get_current_date_format(true)),
            'data_fim' => $this->input->get('data_fim') ?: date(get_current_date_format(true)),
            'centro_custo' => $centro_custo
        ];
        
        $this->load->view('admin/relatorios/dre', $data);
    }

    public function endividamento()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['title'] = _l('gf_menu_endividamento');
        $data['contratos'] = $this->relatorios_model->get_contratos_endividamento();
        $data['evolucao_divida'] = $this->relatorios_model->get_evolucao_divida();
        
        $this->load->view('admin/relatorios/endividamento', $data);
    }

    public function operacionais()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['title'] = _l('gf_menu_relatorios_operacionais');
        $this->load->view('admin/relatorios/relatorios_operacionais', $data);
    }
}
