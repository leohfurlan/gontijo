<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca o saldo consolidado de todas as contas ativas.
     * Utiliza a view que criamos na instalação.
     */
    public function get_saldo_total_caixa()
    {
        $this->db->select_sum('saldo_atual');
        // CORREÇÃO: Usando o novo nome da view com prefixo 'gf_'.
        $this->db->from(db_prefix() . 'view_gf_saldo_contas_bancarias');
        $result = $this->db->get()->row();
        
        return $result->saldo_atual ?? 0;
    }

    /**
     * Busca o valor total de despesas com status 'A Pagar'.
     */
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

    /**
     * Busca o valor total de receitas com status 'A Receber'.
     */
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

    /**
     * Busca o total de receitas e despesas dos últimos X meses para o gráfico.
     */
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

    /**
     * Busca a evolução do fluxo de caixa (entradas - saídas) dos últimos X meses.
     */
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

    /**
     * Busca lançamentos a pagar/receber que vencem nos próximos X dias.
     */
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
}
