<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão de Lançamentos Financeiros
 */
class Lancamentos_model extends App_Model
{
    private $table_name = 'gf_lancamentos_financeiros';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca todos os lançamentos financeiros com dados relacionados.
     * @param  array  $where
     * @return array
     */
    public function get_all_lancamentos($where = [])
    {
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, e.nome_razao_social as entidade_nome, cc.nome as centro_custo_nome');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'gf_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->order_by('lf.data_vencimento', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Busca um único lançamento pelo seu ID, com todos os dados relacionados.
     * @param  int $id
     * @return array|null
     */
    public function get_lancamento($id)
    {
        $this->db->select('lf.*, pc.nome_conta, pc.tipo_conta, e.nome_razao_social as entidade_nome, cc.nome as centro_custo_nome, cb.banco, cb.agencia, cb.conta');
        $this->db->from(db_prefix() . $this->table_name . ' lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->db->join(db_prefix() . 'gf_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'gf_contas_bancarias cb', 'lf.id_conta_bancaria = cb.id', 'left');
        $this->db->where('lf.id', $id);
        
        return $this->db->get()->row_array();
    }

    /**
     * Adiciona um novo lançamento financeiro.
     * @param array $data
     * @return int|false
     */
    public function add_lancamento($data)
    {
        // CORREÇÃO: Verifica se o id_conta_bancaria está vazio e, se estiver, define como NULL.
        if (isset($data['id_conta_bancaria']) && empty($data['id_conta_bancaria'])) {
            $data['id_conta_bancaria'] = null;
        }

        $data['data_cadastro'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . $this->table_name, $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('Novo Lançamento Financeiro Adicionado [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza um lançamento financeiro.
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update_lancamento($id, $data)
    {
        // CORREÇÃO: Verifica se o id_conta_bancaria está vazio e, se estiver, define como NULL.
        if (isset($data['id_conta_bancaria']) && empty($data['id_conta_bancaria'])) {
            $data['id_conta_bancaria'] = null;
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Lançamento Financeiro Atualizado [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta um lançamento financeiro.
     * @param  int $id
     * @return boolean
     */
    public function delete_lancamento($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Lançamento Financeiro Excluído [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
}
