<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão do Plano de Contas
 */
class Planocontas_model extends App_Model
{
    private $table_name = 'gf_plano_contas';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca todas as contas e as organiza em uma estrutura de árvore (hierarquia).
     * @return array
     */
    public function get_plano_contas_hierarquico()
    {
        $this->db->order_by('codigo_conta', 'ASC');
        $contas = $this->db->get(db_prefix() . $this->table_name)->result_array();
        
        return $this->_build_tree($contas);
    }

    /**
     * Busca apenas as contas sintéticas (contas-pai, que não aceitam lançamentos).
     * @return array
     */
    public function get_contas_pai()
    {
        $this->db->where('aceita_lancamento', 0);
        $this->db->order_by('codigo_conta', 'ASC');
        return $this->db->get(db_prefix() . $this->table_name)->result_array();
    }

    /**
     * Busca apenas as contas analíticas (que aceitam lançamentos).
     * @return array
     */
    public function get_contas_lancamento()
    {
        $this->db->where('aceita_lancamento', 1);
        $this->db->where('ativo', 1);
        $this->db->order_by('codigo_conta', 'ASC');
        return $this->db->get(db_prefix() . $this->table_name)->result_array();
    }

    /**
     * Busca uma única conta pelo seu ID.
     * @param  int $id
     * @return array|null
     */
    public function get_conta($id)
    {
        return $this->db->get_where(db_prefix() . $this->table_name, ['id' => $id])->row_array();
    }

    /**
     * Adiciona uma nova conta ao plano de contas.
     * @param array $data
     * @return int|false
     */
    public function add_conta($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . $this->table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Nova Conta Adicionada ao Plano de Contas [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza os dados de uma conta.
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update_conta($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Conta do Plano de Contas Atualizada [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta uma conta, se não estiver em uso.
     * @param  int $id
     * @return array
     */
    public function delete_conta($id)
    {
        // Verificar se a conta está sendo usada em lançamentos
        $this->db->where('id_plano_contas', $id);
        $lancamentos = $this->db->get(db_prefix() . 'gf_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            return [
                'success' => false,
                'message' => 'Não é possível excluir a conta, pois ela já possui lançamentos financeiros associados.'
            ];
        }

        // Verificar se a conta é pai de outras contas
        $this->db->where('id_pai', $id);
        $children = $this->db->get(db_prefix() . $this->table_name)->num_rows();

        if ($children > 0) {
            return [
                'success' => false,
                'message' => 'Não é possível excluir a conta, pois ela é uma conta-pai para outras contas no plano.'
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Conta Excluída do Plano de Contas [ID: ' . $id . ']');
            return ['success' => true];
        }
        
        return ['success' => false];
    }

    /**
     * Método auxiliar recursivo para construir a árvore de contas.
     * @param  array $elements
     * @param  int|null $parentId
     * @return array
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
