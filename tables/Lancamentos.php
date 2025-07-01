<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lancamentos_table extends App_table
{
    public function __construct()
    {
        // O último parâmetro 'lancamentos' é a classe CSS que usaremos na view.
        parent::__construct('lancamentos');
    }

    /**
     * Retorna as colunas do banco de dados a serem usadas na tabela.
     */
    protected function get_columns()
    {
        return [
            db_prefix() . 'gf_lancamentos_financeiros.id as id',
            'pc.tipo_conta as tipo_conta',
            'lf.descricao as descricao',
            'lf.valor as valor',
            'lf.data_vencimento as data_vencimento',
            'pc.nome_conta as nome_conta',
            'cc.nome as centro_custo_nome',
            'lf.status as status',
        ];
    }

    /**
     * Monta a consulta SQL com todos os joins necessários.
     */
    protected function get_query()
    {
        $this->ci->db->select($this->get_columns_as_string() . ', e.nome_razao_social as entidade_nome');
        $this->ci->db->from(db_prefix() . 'gf_lancamentos_financeiros lf');
        $this->ci->db->join(db_prefix() . 'gf_plano_contas pc', 'lf.id_plano_contas = pc.id', 'left');
        $this->ci->db->join(db_prefix() . 'gf_entidades e', 'lf.id_entidade = e.id', 'left');
        $this->ci->db->join(db_prefix() . 'gf_centros_custo cc', 'lf.id_centro_custo = cc.id', 'left');
    }

    /**
     * Formata cada linha da tabela antes de exibir.
     */
    protected function row_attributes($aRow)
    {
        // Coluna 1: ID
        $row[0] = $aRow['id'];

        // Coluna 2: Tipo (Receita/Despesa)
        if ($aRow['tipo_conta'] == 'Receita') {
            $row[1] = '<span class="label label-success">Receita</span>';
        } else {
            $row[1] = '<span class="label label-danger">Despesa</span>';
        }

        // Coluna 3: Descrição com link para edição
        $row[2] = '<a href="' . admin_url('gestaofinanceira/lancamentos/lancamento/' . $aRow['id']) . '">' . $aRow['descricao'] . '</a>';
        if ($aRow['entidade_nome']) {
            $row[2] .= '<div class="row-options">' . $aRow['entidade_nome'] . '</div>';
        }

        // Coluna 4: Valor
        $row[3] = format_currency_br($aRow['valor']);

        // Coluna 5: Data de Vencimento
        $row[4] = _d($aRow['data_vencimento']);

        // Coluna 6: Categoria (Plano de Contas)
        $row[5] = $aRow['nome_conta'];

        // Coluna 7: Centro de Custo
        $row[6] = $aRow['centro_custo_nome'];

        // Coluna 8: Status
        $row[7] = get_status_badge($aRow['status']);

        // Coluna 9: Opções
        $options = icon_btn('gestaofinanceira/lancamentos/lancamento/' . $aRow['id'], 'pencil-square-o');
        $options .= icon_btn('gestaofinanceira/lancamentos/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
        $row[8] = $options;
        
        // Adiciona classe CSS se estiver vencido
        $row['DT_RowClass'] = get_overdue_class($aRow['data_vencimento'], $aRow['status']);

        return $row;
    }
}
