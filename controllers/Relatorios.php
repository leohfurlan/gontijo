<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorios extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gestaofinanceira_model');
        $this->load->helper('gestaofinanceira');
    }

    public function fluxo_caixa()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) { access_denied('gestao_fazendas'); }
        $data['title'] = _l('gf_menu_fluxo_caixa');
        $data_inicio = $this->input->get('data_inicio') ?: date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ?: date('Y-m-t');
        $centro_custo = $this->input->get('centro_custo');
        $data['fluxo_caixa'] = $this->gestaofinanceira_model->get_fluxo_caixa($data_inicio, $data_fim, $centro_custo);
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        $data['filtros'] = ['data_inicio'=>$data_inicio,'data_fim'=>$data_fim,'centro_custo'=>$centro_custo];
        $this->load->view('admin/gestaofinanceira/fluxo_caixa', $data);
    }

    public function dre()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) { access_denied('gestao_fazendas'); }
        $data['title'] = _l('gf_menu_dre');
        $data_inicio = $this->input->get('data_inicio') ?: date('Y-m-01');
        $data_fim = $this->input->get('data_fim') ?: date('Y-m-t');
        $centro_custo = $this->input->get('centro_custo');
        $data['dre'] = $this->gestaofinanceira_model->get_dre($data_inicio, $data_fim, $centro_custo);
        $data['centros_custo'] = $this->gestaofinanceira_model->get_centros_custo();
        $data['filtros'] = ['data_inicio'=>$data_inicio,'data_fim'=>$data_fim,'centro_custo'=>$centro_custo];
        $this->load->view('admin/gestaofinanceira/dre', $data);
    }

    public function endividamento()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) { access_denied('gestao_fazendas'); }
        $data['title'] = _l('gf_menu_endividamento');
        $data['contratos'] = $this->gestaofinanceira_model->get_contratos_endividamento();
        $data['evolucao_divida'] = $this->gestaofinanceira_model->get_evolucao_divida();
        $this->load->view('admin/gestaofinanceira/endividamento', $data);
    }

    public function operacionais()
    {
        if (!has_permission('gestao_fazendas', '', 'view')) { access_denied('gestao_fazendas'); }
        $data['title'] = _l('gf_menu_relatorios_operacionais');
        $data['relatorios'] = $this->gestaofinanceira_model->get_relatorios_operacionais();
        $this->load->view('admin/gestaofinanceira/relatorios_operacionais', $data);
    }
}
