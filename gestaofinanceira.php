<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Gestão Financeira
Description: Módulo para gestão financeira completa, incluindo contas a pagar/receber, fluxo de caixa, ativos e relatórios.
Version: 1.0.0
Requires at least: 2.3.0
*/

/**
 * Define o nome do módulo para usar nas permissões e hooks.
 */
define('GESTAOFINANCEIRA_MODULE_NAME', 'gestaofinanceira');

/**
 * Registra o hook de ativação do módulo.
 */
register_activation_hook(GESTAOFINANCEIRA_MODULE_NAME, 'gestaofinanceira_activation_hook');

/**
 * Função executada na ativação do módulo.
 */
function gestaofinanceira_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Registra o hook de idioma do módulo.
 */
register_language_files(GESTAOFINANCEIRA_MODULE_NAME, [GESTAOFINANCEIRA_MODULE_NAME]);

/**
 * Adiciona os itens de menu e permissões.
 */
hooks()->add_action('admin_init', 'gestaofinanceira_init_menu_and_permissions');

/**
 * Função que inicializa os menus e as permissões.
 */
function gestaofinanceira_init_menu_and_permissions()
{
    $CI = &get_instance();

    // --- PERMISSÕES ---
    $capabilities = [
        'capabilities' => [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
        ],
    ];
    register_staff_capabilities(GESTAOFINANCEIRA_MODULE_NAME, $capabilities, _l('gf_menu_main'));

    // --- MENU PRINCIPAL ---
    $CI->app_menu->add_sidebar_menu_item('gestaofinanceira', [
        'name'     => _l('gf_menu_main'),
        'href'     => admin_url('gestaofinanceira'),
        'position' => 10,
        'icon'     => 'fa fa-money', // O ícone está correto.
    ]);

    // --- SUBMENUS ---
    // Dashboard
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-dashboard',
        'name'     => _l('gf_menu_dashboard'),
        'href'     => admin_url('gestaofinanceira'),
        'position' => 5,
        'icon'     => 'fa fa-dashboard',
    ]);

    // Lançamentos
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-lancamentos',
        'name'     => _l('gf_menu_lancamentos'),
        'href'     => admin_url('gestaofinanceira/lancamentos'),
        'position' => 10,
        'icon'     => 'fa fa-exchange',
    ]);
    
    // Entidades
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-entidades',
        'name'     => _l('gf_menu_entidades'),
        'href'     => admin_url('gestaofinanceira/entidades'),
        'position' => 15,
        'icon'     => 'fa fa-users',
    ]);
    
    // Plano de Contas
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-plano-contas',
        'name'     => _l('gf_menu_plano_contas'),
        'href'     => admin_url('gestaofinanceira/planocontas'),
        'position' => 20,
        'icon'     => 'fa fa-sitemap',
    ]);

    // Contas Bancárias
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-contas-bancarias',
        'name'     => _l('gf_menu_contas_bancarias'),
        'href'     => admin_url('gestaofinanceira/contasbancarias'),
        'position' => 25,
        'icon'     => 'fa fa-bank',
    ]);

    // Ativos (Gado)
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-ativos-gado',
        'name'     => _l('gf_menu_ativos_gado'),
        'href'     => admin_url('gestaofinanceira/ativosgado'),
        'position' => 30,
        'icon'     => 'fa fa-paw',
    ]);

    // Relatórios
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-relatorios',
        'name'     => _l('gf_menu_relatorios'),
        // CORREÇÃO: Aponta para a nova página de seleção de relatórios.
        'href'     => admin_url('gestaofinanceira/relatorios'),
        'position' => 35,
        'icon'     => 'fa fa-bar-chart',
    ]);

    // Configurações
    $CI->app_menu->add_sidebar_children_item('gestaofinanceira', [
        'slug'     => 'gestaofinanceira-configuracoes',
        'name'     => _l('gf_menu_configuracoes'),
        'href'     => admin_url('gestaofinanceira/configuracoes'),
        'position' => 80,
        'icon'     => 'fa fa-cog',
    ]);
}
