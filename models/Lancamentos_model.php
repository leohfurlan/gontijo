<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lancamentos_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca um único lançamento pelo seu ID.
     * Usado para preencher o formulário de edição.
     * @param int $id O ID do lançamento
     * @return object|null O lançamento encontrado ou nulo
     */
    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'gf_lancamentos')->row();
    }

    /**
     * Busca todos os lançamentos para a tabela principal.
     * @return array
     */
    public function get_all_lancamentos()
    {
        $this->db->select(
            db_prefix() . 'gf_lancamentos.*, ' . 
            db_prefix() . 'gf_categorias.nome as categoria_nome, ' . 
            db_prefix() . 'gf_centros_custo.nome as centro_custo_nome'
        );
        $this->db->from(db_prefix() . 'gf_lancamentos');
        $this->db->join(db_prefix() . 'gf_categorias', db_prefix() . 'gf_categorias.id = ' . db_prefix() . 'gf_lancamentos.categoria_id', 'left');
        $this->db->join(db_prefix() . 'gf_centros_custo', db_prefix() . 'gf_centros_custo.id = ' . db_prefix() . 'gf_lancamentos.centro_custo_id', 'left');
        $this->db->order_by(db_prefix() . 'gf_lancamentos.id', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Adiciona um novo lançamento no banco de dados.
     * @param array $data Os dados vindos do formulário
     * @return int|boolean O ID do novo registro ou false em caso de falha
     */
    public function add($data)
    {
        // Trata os dados antes de inserir
        $data['valor'] = str_replace(['.'], '', $data['valor']);
        $data['valor'] = str_replace(',', '.', $data['valor']);
        $data['data_vencimento'] = to_sql_date($data['data_vencimento']);

        if (isset($data['marcar_pago'])) {
            $data['status'] = 'pago_recebido';
            $data['data_pagamento'] = to_sql_date($data['data_pagamento']);
            unset($data['marcar_pago']);
        } else {
            $data['status'] = 'a_pagar_receber';
            $data['data_pagamento'] = null;
        }

        $this->db->insert(db_prefix() . 'gf_lancamentos', $data);
        $insert_id = $this->db->insert_id();
        
        return $insert_id ? $insert_id : false;
    }

    /**
     * Atualiza um lançamento existente no banco de dados.
     * @param array $data Os dados vindos do formulário
     * @param int $id O ID do registro a ser atualizado
     * @return boolean True se a atualização foi bem-sucedida, false caso contrário
     */
    public function update($data, $id)
    {
        // Trata os dados da mesma forma que na adição
        $data['valor'] = str_replace(['.'], '', $data['valor']);
        $data['valor'] = str_replace(',', '.', $data['valor']);
        $data['data_vencimento'] = to_sql_date($data['data_vencimento']);

        if (isset($data['marcar_pago'])) {
            $data['status'] = 'pago_recebido';
            $data['data_pagamento'] = to_sql_date($data['data_pagamento']);
            unset($data['marcar_pago']);
        } else {
            $data['status'] = 'a_pagar_receber';
            $data['data_pagamento'] = null;
        }
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'gf_lancamentos', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Busca todas as categorias (plano de contas).
     * @return array
     */
    public function get_categorias()
    {
        $this->db->order_by('nome', 'ASC');
        return $this->db->get_where(db_prefix() . 'gf_categorias', ['ativo' => 1])->result_array();
    }

    /**
     * Busca todos os centros de custo.
     * @return array
     */
    public function get_centros_custo()
    {
        return $this->db->get_where(db_prefix() . 'gf_centros_custo', ['ativo' => 1])->result_array();
    }
}