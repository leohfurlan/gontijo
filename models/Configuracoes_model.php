<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model para a gestão das Configurações do Módulo
 */
class Configuracoes_model extends App_Model
{
    private $table_name = 'gf_configuracoes';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca todas as configurações e as retorna em um array associativo (chave => valor).
     * @return array
     */
    public function get_configuracoes()
    {
        $this->db->order_by('chave', 'ASC');
        $result = $this->db->get(db_prefix() . $this->table_name)->result_array();
        
        $configuracoes = [];
        foreach ($result as $config) {
            $configuracoes[$config['chave']] = $config['valor'];
        }
        
        return $configuracoes;
    }

    /**
     * Atualiza as configurações do módulo no banco de dados.
     * @param  array $data
     * @return boolean
     */
    public function update_configuracoes($data)
    {
        $success = true;
        
        foreach ($data as $chave => $valor) {
            $this->db->where('chave', $chave);
            $this->db->update(db_prefix() . $this->table_name, ['valor' => $valor]);
            
            if ($this->db->affected_rows() == 0) {
                // Se não afetou nenhuma linha, pode ser que a chave não exista,
                // embora a boa prática seja ter todas as chaves já na tabela.
                // Aqui você poderia optar por inserir a chave se ela não existir.
                // Por enquanto, vamos manter a lógica original de apenas atualizar.
            }
        }
        
        if ($success) {
            log_activity('Configurações do Módulo Atualizadas');
        }
        
        return $success;
    }
}
