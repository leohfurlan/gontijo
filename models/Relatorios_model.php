<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorios_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca o saldo inicial consolidado de todas as contas em uma data específica.
     * @param  string $data_ate A data final para o cálculo do saldo.
     * @return float
     */
    public function get_saldo_inicial_em_data($data_ate)
    {
        $this->db->select('SUM(saldo_inicial) as total_saldo_inicial');
        $saldo_inicial_base = $this->db->get(db_prefix() . 'gf_contas_bancarias')->row()->total_saldo_inicial ?? 0;

        $this->db->select("SUM(CASE WHEN pc.tipo_conta = 'Receita' THEN lf.valor ELSE -lf.valor END) as movimentacao");
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('lf.data_liquidacao <', $data_ate);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        
        $movimentacao = $this->db->get()->row()->movimentacao ?? 0;

        return $saldo_inicial_base + $movimentacao;
    }

    /**
     * Busca e processa os dados para o relatório de Fluxo de Caixa em formato de matriz.
     * @param  string $data_inicio
     * @param  string $data_fim
     * @param  string $agrupamento 'daily' ou 'monthly'
     * @param  int|null $centro_custo
     * @return array
     */
    public function get_fluxo_caixa_matrix($data_inicio, $data_fim, $agrupamento = 'monthly', $centro_custo = null)
    {
        $format = ($agrupamento == 'daily') ? '%Y-%m-%d' : '%Y-%m';

        // Busca Lançamentos Realizados (liquidados)
        $this->db->select("pc.id as conta_id, pc.nome_conta, pc.tipo_conta, DATE_FORMAT(lf.data_liquidacao, '$format') as periodo, SUM(lf.valor) as total");
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('lf.data_liquidacao >=', $data_inicio);
        $this->db->where('lf.data_liquidacao <=', $data_fim);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        if ($centro_custo) {
            $this->db->where('lf.id_centro_custo', $centro_custo);
        }
        $this->db->group_by(['pc.id', 'periodo']);
        $realizado = $this->db->get()->result_array();

        // Busca Lançamentos Orçados (pendentes)
        $this->db->select("pc.id as conta_id, pc.nome_conta, pc.tipo_conta, DATE_FORMAT(lf.data_vencimento, '$format') as periodo, SUM(lf.valor) as total");
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('lf.data_vencimento >=', $data_inicio);
        $this->db->where('lf.data_vencimento <=', $data_fim);
        $this->db->where('lf.data_liquidacao IS NULL'); // Apenas os não liquidados
        if ($centro_custo) {
            $this->db->where('lf.id_centro_custo', $centro_custo);
        }
        $this->db->group_by(['pc.id', 'periodo']);
        $orcado = $this->db->get()->result_array();

        // Estrutura os dados em uma matriz para a view
        $matrix = [];
        foreach ($realizado as $row) {
            $matrix[$row['conta_id']][$row['periodo']]['realizado'] = $row['total'];
        }
        foreach ($orcado as $row) {
            $matrix[$row['conta_id']][$row['periodo']]['orcado'] = $row['total'];
        }

        return $matrix;
    }

    /**
     * Busca os dados para o relatório DRE (Demonstrativo de Resultado do Exercício).
     * @param  string $data_inicio
     * @param  string $data_fim
     * @param  int|null $centro_custo
     * @return array
     */
    public function get_dre($data_inicio, $data_fim, $centro_custo = null)
    {
        $this->db->select('pc.grupo_dre, pc.nome_conta, pc.tipo_conta, SUM(lf.valor) as total_conta');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        
        $this->db->where('lf.data_competencia >=', $data_inicio);
        $this->db->where('lf.data_competencia <=', $data_fim);
        $this->db->where('lf.tipo_lancamento', 'Realizado');
        
        if ($centro_custo) {
            $this->db->where('lf.id_centro_custo', $centro_custo);
        }
        
        $this->db->group_by('pc.grupo_dre, pc.nome_conta, pc.tipo_conta');
        $this->db->order_by('pc.grupo_dre, pc.codigo_conta', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Busca os contratos de endividamento ativos.
     * @return array
     */
    public function get_contratos_endividamento()
    {
        $this->db->select('e.*, ent.nome_razao_social as credor_nome');
        $this->db->from(db_prefix() . 'gf_endividamento e');
        $this->db->join(db_prefix() . 'gf_entidades ent', 'e.id_credor = ent.id', 'left');
        $this->db->order_by('e.data_contratacao', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Busca a evolução da dívida ao longo dos meses.
     * @return array
     */
    public function get_evolucao_divida()
    {
        $this->db->select("DATE_FORMAT(ep.data_vencimento, '%Y-%m') as mes, SUM(ep.valor_parcela) as total_parcelas");
        $this->db->from(db_prefix() . 'gf_endividamento_parcelas ep');
        $this->db->join(db_prefix() . 'gf_endividamento e', 'ep.id_endividamento = e.id', 'left');
        $this->db->where('e.status', 'Ativo');
        $this->db->group_by('mes');
        $this->db->order_by('mes', 'ASC');
        
        return $this->db->get()->result_array();
    }
}
