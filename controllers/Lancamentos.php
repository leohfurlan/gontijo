<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lancamentos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lancamentos_model');
        $this->load->model('planocontas_model');
        $this->load->model('entidades_model');
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }
        
        $data['lancamentos'] = $this->lancamentos_model->get_all_lancamentos();
        $data['title'] = _l('gf_menu_lancamentos');
        
        $this->load->view('admin/lancamentos/manage', $data);
    }

    public function lancamento($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if (isset($data['data_vencimento'])) {
                $data['data_vencimento'] = to_sql_date($data['data_vencimento']);
            }
            if (isset($data['data_liquidacao']) && !empty($data['data_liquidacao'])) {
                $data['data_liquidacao'] = to_sql_date($data['data_liquidacao']);
            } else {
                $data['data_liquidacao'] = null;
            }

            if ($id == '') {
                // Adicionar
                $insert_id = $this->lancamentos_model->add_lancamento($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('lancamento')));
                    redirect(admin_url('gestaofinanceira/lancamentos'));
                }
            } else {
                // Editar
                $success = $this->lancamentos_model->update_lancamento($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lancamento')));
                }
                redirect(admin_url('gestaofinanceira/lancamentos'));
            }
        }

        if ($id == '') {
            $title = 'Adicionar Novo Lançamento';
        } else {
            $data['lancamento'] = $this->lancamentos_model->get_lancamento($id);
            $title = 'Editar Lançamento: ' . ($data['lancamento']['descricao'] ?? '');
        }

        $data['title']           = $title;
        $data['contas']          = $this->planocontas_model->get_contas_lancamento();
        $data['centros_custo']   = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        $data['entidades']       = $this->entidades_model->get_entidades(['ativo' => 1]);
        
        $this->load->view('admin/lancamentos/form_lancamento', $data);
    }
    
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/lancamentos'));
        }
        $success = $this->lancamentos_model->delete_lancamento($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('lancamento')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lancamento')));
        }
        redirect(admin_url('gestaofinanceira/lancamentos'));
    }
}
