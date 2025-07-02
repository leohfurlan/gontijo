<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorios extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('relatorios_model');
        $this->load->model('centroscusto_model');
        $this->load->model('planocontas_model');
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
        
        // CORREÇÃO: As datas padrão agora são formatadas para o padrão do usuário.
        $data_inicio_str = $this->input->get('data_inicio') ?: _d(date('Y-m-01'));
        $data_fim_str = $this->input->get('data_fim') ?: _d(date('Y-m-t'));
        $centro_custo = $this->input->get('centro_custo');
        $agrupamento = $this->input->get('agrupamento') ?: 'monthly';

        // Prepara os dados para o model (converte para o formato do SQL)
        $data_inicio_sql = to_sql_date($data_inicio_str);
        $data_fim_sql = to_sql_date($data_fim_str);

        $data['saldo_inicial'] = $this->relatorios_model->get_saldo_inicial_em_data($data_inicio_sql);
        $data['plano_contas'] = $this->planocontas_model->get_plano_contas_hierarquico();
        $data['matrix_dados'] = $this->relatorios_model->get_fluxo_caixa_matrix($data_inicio_sql, $data_fim_sql, $agrupamento, $centro_custo);
        
        // Gera a lista de períodos para os cabeçalhos da tabela
        $periodos = [];
        $current = new DateTime($data_inicio_sql);
        $end = new DateTime($data_fim_sql);
        $format = ($agrupamento == 'daily') ? 'Y-m-d' : 'Y-m';
        $interval = ($agrupamento == 'daily') ? 'P1D' : 'P1M';

        while($current <= $end) {
            $periodos[] = $current->format($format);
            $current->add(new DateInterval($interval));
        }
        $data['periodos'] = $periodos;
        
        // Envia os filtros de volta para a view
        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        $data['filtros'] = [
            'data_inicio' => $data_inicio_str,
            'data_fim' => $data_fim_str,
            'centro_custo' => $centro_custo,
            'agrupamento' => $agrupamento
        ];
        
        $this->load->view('admin/relatorios/fluxo_caixa', $data);
    }

    public function dre()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }
        
        $data['title'] = _l('gf_menu_dre');

        // CORREÇÃO: As datas padrão agora são formatadas para o padrão do usuário.
        $data_inicio_str = $this->input->get('data_inicio') ?: _d(date('Y-m-01'));
        $data_fim_str = $this->input->get('data_fim') ?: _d(date('Y-m-t'));
        $centro_custo = $this->input->get('centro_custo');

        $data_inicio_sql = to_sql_date($data_inicio_str);
        $data_fim_sql = to_sql_date($data_fim_str);

        $data['dre'] = $this->relatorios_model->get_dre($data_inicio_sql, $data_fim_sql, $centro_custo);
        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        
        $data['filtros'] = [
            'data_inicio' => $data_inicio_str,
            'data_fim' => $data_fim_str,
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
