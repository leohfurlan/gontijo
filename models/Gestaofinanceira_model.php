<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gestaofinanceira_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca todos os centros de custo ativos no banco de dados.
     * Usado pelo filtro do Dashboard.
     * @return array
     */
    public function get_centros_custo()
    {
        return $this->db->get_where(db_prefix() . 'gf_centros_custo', ['ativo' => 1])->result_array();
    }

    /**
     * Busca os dados principais para os widgets do dashboard.
     * @param  mixed $centro_custo_id (null para consolidado)
     * @return array
     */
    public function get_dashboard_kpis($centro_custo_id = null)
    {
        $db_prefix_lancamentos = db_prefix() . 'gf_lancamentos';

        // Datas de referência
        $hoje = date('Y-m-d');
        $proximos_7_dias = date('Y-m-d', strtotime('+7 days'));
        $inicio_mes = date('Y-m-01');
        $fim_mes = date('Y-m-t');

        // --- KPI: A Pagar na Semana ---
        $this->db->select_sum('valor');
        $this->db->where('tipo', 'despesa');
        $this->db->where('status', 'a_pagar_receber');
        $this->db->where('data_vencimento >=', $hoje);
        $this->db->where('data_vencimento <=', $proximos_7_dias);
        if ($centro_custo_id) {
            $this->db->where('centro_custo_id', $centro_custo_id);
        }
        $a_pagar = $this->db->get($db_prefix_lancamentos)->row()->valor;

        // --- KPI: A Receber na Semana ---
        $this->db->select_sum('valor');
        $this->db->where('tipo', 'receita');
        $this->db->where('status', 'a_pagar_receber');
        $this->db->where('data_vencimento >=', $hoje);
        $this->db->where('data_vencimento <=', $proximos_7_dias);
        if ($centro_custo_id) {
            $this->db->where('centro_custo_id', $centro_custo_id);
        }
        $a_receber = $this->db->get($db_prefix_lancamentos)->row()->valor;

        // --- Gráfico: Total de Receitas no Mês (Realizadas) ---
        $this->db->select_sum('valor');
        $this->db->where('tipo', 'receita');
        $this->db->where('status', 'pago_recebido');
        $this->db->where('data_pagamento >=', $inicio_mes);
        $this->db->where('data_pagamento <=', $fim_mes);
        if ($centro_custo_id) {
            $this->db->where('centro_custo_id', $centro_custo_id);
        }
        $receitas_mes = $this->db->get($db_prefix_lancamentos)->row()->valor;

        // --- Gráfico: Total de Despesas no Mês (Realizadas) ---
        $this->db->select_sum('valor');
        $this->db->where('tipo', 'despesa');
        $this->db->where('status', 'pago_recebido');
        $this->db->where('data_pagamento >=', $inicio_mes);
        $this->db->where('data_pagamento <=', $fim_mes);
        if ($centro_custo_id) {
            $this->db->where('centro_custo_id', $centro_custo_id);
        }
        $despesas_mes = $this->db->get($db_prefix_lancamentos)->row()->valor;

        // Monta o array de retorno
        $data = [
            'a_pagar_semana'          => $a_pagar ?? 0,
            'a_receber_semana'        => $a_receber ?? 0,
            'receitas_mes_realizadas' => $receitas_mes ?? 0,
            'despesas_mes_realizadas' => $despesas_mes ?? 0,
        ];

        return $data;
    }
}