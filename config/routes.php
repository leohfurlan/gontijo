<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['gestaofinanceira'] = 'dashboard/index';
$route['admin/gestaofinanceira'] = 'dashboard/index';
$route['admin/gestaofinanceira/entidades'] = 'entidades/index';
$route['admin/gestaofinanceira/plano_contas'] = 'planocontas/index';
$route['admin/gestaofinanceira/contas_bancarias'] = 'contasbancarias/index';
$route['admin/gestaofinanceira/ativos_gado'] = 'ativosgado/index';
$route['admin/gestaofinanceira/lancamentos'] = 'lancamentos/index';
$route['admin/gestaofinanceira/lancamentos/create'] = 'lancamentos/create';
$route['admin/gestaofinanceira/lancamentos/edit/(:num)'] = 'lancamentos/edit/$1';
$route['admin/gestaofinanceira/lancamentos/delete/(:num)'] = 'lancamentos/delete/$1';
$route['admin/gestaofinanceira/fluxo_caixa'] = 'relatorios/fluxo_caixa';
$route['admin/gestaofinanceira/dre'] = 'relatorios/dre';
$route['admin/gestaofinanceira/endividamento'] = 'relatorios/endividamento';
$route['admin/gestaofinanceira/relatorios_operacionais'] = 'relatorios/operacionais';
$route['admin/gestaofinanceira/configuracoes'] = 'configuracoes/index';
