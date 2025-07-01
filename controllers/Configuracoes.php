<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Configuracoes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // CORREÇÃO: Carregando o model específico de configurações.
        $this->load->model('configuracoes_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        // CORREÇÃO: A permissão principal do módulo é 'gestaofinanceira'.
        // Apenas administradores ou quem tem a permissão de edição pode alterar.
        if (!has_permission('gestaofinanceira', '', 'edit') && !is_admin()) {
            access_denied('gestaofinanceira');
        }

        if ($this->input->post()) {
            $success = $this->configuracoes_model->update_configuracoes($this->input->post());
            if ($success) {
                set_alert('success', _l('settings_updated'));
            } else {
                set_alert('danger', _l('problem_updating', _l('settings')));
            }
            redirect(admin_url('gestaofinanceira/configuracoes'));
        }

        $data['title'] = _l('gf_menu_configuracoes');
        // CORREÇÃO: Buscando as configurações do model correto.
        $data['configuracoes'] = $this->configuracoes_model->get_configuracoes();
        $this->load->view('admin/configuracoes/manage', $data);
    }
}
