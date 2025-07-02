<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Roteamento do Módulo Gestão Financeira
|--------------------------------------------------------------------------
*/


// Rota Principal para o Dashboard
$route['admin/gestaofinanceira'] = 'gestaofinanceira/dashboard/index';
$route['admin/gestaofinanceira/dashboard'] = 'gestaofinanceira/dashboard/index';
$route['admin/gestaofinanceira/entidades'] = 'gestaofinanceira/entidades/index';
$route['admin/gestaofinanceira/planocontas'] = 'gestaofinanceira/planocontas/index';
$route['admin/gestaofinanceira/ativosgado'] = 'gestaofinanceira/ativosgado/index';
$route['admin/gestaofinanceira/configuracoes'] = 'gestaofinanceira/configuracoes/index';
$route['admin/gestaofinanceira/lancamentos'] = 'gestaofinanceira/lancamentos/index';
$route['admin/gestaofinanceira/contasbancarias'] = 'gestaofinanceira/contasbancarias/index';
// ROTA ADICIONADA: Rota principal para a landing page de relatórios.
$route['admin/gestaofinanceira/relatorios'] = 'gestaofinanceira/relatorios/index';
$route['admin/gestaofinanceira/centroscusto'] = 'gestaofinanceira/centroscusto/index';


// --- ROTAS ESPECÍFICAS ---

// Lançamentos (CRUD e Importação)
$route['admin/gestaofinanceira/lancamentos'] = 'gestaofinanceira/lancamentos/index';
$route['admin/gestaofinanceira/lancamentos/lancamento'] = 'gestaofinanceira/lancamentos/lancamento';
$route['admin/gestaofinanceira/lancamentos/lancamento/(:num)'] = 'gestaofinanceira/lancamentos/lancamento/$1';
$route['admin/gestaofinanceira/lancamentos/delete/(:num)'] = 'gestaofinanceira/lancamentos/delete/$1';
// ROTAS ADICIONADAS
$route['admin/gestaofinanceira/lancamentos/upload'] = 'gestaofinanceira/lancamentos/upload';
$route['admin/gestaofinanceira/lancamentos/download_sample'] = 'gestaofinanceira/lancamentos/download_sample';


// Contas Bancárias (CRUD)
$route['admin/gestaofinanceira/contasbancarias/conta'] = 'gestaofinanceira/contasbancarias/conta';
$route['admin/gestaofinanceira/contasbancarias/conta/(:num)'] = 'gestaofinanceira/contasbancarias/conta/$1';
$route['admin/gestaofinanceira/contasbancarias/delete/(:num)'] = 'gestaofinanceira/contasbancarias/delete/$1';

// Ativos de Gado (CRUD)
$route['admin/gestaofinanceira/ativosgado/ativo'] = 'gestaofinanceira/ativosgado/ativo';
$route['admin/gestaofinanceira/ativosgado/ativo/(:num)'] = 'gestaofinanceira/ativosgado/ativo/$1';
$route['admin/gestaofinanceira/ativosgado/delete/(:num)'] = 'gestaofinanceira/ativosgado/delete/$1';

// Entidades (CRUD)
$route['admin/gestaofinanceira/entidades/entidade'] = 'gestaofinanceira/entidades/entidade';
$route['admin/gestaofinanceira/entidades/entidade/(:num)'] = 'gestaofinanceira/entidades/entidade/$1';
$route['admin/gestaofinanceira/entidades/delete/(:num)'] = 'gestaofinanceira/entidades/delete/$1';

// Plano de Contas (CRUD e Importação)
$route['admin/gestaofinanceira/planocontas'] = 'gestaofinanceira/planocontas/index';
$route['admin/gestaofinanceira/planocontas/conta'] = 'gestaofinanceira/planocontas/conta';
$route['admin/gestaofinanceira/planocontas/conta/(:num)'] = 'gestaofinanceira/planocontas/conta/$1';
$route['admin/gestaofinanceira/planocontas/delete/(:num)'] = 'gestaofinanceira/planocontas/delete/$1';
$route['admin/gestaofinanceira/planocontas/upload'] = 'gestaofinanceira/planocontas/upload';
$route['admin/gestaofinanceira/planocontas/download_sample'] = 'gestaofinanceira/planocontas/download_sample';


// Endividamento (CRUD)
$route['admin/gestaofinanceira/endividamento/contrato'] = 'gestaofinanceira/endividamento/contrato';
$route['admin/gestaofinanceira/endividamento/contrato/(:num)'] = 'gestaofinanceira/endividamento/contrato/$1';
$route['admin/gestaofinanceira/endividamento/delete/(:num)'] = 'gestaofinanceira/endividamento/delete/$1';


// Centro de Custos (CRUD)
$route['admin/gestaofinanceira/centroscusto/centro'] = 'gestaofinanceira/centroscusto/centro';
$route['admin/gestaofinanceira/centroscusto/centro/(:num)'] = 'gestaofinanceira/centroscusto/centro/$1';
$route['admin/gestaofinanceira/centroscusto/delete/(:num)'] = 'gestaofinanceira/centroscusto/delete/$1';


// Relatórios (rotas para os relatórios individuais)
$route['admin/gestaofinanceira/relatorios/fluxo_caixa'] = 'gestaofinanceira/relatorios/fluxo_caixa';
$route['admin/gestaofinanceira/relatorios/dre'] = 'gestaofinanceira/relatorios/dre';
$route['admin/gestaofinanceira/relatorios/endividamento'] = 'gestaofinanceira/relatorios/endividamento';
$route['admin/gestaofinanceira/relatorios/operacionais'] = 'gestaofinanceira/relatorios/operacionais';
