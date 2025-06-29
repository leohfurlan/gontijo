<?php

/**
 * Ensures that the script is not called directly.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Gestão de Fazendas
Description: Módulo completo para gestão financeira e operacional de fazendas de gado de corte.
Version: 1.0.0
Requires at least: 2.3.0
*/

// Define o nome do módulo para usar nas permissões
define('GESTAO_FAZENDAS_MODULE_NAME', 'gestaofinanceira');

/**
 * Registra o hook de ativação do módulo.
 */
register_activation_hook(GESTAO_FAZENDAS_MODULE_NAME, 'gestao_fazendas_activation_hook');

function gestao_fazendas_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Registra o hook de idioma do módulo.
 */
register_language_files(GESTAO_FAZENDAS_MODULE_NAME, ['gestaofinanceira']);

/**
 * Adiciona o item de menu na barra lateral do admin.
 */
hooks()->add_action('admin_init', 'gestao_fazendas_add_menu_item');

function gestao_fazendas_add_menu_item()
{
    $CI = &get_instance();

    // Menu Principal - Gestão de Fazendas
    $CI->app_menu->add_sidebar_menu_item('gestao-fazendas', [
        'name'     => _l('gf_menu_main'),
        'href'     => admin_url('gestaofinanceira'),
        'position' => 10,
        'icon'     => 'fa fa-leaf',
    ]);

    // Submenu - Dashboard
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-dashboard',
        'name'     => _l('gf_menu_dashboard'),
        'href'     => admin_url('gestaofinanceira'),
        'position' => 5,
        'icon'     => 'fa fa-dashboard',
    ]);

    // Submenu - Lançamentos
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-lancamentos',
        'name'     => _l('gf_menu_lancamentos'),
        'href'     => admin_url('gestaofinanceira/lancamentos'),
        'position' => 10,
        'icon'     => 'fa fa-exchange',
    ]);

    // Submenu - Contas a Pagar
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-contas-pagar',
        'name'     => _l('gf_menu_contas_pagar'),
        'href'     => admin_url('gestaofinanceira/contas_pagar'),
        'position' => 15,
        'icon'     => 'fa fa-arrow-down',
    ]);

    // Submenu - Contas a Receber
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-contas-receber',
        'name'     => _l('gf_menu_contas_receber'),
        'href'     => admin_url('gestaofinanceira/contas_receber'),
        'position' => 20,
        'icon'     => 'fa fa-arrow-up',
    ]);

    // Submenu - Lançamentos Orçados
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-orcados',
        'name'     => _l('gf_menu_orcados'),
        'href'     => admin_url('gestaofinanceira/orcados'),
        'position' => 25,
        'icon'     => 'fa fa-calendar',
    ]);

    // Submenu - Cadastros
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-cadastros',
        'name'     => _l('gf_menu_cadastros'),
        'href'     => admin_url('gestaofinanceira/cadastros'),
        'position' => 30,
        'icon'     => 'fa fa-database',
    ]);

    // Submenu - Entidades (Clientes/Fornecedores)
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-entidades',
        'name'     => _l('gf_menu_entidades'),
        'href'     => admin_url('gestaofinanceira/entidades'),
        'position' => 35,
        'icon'     => 'fa fa-users',
    ]);

    // Submenu - Plano de Contas
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-plano-contas',
        'name'     => _l('gf_menu_plano_contas'),
        'href'     => admin_url('gestaofinanceira/plano_contas'),
        'position' => 40,
        'icon'     => 'fa fa-sitemap',
    ]);

    // Submenu - Contas Bancárias
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-contas-bancarias',
        'name'     => _l('gf_menu_contas_bancarias'),
        'href'     => admin_url('gestaofinanceira/contas_bancarias'),
        'position' => 45,
        'icon'     => 'fa fa-bank',
    ]);

    // Submenu - Ativos (Gado)
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-ativos-gado',
        'name'     => _l('gf_menu_ativos_gado'),
        'href'     => admin_url('gestaofinanceira/ativos_gado'),
        'position' => 50,
        'icon'     => 'fa fa-paw',
    ]);

    // Submenu - Relatórios
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-relatorios',
        'name'     => _l('gf_menu_relatorios'),
        'href'     => admin_url('gestaofinanceira/relatorios'),
        'position' => 55,
        'icon'     => 'fa fa-bar-chart',
    ]);

    // Submenu - Fluxo de Caixa
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-fluxo-caixa',
        'name'     => _l('gf_menu_fluxo_caixa'),
        'href'     => admin_url('gestaofinanceira/fluxo_caixa'),
        'position' => 60,
        'icon'     => 'fa fa-line-chart',
    ]);

    // Submenu - DRE
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-dre',
        'name'     => _l('gf_menu_dre'),
        'href'     => admin_url('gestaofinanceira/dre'),
        'position' => 65,
        'icon'     => 'fa fa-pie-chart',
    ]);

    // Submenu - Endividamento
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-endividamento',
        'name'     => _l('gf_menu_endividamento'),
        'href'     => admin_url('gestaofinanceira/endividamento'),
        'position' => 70,
        'icon'     => 'fa fa-credit-card',
    ]);

    // Submenu - Relatórios Operacionais
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-relatorios-operacionais',
        'name'     => _l('gf_menu_relatorios_operacionais'),
        'href'     => admin_url('gestaofinanceira/relatorios_operacionais'),
        'position' => 75,
        'icon'     => 'fa fa-cogs',
    ]);

    // Submenu - Configurações
    $CI->app_menu->add_sidebar_children_item('gestao-fazendas', [
        'slug'     => 'gestao-fazendas-configuracoes',
        'name'     => _l('gf_menu_configuracoes'),
        'href'     => admin_url('gestaofinanceira/configuracoes'),
        'position' => 80,
        'icon'     => 'fa fa-cog',
    ]);
}

/**
 * Registra as permissões do módulo.
 */
hooks()->add_action('admin_init', 'gestao_fazendas_permissions');

function gestao_fazendas_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('gf_menu_main') . ')',
        'create' => _l('permission_create') . '(' . _l('gf_menu_main') . ')',
        'edit'   => _l('permission_edit') . '(' . _l('gf_menu_main') . ')',
        'delete' => _l('permission_delete') . '(' . _l('gf_menu_main') . ')',
    ];

    register_staff_capabilities('gestao_fazendas', $capabilities, _l('gf_menu_main'));
}

