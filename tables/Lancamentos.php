<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lancamentos extends App_table
{
    public function __construct()
    {
        // A forma mais segura de chamar o construtor, sem parâmetros.
        parent::__construct();
    }

    public function get_sql_select()
    {
        // Esta parte está correta.
        return [
            db_prefix() . 'gf_lancamentos.id as id',
            db_prefix() . 'gf_lancamentos.tipo as tipo',
            db_prefix() . 'gf_lancamentos.descricao as descricao',
            db_prefix() . 'gf_lancamentos.valor as valor',
            db_prefix() . 'gf_lancamentos.data_vencimento as data_vencimento',
            db_prefix() . 'gf_categorias.nome as categoria_nome',
            db_prefix() . 'gf_centros_custo.nome as centro_custo_nome',
            db_prefix() . 'gf_lancamentos.status as status',
        ];
    }

    public function get_sql_from()
    {
        // Correto.
        return db_prefix() . 'gf_lancamentos';
    }

    public function get_sql_join()
    {
        // *** CORREÇÃO APLICADA AQUI ***
        // Adicionamos o db_prefix() a TODAS as tabelas no JOIN.
        return [
           'LEFT JOIN ' . db_prefix() . 'gf_categorias ON ' . db_prefix() . 'gf_categorias.id = ' . db_prefix() . 'gf_lancamentos.categoria_id',
           'LEFT JOIN ' . db_prefix() . 'gf_centros_custo ON ' . db_prefix() . 'gf_centros_custo.id = ' . db_prefix() . 'gf_lancamentos.centro_custo_id',
        ];
    }

    public function get_columns()
    {
        // Para garantir que vai funcionar, vamos usar a formatação mais segura e recomendada.
        $columns = [
            'id'             => 'ID',
            'tipo'           => [
                'name'     => 'Tipo',
                 'formatter' => function ($value) {
                    if ($value == 'receita') {
                        return '<span class="label label-success">Receita</span>';
                    }
                    return '<span class="label label-danger">Despesa</span>';
                },
            ],
            'descricao'      => 'Descrição',
            'valor'          => [
                'name' => 'Valor',
                'formatter' => function ($value) {
                    return app_format_money($value, get_base_currency());
                }
            ],
            'data_vencimento' => [
                'name' => 'Vencimento',
                 'formatter' => function ($value) {
                    return _d($value);
                },
            ],
            'categoria_nome'    => 'Categoria',
            'centro_custo_nome' => 'Centro de Custo',
            'status'         => [
                'name'     => 'Status',
                 'formatter' => function ($value) {
                    if ($value == 'pago_recebido') {
                        return '<span class="label label-info">Pago/Recebido</span>';
                    }
                    return '<span class="label label-warning">A Pagar/Receber</span>';
                },
            ],
        ];

        $columns['opcoes'] = [
            'name' => 'Opções',
            'formatter' => function ($value, $row) {
                $id = $row['id'];
                $options = icon_btn('#', 'pencil-square-o', 'btn-default'); // Botão de editar (futuro)
                $options .= icon_btn('#', 'remove', 'btn-danger _delete'); // Botão de excluir (futuro)
                return $options;
            }
        ];

        return $columns;
    }
}