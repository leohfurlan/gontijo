<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão de Centros de Custo
 */
class Centroscusto_model extends App_Model
{
    private $table_name = 'gf_centros_custo';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca centros de custo, com opção de filtro.
     * @param  array  $where
     * @return array
     */
    public function get_centros_custo($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('nome', 'ASC');
        return $this->db->get(db_prefix() . $this->table_name)->result_array();
    }

    /**
     * Busca um único centro de custo pelo seu ID.
     * @param  int $id
     * @return array|null
     */
    public function get_centro_custo($id)
    {
        return $this->db->get_where(db_prefix() . $this->table_name, ['id' => $id])->row_array();
    }

    /**
     * Adiciona um novo centro de custo.
     * @param array $data
     * @return int|false
     */
    public function add_centro_custo($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . $this->table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Novo Centro de Custo Adicionado [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza os dados de um centro de custo.
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update_centro_custo($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Centro de Custo Atualizado [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta um centro de custo, se não estiver em uso.
     * @param  int $id
     * @return array
     */
    public function delete_centro_custo($id)
    {
        // Verificar se o centro de custo está sendo usado
        $this->db->where('id_centro_custo', $id);
        $lancamentos = $this->db->get(db_prefix() . 'gf_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            return [
                'success' => false,
                'message' => 'Não é possível excluir o centro de custo, pois ele já possui lançamentos financeiros associados.'
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Centro de Custo Excluído [ID: ' . $id . ']');
            return ['success' => true];
        }
        
        return ['success' => false];
    }
}
