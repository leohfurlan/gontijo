<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Endividamento_model extends App_Model
{
    private $table_name = 'gf_endividamento';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca um único contrato pelo seu ID.
     * @param int $id
     * @return array|null
     */
    public function get_contrato($id)
    {
        return $this->db->get_where($this->table_name, ['id' => $id])->row_array();
    }

    /**
     * Busca todos os contratos de endividamento.
     * @return array
     */
    public function get_contratos()
    {
        $this->db->select('e.*, ent.nome_razao_social as credor_nome');
        $this->db->from($this->table_name . ' e');
        $this->db->join(db_prefix() . 'gf_entidades ent', 'e.id_credor = ent.id', 'left');
        $this->db->order_by('e.data_contratacao', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Adiciona um novo contrato de endividamento.
     * @param array $data
     * @return int|false
     */
    public function add_contrato($data)
    {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table_name, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Novo Contrato de Endividamento Adicionado [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Atualiza um contrato de endividamento.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_contrato($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table_name, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Contrato de Endividamento Atualizado [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Deleta um contrato de endividamento.
     * @param int $id
     * @return bool
     */
    public function delete_contrato($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
        if ($this->db->affected_rows() > 0) {
            log_activity('Contrato de Endividamento Excluído [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Busca um resumo completo do endividamento.
     * @return array
     */
    public function get_endividamento_summary()
    {
        $summary = [
            'total_contratos'      => 0,
            'valor_total_original' => 0,
            'saldo_devedor'        => 0,
            'valor_pago'           => 0,
        ];

        $this->db->where('status', 'Ativo');
        $summary['total_contratos'] = $this->db->count_all_results($this->table_name);

        $this->db->select_sum('valor_contrato');
        $this->db->where('status', 'Ativo');
        $summary['valor_total_original'] = $this->db->get($this->table_name)->row()->valor_contrato ?? 0;

        // CORREÇÃO: Usando select() com SUM() explícito para evitar o bug do query builder.
        $this->db->select('SUM(ep.valor_parcela) as total');
        $this->db->from(db_prefix() . 'gf_endividamento_parcelas ep');
        $this->db->join($this->table_name . ' e', 'ep.id_endividamento = e.id');
        $this->db->where('e.status', 'Ativo');
        $this->db->where('ep.status', 'Aberta');
        $summary['saldo_devedor'] = $this->db->get()->row()->total ?? 0;
        
        // CORREÇÃO: Usando select() com SUM() explícito para evitar o bug do query builder.
        $this->db->select('SUM(ep.valor_parcela) as total');
        $this->db->from(db_prefix() . 'gf_endividamento_parcelas ep');
        $this->db->join($this->table_name . ' e', 'ep.id_endividamento = e.id');
        $this->db->where('e.status', 'Ativo');
        $this->db->where('ep.status', 'Paga');
        $summary['valor_pago'] = $this->db->get()->row()->total ?? 0;

        return $summary;
    }

    /**
     * Busca parcelas com vencimento próximo.
     * @param int $dias
     * @return array
     */
    public function get_parcelas_vencendo($dias = 30)
    {
        $data_limite = date('Y-m-d', strtotime("+{$dias} days"));

        $this->db->select('ep.*, e.descricao as contrato_descricao, ent.nome_razao_social as credor_nome');
        $this->db->from(db_prefix() . 'gf_endividamento_parcelas ep');
        $this->db->join($this->table_name . ' e', 'ep.id_endividamento = e.id');
        $this->db->join(db_prefix() . 'gf_entidades ent', 'e.id_credor = ent.id');
        $this->db->where('e.status', 'Ativo');
        $this->db->where('ep.status', 'Aberta');
        $this->db->where('ep.data_vencimento <=', $data_limite);
        $this->db->order_by('ep.data_vencimento', 'ASC');

        return $this->db->get()->result_array();
    }
}
