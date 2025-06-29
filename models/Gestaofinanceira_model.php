<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model principal do módulo Gestão de Fazendas
 */
class Gestaofinanceira_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ENTIDADES (Clientes/Fornecedores/Credores)
     */
    public function get_entidades($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('nome_razao_social', 'ASC');
        return $this->db->get(db_prefix() . 'tblfaz_entidades')->result_array();
    }

    public function get_entidade($id)
    {
        return $this->db->get_where(db_prefix() . 'tblfaz_entidades', ['id' => $id])->row_array();
    }

    public function add_entidade($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'tblfaz_entidades', $data);
        return $this->db->insert_id();
    }

    public function update_entidade($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'tblfaz_entidades', $data);
    }

    public function delete_entidade($id)
    {
        // Verificar se a entidade está sendo usada
        $this->db->where('id_entidade', $id);
        $lancamentos = $this->db->get(db_prefix() . 'tblfaz_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            return false; // Não pode excluir se há lançamentos
        }

        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'tblfaz_entidades');
    }

    /**
     * CENTROS DE CUSTO
     */
    public function get_centros_custo($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('nome', 'ASC');
        return $this->db->get(db_prefix() . 'tblfaz_centros_custo')->result_array();
    }

    public function get_centro_custo($id)
    {
        return $this->db->get_where(db_prefix() . 'tblfaz_centros_custo', ['id' => $id])->row_array();
    }

    /**
     * PLANO DE CONTAS
     */
    public function get_plano_contas_hierarquico()
    {
        $this->db->order_by('codigo_conta', 'ASC');
        $contas = $this->db->get(db_prefix() . 'tblfaz_plano_contas')->result_array();
        
        return $this->_build_tree($contas);
    }

    public function get_contas_pai()
    {
        $this->db->where('aceita_lancamento', 0);
        $this->db->order_by('codigo_conta', 'ASC');
        return $this->db->get(db_prefix() . 'tblfaz_plano_contas')->result_array();
    }

    public function get_contas_lancamento()
    {
        $this->db->where('aceita_lancamento', 1);
        $this->db->where('ativo', 1);
        $this->db->order_by('codigo_conta', 'ASC');
        return $this->db->get(db_prefix() . 'tblfaz_plano_contas')->result_array();
    }

    public function get_conta($id)
    {
        return $this->db->get_where(db_prefix() . 'tblfaz_plano_contas', ['id' => $id])->row_array();
    }

    public function add_conta($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'tblfaz_plano_contas', $data);
        return $this->db->insert_id();
    }

    public function update_conta($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'tblfaz_plano_contas', $data);
    }

    public function delete_conta($id)
    {
        // Verificar se a conta está sendo usada
        $this->db->where('id_plano_contas', $id);
        $lancamentos = $this->db->get(db_prefix() . 'tblfaz_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            return false; // Não pode excluir se há lançamentos
        }

        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'tblfaz_plano_contas');
    }

    /**
     * CONTAS BANCÁRIAS
     */
    public function get_contas_bancarias($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        $this->db->select('cb.*, cc.nome as centro_custo_nome');
        $this->db->from(db_prefix() . 'tblfaz_contas_bancarias cb');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'cb.id_centro_custo = cc.id', 'left');
        $this->db->order_by('cb.banco, cb.agencia, cb.conta', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_conta_bancaria($id)
    {
        $this->db->select('cb.*, cc.nome as centro_custo_nome');
        $this->db->from(db_prefix() . 'tblfaz_contas_bancarias cb');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'cb.id_centro_custo = cc.id', 'left');
        $this->db->where('cb.id', $id);
        
        return $this->db->get()->row_array();
    }

    public function add_conta_bancaria($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'tblfaz_contas_bancarias', $data);
        return $this->db->insert_id();
    }

    public function update_conta_bancaria($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'tblfaz_contas_bancarias', $data);
    }

    public function delete_conta_bancaria($id)
    {
        // Verificar se a conta está sendo usada
        $this->db->where('id_conta_bancaria', $id);
        $lancamentos = $this->db->get(db_prefix() . 'tblfaz_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            return false; // Não pode excluir se há lançamentos
        }

        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'tblfaz_contas_bancarias');
    }

    /**
     * ATIVOS DE GADO
     */
    public function get_ativos_gado($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        $this->db->select('ag.*, cc.nome as centro_custo_nome, lf.descricao as lancamento_descricao');
        $this->db->from(db_prefix() . 'tblfaz_ativos_gado ag');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'ag.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_lancamentos_financeiros lf', 'ag.id_lancamento_compra = lf.id', 'left');
        $this->db->order_by('ag.data_entrada', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function get_ativo_gado($id)
    {
        $this->db->select('ag.*, cc.nome as centro_custo_nome, lf.descricao as lancamento_descricao');
        $this->db->from(db_prefix() . 'tblfaz_ativos_gado ag');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'ag.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_lancamentos_financeiros lf', 'ag.id_lancamento_compra = lf.id', 'left');
        $this->db->where('ag.id', $id);
        
        return $this->db->get()->row_array();
    }

    public function get_lancamentos_compra_gado()
    {
        $this->db->select('lf.id, lf.descricao, lf.valor, lf.data_competencia');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->where('pc.nome_conta LIKE', '%gado%');
        $this->db->or_where('pc.nome_conta LIKE', '%bovino%');
        $this->db->order_by('lf.data_competencia', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function add_ativo_gado($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'tblfaz_ativos_gado', $data);
        return $this->db->insert_id();
    }

    public function update_ativo_gado($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'tblfaz_ativos_gado', $data);
    }

    public function delete_ativo_gado($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'tblfaz_ativos_gado');
    }

    /**
     * LANÇAMENTOS FINANCEIROS
     */
    public function get_lancamentos($where = [], $limit = null, $offset = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, e.nome_razao_social as entidade_nome, 
                          cc.nome as centro_custo_nome, cb.banco, cb.agencia, cb.conta');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_contas_bancarias cb', 'lf.id_conta_bancaria = cb.id', 'left');
        $this->db->order_by('lf.data_vencimento', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }

    public function get_lancamento($id)
    {
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, e.nome_razao_social as entidade_nome, 
                          cc.nome as centro_custo_nome, cb.banco, cb.agencia, cb.conta');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_contas_bancarias cb', 'lf.id_conta_bancaria = cb.id', 'left');
        $this->db->where('lf.id', $id);
        
        return $this->db->get()->row_array();
    }

    public function get_contas_pagar()
    {
        $this->db->select('lf.*, pc.nome_conta, e.nome_razao_social as entidade_nome, cc.nome as centro_custo_nome');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        $this->db->where('pc.tipo_conta', 'Despesa');
        $this->db->where_in('lf.status', ['A Pagar']);
        $this->db->order_by('lf.data_vencimento', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_contas_receber()
    {
        $this->db->select('lf.*, pc.nome_conta, e.nome_razao_social as entidade_nome, cc.nome as centro_custo_nome');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        $this->db->where('pc.tipo_conta', 'Receita');
        $this->db->where_in('lf.status', ['A Receber']);
        $this->db->order_by('lf.data_vencimento', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function add_lancamento($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'tblfaz_lancamentos_financeiros', $data);
        return $this->db->insert_id();
    }

    public function update_lancamento($id, $data)
    {
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'tblfaz_lancamentos_financeiros', $data);
    }

    public function delete_lancamento($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'tblfaz_lancamentos_financeiros');
    }

    /**
     * DASHBOARD - DADOS RESUMIDOS
     */
    public function get_saldo_total_caixa()
    {
        $this->db->select_sum('saldo_atual');
        $this->db->from(db_prefix() . 'view_saldo_contas_bancarias');
        $result = $this->db->get()->row_array();
        
        return $result['saldo_atual'] ?? 0;
    }

    public function get_total_a_pagar()
    {
        $this->db->select_sum('lf.valor');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->where('pc.tipo_conta', 'Despesa');
        $this->db->where('lf.status', 'A Pagar');
        $result = $this->db->get()->row_array();
        
        return $result['valor'] ?? 0;
    }

    public function get_total_a_receber()
    {
        $this->db->select_sum('lf.valor');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->where('pc.tipo_conta', 'Receita');
        $this->db->where('lf.status', 'A Receber');
        $result = $this->db->get()->row_array();
        
        return $result['valor'] ?? 0;
    }

    public function get_receitas_despesas_ultimos_meses($meses = 6)
    {
        $data_inicio = date('Y-m-01', strtotime("-{$meses} months"));
        
        $this->db->select("DATE_FORMAT(lf.data_competencia, '%Y-%m') as mes, 
                          pc.tipo_conta, 
                          SUM(lf.valor) as total");
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->where('lf.data_competencia >=', $data_inicio);
        $this->db->where('lf.tipo_lancamento', 'Realizado');
        $this->db->group_by(['mes', 'pc.tipo_conta']);
        $this->db->order_by('mes', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_evolucao_fluxo_caixa($meses = 12)
    {
        $data_inicio = date('Y-m-01', strtotime("-{$meses} months"));
        
        $this->db->select("DATE_FORMAT(lf.data_liquidacao, '%Y-%m') as mes, 
                          SUM(CASE WHEN pc.tipo_conta = 'Receita' THEN lf.valor ELSE -lf.valor END) as saldo_mes");
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->where('lf.data_liquidacao >=', $data_inicio);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        $this->db->group_by('mes');
        $this->db->order_by('mes', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_alertas_vencimento($dias = 7)
    {
        $data_limite = date('Y-m-d', strtotime("+{$dias} days"));
        
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, e.nome_razao_social as entidade_nome');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->join(db_prefix() . 'tblfaz_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->where('lf.data_vencimento <=', $data_limite);
        $this->db->where('lf.data_vencimento >=', date('Y-m-d'));
        $this->db->where_in('lf.status', ['A Pagar', 'A Receber']);
        $this->db->order_by('lf.data_vencimento', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * RELATÓRIOS
     */
    public function get_fluxo_caixa($data_inicio, $data_fim, $centro_custo = null)
    {
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, cc.nome as centro_custo_nome, 
                          cb.banco, cb.agencia, cb.conta');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'lf.id_centro_custo = cc.id');
        $this->db->join(db_prefix() . 'tblfaz_contas_bancarias cb', 'lf.id_conta_bancaria = cb.id', 'left');
        $this->db->where('lf.data_liquidacao >=', $data_inicio);
        $this->db->where('lf.data_liquidacao <=', $data_fim);
        $this->db->where('lf.data_liquidacao IS NOT NULL');
        
        if ($centro_custo) {
            $this->db->where('lf.id_centro_custo', $centro_custo);
        }
        
        $this->db->order_by('lf.data_liquidacao', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_dre($data_inicio, $data_fim, $centro_custo = null)
    {
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, pc.grupo_dre, cc.nome as centro_custo_nome');
        $this->db->from(db_prefix() . 'tblfaz_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'tblfaz_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->join(db_prefix() . 'tblfaz_centros_custo cc', 'lf.id_centro_custo = cc.id');
        $this->db->where('lf.data_competencia >=', $data_inicio);
        $this->db->where('lf.data_competencia <=', $data_fim);
        $this->db->where('lf.tipo_lancamento', 'Realizado');
        
        if ($centro_custo) {
            $this->db->where('lf.id_centro_custo', $centro_custo);
        }
        
        $this->db->order_by('pc.grupo_dre, pc.nome_conta', 'ASC');
        
        return $this->db->get()->result_array();
    }

    public function get_contratos_endividamento()
    {
        $this->db->select('e.*, ent.nome_razao_social as credor_nome');
        $this->db->from(db_prefix() . 'tblfaz_endividamento e');
        $this->db->join(db_prefix() . 'tblfaz_entidades ent', 'e.id_credor = ent.id');
        $this->db->order_by('e.data_contratacao', 'DESC');
        
        return $this->db->get()->result_array();
    }

    public function get_evolucao_divida()
    {
        $this->db->select("DATE_FORMAT(ep.data_vencimento, '%Y-%m') as mes, 
                          SUM(ep.valor_parcela) as total_parcelas");
        $this->db->from(db_prefix() . 'tblfaz_endividamento_parcelas ep');
        $this->db->join(db_prefix() . 'tblfaz_endividamento e', 'ep.id_endividamento = e.id');
        $this->db->where('e.status', 'Ativo');
        $this->db->group_by('mes');
        $this->db->order_by('mes', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * CONFIGURAÇÕES
     */
    public function get_configuracoes()
    {
        $this->db->order_by('chave', 'ASC');
        $result = $this->db->get(db_prefix() . 'tblfaz_configuracoes')->result_array();
        
        $configuracoes = [];
        foreach ($result as $config) {
            $configuracoes[$config['chave']] = $config['valor'];
        }
        
        return $configuracoes;
    }

    public function update_configuracoes($data)
    {
        $success = true;
        
        foreach ($data as $chave => $valor) {
            $this->db->where('chave', $chave);
            $exists = $this->db->get(db_prefix() . 'tblfaz_configuracoes')->num_rows();
            
            if ($exists > 0) {
                $this->db->where('chave', $chave);
                $result = $this->db->update(db_prefix() . 'tblfaz_configuracoes', [
                    'valor' => $valor,
                    'data_atualizacao' => date('Y-m-d H:i:s')
                ]);
            } else {
                $result = $this->db->insert(db_prefix() . 'tblfaz_configuracoes', [
                    'chave' => $chave,
                    'valor' => $valor,
                    'data_cadastro' => date('Y-m-d H:i:s')
                ]);
            }
            
            if (!$result) {
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * MÉTODOS AUXILIARES
     */
    private function _build_tree($elements, $parentId = null)
    {
        $branch = [];
        
        foreach ($elements as $element) {
            if ($element['id_pai'] == $parentId) {
                $children = $this->_build_tree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        
        return $branch;
    }
}

