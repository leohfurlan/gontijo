<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Configuracoes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gestaofinanceira_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestao_fazendas', '', 'edit')) {
            access_denied('gestao_fazendas');
        }

        if ($this->input->post()) {
            $this->_handle_configuracoes_form();
            return;
        }

        $data['title'] = _l('gf_menu_configuracoes');
        $data['configuracoes'] = $this->gestaofinanceira_model->get_configuracoes();
        $this->load->view('admin/gestaofinanceira/configuracoes', $data);
    }

    private function _handle_configuracoes_form()
    {
        $data = $this->input->post();
        $success = $this->gestaofinanceira_model->update_configuracoes($data);
        $message = $success ? _l('gf_msg_sucesso_salvar') : _l('gf_msg_erro_salvar');
        set_alert($success ? 'success' : 'danger', $message);
        redirect(admin_url('gestaofinanceira/configuracoes'));
    }
}
