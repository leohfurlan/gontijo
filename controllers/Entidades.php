<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entidades extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('entidades_model');
        $this->load->helper('gestaofinanceira');
    }

    /**
     * Exibe a página com a lista de Entidades.
     */
    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['entidades'] = $this->entidades_model->get_entidades();
        $data['title'] = _l('gf_menu_entidades');
        
        $this->load->view('admin/entidades/manage', $data);
    }

    /**
     * Adiciona ou Edita uma Entidade.
     * @param int $id O ID da entidade a ser editada (opcional)
     */
    public function entidade($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                // Adicionar
                if (!has_permission('gestaofinanceira', '', 'create')) {
                    access_denied('gestaofinanceira');
                }
                $insert_id = $this->entidades_model->add_entidade($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('gf_entidade')));
                }
            } else {
                // Editar
                if (!has_permission('gestaofinanceira', '', 'edit')) {
                    access_denied('gestaofinanceira');
                }
                $success = $this->entidades_model->update_entidade($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('gf_entidade')));
                }
            }
            redirect(admin_url('gestaofinanceira/entidades'));
        }

        if ($id == '') {
            $title = 'Adicionar Nova Entidade';
        } else {
            $data['entidade'] = $this->entidades_model->get_entidade($id);
            $title = 'Editar Entidade: ' . $data['entidade']['nome_razao_social'];
        }

        $data['title'] = $title;
        $this->load->view('admin/entidades/entidade_form', $data);
    }

    /**
     * Deleta uma Entidade.
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/entidades'));
        }
        $response = $this->entidades_model->delete_entidade($id);
        if ($response['success']) {
            set_alert('success', _l('deleted', _l('gf_entidade')));
        } else {
            set_alert('warning', $response['message']);
        }
        redirect(admin_url('gestaofinanceira/entidades'));
    }
}
