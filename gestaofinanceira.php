<?php

/**
 * Ensures that the script is not called directly.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Gestão Financeira
Description: Módulo de gestão financeira avançada para fazendas e centros de custo.
Version: 1.0.0
Requires at least: 2.3.0
*/

// Define o nome do módulo para usar nas permissões
define('GESTAO_FINANCEIRA_MODULE_NAME', 'gestaofinanceira');

/**
 * Registra o hook de ativação do módulo.
 */
register_activation_hook(GESTAO_FINANCEIRA_MODULE_NAME, 'gestao_financeira_activation_hook');

function gestao_financeira_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Registra o hook de idioma do módulo.
 */
register_language_files(GESTAO_FINANCEIRA_MODULE_NAME, ['gestaofinanceira']);


/**
 * Adiciona o item de menu na barra lateral do admin.
 */
hooks()->add_action('admin_init', 'gestao_financeira_add_menu_item');

function gestao_financeira_add_menu_item()
{
    $CI = &get_instance();

    // Menu Principal
    $CI->app_menu->add_sidebar_menu_item('gestao-financeira-dashboard', [
        'name'     => _l('gf_menu_main'), // Alterado para um nome mais genérico
        'href'     => admin_url('gestaofinanceira'),
        'position' => 10,
        'icon'     => 'fa fa-money', // Ícone atualizado
    ]);

    // Submenu para o Dashboard
    $CI->app_menu->add_sidebar_children_item('gestao-financeira-dashboard', [
        'slug'     => 'gestao-financeira-dashboard-sub',
        'name'     => _l('gf_menu_dashboard'),
        'href'     => admin_url('gestaofinanceira'),
        'position' => 5,
        'icon'     => 'fa fa-dashboard',
    ]);

    // Submenu para Lançamentos
    $CI->app_menu->add_sidebar_children_item('gestao-financeira-dashboard', [
        'slug'     => 'gestao-financeira-lancamentos-sub',
        'name'     => _l('gf_menu_lancamentos'),
        'href'     => admin_url('gestaofinanceira/lancamentos'), // Novo link
        'position' => 10,
        'icon'     => 'fa fa-exchange',
    ]);
}