<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão de Contas Bancárias
 */
class Contasbancarias_model extends App_Model
{
    private $table_name = 'gf_contas_bancarias';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca contas bancárias, com opção de filtro, e inclui o saldo atual.
     * @param  array  $where
     * @return array
     */
    public function get_contas_bancarias($where = [])
    {
        // CORREÇÃO: A consulta agora junta-se à view de saldos para buscar o saldo atual.
        // O nome da view foi corrigido para 'view_saldo_contas_bancarias'.
        $this->db->select('cb.*, cc.nome as centro_custo_nome, CONCAT(cb.banco, " (", cb.conta, ")") as nome_formatado, vs.saldo_atual');
        $this->db->from(db_prefix() . $this->table_name . ' cb');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'cb.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'view_saldo_contas_bancarias vs', 'vs.id = cb.id', 'left');


        if (!empty($where)) {
            if (isset($where['ativo'])) {
                $this->db->where('cb.ativo', $where['ativo']);
                unset($where['ativo']);
            }
            if (!empty($where)) {
                $this->db->where($where);
            }
        }
        
        $this->db->order_by('cb.banco, cb.agencia, cb.conta', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Busca uma única conta bancária pelo seu ID, incluindo o saldo atual.
     * @param  int $id
     * @return array|null
     */
    public function get_conta_bancaria($id)
    {
        // CORREÇÃO: O nome da view foi corrigido para 'view_saldo_contas_bancarias'.
        $this->db->select('cb.*, cc.nome as centro_custo_nome, vs.saldo_atual');
        $this->db->from(db_prefix() . $this->table_name . ' cb');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'cb.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'view_saldo_contas_bancarias vs', 'vs.id = cb.id', 'left');
        $this->db->where('cb.id', $id);
        
        return $this->db->get()->row_array();
    }

    /**
     * Adiciona uma nova conta bancária.
     * @param array $data
     * @return int|false
     */
    public function add_conta_bancaria($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . $this->table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Nova Conta Bancária Adicionada [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza os dados de uma conta bancária.
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update_conta_bancaria($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Conta Bancária Atualizada [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta uma conta bancária, se não estiver em uso.
     * @param  int $id
     * @return array
     */
    public function delete_conta_bancaria($id)
    {
        // Verificar se a conta está sendo usada em lançamentos
        $this->db->where('id_conta_bancaria', $id);
        $lancamentos = $this->db->get(db_prefix() . 'gf_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            return [
                'success' => false,
                'message' => 'Não é possível excluir a conta, pois ela já possui lançamentos financeiros associados.'
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Conta Bancária Excluída [ID: ' . $id . ']');
            return ['success' => true];
        }
        
        return ['success' => false];
    }
}
