<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão de Entidades (Clientes, Fornecedores, etc.)
 */
class Entidades_model extends App_Model
{
    private $table_name = 'gf_entidades';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca uma ou mais entidades com base em um filtro.
     * @param  array  $where
     * @return array
     */
    public function get_entidades($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('nome_razao_social', 'ASC');
        return $this->db->get(db_prefix() . $this->table_name)->result_array();
    }

    /**
     * Busca uma única entidade pelo seu ID.
     * @param  int $id
     * @return array|null
     */
    public function get_entidade($id)
    {
        return $this->db->get_where(db_prefix() . $this->table_name, ['id' => $id])->row_array();
    }

    /**
     * Adiciona uma nova entidade no banco de dados.
     * @param array $data
     * @return int|false O ID da entidade inserida ou false em caso de falha.
     */
    public function add_entidade($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . $this->table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Nova Entidade Adicionada [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza os dados de uma entidade.
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update_entidade($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Entidade Atualizada [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta uma entidade, se não estiver em uso.
     * @param  int $id
     * @return boolean
     */
    public function delete_entidade($id)
    {
        // CORREÇÃO: Verificando na tabela de lançamentos com o prefixo correto.
        $this->db->where('id_entidade', $id);
        $lancamentos = $this->db->get(db_prefix() . 'gf_lancamentos_financeiros')->num_rows();
        
        if ($lancamentos > 0) {
            // Não pode excluir se há lançamentos associados.
            return [
                'success' => false,
                'message' => 'Não é possível excluir a entidade, pois ela já possui lançamentos financeiros associados.'
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Entidade Excluída [ID: ' . $id . ']');
            return ['success' => true];
        }
        
        return ['success' => false];
    }
}
