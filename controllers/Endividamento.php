<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Endividamento extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('endividamento_model');
        $this->load->model('entidades_model'); // Para buscar a lista de credores
        $this->load->helper('gestaofinanceira');
    }

    /**
     * Exibe a página com a lista de Contratos de Endividamento e os KPIs.
     */
    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        $data['contratos'] = $this->endividamento_model->get_contratos();
        // Busca os dados de resumo e alertas
        $data['summary'] = $this->endividamento_model->get_endividamento_summary();
        $data['parcelas_vencendo'] = $this->endividamento_model->get_parcelas_vencendo(30);
        
        $data['title'] = 'Gestão de Endividamento';
        
        $this->load->view('admin/endividamento/manage', $data);
    }

    /**
     * Adiciona ou Edita um Contrato.
     * @param int $id O ID do contrato a ser editado (opcional)
     */
    public function contrato($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            // Converte a data para o formato do banco de dados antes de salvar
            $data['data_contratacao'] = to_sql_date($data['data_contratacao']);

            if ($id == '') {
                // Adicionar
                if (!has_permission('gestaofinanceira', '', 'create')) {
                    access_denied('gestaofinanceira');
                }
                $insert_id = $this->endividamento_model->add_contrato($data);
                if ($insert_id) {
                    set_alert('success', 'Contrato adicionado com sucesso!');
                }
            } else {
                // Editar
                if (!has_permission('gestaofinanceira', '', 'edit')) {
                    access_denied('gestaofinanceira');
                }
                $success = $this->endividamento_model->update_contrato($id, $data);
                if ($success) {
                    set_alert('success', 'Contrato atualizado com sucesso!');
                }
            }
            redirect(admin_url('gestaofinanceira/endividamento'));
        }

        if ($id == '') {
            $title = 'Adicionar Novo Contrato de Endividamento';
        } else {
            $data['contrato'] = $this->endividamento_model->get_contrato($id);
            $title = 'Editar Contrato: ' . ($data['contrato']['numero_contrato'] ?? '');
        }

        $data['title'] = $title;
        // Busca apenas entidades do tipo 'Credor' para o formulário
        $data['credores'] = $this->entidades_model->get_entidades(['tipo_entidade' => 'Credor']);
        
        $this->load->view('admin/endividamento/contrato_form', $data);
    }

    /**
     * Deleta um Contrato.
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/endividamento'));
        }
        $response = $this->endividamento_model->delete_contrato($id);
        if ($response) {
            set_alert('success', _l('deleted', 'Contrato'));
        } else {
            set_alert('warning', _l('problem_deleting', 'contrato'));
        }
        redirect(admin_url('gestaofinanceira/endividamento'));
    }
}
