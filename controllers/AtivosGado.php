<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ativosgado extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ativosgado_model');
        $this->load->model('centroscusto_model');
        $this->load->helper('gestaofinanceira');
    }

    /**
     * Exibe a página principal com a tabela e o resumo dos Ativos de Gado.
     */
    public function index()
    {
        if (!has_permission('gestaofinanceira', '', 'view')) {
            access_denied('gestaofinanceira');
        }

        // Prepara todos os dados para a view
        $data['title'] = _l('gf_menu_ativos_gado');
        $data['summary'] = $this->ativosgado_model->get_rebanho_summary();
        // CORREÇÃO: Buscando a lista de ativos para passar para a view.
        $data['ativos_gado'] = $this->ativosgado_model->get_ativos_gado();
        
        $this->load->view('admin/ativosgado/manage', $data);
    }

    /**
     * O método tabela() não é mais necessário com a renderização client-side.
     * Foi removido.
     */

    /**
     * Adiciona ou Edita um lote de gado.
     * @param int $id O ID do lote a ser editado (opcional)
     */
    public function ativo($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                // Adicionar
                if (!has_permission('gestaofinanceira', '', 'create')) {
                    access_denied('gestaofinanceira');
                }
                $insert_id = $this->ativosgado_model->add_ativo_gado($data);
                if ($insert_id) {
                    set_alert('success', _l('added_successfully', _l('gf_ativo_gado')));
                    redirect(admin_url('gestaofinanceira/ativosgado'));
                }
            } else {
                // Editar
                if (!has_permission('gestaofinanceira', '', 'edit')) {
                    access_denied('gestaofinanceira');
                }
                $success = $this->ativosgado_model->update_ativo_gado($id, $data);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('gf_ativo_gado')));
                }
                redirect(admin_url('gestaofinanceira/ativosgado'));
            }
        }

        if ($id == '') {
            $title = 'Adicionar Novo Lote de Gado';
        } else {
            $data['ativo_gado'] = $this->ativosgado_model->get_ativo_gado($id);
            $title = 'Editar Lote de Gado: ' . ($data['ativo_gado']['descricao_lote'] ?? '');
        }

        $data['title']              = $title;
        $data['centros_custo']      = $this->centroscusto_model->get_centros_custo(['ativo' => 1]);
        $data['lancamentos_compra'] = $this->ativosgado_model->get_lancamentos_compra_gado();
        
        $this->load->view('admin/ativosgado/ativo_form', $data);
    }

    /**
     * Deleta um lote de gado.
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('gestaofinanceira', '', 'delete')) {
            access_denied('gestaofinanceira');
        }
        if (!$id) {
            redirect(admin_url('gestaofinanceira/ativosgado'));
        }
        $response = $this->ativosgado_model->delete_ativo_gado($id);
        if ($response) {
            set_alert('success', _l('deleted', _l('gf_ativo_gado')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('gf_ativo_gado')));
        }
        redirect(admin_url('gestaofinanceira/ativosgado'));
    }
}
