<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Centroscusto extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    /**
     * Exibe a página com a lista de Centros de Custo.
     */
    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['centros_custo'] = $this->centroscusto_model->get_centros_custo();
        $data['title'] = 'Centros de Custo';
        
        $this->load->view('admin/centroscusto/manage', $data);
    }

    /**
     * Adiciona ou Edita um Centro de Custo.
     * @param int $id O ID do centro de custo a ser editado (opcional)
     */
    public function centro($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                // Adicionar
                if (!has_permission('gestaofinanceira', '', 'create')) {
                    access_denied('gestaofinanceira');
                }
                $insert_id = $this->centroscusto_model->add_centro_custo($data);
                if ($insert_id) {
                    set_alert('success', 'Centro de Custo adicionado com sucesso!');
                }
            } else {
                // Editar
                if (!has_permission('gestaofinanceira', '', 'edit')) {
                    access_denied('gestaofinanceira');
                }
                $success = $this->centroscusto_model->update_centro_custo($id, $data);
                if ($success) {
                    set_alert('success', 'Centro de Custo atualizado com sucesso!');
                }
            }
            redirect(admin_url('gestaofinanceira/centroscusto'));
        }

        if ($id == '') {
            $title = 'Adicionar Novo Centro de Custo';
        } else {
            $data['centro_custo'] = $this->centroscusto_model->get_centro_custo($id);
            $title = 'Editar Centro de Custo: ' . $data['centro_custo']['nome'];
        }

        $data['title'] = $title;
        $this->load->view('admin/centroscusto/centro_form', $data);
    }

    /**
     * Deleta um Centro de Custo.
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/centroscusto'));
        }
        $response = $this->centroscusto_model->delete_centro_custo($id);
        if ($response['success']) {
            set_alert('success', 'Centro de Custo excluído com sucesso.');
        } else {
            set_alert('warning', $response['message']);
        }
        redirect(admin_url('gestaofinanceira/centroscusto'));
    }
}
