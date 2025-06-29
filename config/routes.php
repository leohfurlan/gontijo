<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Rotas do Dashboard (continuam iguais)
$route['gestaofinanceira'] = 'gestaofinanceira/index';
$route['admin/gestaofinanceira/lancamentos/create']          = 'lancamentos/create';
$route['admin/gestaofinanceira/lancamentos/edit/(:num)']     = 'lancamentos/edit/$1';
$route['admin/gestaofinanceira/lancamentos/delete/(:num)']   = 'lancamentos/delete/$1';
$route['admin/gestaofinanceira/lancamentos']                 = 'lancamentos/index';