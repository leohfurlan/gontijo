<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a geração de Relatórios Financeiros
 */
class Relatorios_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca os dados para o relatório de Fluxo de Caixa.
     * @param  string $data_inicio
     * @param  string $data_fim
     * @param  int|null $centro_custo
     * @return array
     */
    public function get_fluxo_caixa($data_inicio, $data_fim, $centro_custo = null)
    {
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, cc.nome as centro_custo_nome, cb.banco, cb.agencia, cb.conta');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'gf_contas_bancarias cb', 'lf.id_conta_bancaria = cb.id', 'left');
        
        $this->db->where('lf.data_liquidacao >=', $data_inicio);
        $this->db->where('lf.data_liquidacao <=', $data_fim);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        
        if ($centro_custo) {
            $this->db->where('lf.id_centro_custo', $centro_custo);
        }
        
        $this->db->order_by('lf.data_liquidacao', 'ASC');
        
        return $this->db->get()->result_array();
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
