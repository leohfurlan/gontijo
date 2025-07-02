<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_saldo_total_caixa()
    {
        $this->db->select_sum('saldo_atual');
        $this->db->from(db_prefix() . 'view_saldo_contas_bancarias');
        $result = $this->db->get()->row();
        return $result->saldo_atual ?? 0;
    }

    public function get_total_a_pagar()
    {
        $this->db->select_sum('valor');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('pc.tipo_conta', 'Despesa');
        $this->db->where('lf.status', 'A Pagar');
        $result = $this->db->get()->row();
        return $result->valor ?? 0;
    }

    public function get_total_a_receber()
    {
        $this->db->select_sum('valor');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('pc.tipo_conta', 'Receita');
        $this->db->where('lf.status', 'A Receber');
        $result = $this->db->get()->row();
        return $result->valor ?? 0;
    }

    public function get_receitas_despesas_ultimos_meses($meses = 6)
    {
        $data_inicio = date('Y-m-01', strtotime("-{$meses} months"));
        $this->db->select("DATE_FORMAT(lf.data_competencia, '%Y-%m') as mes, pc.tipo_conta, SUM(lf.valor) as total");
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('lf.data_competencia >=', $data_inicio);
        $this->db->where('lf.tipo_lancamento', 'Realizado');
        $this->db->group_by(['mes', 'pc.tipo_conta']);
        $this->db->order_by('mes', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_evolucao_fluxo_caixa($meses = 12)
    {
        $data_inicio = date('Y-m-01', strtotime("-{$meses} months"));
        $this->db->select("DATE_FORMAT(lf.data_liquidacao, '%Y-%m') as mes, SUM(CASE WHEN pc.tipo_conta = 'Receita' THEN lf.valor ELSE -lf.valor END) as saldo_mes");
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('lf.data_liquidacao >=', $data_inicio);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        $this->db->group_by('mes');
        $this->db->order_by('mes', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_alertas_vencimento($dias = 7)
    {
        $data_limite = date('Y-m-d', strtotime("+{$dias} days"));
        $this->db->select('lf.descricao, lf.valor, lf.data_vencimento, pc.tipo_conta, e.nome_razao_social as entidade_nome');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'gf_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->where('lf.data_vencimento <=', $data_limite);
        $this->db->where('lf.data_vencimento >=', date('Y-m-d'));
        $this->db->where_in('lf.status', ['A Pagar', 'A Receber']);
        $this->db->order_by('lf.data_vencimento', 'ASC');
        return $this->db->get()->result_array();
    }
    /**
     * NOVO: Calcula o resultado (lucro/prejuízo) de um determinado período.
     * @param  int $dias O número de dias a considerar.
     * @return float
     */
    public function get_resultado_periodo($dias = 30)
    {
        $data_inicio = date('Y-m-d', strtotime("-{$dias} days"));

        $this->db->select("SUM(CASE WHEN pc.tipo_conta = 'Receita' THEN lf.valor ELSE -lf.valor END) as resultado");
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('lf.data_liquidacao >=', $data_inicio);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        
        $result = $this->db->get()->row();
        return $result->resultado ?? 0;
    }

    /**
     * NOVO: Busca os custos totais agrupados por categoria para o gráfico.
     * @return array
     */
    public function get_custos_por_categoria()
    {
        $this->db->select('pc.nome_conta, SUM(lf.valor) as total');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->where('pc.tipo_conta', 'Despesa');
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        $this->db->group_by('pc.id');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(5); // Limita aos 5 maiores custos para um gráfico mais limpo
        
        return $this->db->get()->result_array();
    }
}
