<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão de Ativos de Gado
 */
class Ativosgado_model extends App_Model
{
    private $table_name = 'gf_ativos_gado';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca lotes de gado, com opção de filtro.
     * @param  array  $where
     * @return array
     */
    public function get_ativos_gado($where = [])
    {
        $this->db->select('ag.*, cc.nome as centro_custo_nome, lf.descricao as lancamento_descricao');
        $this->db->from(db_prefix() . $this->table_name . ' ag');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'ag.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'gf_lancamentos_financeiros lf', 'ag.id_lancamento_compra = lf.id', 'left');

        if (!empty($where)) {
            $this->db->where($where);
        }
        
        $this->db->order_by('ag.data_entrada', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Busca um único lote de gado pelo seu ID.
     * @param  int $id
     * @return array|null
     */
    public function get_ativo_gado($id)
    {
        $this->db->select('ag.*, cc.nome as centro_custo_nome, lf.descricao as lancamento_descricao');
        $this->db->from(db_prefix() . $this->table_name . ' ag');
        $this->db->join(db_prefix() . 'gf_centros_custo cc', 'ag.id_centro_custo = cc.id', 'left');
        $this->db->join(db_prefix() . 'gf_lancamentos_financeiros lf', 'ag.id_lancamento_compra = lf.id', 'left');
        $this->db->where('ag.id', $id);
        
        return $this->db->get()->row_array();
    }

    /**
     * Busca lançamentos financeiros que podem ser associados a uma compra de gado.
     * @return array
     */
    public function get_lancamentos_compra_gado()
    {
        $this->db->select('lf.id, lf.descricao, lf.valor, lf.data_competencia');
        $this->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id');
        $this->db->group_start();
        $this->db->like('pc.nome_conta', 'gado', 'both');
        $this->db->or_like('pc.nome_conta', 'bovino', 'both');
        $this->db->or_like('pc.nome_conta', 'compra de animais', 'both');
        $this->db->group_end();
        $this->db->order_by('lf.data_competencia', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Adiciona um novo lote de gado.
     * @param array $data
     * @return int|false
     */
    public function add_ativo_gado($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . $this->table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Novo Lote de Gado Adicionado [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza os dados de um lote de gado.
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update_ativo_gado($id, $data)
    {
        // O campo data_atualizacao já está configurado para ON UPDATE CURRENT_TIMESTAMP no DB.
        // Mas podemos garantir a atualização aqui também.
        $data['data_atualizacao'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Lote de Gado Atualizado [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta um lote de gado.
     * @param  int $id
     * @return boolean
     */
    public function delete_ativo_gado($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Lote de Gado Excluído [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Calcula e retorna um resumo do rebanho por categoria.
     * @return array
     */
    public function get_rebanho_summary()
    {
        $this->db->select('categoria, COUNT(id) as total_lotes, SUM(quantidade_cabecas) as total_cabecas, SUM(custo_total_aquisicao) as valor_total');
        $this->db->from(db_prefix() . 'gf_ativos_gado');
        $this->db->where('status_lote', 'Ativo'); // Considerar apenas lotes ativos no resumo
        $this->db->group_by('categoria');
        $this->db->order_by('categoria', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        // Calcula os totais gerais
        $summary = [
            'categorias' => $result,
            'total_geral_cabecas' => 0,
            'total_geral_valor' => 0,
        ];

        foreach ($result as $row) {
            $summary['total_geral_cabecas'] += $row['total_cabecas'];
            $summary['total_geral_valor'] += $row['valor_total'];
        }

        return $summary;
    }
}
